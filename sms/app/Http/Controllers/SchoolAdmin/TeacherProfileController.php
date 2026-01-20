<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassroomAssignment;
use App\Models\ClassLevel;
use App\Models\Subject;
use App\Models\Section;
use App\Models\School;
use Illuminate\Support\Facades\Hash;


class TeacherProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->with(['taughtClasses.subject']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }
    
        $teachers = $query->latest()->paginate(10);
        return view('schooladmin.teacherProfile.index', compact('teachers'));
    }

    public function create()
    {
        // Generate Sequential ID: VICTCH2026001
        $nextEmployeeId = $this->generateEmployeeId();

        return view('schooladmin.teacherProfile.create', compact('nextEmployeeId'));
    }

    /**
     * Generate ID Format: SCHOOL + ROLE + YEAR + NUMBER
     * Example: VICTCH2026001
     */
    private function generateEmployeeId()
    {
        $schoolId = auth()->user()->school_id;
        $school = School::find($schoolId);
        
        // Prefix: First 3 letters of school 
        $cleanName = preg_replace('/[^a-zA-Z]/', '', $school->name ?? 'SCH');
        $prefix = strtoupper(substr($cleanName, 0, 3));
        
        // Role and Year
        $role = 'TCH'; // TCH for Teacher
        $year = date('Y');
        
        // Search Pattern: VICTCH2026%
        $searchPattern = "{$prefix}{$role}{$year}%";

        // Find the last teacher
        $lastTeacher = User::role('Teacher')
            ->where('school_id', $schoolId)
            ->where('employee_id', 'like', $searchPattern)
            ->orderByRaw('LENGTH(employee_id) DESC') 
            ->orderBy('employee_id', 'desc')
            ->first();

        if (!$lastTeacher) {
            return "{$prefix}{$role}{$year}001";
        }

        // Extract Number
        $lastNumber = substr($lastTeacher->employee_id, 10); 
        
        $newNumber = intval($lastNumber) + 1;
        $paddedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return "{$prefix}{$role}{$year}{$paddedNumber}";
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'required|string|unique:users,employee_id'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'employee_id' => $validated['employee_id'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        $user->assignRole('Teacher');

        return redirect()->route('schooladmin.teachers.index')
            ->with('success', 'Teacher created successfully!');
    }

    public function show(User $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) abort(403);
        return view('schooladmin.teacherProfile.show', compact('teacher'));
    }

    public function edit(User $teacher)
    {
         if ($teacher->school_id !== auth()->user()->school_id) abort(403);
         return view('schooladmin.teacherProfile.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
         if ($teacher->school_id !== auth()->user()->school_id) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_approved' => 'nullable|boolean',
        ]);

        $teacher->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'phone' => $validated['phone'] ?? $teacher->phone,
            'is_approved' => $request->boolean('is_approved'), 
        ]);

        return redirect()->route('schooladmin.teachers.index')
            ->with('success', 'Teacher updated successfully!');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) abort(403);
        $teacher->delete();
        return redirect()->route('schooladmin.teachers.index')->with('success', 'Teacher deleted successfully!');
    }

    
    public function showAssignForm()
    {
        $teachers = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->where('is_approved', true)
            ->get();
        
        $subjects = Subject::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
        
        $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
        
        $sections = Section::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();

        return view('schooladmin.teacherProfile.assign-teachers', compact('teachers', 'subjects', 'classLevels', 'sections'));
    }
    

    public function assignTeacher(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year' => 'required|string',
        ]);

        // Check if assignment already exists
        $existingAssignment = ClassroomAssignment::where([
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'class_level_id' => $request->class_level_id,
            'school_id' => auth()->user()->school_id,
            'academic_year' => $request->academic_year,
        ])->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'This teacher is already assigned to this subject and class.');
        }

        ClassroomAssignment::create([
            'school_id' => auth()->user()->school_id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'class_level_id' => $request->class_level_id,
            'section_id' => $request->section_id,
            'academic_year' => $request->academic_year,
        ]);

        return redirect()->route('schooladmin.teachers.index')
            ->with('success', 'Teacher assigned successfully!');
    }
    
}
