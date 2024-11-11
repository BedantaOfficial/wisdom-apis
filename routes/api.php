<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/', function () {
    return response()->json("Hello, World");
});


Route::get('v1/students', [StudentController::class, 'index']);

// Attendance routes
Route::prefix('v1/attendance')->group(function () {
    // Fetch date range for a specific student by year and month (GET request)
    Route::get('/range', [AttendanceController::class, 'getAttendanceDateRange']);
    // Store attendance (POST request to store attendance for a student on a specific date)
    Route::post('/', [AttendanceController::class, 'store']);

    // Fetch attendance report for a specific student by year and month (GET request)
    Route::get('/', [AttendanceController::class, 'index']);  // Fetch attendance by student_id, year, and month
});

Route::get('v1/certificates', [CertificateController::class, 'index']);
