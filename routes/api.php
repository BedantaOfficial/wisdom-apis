<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Route for getting the token
Route::post('/auth/token', [AdminController::class, 'getToken']);

Route::prefix('v1')->middleware('tokencheck')->group(function () {
    // Simple Hello World route
    Route::get('/', function () {
        return response()->json("Hello, World");
    });

    // Students route
    Route::get('students', [StudentController::class, 'index']);



    // Attendance routes
    Route::prefix('attendance')->group(function () {
        // Fetch date range for a specific student by year and month (GET request)
        Route::get('/range', [AttendanceController::class, 'getAttendanceDateRange']);
        // Store attendance (POST request to store attendance for a student on a specific date)
        Route::post('/', [AttendanceController::class, 'store']);
        // Fetch attendance report for a specific student by year and month (GET request)
        Route::get('/', [AttendanceController::class, 'index']);
    });



    // Certificates route
    Route::get('certificates', [CertificateController::class, 'index']);
});
