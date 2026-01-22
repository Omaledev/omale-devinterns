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
use App\Models\AssignmentSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $user = auth()->user();

        // SAFETY CHECK: Must have profile andclass
        if (!$user->studentProfile || !$user->studentProfile->class_level_id) {
            return redirect()->route('student.dashboard')->with('error', 'You have not been assigned to a class yet.');
        }

        $classId = $user->studentProfile->class_level_id;
        
        $timetables = Timetable::where('class_level_id', $classId)
            ->with(['subject', 'teacher', 'section']) 
            ->orderBy('start_time')
            ->get();

        $weeklyTimetable = $timetables->groupBy('weekday')->map(function ($dayEntries) {
            return $dayEntries->keyBy(function ($entry) {
                return $entry->start_time . '-' . $entry->end_time;
            });
        });
            
        return view('student.timetable', compact('weeklyTimetable'));
    }

    /**
     * Attendance History
     */
    public function attendance()
    {
        $user = auth()->user();

        // SAFETY CHECK: Must have profile
        if (!$user->studentProfile) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $studentId = $user->studentProfile->id;
        
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
        $user = auth()->user();

        // SAFETY CHECK: Must have profile
        if (!$user->studentProfile) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $studentId = $user->studentProfile->id;
        
        $results = Grade::where('student_id', $studentId)
            ->with(['subject', 'academicSession', 'term'])
            ->get()
            ->groupBy('term.name'); 
            
        return view('student.results', compact('results'));
    }

    // assignments
    public function assignments() 
    {
        $user = auth()->user();

        // SAFETY CHECK: Must have profile and class
        if (!$user->studentProfile || !$user->studentProfile->class_level_id) {
            return redirect()->route('student.dashboard')->with('error', 'You have not been assigned to a class yet.');
        }

        $classId = $user->studentProfile->class_level_id;
        $studentId = $user->id;

        $assignments = Assignment::where('class_level_id', $classId)
            ->with(['subject', 'submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->latest()
            ->paginate(10);
            
        return view('student.assignments', compact('assignments'));
    }

    /**
     * Submit Assignment
     */
    public function submitAssignment(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120', // Max 5MB
            'comments' => 'nullable|string'
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::create([
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

        // SAFETY CHECK: Must have profile and class
        if (!$user->studentProfile || !$user->studentProfile->class_level_id) {
            return redirect()->route('student.dashboard')->with('error', 'You have not been assigned to a class yet.');
        }

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
        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            return back()->with('error', 'File not found on server.');
        }

        return Storage::disk('public')->download(
            $book->file_path, 
            Str::slug($book->title) . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Financials / Fees Dashboard
     * (Safe without checks because it uses User ID)
     */
    public function fees()
    {
        $studentId = auth()->id();
        
        $invoices = Invoice::where('student_id', $studentId)
            ->with(['payments', 'term', 'academicSession'])
            ->latest()
            ->get();
            
        $totalBilled = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum('paid_amount');
        $totalOutstanding = $totalBilled - $totalPaid;
        
        $school = auth()->user()->school;

        return view('student.fees', compact('invoices', 'totalBilled', 'totalPaid', 'totalOutstanding', 'school'));
    }

    /**
     * Upload Payment Proof
     */
    public function uploadPayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'reference_number' => 'required|string',
            'proof' => 'required|file|mimes:jpeg,png,pdf|max:2048', 
        ]);

        $path = $request->file('proof')->store('payment_proofs', 'public');

        Payment::create([
            'invoice_id' => $request->invoice_id,
            'amount' => $request->amount,
            'payment_method' => 'Bank Transfer', 
            'reference_number' => $request->reference_number,
            'payment_date' => now(), 
            'recorded_by' => auth()->id(), 
            'proof_file_path' => $path,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Payment proof uploaded successfully! The Bursar will review and update your balance shortly.');
    }

    /**
     * Download Invoice PDF
     */
    public function printInvoice($id)
    {
        $studentId = auth()->id();

        $invoice = Invoice::where('id', $id)
            ->where('student_id', $studentId)
            ->with(['payments', 'student', 'term', 'academicSession']) 
            ->firstOrFail();
        
        $school = auth()->user()->school; 

        $pdf = Pdf::loadView('student.invoice_pdf', compact('invoice', 'school'));
        return $pdf->download('Invoice_' . $invoice->invoice_number . '.pdf');
    }

    /**
     * My Subjects
     */
    public function subjects()
    {
        $user = auth()->user();

        // SAFETY CHECK: Must have profile and class
        if (!$user->studentProfile || !$user->studentProfile->class_level_id) {
            return redirect()->route('student.dashboard')->with('error', 'You have not been assigned to a class yet.');
        }

        $classId = $user->studentProfile->class_level_id;
        
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
        $user = auth()->user();

        // SAFETY CHECK: Must have profile and class
        if (!$user->studentProfile || !$user->studentProfile->class_level_id) {
            return redirect()->route('student.dashboard')->with('error', 'You have not been assigned to a class yet.');
        }

        $classId = $user->studentProfile->class_level_id;
        
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

    /**
     * Print Result
     */
    public function printResult($termId, $sessionId)
    {
        $user = auth()->user();
        
        // SAFETY CHECK: Must have profile
        if (!$user->studentProfile) {
            return back()->with('error', 'Student profile not found.');
        }

        $studentProfile = $user->studentProfile;

        $grades = Grade::where('student_id', $studentProfile->id)
            ->where('term_id', $termId)
            ->where('academic_session_id', $sessionId)
            ->with(['subject', 'term', 'academicSession']) 
            ->get();

        if ($grades->isEmpty()) {
            return back()->with('error', 'No grades found for this term.');
        }

        $stats = [
            'total_score' => $grades->sum('total_score'),
            'average' => $grades->avg('total_score'),
            'total_subjects' => $grades->count(),
            'term_name' => $grades->first()->term->name,
            'session_name' => $grades->first()->academicSession->name,
        ];

        return view('student.result_print', compact('user', 'studentProfile', 'grades', 'stats'));
    }
}