<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TeacherProfile;
use App\Models\ClassLevel;
use App\Models\User; 
use App\Models\ClassroomAssignment;

class TeacherController extends Controller
{
    public function myClasses()
    {   
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }

        $user = auth()->user();
        $schoolId = session('active_school');
        
        $teacherProfile = \App\Models\TeacherProfile::where('user_id', $user->id)->first();

        if (!$teacherProfile || !$user->is_approved) {
            return view('teacher.my-classes', ['classes' => collect()]);
        }

        $classes = $teacherProfile->assignedClasses()
                   ->where('class_levels.school_id', $schoolId)
                    ->get()
                    ->unique('id'); 

        return view('teacher.my-classes', compact('classes'));
    }

    public function classStudents($classId)
    {   
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }

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
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }

        $user = auth()->user();

        // Ensuring Teacher Profile exists
        if (!$user->teacherProfile) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Profile not found. Please contact admin.');
        }

        // Fetching Assignments
        $assignments = ClassroomAssignment::where('teacher_id', $user->teacherProfile->id)
            ->where('is_active', true)
            ->with(['classLevel', 'section', 'subject']) 
            ->get();

        return view('teacher.subjects.index', compact('assignments'));
    }

    public function meetings()
    {  
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }

        // Getting the authenticated teacher's profile
        $teacherProfile = Auth::user()->teacherProfile;

        // Fetching meetings assigned to this teacher
        $meetings = \App\Models\ParentTeacherMeeting::where('teacher_id', $teacherProfile->id)
            ->with(['parent', 'student']) 
            ->orderBy('scheduled_at', 'asc') // Show upcoming first
            ->get();

        return view('teacher.meetings', compact('meetings'));
    }

    /**
     * Meeting Status (Approve/Decline)
     */
    public function updateMeetingStatus(Request $request, $id)
    {   
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }
        
        $request->validate([
            'status' => 'required|in:approved,declined,completed'
        ]);

        $meeting = \App\Models\ParentTeacherMeeting::findOrFail($id);
        
        // Security: Ensure this meeting belongs to the logged-in teacher
        if ($meeting->teacher_id !== Auth::user()->teacherProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        $meeting->update([
            'status' => $request->status
        ]);


        return back()->with('success', 'Meeting status updated successfully.');
    }
}