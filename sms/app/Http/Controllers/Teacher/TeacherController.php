<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherProfile;
use App\Models\ClassLevel;
use App\Models\User; 
use App\Models\ClassroomAssignment;

class TeacherController extends Controller
{
    public function myClasses()
    {
        $user = auth()->user();
        $schoolId = session('active_school');
        
        $teacherProfile = TeacherProfile::where('user_id', $user->id)->first();

        if (!$teacherProfile) {
            return view('teacher.my-classes', ['classes' => []]);
        }

        $classes = $teacherProfile->assignedClasses()
                   ->where('class_levels.school_id', $schoolId)
                    ->get()
                    ->unique('id'); 

        return view('teacher.my-classes', compact('classes'));
    }

    public function classStudents($classId)
    {
        $user = auth()->user();
        $teacherProfile = TeacherProfile::where('user_id', $user->id)->firstOrFail();

       
        $isAssigned = $teacherProfile->assignedClasses()
                        ->where('class_levels.id', $classId)
                        ->exists();

        if (!$isAssigned) {
            abort(403, 'Access denied. You are not assigned to this class.');
        }

        $class = ClassLevel::findOrFail($classId);

        $students = User::role('Student')
            ->whereHas('studentProfile', function($query) use ($classId) {
                $query->where('class_level_id', $classId);
            })
            ->with('studentProfile') 
            ->get();
        
        return view('teacher.attendance.class-students', compact('class', 'students'));
    }

    public function mySubjects()
    {
        $user = auth()->user();

        // Ensuring Teacher Profile exists
        if (!$user->teacherProfile) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Profile not found. Please contact admin.');
        }

        // Fetching Assignments
        $assignments = ClassroomAssignment::where('teacher_id', $user->teacherProfile->id)
            ->where('is_active', true)
            ->with(['classLevel', 'section', 'subject']) // Load relationships
            ->get();

        return view('teacher.subjects.index', compact('assignments'));
    }
}