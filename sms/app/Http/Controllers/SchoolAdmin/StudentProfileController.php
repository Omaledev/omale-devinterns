<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\ClassLevel;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class StudentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::role('Student')
            ->where('school_id', auth()->user()->school_id)
            ->with(['studentProfile.classLevel', 'studentProfile.section']);

        // Search Functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('admission_number', 'like', "%{$search}%");
            });
        }

        // paginate(10)
        $students = $query->latest()->paginate(10);

        return view('schooladmin.studentProfile.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Fetch ClassLevels created by this school
        $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)->get();

        $sections = Section::where('school_id', auth()->user()->school_id)->get();
        //Passing them to the view
        return view('schooladmin.studentProfile.create', compact('classLevels', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'admission_number' => 'required|string|unique:users,admission_number',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'class_level_id' => 'required|exists:class_levels,id', 
            'section_id' => 'nullable|exists:sections,id',
            'admission_date' => 'nullable|date',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'emergency_contact' => 'required|string|max:255',
        ]);

        // Creating the user
        $user = User::create([
            'name' => $validated['full_name'], 
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'admission_number' => $validated['admission_number'],
            'phone' => $validated['phone'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        // Assigning Student role
        $user->assignRole('Student');

        // Creating student profile
        StudentProfile::create([
            'user_id' => $user->id,
            'school_id' => auth()->user()->school_id,
            'class_level_id' => $validated['class_level_id'],
            'section_id' => $validated['section_id'] ?? null,
            'student_id' => $validated['admission_number'], 
            'admission_date' => $validated['admission_date'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'emergency_contact' => $validated['emergency_contact'],
        ]);

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student created and Assigned to class successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        // Ensuring the student belongs to the same school
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $student->load('studentProfile');
        return view('schooladmin.studentProfile.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
        abort(403);
    }
    
    $student->load('studentProfile');

    $classLevels = \App\Models\ClassLevel::where('school_id', auth()->user()->school_id)->get();
    $sections = \App\Models\Section::where('school_id', auth()->user()->school_id)->get();

        return view('schooladmin.studentProfile.edit', compact('student', 'classLevels', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $student)
    {
        // Security Check
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Validation
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'admission_number' => 'required|string|unique:users,admission_number,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'class_level_id' => 'nullable|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id',
            'admission_date' => 'nullable|date',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'is_approved' => 'nullable|boolean',
        ]);

        $student->update([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'admission_number' => $validated['admission_number'],
            'phone' => $validated['phone'],
            'is_approved' => $request->boolean('is_approved'),
        ]);

        \App\Models\StudentProfile::updateOrCreate(
            ['user_id' => $student->id], 
            [
                'school_id' => auth()->user()->school_id,
                'student_id' => $validated['admission_number'], 
                'class_level_id' => $validated['class_level_id'],
                'section_id' => $validated['section_id'] ?? null,
                'admission_date' => $validated['admission_date'],
                'date_of_birth' => $validated['date_of_birth'],
                'address' => $validated['address'],
                'state' => $validated['state'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
            ]
        );

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Delete student profile if exists
        if ($student->studentProfile) {
            $student->studentProfile->delete();
        }

        $student->delete();

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student deleted successfully!');
    }

        public function export() 
    {   
        // Authorization check
        if (!auth()->user()->hasAnyRole(['SchoolAdmin', 'SuperAdmin'])) {
            abort(403, 'Unauthorized action.');
        }
        
        // School context to filename
        $filename = 'students-' . auth()->user()->school_id . '-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new StudentsExport, $filename);
    }

    public function import(Request $request) 
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['SchoolAdmin', 'SuperAdmin'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240' // 10MB max
        ]);
        
        try {
            Excel::import(new StudentsImport, $request->file('file')->store('temp'));
            
            return back()->with('success', 'Students imported successfully!');
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: {$failure->errors()[0]}";
            }
            
            return back()->with('error', implode('<br>', $errorMessages));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        if (!auth()->user()->hasAnyRole(['SchoolAdmin', 'SuperAdmin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Creating a simple template
        $template = new class implements \Maatwebsite\Excel\Concerns\FromArray {
            public function array(): array {
                return [
                    // Headers
                    ['email', 'name', 'class_level_id', 'student_id', 'admission_date', 'date_of_birth', 'gender', 'contact', 'address'],
                    // Example row 1
                    ['student1@school.com', 'John Doe', '1', 'STD001', '2024-01-15', '2008-05-20', 'Male', '1234567890', '123 Main St'],
                    // Example row 2
                    ['student2@school.com', 'Jane Smith', '1', 'STD002', '2024-01-15', '2008-07-15', 'Female', '0987654321', '456 Oak Ave'],
                ];
            }
        };
        
        return Excel::download($template, 'student-import-template.xlsx');
    }
}