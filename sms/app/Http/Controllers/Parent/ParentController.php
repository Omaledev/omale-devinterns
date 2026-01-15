<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\Timetable;
use App\Models\Grade;
use App\Models\ParentTeacherMeeting;

class ParentController extends Controller
{
    
    /**
     * Dashboard Overview
     */
    public function index()
    {
        $children = $this->getMyChildren();
        
        // Loop through children to calculate INDIVIDUAL stats for cards
        foreach($children as $child) {
            // Attendance Rate
            $totalDays = $child->studentProfile ? $child->studentProfile->attendances->count() : 0;
            $presentDays = $child->studentProfile ? $child->studentProfile->attendances->where('status', 'PRESENT')->count() : 0;
            $child->attendance_rate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

            // Average Grade 
            $child->average_grade = $child->grades->isNotEmpty() 
                ? round($child->grades->avg('total_score'), 1) 
                : 0;

            // Fee Balance
            $child->fee_balance = $child->invoices->sum(function($invoice) {
                return $invoice->total_amount - $invoice->paid_amount;
            });
        }
        
        // Get Sidebar Stats
        $sidebarStats = $this->getSidebarStats($children);

        // Merge with Dashboard-Specific Stats
        $stats = array_merge($sidebarStats, [
            'average_attendance' => $children->isNotEmpty() ? round($children->avg('attendance_rate')) : 0,
            'average_grade' => $children->isNotEmpty() ? round($children->avg('average_grade'), 1) : 0,
            'pending_assignments' => $this->calculatePendingAssignments($children),
        ]);

        // Get Other Data
        $recentActivities = $this->getRecentActivities($children);
        $activeSession = AcademicSession::where('is_active', true)->first();
        $activeTerm = Term::where('is_active', true)->first();

        return view('parent.dashboard', compact('stats', 'children', 'recentActivities', 'activeSession', 'activeTerm'));
    }

    /**
     * List of Children
     */
    public function children()
    {
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);

