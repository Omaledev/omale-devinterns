<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassLevel;


class ClassLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classLevels = ClassLevel::with('sections')->get();
        return view('schooladmin.class-levels.index', compact('classLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schooladmin.class-levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        ClassLevel::create([
            'school_id' => session('active_school'),
            ...$request->all()
        ]);

        return redirect()->route('schooladmin.class-levels.index')
            ->with('success', 'Class level created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('schooladmin.class-levels.edit', compact('classLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classlevel $classLevel)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        $classLevel->update($request->all());

        return redirect()->route('schooladmin.class-levels.index')
            ->with('success', 'Class level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classlevel $classLevel)
    {
        $classLevel->delete();
        return redirect()->route('schooladmin.class-levels.index')
          ->with('success', 'Class level deleted successfully.');
    }
}
