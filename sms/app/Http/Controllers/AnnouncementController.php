<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncement;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function index()
    {  
        
        $user = auth()->user();
        
        // Active announcements
        $query = Announcement::active();

        // SCHOOL VISIBILITY FILTER 
        if (!$user->hasRole('SuperAdmin')) {
            $query->where(function($q) use ($user) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $user->school_id);
            });
        }

        // ROLE VISIBILITY FILTER 
        if (!$user->hasRole(['SuperAdmin', 'SchoolAdmin', 'Parent'])) {
            $query->where(function($q) use ($user) {
                $q->whereNull('target_role')            
                ->orWhere('target_role', $user->role) 
                ->orWhere('created_by', $user->id);   
            });
        }

        $announcements = $query->latest('publish_at')->get();

        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'target_role' => 'nullable', 
            'publish_at' => 'required|date',
            'expires_at' => 'nullable|date|after:publish_at',
        ]);

        $data['created_by'] = auth()->id();
        
        // Logic to assign school id
        if (auth()->user()->hasRole('SuperAdmin')) {
            $data['school_id'] = null; 
        } else {
            $data['school_id'] = auth()->user()->school_id; 
        }
        
        $announcement = Announcement::create($data);

        // Filter notification recepients
        $userQuery = User::query();

        // If it's a specific school post, only notify that school's users
        if ($announcement->school_id) {
            $userQuery->where('school_id', $announcement->school_id);
        }

        // If it targets a specific role, filter by that role
        if ($announcement->target_role) {
            $userQuery->where('role', $announcement->target_role);
        }

        $users = $userQuery->get();

        // Send the Notification
        Notification::send($users, new NewAnnouncement($announcement));

        return back()->with('success', 'Announcement posted and notifications sent!');
    }

    public function update(Request $request, Announcement $announcement)
    {
        // Security check: SuperAdmin only
        if(auth()->id() !== $announcement->created_by && !auth()->user()->hasRole('SuperAdmin')) {
            abort(403);
        }

        $announcement->update($request->all());

        return back()->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement)
    {
        // Security check : SuperAdmin only
        if(auth()->id() !== $announcement->created_by && !auth()->user()->hasRole('SuperAdmin')) {
            abort(403);
        }

        $announcement->delete();

        return back()->with('success', 'Announcement deleted');
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            return redirect($notification->data['url'] ?? route('announcements.index'));
        }
        return back();
    }
}