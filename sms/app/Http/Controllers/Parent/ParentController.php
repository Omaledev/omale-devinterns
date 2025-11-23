<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Assessment;
use App\Models\FeePayment;
use App\Models\Timetable;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\ParentTeacherMeeting;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    /**
     * Display parent dashboard
     */
    public function index()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $stats = [
            'children_count' => $children->count(),
            'average_attendance' => $this->calculateAverageAttendance($children),
            'average_grade' => $this->calculateAverageGrade($children),
            'pending_assignments' => $this->calculatePendingAssignments($children),
            'total_fee_balance' => $this->calculateTotalFeeBalance($children),
            // 'unread_messages' => $this->getUnreadMessagesCount($parent),
        ];

        $recentActivities = $this->getRecentActivities($children);

        return view('parent.dashboard', compact('stats', 'children', 'recentActivities'));
    }

    /**
     * Display parent's children
     */
    public function children()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        return view('parent.children', compact('children'));
    }

    /**
     * Display child's results
     */
    public function childResults($id)
    {
        $child = Student::with(['user', 'classLevel', 'assessments', 'grades'])
                      ->where('id', $id)
                      ->whereHas('parent', function($query) {
                          $query->where('user_id', Auth::id());
                      })
                      ->firstOrFail();

        $results = $child->grades()->with('assessment')->get();
        $averageGrade = $results->avg('grade');

        return view('parent.child-results', compact('child', 'results', 'averageGrade'));
    }

    /**
     * Display child's attendance
     */
    public function childAttendance($id)
    {
        $child = Student::with(['user', 'classLevel', 'attendances'])
                      ->where('id', $id)
                      ->whereHas('parent', function($query) {
                          $query->where('user_id', Auth::id());
                      })
                      ->firstOrFail();

        $attendance = $child->attendances()
                          ->whereMonth('date', now()->month)
                          ->get();

        $attendanceStats = $this->calculateChildAttendanceStats($attendance);

        return view('parent.child-attendance', compact('child', 'attendance', 'attendanceStats'));
    }

    /**
     * Display child's fees
     */
    public function childFees($id)
    {
        $child = Student::with(['user', 'classLevel', 'feePayments'])
                      ->where('id', $id)
                      ->whereHas('parent', function($query) {
                          $query->where('user_id', Auth::id());
                      })
                      ->firstOrFail();

        $feePayments = $child->feePayments()->orderBy('due_date', 'desc')->get();
        $totalPaid = $feePayments->where('status', 'paid')->sum('amount');
        $totalDue = $feePayments->where('status', 'pending')->sum('amount');

        return view('parent.child-fees', compact('child', 'feePayments', 'totalPaid', 'totalDue'));
    }

    /**
     * Display overall attendance for all children
     */
    public function attendance()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $attendanceData = [];
        foreach ($children as $child) {
            $attendanceData[$child->id] = [
                'child' => $child,
                'attendance' => $child->attendances()->whereMonth('date', now()->month)->get(),
                'stats' => $this->calculateChildAttendanceStats($child->attendances()->whereMonth('date', now()->month)->get())
            ];
        }

        return view('parent.attendance', compact('attendanceData'));
    }

    /**
     * Display overall results for all children
     */
    public function results()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $resultsData = [];
        foreach ($children as $child) {
            $grades = $child->grades()->with('assessment')->get();
            $resultsData[$child->id] = [
                'child' => $child,
                'grades' => $grades,
                'average' => $grades->avg('grade')
            ];
        }

        return view('parent.results', compact('resultsData'));
    }

    /**
     * Display fee payments for all children
     */
    public function fees()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $feeData = [];
        $totalBalance = 0;
        
        foreach ($children as $child) {
            $feePayments = $child->feePayments()->orderBy('due_date', 'desc')->get();
            $balance = $feePayments->where('status', 'pending')->sum('amount');
            $totalBalance += $balance;
            
            $feeData[$child->id] = [
                'child' => $child,
                'payments' => $feePayments,
                'balance' => $balance
            ];
        }

        return view('parent.fees', compact('feeData', 'totalBalance'));
    }

    /**
     * Display timetable for children
     */
    public function timetable()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $timetableData = [];
        foreach ($children as $child) {
            $timetableData[$child->id] = [
                'child' => $child,
                'timetable' => Timetable::where('class_level_id', $child->class_level_id)
                                      ->where('section_id', $child->section_id)
                                      ->with('subject', 'teacher.user')
                                      ->get()
                                      ->groupBy('day_of_week')
            ];
        }

        return view('parent.timetable', compact('timetableData'));
    }

    /**
     * Display teachers for children
     */
    public function teachers()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $teachers = collect();
        foreach ($children as $child) {
            $childTeachers = $child->classLevel->teachers ?? collect();
            $teachers = $teachers->merge($childTeachers);
        }

        $teachers = $teachers->unique('id');

        return view('parent.teachers', compact('teachers', 'children'));
    }

    /**
     * Display messages
     */
    public function messages()
    {
        $parent = Auth::user();
        $messages = Message::where('recipient_id', $parent->id)
                          ->orWhere('sender_id', $parent->id)
                          ->with('sender', 'recipient')
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('parent.messages', compact('messages'));
    }

    /**
     * Display announcements
     */
    public function announcements()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $classLevelIds = $children->pluck('class_level_id')->unique();
        
        $announcements = Announcement::whereIn('class_level_id', $classLevelIds)
                                   ->orWhereNull('class_level_id')
                                   ->with('teacher.user')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return view('parent.announcements', compact('announcements'));
    }

    /**
     * Display parent-teacher meetings
     */
    public function meetings()
    {
        $parent = Auth::user();
        $children = $parent->parentProfile->children ?? collect();
        
        $meetings = ParentTeacherMeeting::where('parent_id', $parent->id)
                                      ->orWhereIn('student_id', $children->pluck('id'))
                                      ->with(['teacher.user', 'student.user'])
                                      ->orderBy('scheduled_at', 'desc')
                                      ->get();

        return view('parent.meetings', compact('meetings'));
    }

    /**
     * Calculate average attendance for children
     */
    private function calculateAverageAttendance($children)
    {
        if ($children->isEmpty()) return 0;

        $totalAttendance = 0;
        $childCount = 0;

        foreach ($children as $child) {
            $monthAttendance = $child->attendances()->whereMonth('date', now()->month)->get();
            if ($monthAttendance->count() > 0) {
                $presentCount = $monthAttendance->where('status', 'present')->count();
                $attendanceRate = ($presentCount / $monthAttendance->count()) * 100;
                $totalAttendance += $attendanceRate;
                $childCount++;
            }
        }

        return $childCount > 0 ? round($totalAttendance / $childCount, 1) : 0;
    }

    /**
     * Calculate average grade for children
     */
    private function calculateAverageGrade($children)
    {
        if ($children->isEmpty()) return 'N/A';

        $totalGrade = 0;
        $gradeCount = 0;

        foreach ($children as $child) {
            $grades = $child->grades;
            if ($grades->count() > 0) {
                $totalGrade += $grades->avg('grade');
                $gradeCount++;
            }
        }

        return $gradeCount > 0 ? round($totalGrade / $gradeCount, 1) : 'N/A';
    }

    /**
     * Calculate pending assignments for children
     */
    private function calculatePendingAssignments($children)
    {
        $pendingCount = 0;

        foreach ($children as $child) {
            $pendingCount += $child->assessments()
                                ->where('due_date', '>=', now())
                                ->whereDoesntHave('submissions', function($query) use ($child) {
                                    $query->where('student_id', $child->id);
                                })
                                ->count();
        }

        return $pendingCount;
    }

    /**
     * Calculate total fee balance for children
     */
    private function calculateTotalFeeBalance($children)
    {
        $totalBalance = 0;

        foreach ($children as $child) {
            $totalBalance += $child->feePayments()
                                ->where('status', 'pending')
                                ->sum('amount');
        }

        return $totalBalance;
    }

    /**
     * Get unread messages count
     */
    // private function getUnreadMessagesCount($parent)
    // {
    //     return Message::where('recipient_id', $parent->id)
    //                  ->whereNull('read_at')
    //                  ->count();
    // }

    /**
     * Calculate child attendance statistics
     */
    private function calculateChildAttendanceStats($attendances)
    {
        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0
        ];
    }

    /**
     * Get recent activities for children
     */
    private function getRecentActivities($children)
    {
        $activities = collect();

        foreach ($children as $child) {
            // Recent grades
            $recentGrades = $child->grades()
                                ->with('assessment')
                                ->orderBy('created_at', 'desc')
                                ->take(3)
                                ->get();

            foreach ($recentGrades as $grade) {
                $activities->push([
                    'type' => 'grade',
                    'title' => "New grade: {$grade->assessment->title}",
                    'child_name' => $child->user->name,
                    'time' => $grade->created_at->diffForHumans(),
                    'icon' => 'chart-bar',
                    'color' => 'success',
                    'badge_color' => 'success',
                    'badge_text' => $grade->grade
                ]);
            }

            // Recent attendance
            $recentAttendance = $child->attendances()
                                    ->orderBy('date', 'desc')
                                    ->take(3)
                                    ->get();

            foreach ($recentAttendance as $attendance) {
                $activities->push([
                    'type' => 'attendance',
                    'title' => "Attendance: " . ucfirst($attendance->status),
                    'child_name' => $child->user->name,
                    'time' => $attendance->date->diffForHumans(),
                    'icon' => 'calendar-check',
                    'color' => $attendance->status === 'present' ? 'success' : 'warning',
                    'badge_color' => $attendance->status === 'present' ? 'success' : 'danger',
                    'badge_text' => ucfirst($attendance->status)
                ]);
            }
        }

        return $activities->sortByDesc('time')->take(10);
    }
}