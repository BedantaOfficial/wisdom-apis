<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Route for getting the token
Route::post('/auth/token', [AdminController::class, 'getToken']);
/*
    **** Use This For Getting The Token
    **** Send POST request to this endpoint
    **** Body should contain username and password
    **** username admin 
    **** password 12345
*/

Route::prefix('v1')->middleware('tokencheck')->group(function () {

    // Students route
    Route::prefix('students')->group(function () {
        Route::get('/course-range', [StudentController::class, 'getCourseRange']);
        Route::get('/', [StudentController::class, 'index']);
    });




    // Attendance routes
    Route::prefix('attendance')->group(function () {
        Route::get('/all', [AttendanceController::class, 'getAllAttendance']);
        Route::get('/range', [AttendanceController::class, 'getAttendanceDateRange']);
        Route::post('/', [AttendanceController::class, 'store']);
        Route::get('/', [AttendanceController::class, 'index']);
    });


    // Paymetns routes
    Route::prefix('payments')->group(function () {
        Route::get('/all', [PaymentController::class, 'getAllPaymentsOfStudent']);
        Route::get('/', [PaymentController::class, 'index']);
    });



    // Certificates route
    Route::prefix('certificates')->group(function () {
        Route::get('/', [CertificateController::class, 'index']);
        Route::post('/', [CertificateController::class, 'store']);
    });
});
