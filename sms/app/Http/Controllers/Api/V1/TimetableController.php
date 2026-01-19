<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TimetableResource;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    /**
     * Get timetable for the logged-in STUDENT
     */
    public function forStudent(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('Student')) {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $studentProfile = $user->studentProfile;
        
        if (!$studentProfile) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $timetable = Timetable::with(['subject', 'teacher', 'classLevel', 'section'])
            ->where('school_id', $user->school_id)
            ->where('class_level_id', $studentProfile->class_level_id)
            ->where(function($q) use ($studentProfile) {
                $q->where('section_id', $studentProfile->section_id)
                  ->orWhereNull('section_id');
            })
            // MySQL specific ordering for days
            ->orderByRaw("FIELD(weekday, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')")
            ->orderBy('start_time')
            ->get();

        return TimetableResource::collection($timetable);
    }

    /**
     * Get timetable for the logged-in TEACHER
     */
    public function forTeacher(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('Teacher')) {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $timetable = Timetable::with(['subject', 'classLevel', 'section'])
            ->where('school_id', $user->school_id)
            ->where('teacher_id', $user->id)
            ->orderByRaw("FIELD(weekday, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')")
            ->orderBy('start_time')
            ->get();

        return TimetableResource::collection($timetable);
    }

    /**
     * Get timetable by class (For Admins/Teachers)
     */
    public function forClass(Request $request, $classId)
    {
        $user = $request->user();
        
        if (!$user->hasAnyRole(['Teacher', 'SchoolAdmin', 'SuperAdmin'])) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $timetable = Timetable::with(['subject', 'teacher', 'section'])
            ->where('school_id', $user->school_id)
            ->where('class_level_id', $classId)
            ->orderByRaw("FIELD(weekday, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('weekday')
            ->map(fn($items) => TimetableResource::collection($items));

        return response()->json($timetable);
    }

    /**
     * Get weekly timetable view (For filtering)
     */
    public function weeklyView(Request $request)
    {
        $user = $request->user();
        $classId = $request->input('class_id');
        
        $query = Timetable::with(['subject', 'teacher', 'classLevel', 'section'])
            ->where('school_id', $user->school_id);

        if ($classId) {
            $query->where('class_level_id', $classId);
        }

        $timetable = $query->get()
            ->groupBy(['class_level_id', 'weekday'])
            ->map(function ($classGroup) {
                return $classGroup->map(function ($dayGroup) {
                    return TimetableResource::collection($dayGroup);
                });
            });

        return response()->json($timetable);
    }
}