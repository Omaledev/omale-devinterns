<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SchoolAdmin\DashboardController as SchoolAdminDashboardController;
use App\Http\Controllers\SchoolAdmin\StudentController as SchoolAdminStudentController;
use App\Http\Controllers\SchoolAdmin\TeacherController as SchoolAdminTeacherController;
use App\Http\Controllers\SchoolAdmin\ParentController as SchoolAdminParentController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\ParentController;
use App\Http\Controllers\Bursar\BursarController;
use App\Http\Controllers\Class\ClassController;
use App\Http\Controllers\Approval\ApprovalController;
use App\Http\Controllers\Subject\SubjectController;
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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// SuperAdmin Routes
Route::middleware(['auth', 'role:SuperAdmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::resource('schools', SchoolController::class);
    // Superadmin management routes
    Route::get('/schools/{school}/create-user', [SchoolController::class, 'createUser'])->name('schools.create-user');
    Route::post('/schools/{school}/store-user', [SchoolController::class, 'storeUser'])->name('schools.store-user');
    Route::get('/schools/{school}/users', [SchoolController::class, 'showUsers'])->name('schools.users');
    //     Route::get('/reports', [SuperAdminDashboardController::class, 'reports'])->name('superadmin.reports');
});

// SchoolAdmin Routes
Route::middleware(['auth', 'role:SchoolAdmin'])->prefix('admin')->name('schooladmin.')->group(function () {
    Route::get('/dashboard', [SchoolAdminDashboardController::class, 'index'])->name('dashboard');
     // Resource Routes
    Route::resource('students', SchoolAdminStudentController::class);
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
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
    // Route::resource('attendances', AttendanceController::class);
    // Route::resource('assessments', AssessmentController::class);
    // Route::resource('grades', GradeController::class);
    // Route::get('/my-classes', [TeacherController::class, 'myClasses'])->name('teacher.my-classes');
    // Route::get('/students/{class}', [TeacherController::class, 'classStudents'])->name('teacher.class-students');
});

// Student Routes
Route::middleware(['auth', 'role:Student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    // Route::get('/timetable', [StudentController::class, 'timetable'])->name('student.timetable');
    // Route::get('/results', [StudentController::class, 'results'])->name('student.results');
    // Route::get('/attendance', [StudentController::class, 'attendance'])->name('student.attendance');
    // Route::get('/fees', [StudentController::class, 'fees'])->name('student.fees');
});

// Parent Routes
Route::middleware(['auth', 'role:Parent'])->prefix('parent')->group(function () {
    Route::get('/dashboard', [ParentController::class, 'index'])->name('parent.dashboard');
    // Route::get('/children', [ParentController::class, 'children'])->name('parent.children');
    // Route::get('/child/{id}/results', [ParentController::class, 'childResults'])->name('parent.child-results');
    // Route::get('/child/{id}/attendance', [ParentController::class, 'childAttendance'])->name('parent.child-attendance');
    // Route::get('/child/{id}/fees', [ParentController::class, 'childFees'])->name('parent.child-fees');
});

// Bursar Routes
Route::middleware(['auth', 'role:Bursar'])->prefix('bursar')->group(function () {
    // Route::get('/dashboard', [BursarController::class, 'index'])->name('bursar.dashboard');
    // Route::resource('fee-structures', FeeStructureController::class);
    // Route::resource('invoices', InvoiceController::class);
    // Route::resource('payments', PaymentController::class);
    // Route::get('/reports/financial', [BursarController::class, 'financialReports'])->name('bursar.financial-reports');
});
