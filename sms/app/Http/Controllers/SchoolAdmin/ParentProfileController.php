<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ParentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::role('Parent')
            ->where('school_id', auth()->user()->school_id)
             ->with('children') 
            ->withCount('children');

        // Search Functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $parents = $query->latest()->paginate(10);
        
        return view('schooladmin.parentProfile.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::role('Student')
            ->where('school_id', auth()->user()->school_id)
            ->get();
            
        return view('schooladmin.parentProfile.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'children' => 'nullable|array',
            'children.*' => 'exists:users,id'
        ]);
        
          \Log::info('Creating parent with data:', $validated);
          \Log::info('Children to attach:', $request->children ?? []);

        $parent = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        $parent->assignRole('Parent');
         
        
    \Log::info('Parent created:', $parent->toArray());

        // Attach the children if provided
        if ($request->has('children')) {
            foreach ($request->children as $childId) {
                $parent->children()->attach($childId, [
                    'relationship' => 'parent',
                    'is_primary' => true
                ]);
            }
        }

        return redirect()->route('schooladmin.parents.index')
            ->with('success', 'Parent created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $parent)
    {
          if ($parent->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $parent->load('children');
        return view('schooladmin.parentProfile.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $parent)
    {
         if ($parent->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $students = User::role('Student')
            ->where('school_id', auth()->user()->school_id)
            ->get();
            
        $parent->load('children');
        
        return view('schooladmin.parentProfile.edit', compact('parent', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $parent)
    {
            if ($parent->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'children' => 'nullable|array',
            'children.*' => 'exists:users,id',
            'is_approved' => 'nullable|boolean',
        ]);

        $parent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_approved' => $request->boolean('is_approved'), 
        ]);

        // Sync children
        if ($request->has('children')) {
            $parent->children()->syncWithPivotValues($request->children, [
                'relationship' => 'parent',
                'is_primary' => true
            ]);
        } else {
            $parent->children()->detach();
        }

        return redirect()->route('schooladmin.parents.index')
            ->with('success', 'Parent updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $parent)
    {
            if ($parent->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $parent->children()->detach();
        $parent->delete();

        return redirect()->route('schooladmin.parents.index')
            ->with('success', 'Parent deleted successfully!');
    }

    
}
