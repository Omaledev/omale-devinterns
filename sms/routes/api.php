<?php
// routes/api.php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Master API Routes
|--------------------------------------------------------------------------
|
| This file handles all API routing and versioning
|
*/

// API Welcome/Info endpoint
Route::get('/', function () {
    return response()->json([
        'app' => 'School Management System API',
        'version' => '1.0.0',
        'status' => 'operational',
        'timestamp' => now()->toDateTimeString(),
        'available_versions' => ['v1'],
        'documentation' => url('/api/documentation'),
    ]);
});

// API v1 routes
Route::prefix('v1')->group(function () {
    require __DIR__.'/api_v1.php';
});

// API v2 
// Route::prefix('v2')->group(function () {
//     require __DIR__.'/api_v2.php';
// });