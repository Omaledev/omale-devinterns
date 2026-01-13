<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable; 
use App\Models\ClassLevel;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = session('active_school');
        
        // Get all classes to populate a dropdown filter
        $classes = ClassLevel::where('school_id', $schoolId)->get();

        // If a class is selected, fetch that timetable, otherwise empty or first class
        $selectedClassId = $request->get('class_level_id');
        $timetable = null;

        if ($selectedClassId) {
            $timetable = Timetable::where('school_id', $schoolId)
                ->where('class_level_id', $selectedClassId)
                ->first(); 
        }

        return view('teacher.timetable.index', compact('classes', 'timetable', 'selectedClassId'));
    }
}