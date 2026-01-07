<?php

// Home routes
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

// SuperAdmin routes
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UsersController as SuperAdminUsersController;

// SchoolAdmin routes
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
use App\Http\Controllers\SchoolAdmin\AssessmentTypeController as SchoolAdminAssessmentTypeController;
// Shared Routes between schooladmin and bursar
use App\Http\Controllers\SchoolAdmin\FeeStructureController as SchoolAdminFeeStructureController;
use App\Http\Controllers\SchoolAdmin\InvoiceController as SchoolAdminInvoiceController;

// Teacher routes
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherController as TeacherTeacherController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\GradeController as TeacherGradeController;
use App\Http\Controllers\Teacher\ReportCardController as TeacherReportCardController;


use App\Http\Controllers\Teacher\AnnouncementController as TeacherAnnouncementController;
use App\Http\Controllers\Teacher\AssessmentController as TeacherAssessmentController;
use App\Http\Controllers\Teacher\MessageController as TeacherMessageController;
use App\Http\Controllers\Teacher\ReportController as TeacherReportController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\BookController as TeacherBookController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\Parent\ParentController;

// Bursar Controller
use App\Http\Controllers\Bursar\DashboardController as BursarDashboardController;
use App\Http\Controllers\Bursar\PaymentController as BursarPaymentController;
use App\Http\Controllers\Bursar\ReportController as BursarReportController;


Route::get('/', function () {
    return view('welcome');
});

/**
 * Dynamic dashboard redirect based on user roles
 * Authenticated users are redirected to their respective dashboards
 */
Route::get('/dashboard', function () {
    $user = auth()->user();

    $routeName = match (true) {
        $user->hasRole('SuperAdmin')  => 'superadmin.dashboard',
        $user->hasRole('SchoolAdmin') => 'schooladmin.dashboard',
        $user->hasRole('Teacher')     => 'teacher.dashboard',
        $user->hasRole('Student')     => 'student.dashboard',
        $user->hasRole('Parent')      => 'parent.dashboard',
        $user->hasRole('Bursar')      => 'bursar.dashboard',
        default                       => 'home', 
    };

    return redirect()->route($routeName);
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

    // Bulk import and export routes
    Route::get('/students/export', [SchoolAdminStudentProfileController::class, 'export'])->name('students.export');
    Route::post('/students/import', [SchoolAdminStudentProfileController::class, 'import'])->name('students.import');
    Route::get('/students/download-template', [SchoolAdminStudentProfileController::class, 'downloadTemplate'])->name('students.download-template');

    // Assessments routes
    Route::get('/assessments', [SchoolAdminAssessmentTypeController::class, 'index'])->name('assessments.index');
    Route::post('/assessments', [SchoolAdminAssessmentTypeController::class, 'store'])->name('assessments.store');
    Route::delete('/assessments/{assessmentType}', [SchoolAdminAssessmentTypeController::class, 'destroy'])->name('assessments.destroy');

// });
    
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

    Route::get('/grades', [TeacherGradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [TeacherGradeController::class, 'create'])->name('grades.create');
    Route::post('/grades/store', [TeacherGradeController::class, 'store'])->name('grades.store');
    Route::post('/grades/lock', [TeacherGradeController::class, 'lock'])->name('grades.lock');

    Route::get('/reports', [TeacherReportCardController::class, 'index'])->name('reports.index');
    Route::get('/reports/download/{student}', [TeacherReportCardController::class,'download'])
    ->name('reports.download');


    Route::resource('assessments', TeacherAssessmentController::class);
    Route::resource('messages', TeacherMessageController::class);
    Route::resource('announcements', TeacherAnnouncementController::class);
    Route::resource('books', TeacherBookController::class);
   
});

// Student Routes
Route::middleware(['auth', 'role:Student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/timetable', [StudentController::class, 'timetable'])->name('timetable');
    Route::get('/results', [StudentController::class, 'results'])->name('student.results');
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('attendance');
    Route::get('/fees', [StudentController::class, 'fees'])->name('student.fees');
    Route::get('/assignments', [StudentController::class, 'assignment'])->name('assignments');
    Route::get('/subjects', [StudentController::class, 'subjects'])->name('subjects');
    Route::get('/teachers', [StudentController::class, 'teachers'])->name('teachers');
    Route::get('/messages', [StudentController::class, 'messages'])->name('messages');
    Route::get('/announcements', [StudentController::class, 'announcements'])->name('announcements');
    Route::get('/reports', [StudentController::class, 'index'])->name('reports.index');
    Route::get('/reports/download/{student}', [StudentController::class,'download'])
    ->name('reports.download');
    Route::get('/books', [StudentController::class, 'books'])->name('books');
    Route::get('/books/{book}/download', [StudentController::class, 'downloadBook'])->name('books.download');
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
Route::middleware(['auth', 'role:Bursar'])->prefix('bursar')->name('bursar.')->group(function () {
    Route::get('/dashboard', [BursarDashboardController::class, 'index'])->name('dashboard');
    Route::get('/payments/create/{invoice_id}', [BursarPaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/store', [BursarPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/receipt/{id}', [BursarPaymentController::class, 'show'])->name('payments.receipt');
    Route::get('/reports', [BursarReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-debtors', [BursarReportController::class, 'exportDebtors'])->name('reports.export_debtors');
});

//  Fees routes for schoolAdmin and Bursar (Allow BOTH roles)
Route::middleware(['auth', 'role:SuperAdmin|SchoolAdmin|Bursar']) 
    ->prefix('finance') 
    ->name('finance.')
    ->group(function () {
        // Fee Structures
        Route::resource('fee-structures', SchoolAdminFeeStructureController::class);
        // Invoices
        Route::get('invoices/generate', [SchoolAdminInvoiceController::class, 'create'])->name('invoices.generate');
        Route::post('invoices/generate', [SchoolAdminInvoiceController::class, 'store'])->name('invoices.store');
        Route::resource('invoices', SchoolAdminInvoiceController::class);
});


