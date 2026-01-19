<?php
// routes/api_v1.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TimetableController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\InvoiceController;



/*
|--------------------------------------------------------------------------
| API Version 1 Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// // Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
       // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Timetable routes
    Route::prefix('timetable')->group(function () {
        Route::get('/student', [TimetableController::class, 'forStudent']);
        Route::get('/teacher', [TimetableController::class, 'forTeacher']);
        Route::get('/class/{class}', [TimetableController::class, 'forClass']);
        Route::get('/weekly', [TimetableController::class, 'weeklyView']);
    });
    
    // Grade routes
    Route::prefix('grades')->group(function () {
        // GET /api/v1/grades/student (with option: ?term_id=1)
        Route::get('/student/{termId?}', [GradeController::class, 'forStudent']);
        // GET /api/v1/grades/teacher (with optional: ?class_id=1&subject_id=2)
        Route::get('/teacher', [GradeController::class, 'forTeacher']);
    });

    Route::prefix('invoices')->group(function () {
        // Student/Parent View
        Route::get('/student', [InvoiceController::class, 'forStudent']);
        
        // Admin/Bursar Summary
        Route::get('/summary', [InvoiceController::class, 'summary']);
        
        // Specific Invoice (Shared by all roles)
        Route::get('/{id}', [InvoiceController::class, 'show']);
    });
});