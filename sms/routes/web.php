<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UsersController;
use App\Http\Controllers\SchoolAdmin\DashboardController as SchoolAdminDashboardController;
use App\Http\Controllers\SchoolAdmin\StudentController as SchoolAdminStudentController;
use App\Http\Controllers\SchoolAdmin\TeacherController as SchoolAdminTeacherController;
use App\Http\Controllers\SchoolAdmin\ParentController as SchoolAdminParentController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherController;
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
use App\Http\Controllers\Attendance\AttendanceController;
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
Route::middleware(['auth', 'role:SuperAdmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/users/index', [UsersController::class, 'index'])->name('superadmin.users.index');
    
    Route::resource('schools', SchoolController::class)->names([
        'index' => 'superadmin.schools.index',
        'create' => 'superadmin.schools.create',
        'store' => 'superadmin.schools.store',
        'show' => 'superadmin.schools.show',
        'edit' => 'superadmin.schools.edit',
        'update' => 'superadmin.schools.update',
        'destroy' => 'superadmin.schools.destroy'
    ]);
    // Superadmin management routes
    Route::get('/schools/{school}/create-user', [SchoolController::class, 'createUser'])->name('superadmin.schools.create-user');
    Route::post('/schools/{school}/store-user', [SchoolController::class, 'storeUser'])->name('superadmin.schools.store-user');
    Route::get('/schools/{school}/users', [SchoolController::class, 'showUsers'])->name('superadmin.schools.users');
});

// SchoolAdmin Routes
Route::middleware(['auth', 'role:SchoolAdmin'])->prefix('admin')->name('schooladmin.')->group(function () {
    Route::get('/dashboard', [SchoolAdminDashboardController::class, 'index'])->name('dashboard');
     // Resource Routes
    Route::resource('students', SchoolAdminStudentController::class);
    Route::resource('class-levels', ClassLevelController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    // Route::resource('teachers', SchoolAdminTeacherController::class);
    // Route::resource('parents', SchoolAdminParentController::class);
    // Route::resource('classes', ClassController::class);

    // Approval routes
    // Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    // Route::post('/approve-user/{user}', [ApprovalController::class, 'approve'])->name('approve-user');
    // Route::get('/reports', [SchoolAdminDashboardController::class, 'reports'])->name('schooladmin.reports');
});

// Teacher Routes
Route::middleware(['auth', 'role:Teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    Route::resource('students', StudentController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('assessments', AssessmentController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('reports', ReportController::class);
    Route::get('/classes', [TeacherController::class, 'Classes'])->name('teacher.classes');
    Route::get('/students/{class}', [TeacherController::class, 'classStudents'])->name('teacher.class-students');
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
    Route::resource('fee-structures', FeeStructureController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::get('/reports/financial', [BursarController::class, 'financialReports'])->name('bursar.financial-reports');
});
