<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\ClassLevel;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::with(['classLevel', 'studentProfiles'])->get();
        return view('schooladmin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classLevels = ClassLevel::where('is_active', true)->get();
        return view('schooladmin.sections.create', compact('classLevels'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        // Checking if the section with same name already exists in the same class level
        $existingSection = Section::where('class_level_id', $request->class_level_id)
            ->where('name', $request->name)
            ->where('school_id', session('active_school'))
            ->first();

        if ($existingSection) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A section with this name already exists in the selected class level.');
        }

        Section::create([
            'school_id' => session('active_school'),
            'class_level_id' => $request->class_level_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('schooladmin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        $section->load(['classLevel', 'studentProfiles.user']);
        return view('schooladmin.sections.show', compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $classLevels = ClassLevel::where('is_active', true)->get();
        return view('schooladmin.sections.edit', compact('section', 'classLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        // Checking if the section with same name already exists in the same class level (excluding the current section)
        $existingSection = Section::where('class_level_id', $request->class_level_id)
            ->where('name', $request->name)
            ->where('school_id', session('active_school'))
            ->where('id', '!=', $section->id)
            ->first();

        if ($existingSection) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A section with this name already exists in the selected class level.');
        }

        $section->update([
            'class_level_id' => $request->class_level_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('schooladmin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
         // Checking if section has students assigned to it
        if ($section->studentProfiles()->count() > 0) {
            return redirect()->route('schooladmin.sections.index')
                ->with('error', 'Cannot delete section. There are students assigned to this section.');
        }

        $section->delete();
        return redirect()->route('schooladmin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}
