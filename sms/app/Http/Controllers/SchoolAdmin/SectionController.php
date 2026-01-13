<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\ClassLevel;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::where('school_id', auth()->user()->school_id)
            ->with('classLevel'); // Load class name

        // Calculate students
        $query->withCount('studentProfiles');

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $sections = $query->latest()->paginate(10);

        return view('schooladmin.sections.index', compact('sections'));
    }

    public function create()
    {
        $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
            
        return view('schooladmin.sections.create', compact('classLevels'));
    }

    public function store(Request $request)
    {
         $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $existingSection = Section::where('class_level_id', $request->class_level_id)
            ->where('name', $request->name)
            ->where('school_id', auth()->user()->school_id)
            ->first();

        if ($existingSection) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A section with this name already exists in this class.');
        }

        Section::create([
            'school_id' => auth()->user()->school_id,
            'class_level_id' => $request->class_level_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'is_active' => true 
        ]);

        return redirect()->route('schooladmin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function show(Section $section)
    {
        if ($section->school_id !== auth()->user()->school_id) {
            abort(403);
        }
        $section->load(['classLevel', 'studentProfiles.user']);
        return view('schooladmin.sections.show', compact('section'));
    }

    public function edit(Section $section)
    {
        if ($section->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();

        return view('schooladmin.sections.edit', compact('section', 'classLevels'));
    }

    public function update(Request $request, Section $section)
    {
        if ($section->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean', 
        ]);

        // Check for duplicates (excluding current section)
        $existingSection = Section::where('class_level_id', $request->class_level_id)
            ->where('name', $request->name)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', '!=', $section->id)
            ->first();

        if ($existingSection) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A section with this name already exists in this class.');
        }

        $section->update([
            'class_level_id' => $request->class_level_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'is_active' => $request->boolean('is_active'), 
        ]);

        return redirect()->route('schooladmin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        if ($section->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        if ($section->studentProfiles()->count() > 0) {
            return redirect()->route('schooladmin.sections.index')
                ->with('error', 'Cannot delete section. There are students assigned to it.');
        }

        $section->delete();
        return redirect()->route('schooladmin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}