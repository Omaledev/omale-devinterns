<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ClassLevel;
use App\Models\StudentProfile;
use App\Models\Section;
use App\Models\User;

class AttendanceController extends Controller
{
    /**
     * View to select which class to take attendance for.
     */
    public function select()
    {
        $schoolId = session('active_school');
        $classes = ClassLevel::where('school_id', $schoolId)->get(); 
        $sections = Section::where('school_id', $schoolId)->get();
        
        return view('teacher.attendance.select', compact('classes', 'sections'));
    }

    /**
     * Actual attendance form with student list.
     */
    public function create(Request $request)
    {
        $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id', 
            'date' => 'required|date',
        ]);

        $classId = $request->class_level_id;
        $sectionId = $request->section_id; 
        $date = $request->date;
        $schoolId = session('active_school');

        // Start building the query
        $query = StudentProfile::where('school_id', $schoolId)
            ->where('class_level_id', $classId)
            ->with('user');

        // If a section is selected, filter by it
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $students = $query->get();

        // Check if attendance was already taken
        $attendanceQuery = Attendance::where('class_level_id', $classId)->where('date', $date);
        
        // Filter existing attendance record by section too if applicable
        if ($sectionId) {
            $attendanceQuery->where('section_id', $sectionId);
        }
        $existingAttendance = $attendanceQuery->get()->keyBy('student_id');

        return view('teacher.attendance.create', compact('students', 'classId', 'sectionId', 'date', 'existingAttendance'));
    }

    /**
     * Storing the bulk attendance data.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id', 
            'date' => 'required|date',
            'attendances' => 'required|array', 
            'attendances.*' => 'in:PRESENT,ABSENT,LATE', 
        ]);

        $schoolId = session('active_school');
        $teacherId = auth()->id();

        foreach ($data['attendances'] as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $data['date'],
                ],
                [
                    'school_id' => $schoolId,
                    'teacher_id' => $teacherId,
                    'class_level_id' => $data['class_level_id'],
                    'section_id' => $data['section_id'] ?? null, 
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('teacher.attendance.select')
            ->with('success', 'Attendance recorded successfully for ' . $data['date']);
    }
    
    public function summary()
    {
         $schoolId = session('active_school');
         $attendanceHistory = Attendance::where('school_id', $schoolId)
            ->with(['student.user', 'classLevel'])
            ->where('teacher_id', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate(20);

         return view('teacher.attendance.summary', compact('attendanceHistory'));
    }
}