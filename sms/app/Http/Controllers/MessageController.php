<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display the Inbox (List of Threads).
     */
    public function index()
    {
        $user = auth()->user();

        // Get Threads (Inbox)
        $threads = $user->threads()
            ->with(['latestMessage', 'participants'])
            ->orderByDesc(
                \App\Models\Message::select('created_at')
                    ->whereColumn('thread_id', 'threads.id')
                    ->latest()
                    ->take(1)
            )
            ->get();

        // Get Eligible Recipients (Who can I start a chat with?)
        $query = \App\Models\User::where('id', '!=', $user->id);

        // (Everyone is locked to their school, except SuperAdmin)
        if (!$user->hasRole('SuperAdmin')) {
            $query->where('school_id', $user->school_id);
        }

        if ($user->hasRole('Parent')) {
        // Parents see Teachers & Admins
        $query->role(['Teacher', 'SchoolAdmin']); 
        } 
        elseif ($user->hasRole('Student')) {
            // Students see Teachers
            $query->role(['Teacher']);
        }
        else {
            // Teachers/Admins see Parents, Teachers, Admins
            $query->role(['Parent', 'Teacher', 'SchoolAdmin']);
        }

        $potentialRecipients = $query->with('roles') 
            ->orderBy('name')
            ->get()
            ->groupBy(function($user) {
                return $user->role; 
            });

        return view('messages.index', compact('threads', 'potentialRecipients'));
    }

    /**
     * Show a conversation (Chat View).
     */
    public function show($id)
    {
        // Find thread and ensure user is part of it
        $thread = Thread::with(['messages.user', 'participants'])
            ->findOrFail($id);

        // Security Check: Abort if user is not in this thread
        if (!$thread->participants->contains(auth()->id())) {
            abort(403);
        }

        // Mark unread messages in this thread as "Read"
        $thread->messages()
            ->where('user_id', '!=', auth()->id()) 
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('thread'));
    }

    /**
     * Store a new message (Start new thread OR Reply).
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            
            // Security validation for receipients
            'recipient_id' => [
                'required_without:thread_id',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    $targetUser = \App\Models\User::find($value);

                    if (!$targetUser) return; // 'exists' handles this, but safety first

                    // Rule: School Isolation (Must be same school, unless SuperAdmin)
                    if (!$user->hasRole('SuperAdmin') && $targetUser->school_id !== $user->school_id) {
                        return $fail('You cannot message users from other schools.');
                    }

                    // Rule: Role Isolation (Prevent Parent-to-Parent chat)
                    if ($user->hasRole('Parent')) {
                        if (!in_array($targetUser->role, ['Teacher', 'SchoolAdmin'])) {
                            return $fail('Parents can only message Teachers or Administrators.');
                        }
                    }
                    elseif ($user->hasRole('Student')) {
                        if ($targetUser->role !== 'Teacher') {
                            return $fail('Students can only message Teachers.');
                        }
                    }
                },
            ],
        ]);

        DB::transaction(function () use ($request) {
            // Handle File Upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('message_attachments', 'public');
            }

            // Determine Thread (Create New or Use Existing)
            if ($request->thread_id) {
                $thread = \App\Models\Thread::findOrFail($request->thread_id);
                
                // Security: Ensure user actually belongs to this thread
                if (!$thread->participants->contains(auth()->id())) {
                    abort(403, 'You are not part of this conversation.');
                }
                
                $thread->touch(); // Move to top of inbox
            } else {
                // Create New Thread
                $thread = \App\Models\Thread::create([
                    'subject' => $request->subject ?? 'Conversation',
                ]);
                // Attach Participants
                $thread->participants()->attach([auth()->id(), $request->recipient_id]);
            }

            // Create the Message
            \App\Models\Message::create([
                'thread_id' => $thread->id,
                'user_id'   => auth()->id(),
                'body'      => $request->body,
                'attachment_path' => $attachmentPath,
            ]);
        });

        if ($request->thread_id) {
            return back();
        }
        
        return redirect()->route('messages.index')->with('success', 'Message sent!');
    }

    /**
     * Delete a thread .
     */
    public function destroy($id)
    {
        $thread = Thread::findOrFail($id);

        $thread->participants()->detach(auth()->id());

        if ($thread->participants()->count() == 0) {
            $thread->delete();
        }

        return redirect()->route('messages.index')->with('success', 'Conversation removed.');
    }
}