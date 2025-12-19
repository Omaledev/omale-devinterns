<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UsersController ;
use App\Http\Controllers\SuperAdmin\UsersController as SuperAdminUsersController;
use App\Http\Controllers\SchoolAdmin\DashboardController as SchoolAdminDashboardController;
use App\Http\Controllers\SchoolAdmin\StudentProfileController as SchoolAdminStudentProfileController;
use App\Http\Controllers\SchoolAdmin\TeacherProfileController as SchoolAdminTeacherProfileController;
use App\Http\Controllers\SchoolAdmin\ParentProfileController as SchoolAdminParentProfileController;
use App\Http\Controllers\SchoolAdmin\ClassLevelController as SchoolAdminClassLevelController;
use App\Http\Controllers\SchoolAdmin\SectionController as SchoolAdminSectionController;
use App\Http\Controllers\SchoolAdmin\SubjectController as SchoolAdminSubjectController;
use App\Http\Controllers\SchoolAdmin\BursarController as SchoolAdminBursarController;
use App\Http\Controllers\SchoolAdmin\TeacherAssignmentController as SchoolAdminTeacherAssignmentController;
use App\Http\Controllers\SchoolAdmin\AcademicSessionController as SchoolAdminAcademicSessionController;
use App\Http\Controllers\SchoolAdmin\TermController as SchoolAdminTermController;
use App\Http\Controllers\SchoolAdmin\TimetableController as SchoolAdminTimetableController;

use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;

use App\Http\Controllers\Teacher\AnnouncementController as TeacherAnnouncementController;
use App\Http\Controllers\Teacher\AssessmentController as TeacherAssessmentController;
use App\Http\Controllers\Teacher\GradeController as TeacherGradeController;
use App\Http\Controllers\Teacher\MessageController as TeacherMessageController;
use App\Http\Controllers\Teacher\ReportController as TeacherReportController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\BookController as TeacherBookController;
use App\Http\Controllers\Teacher\TeacherController as TeacherTeacherController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\Parent\ParentController;
use App\Http\Controllers\Bursar\DashboardController as BursarDashboardController;
use App\Http\Controllers\Bursar\BursarController;
use App\Http\Controllers\ClassLevel\ClassLevelController;
use App\Http\Controllers\Section\SectionController;
use App\Http\Controllers\Subject\SubjectController;
use App\Http\Controllers\Class\ClassController;
use App\Http\Controllers\Approval\ApprovalController;
use App\Http\Controllers\Timetable\TimetableController;
use App\Http\Controllers\Assessment\AssessmentController;
use App\Http\Controllers\Grade\GradeController;
use App\Http\Controllers\FeeStructure\FeeStructureController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Payment\PaymentController;


Route::get('/', function () {
    return view('welcome');
});

/**
 * Dynamic dashboard redirect based on user roles
 * Authenticated users are redirected to their respective dashboards
 */
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('SuperAdmin')) {
        return redirect()->route('superadmin.dashboard');
    } elseif ($user->hasRole('SchoolAdmin')) {
        return redirect()->route('schooladmin.dashboard'); 
    } elseif ($user->hasRole('Teacher')) {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->hasRole('Student')) {
        return redirect()->route('student.dashboard');
    } elseif ($user->hasRole('Parent')) {
        return redirect()->route('parent.dashboard');
    } elseif ($user->hasRole('Bursar')) {
        return redirect()->route('bursar.dashboard');
    }
    
    return redirect('/home');
})->name('dashboard')->middleware('auth'); 

// Auth::routes();

Route::get('/login', function () {
    return redirect()->route('sign-in');
})->name('login'); 

Route::get('/register', function () {
    return redirect()->route('sign-up');
})->name('register'); 

// Decided to use custom Auth Routes with URLs
Route::get('/sign-in', [LoginController::class, 'showLoginForm'])->name('sign-in');
Route::post('/sign-in', [LoginController::class, 'login']);
Route::post('/sign-out', [LoginController::class, 'logout'])->name('sign-out');

Route::get('/sign-up', [RegisterController::class, 'showRegistrationForm'])->name('sign-up');
Route::post('/sign-up', [RegisterController::class, 'register']);

// Password Reset Routes 
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// SuperAdmin Routes
Route::middleware(['auth', 'role:SuperAdmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    // Route::get('/users/index', [UsersController::class, 'index'])->name('superadmin.users.index');
    Route::resource('users', SuperAdminUsersController::class);
    
    Route::resource('schools', SchoolController::class)->names([
        'index' => 'schools.index',
        'create' => 'schools.create',
        'store' => 'schools.store',
        'show' => 'schools.show',
        'edit' => 'schools.edit',
        'update' => 'schools.update',
        'destroy' => 'schools.destroy'
    ]);
    // Superadmin management routes
    Route::get('/schools/{school}/create-user', [SchoolController::class, 'createUser'])->name('schools.create-user');
    Route::post('/schools/{school}/store-user', [SchoolController::class, 'storeUser'])->name('schools.store-user');
    Route::get('/schools/{school}/users', [SchoolController::class, 'showUsers'])->name('schools.users');
});

