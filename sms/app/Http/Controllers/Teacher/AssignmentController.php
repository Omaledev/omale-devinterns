<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\ClassroomAssignment; 
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::where('teacher_id', auth()->id())
            ->with(['classLevel', 'subject', 'section'])
            ->latest()
            ->paginate(10);

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $user = auth()->user();

        // Safety Check: Ensuring User is actually a teacher
        if (!$user->teacherProfile) {
            return redirect()->back()->with('error', 'Teacher Profile not found.');
        }

        // Using teacherProfile->id to find assigned classes
        $assignedClasses = ClassroomAssignment::where('teacher_id', $user->teacherProfile->id)
            ->where('is_active', true)
            ->with(['classLevel', 'subject', 'section'])
            ->get();

        return view('teacher.assignments.create', compact('assignedClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'classroom_assignment_id' => 'required|exists:classroom_assignments,id',
            'description' => 'required|string',
            'deadline' => 'required|date|after:today',
            'file' => 'nullable|file|max:5120', 
        ]);

        $user = auth()->user();

        // Fetching the allocation details
        $allocation = ClassroomAssignment::findOrFail($request->classroom_assignment_id);

        // Checking if this allocation belongs to the logged-in teacher's PROFILE
        if($allocation->teacher_id !== $user->teacherProfile->id){
            abort(403, 'You are not authorized to post for this class.');
        }

        // Handling File
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('assignments', 'public');
        }

        // Creating Assignment
        // Note: 'teacher_id' here usually refers to the User ID (the uploader)
        Assignment::create([
            'school_id'      => session('active_school'),
            'teacher_id'     => $user->id, // User ID
            'class_level_id' => $allocation->class_level_id,
            'section_id'     => $allocation->section_id,
            'subject_id'     => $allocation->subject_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'deadline'       => $request->deadline,
            'file_path'      => $path,
        ]);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment posted successfully!');
    }

    public function edit(Assignment $assignment)
    {
        if ($assignment->teacher_id !== auth()->id()) {
            abort(403);
        }

        $user = auth()->user();

        // Using teacherProfile->id
        $assignedClasses = ClassroomAssignment::where('teacher_id', $user->teacherProfile->id)
            ->where('is_active', true)
            ->with(['classLevel', 'subject', 'section'])
            ->get();

        return view('teacher.assignments.edit', compact('assignment', 'assignedClasses'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        if ($assignment->teacher_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'classroom_assignment_id' => 'required|exists:classroom_assignments,id',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'file' => 'nullable|file|max:5120',
        ]);

        $user = auth()->user();
        $allocation = ClassroomAssignment::findOrFail($request->classroom_assignment_id);
        
        // Checking Profile ID
        if($allocation->teacher_id !== $user->teacherProfile->id){
            abort(403, 'Unauthorized class selection.');
        }

        $path = $assignment->file_path;
        if ($request->hasFile('file')) {
            if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            $path = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update([
            'class_level_id' => $allocation->class_level_id,
            'section_id'     => $allocation->section_id,
            'subject_id'     => $allocation->subject_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'deadline'       => $request->deadline,
            'file_path'      => $path,
        ]);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        if ($assignment->teacher_id !== auth()->id()) {
            abort(403);
        }

        if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        $assignment->delete();

        return redirect()->back()->with('success', 'Assignment deleted.');
    }
}