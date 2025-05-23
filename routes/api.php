<?php

use Illuminate\Support\Facades\Route;

Route::post('/login-student', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.login.student');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout-student', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.logout.student');

    Route::get('profile-student', [App\Http\Controllers\Api\StudentController::class, 'profile'])->name('api.profile');
    Route::post('profile-update', [App\Http\Controllers\Api\StudentController::class, 'editProfile'])->name('api.profile.update');

    Route::get('book-loan', [App\Http\Controllers\Api\BooksController::class, 'index'])->name('api.book-loan');

    Route::get('payments-history', [App\Http\Controllers\Api\PaymentsController::class, 'index'])->name('api.payments.history');

    Route::get('student-acchievement', [App\Http\Controllers\Api\StudentAchievementController::class, 'index'])->name('api.student.acchievement');

    Route::post('/student/attendance/check-in', [App\Http\Controllers\API\AttendanceController::class, 'studentAttendance'])->name('api.student.attendance.checkin');
    Route::get('/student/attendance/histories', [App\Http\Controllers\API\AttendanceController::class, 'getHistories'])->name('api.student.attendance.histories');
    Route::get('/student/attendance/now', [App\Http\Controllers\API\AttendanceController::class, 'getAttendanceNow'])->name('api.student.attendance.now');
    Route::post('/student/attendance/check-out', [App\Http\Controllers\API\AttendanceController::class, 'checkoutAttendance'])->name('api.student.attendance.checkout');
});