// SchoolAdmin Routes
Route::middleware(['auth', 'role:SchoolAdmin'])->prefix('admin')->name('schooladmin.')->group(function () {
    Route::get('/dashboard', [SchoolAdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/students/export', [SchoolAdminStudentProfileController::class, 'export'])->name('students.export');
    Route::post('/students/import', [SchoolAdminStudentProfileController::class, 'import'])->name('students.import');
    Route::get('/students/download-template', [SchoolAdminStudentProfileController::class, 'downloadTemplate'])->name('students.download-template');
    
     // Resource Routes
    Route::resource('students', SchoolAdminStudentProfileController::class);
    Route::resource('class-levels', SchoolAdminClassLevelController::class);
    Route::resource('sections', SchoolAdminSectionController::class);
    Route::resource('subjects', SchoolAdminSubjectController::class);
    Route::resource('teachers', SchoolAdminTeacherProfileController::class);
    Route::resource('parents', SchoolAdminParentProfileController::class);
    Route::resource('bursars', SchoolAdminBursarController::class);
    Route::resource('teacher-assignments', SchoolAdminTeacherAssignmentController::class);
    
    Route::resource('academic-sessions', SchoolAdminAcademicSessionController::class);
    Route::post('academic-sessions/{session}/activate', [SchoolAdminAcademicSessionController::class, 'activate'])->name('academic-sessions.activate');

    Route::resource('terms', SchoolAdminTermController::class);
    Route::post('terms/{term}/activate', [SchoolAdminTermController::class, 'activate'])->name('terms.activate');

    Route::get('timetables/export', [SchoolAdminTimetableController::class, 'export'])->name('timetables.export');
    Route::resource('timetables', SchoolAdminTimetableController::class);

});

// Teacher Routes
Route::middleware(['auth', 'role:Teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    // Attendance Routes
    Route::get('/attendance/select', [TeacherAttendanceController::class, 'select'])->name('attendance.select');
    Route::post('/attendance/create', [TeacherAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [TeacherAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/summary', [TeacherAttendanceController::class, 'summary'])->name('attendance.summary');

    Route::get('/my-classes', [TeacherTeacherController::class, 'myClasses'])->name('my-classes');
     Route::get('/my-students', [TeacherTeacherController::class, 'myStudents'])->name('my-students');
    Route::get('/students/{class}', [TeacherTeacherController::class, 'classStudents'])->name('class-students');


    Route::resource('students', TeacherStudentController::class);
    Route::resource('assessments', TeacherAssessmentController::class);
    // Route::resource('assignments', TeacherAssignmentController::class);
    Route::resource('grades', TeacherGradeController::class);
    Route::resource('messages', TeacherMessageController::class);
    Route::resource('announcements', TeacherAnnouncementController::class);
    Route::resource('reports', TeacherReportController::class);
    Route::resource('books', TeacherBookController::class);
   
});

// Student Routes
Route::middleware(['auth', 'role:Student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/timetable', [StudentController::class, 'timetable'])->name('student.timetable');
    Route::get('/results', [StudentController::class, 'results'])->name('student.results');
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('student.attendance');
    Route::get('/fees', [StudentController::class, 'fees'])->name('student.fees');
    Route::get('/assignments', [StudentController::class, 'assignment'])->name('student.assignments');
    Route::get('/subjects', [StudentController::class, 'subjects'])->name('student.subjects');
    Route::get('/teachers', [StudentController::class, 'teachers'])->name('student.teachers');
    Route::get('/messages', [StudentController::class, 'messages'])->name('student.messages');
    Route::get('/announcements', [StudentController::class, 'announcements'])->name('student.announcements');
    Route::get('/books', [StudentController::class, 'books'])->name('student.books');
    Route::get('/books/{book}/download', [StudentController::class, 'downloadBook'])->name('student.books.download');
});

// Parent Routes
Route::middleware(['auth', 'role:Parent'])->prefix('parent')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
    Route::get('/children', [ParentController::class, 'children'])->name('parent.children');
    Route::get('/child/{id}/results', [ParentController::class, 'childResults'])->name('parent.child-results');
    Route::get('/child/{id}/attendance', [ParentController::class, 'childAttendance'])->name('parent.child-attendance');
    Route::get('/child/{id}/fees', [ParentController::class, 'childFees'])->name('parent.child-fees');
    Route::get('/attendance', [ParentController::class, 'attendance'])->name('parent.attendance');
    Route::get('/results', [ParentController::class, 'results'])->name('parent.results');
    Route::get('/fees', [ParentController::class, 'fees'])->name('parent.fees');
    Route::get('/timetable', [ParentController::class, 'timetable'])->name('parent.timetable');
    Route::get('/teachers', [ParentController::class, 'teachers'])->name('parent.teachers');
    Route::get('/messages', [ParentController::class, 'messages'])->name('parent.messages');
    Route::get('/announcements', [ParentController::class, 'announcements'])->name('parent.announcements');
    Route::get('/meetings', [ParentController::class, 'meetings'])->name('parent.meetings');
});

// Bursar Routes
Route::middleware(['auth', 'role:Bursar'])->prefix('bursar')->group(function () {
    Route::get('/dashboard', [BursarDashboardController::class, 'index'])->name('bursar.dashboard');
    // Route::resource('fee-structures', FeeStructureController::class);
    // Route::resource('invoices', InvoiceController::class);
    // Route::resource('payments', PaymentController::class);
    // Route::get('/reports/financial', [BursarController::class, 'financialReports'])->name('bursar.financial-reports');
});
