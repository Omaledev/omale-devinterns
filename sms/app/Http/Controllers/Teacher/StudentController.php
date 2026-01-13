<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassLevel;
use App\Models\Section;
use App\Models\StudentProfile;

class StudentController extends Controller
{
    /**
     * Show list of classes to choose from
     */
    public function index()
    {
        $schoolId = session('active_school');
        
        // Fetch classes associated with this school
        $classes = ClassLevel::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();

        return view('teacher.students.index', compact('classes', 'sections'));
    }

    /**
     * Show students for a specific Class (and Section)
     */
    public function list(Request $request)
    {
        $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $schoolId = session('active_school');
        $classId = $request->class_level_id;
        $sectionId = $request->section_id;

        // Build Query
        $query = StudentProfile::where('school_id', $schoolId)
            ->where('class_level_id', $classId)
            ->with(['user', 'classLevel', 'section', 'attendances']); 

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $students = $query->paginate(20);
        
        // Get Class/Section Names for the view header
        $selectedClass = ClassLevel::find($classId);
        $selectedSection = $sectionId ? Section::find($sectionId) : null;

        return view('teacher.students.list', compact('students', 'selectedClass', 'selectedSection'));
    }

    public function performance(StudentProfile $student)
    {
        // Ensure the teacher is allowed to view this student (Optional security check)
        if($student->school_id !== session('active_school')) abort(403);

        // Load relationship data needed for the performance view
        $student->load(['user', 'classLevel', 'section', 'attendances']);

        // You would typically calculate stats here or pass grades
        return view('teacher.students.performance', compact('student'));
    }
}