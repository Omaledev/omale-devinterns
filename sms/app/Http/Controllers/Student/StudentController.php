<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Timetable;
use App\Models\Attendance;
use App\Models\Grade; 
use App\Models\Book;
use App\Models\Invoice;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\ClassroomAssignment; 
use App\Models\ReportCard; 
use App\Models\Payment;

class StudentController extends Controller
{
    /**
     * Timetable: Grouped by Day
     */
    public function timetable()
    {
        $classId = auth()->user()->studentProfile->class_level_id;
        
        $timetable = Timetable::where('class_level_id', $classId)
            ->with(['subject', 'teacher']) 
            ->orderBy('start_time')
            ->get()
            ->groupBy('weekday'); 
            
        return view('student.timetable', compact('timetable'));
    }

    /**
     * Attendance History
     */
    public function attendance()
    {
        $studentId = auth()->user()->studentProfile->id;
        
        $attendanceRecords = Attendance::where('student_id', $studentId)
            ->orderBy('date', 'desc')
            ->paginate(15);
            
        return view('student.attendance', compact('attendanceRecords'));
    }

    /**
     * Results / Grades
     */
    public function results()
    {
        $studentId = auth()->user()->studentProfile->id;
        
        // Fetch grades and group them by Term
        $results = Grade::where('student_id', $studentId)
            ->with(['subject', 'academicSession', 'term'])
            ->get()
            ->groupBy('term.name'); 
            
        return view('student.results', compact('results'));
    }

    /**
     * Assignments
     */
    public function assignment()
    {
        $classId = auth()->user()->studentProfile->class_level_id;
        $studentId = auth()->id();

        $assignments = Assignment::where('class_level_id', $classId)
            ->with(['subject', 'submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->latest()
            ->paginate(10);
            
        return view('student.assignments', compact('assignments'));
    }

    // Handle the form submit method
    public function submitAssignment(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120', // Max 5MB
            'comments' => 'nullable|string'
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        \App\Models\AssignmentSubmission::create([
            'assignment_id' => $request->assignment_id,
            'student_id' => auth()->id(),
            'file_path' => $filePath,
            'comments' => $request->comments
        ]);

        return back()->with('success', 'Assignment submitted successfully!');
    }

    /**
     * Study Books / Library
     */
    public function books()
    {
        $user = auth()->user();
        $classId = $user->studentProfile->class_level_id;

        $books = Book::where(function($query) use ($classId) {
                $query->where('class_level_id', $classId)
                      ->orWhereNull('class_level_id');
            })
            ->with(['subject', 'teacher']) 
            ->latest()
            ->paginate(12);

        return view('student.books', compact('books'));
    }

    /**
     * Handle the file download securely.
     */
    public function downloadBook(Book $book)
    {
        // Security Check: ensure file exists
        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            return back()->with('error', 'File not found on server.');
        }

        // Download the file
        
        return Storage::disk('public')->download(
            $book->file_path, 
            Str::slug($book->title) . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION)
        );
    }
   

    /**
     * Financials / Fees
     */
    public function fees()
    {
        $studentId = auth()->user()->studentProfile->id;
        
        $invoices = Invoice::where('student_id', $studentId)
            ->with('payments')
            ->latest()
            ->get();
        
        return view('student.fees', compact('invoices'));
    }

    /**
     * My Subjects
     */
    public function subjects()
    {
    
        $classId = auth()->user()->studentProfile->class_level_id;
        
        // Find subjects via the ClassroomAssignments table (where teachers are assigned)
        // This ensures subjects that actually have a teacher/class assigned
        $subjects = ClassroomAssignment::where('class_level_id', $classId)
            ->with('subject')
            ->get()
            ->pluck('subject') 
            ->unique('id'); 
        
        return view('student.subjects', compact('subjects'));
    }

    /**
     * My Teachers
     */
    public function teachers()
    {
        $classId = auth()->user()->studentProfile->class_level_id;
        
        // Finding teachers assigned to this student's class
        $teachers = ClassroomAssignment::where('class_level_id', $classId)
            ->with(['teacher.user', 'subject'])
            ->get();
            
        return view('student.teachers', compact('teachers'));
    }

    /**
     * Messages (Redirect)
     */
    public function messages()
    {
        return redirect()->route('messages.index');
    }

    public function printResult($termId, $sessionId)
    {
        $user = auth()->user();
        $studentProfile = $user->studentProfile;

        // Fetching the Grades for this specific Term & Session
        $grades = \App\Models\Grade::where('student_id', $studentProfile->id)
            ->where('term_id', $termId)
            ->where('academic_session_id', $sessionId)
            ->with(['subject', 'term', 'academicSession']) 
            ->get();

        if ($grades->isEmpty()) {
            return back()->with('error', 'No grades found for this term.');
        }

        // Calculate Summary Stats
        $stats = [
            'total_score' => $grades->sum('total_score'),
            'average' => $grades->avg('total_score'),
            'total_subjects' => $grades->count(),
            'term_name' => $grades->first()->term->name,
            'session_name' => $grades->first()->academicSession->name,
        ];

        return view('student.result_print', compact('user', 'studentProfile', 'grades', 'stats'));
    }

    public function uploadPayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'reference_number' => 'required|string',
            'proof' => 'required|file|mimes:jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        // Uploading the file
        $path = $request->file('proof')->store('payment_proofs', 'public');

        // Creating the Payment Record
        \App\Models\Payment::create([
            'invoice_id' => $request->invoice_id,
            'amount' => $request->amount,
            'payment_method' => 'Bank Transfer/Upload', 
            'reference_number' => $request->reference_number,
            'payment_date' => now(), 
            'recorded_by' => auth()->id(), 
            'proof_file_path' => $path,
            'status' => 'pending' 
        ]);

        return back()->with('success', 'Payment proof uploaded! Waiting for Bursar verification.');
    }
}