        return view('parent.children.index', compact('children', 'stats'));
    }

    /**
     * Timetable View
     */
    public function timetable()
    {
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);
        
        $timetableData = [];
        foreach ($children as $child) {
            if ($child->studentProfile && $child->studentProfile->class_level_id) {
                $timetableData[$child->id] = Timetable::where('class_level_id', $child->studentProfile->class_level_id)
                    ->with(['subject', 'teacher']) 
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day_of_week');
            }
        }

        return view('parent.timetable', compact('children', 'timetableData', 'stats'));
    }

    /**
     * Attendance Overview
     */
    public function attendance()
    {
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);
        
        // Calculating attendance rate for view display
        foreach($children as $child) {
            $total = $child->studentProfile->attendances->count();
            $present = $child->studentProfile->attendances->where('status', 'PRESENT')->count();
            $child->attendance_rate = $total > 0 ? round(($present / $total) * 100) : 0;
        }

        return view('parent.attendance', compact('children', 'stats'));
    }

    /**
     * Academic Results Overview
     */
    public function results()
    {
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);

        return view('parent.results', compact('children', 'stats'));
    }

    /**
     * Fee Payments Overview
     */
    public function fees()
    {
        $children = Auth::user()->children()
            ->with(['studentProfile', 'invoices.academicSession', 'invoices.term'])
            ->get();
            
        $statsChildren = $this->getMyChildren();
        $stats = $this->getSidebarStats($statsChildren);

        return view('parent.fees', compact('children', 'stats'));
    }

    /**
     * Teachers List
     */
    public function teachers()
    {
        $children = Auth::user()->children()
            ->with(['studentProfile.classLevel.classroomAssignments.teacher.user'])
            ->get();
            
        $statsChildren = $this->getMyChildren();
        $stats = $this->getSidebarStats($statsChildren);

        return view('parent.teachers', compact('children', 'stats'));
    }

    /**
     * Messages Inbox
     */
    public function messages()
    {
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $messages = collect(); 
        
        if (method_exists($user, 'threads')) {
            $messages = $user->threads()->with('latestMessage')->get();
        }

        return view('parent.messages', compact('messages', 'stats'));
    }

    /**
     * Announcements Board
     */
    public function announcements()
    {
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);

        $classLevelIds = $children->pluck('studentProfile.class_level_id')->filter()->unique();
        
        $announcements = Announcement::whereIn('class_level_id', $classLevelIds)
                                   ->orWhereNull('class_level_id') 
                                   ->with('creator') 
                                   ->latest()
                                   ->get();

        return view('parent.announcements', compact('announcements', 'stats'));
    }

    /**
     * View Meetings
     */
    public function meetings()
    {
        $children = $this->getMyChildren(); 
        $stats = $this->getSidebarStats($children);

        $meetings = ParentTeacherMeeting::where('parent_id', Auth::id())
            ->with(['teacher.user', 'student'])
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('parent.meetings', compact('meetings', 'children', 'stats'));
    }

    /**
     * Store New Meeting Request
     */
    public function storeMeeting(Request $request)
    {
        $request->validate([
            'teacher_data' => 'required', 
            'subject' => 'required|string|max:255',
            'scheduled_at' => 'required|date|after:now',
        ]);

        // Splitting the combined value (Teacher ID | Student ID)
        [$teacherId, $studentId] = explode('|', $request->teacher_data);

        ParentTeacherMeeting::create([
            'parent_id' => Auth::id(),
            'teacher_id' => $teacherId,
            'student_id' => $studentId,
            'subject' => $request->subject,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Meeting request sent successfully! The teacher has been notified.');
    }

    /**
     * Single Child Attendance History
     */
    public function childAttendance($id)
    {
        $child = Auth::user()->children()->where('users.id', $id)->firstOrFail();
        
        // Stats needed for sidebar
        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);
        
        $attendances = $child->studentProfile->attendances()
            ->orderBy('date', 'desc') 
            ->paginate(30);

        return view('parent.child_attendance', compact('child', 'attendances', 'stats'));
    }
    
    /**
     * Single Child Results History
     */
    public function childResults($id)
    {
        $child = Auth::user()->children()->where('users.id', $id)->firstOrFail();

        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);

        $grades = $child->grades()->with(['subject', 'term', 'academicSession'])->get();
        
        $results = $grades->groupBy(function($grade) {
            return $grade->term->name . ' (' . $grade->academicSession->name . ')';
        });

        return view('parent.child_results', compact('child', 'results', 'stats'));
    }

    /**
     * Single Child Fee History
     */
    public function childFees($id)
    {
        $child = Auth::user()->children()
            ->where('users.id', $id)
            ->with(['studentProfile', 'invoices.academicSession', 'invoices.term'])
            ->firstOrFail();

        $children = $this->getMyChildren();
        $stats = $this->getSidebarStats($children);

        // Child specific calculations
        $invoices = $child->invoices->sortByDesc('created_at');
        $totalBilled = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum('paid_amount');
        $balance = $totalBilled - $totalPaid;

        return view('parent.child_fees', compact('child', 'invoices', 'totalBilled', 'totalPaid', 'balance', 'stats'));
    }

    public function downloadReportCard($termId, $sessionId, $studentId)
    {
        // Verifying Child
        $child = Auth::user()->children()->where('users.id', $studentId)->firstOrFail();

        // Fetching Grades
        $grades = Grade::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('academic_session_id', $sessionId)
            ->with(['subject', 'academicSession', 'term']) 
            ->get();

        if ($grades->isEmpty()) {
            return back()->with('error', 'No result records found for this academic session.');
        }

        // Stats for Report Card
        $totalScore = $grades->sum('total_score');
        $average = $grades->avg('total_score');
        $attendance = $child->studentProfile->attendances()
            ->where('academic_session_id', $sessionId)
            ->where('term_id', $termId)
            ->count(); 

        // Generate PDF
        $pdf = Pdf::loadView('parent.reports.pdf', compact('child', 'grades', 'totalScore', 'average', 'attendance'));

        return $pdf->stream('Report_Card_' . $child->name . '.pdf');
    }

    /**
     * Helper: Get Authenticated Parent's Children with all necessary relationships
     */
    private function getMyChildren()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->children()
            ->with([
                'studentProfile.classLevel', 
                'studentProfile.section', 
                'studentProfile.attendances', 
                'grades.subject', 
                'invoices'
            ])
            ->get();
    }

    /**
     * Helper: Calculate Stats for the Sidebar (Counts, Due Fees, etc.)
     */
    private function getSidebarStats($children)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            'children_count' => $children->count(),
            
            // Total fee balance across all children
            'total_fee_balance' => $children->sum(function($child) {
                return $child->invoices->sum(function($inv) {
                    return $inv->total_amount - $inv->paid_amount;
                });
            }),
            
            // Unread messages count
            'unread_messages' => method_exists($user, 'threads') 
                ? $user->threads()->wherePivot('last_read_at', null)->count() 
                : 0,

            // Approved upcoming meetings
            'approved_meetings' => ParentTeacherMeeting::where('parent_id', $user->id)
                ->where('status', 'approved')
                ->where('scheduled_at', '>=', now()) 
                ->count(),
        ];
    }

    /**
     * Helper: Calculate pending assignments count
     */
    private function calculatePendingAssignments($children)
    {
        $pendingCount = 0;
        foreach ($children as $child) {
            if(!$child->studentProfile) continue;

            $submittedIds = AssignmentSubmission::where('student_id', $child->id)->pluck('assignment_id')->toArray();

            $count = Assignment::where('class_level_id', $child->studentProfile->class_level_id)
                ->where('deadline', '>=', now())
                ->whereNotIn('id', $submittedIds)
                ->count();
                
            $pendingCount += $count;
        }
        return $pendingCount;
    }

    /**
     * Helper: Get Recent Activity Feed
     */
    private function getRecentActivities($children)
    {
        $activities = collect();

        foreach ($children as $child) {
            // Recent Grades
            $recentGrades = $child->grades->sortByDesc('created_at')->take(2);
            foreach ($recentGrades as $grade) {
                $activities->push([
                    'title' => "Grade: " . ($grade->subject->name ?? 'Subject'),
                    'child_name' => $child->name,
                    'time' => $grade->created_at->diffForHumans(),
                    'badge_color' => 'success',
                    'badge_text' => $grade->total_score
                ]);
            }

            // Recent Attendance
            if ($child->studentProfile) {
                $recentAttendance = $child->studentProfile->attendances()
                    ->orderBy('date', 'desc') 
                    ->take(2)
                    ->get();

                foreach ($recentAttendance as $att) {
                    $activities->push([
                        'title' => "Marked " . ucfirst(strtolower($att->status)),
                        'child_name' => $child->name,
                        'time' => $att->created_at->diffForHumans(),
                        'badge_color' => $att->status == 'PRESENT' ? 'success' : 'danger',
                        'badge_text' => 'Attendance'
                    ]);
                }
            }
        }

        return $activities->sortByDesc('time')->take(6);
    }
}