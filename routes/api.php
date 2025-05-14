<?php

use Illuminate\Support\Facades\Route;

Route::post('/login-student', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.login.student');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout-student', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.logout.student');

    Route::get('class/{id}/students', function ($id) {
        $class = \App\Models\Classes::findOrFail($id);
        $students = \App\Models\Student::where('class_id', $id)->get();
        return response()->json([
            'class_name' => $class->name,
            'students' => $students
        ]);
    });

    Route::get('student/{id}/loans', function ($id) {
        $loans = \App\Models\BookLoan::with('book')->where('id_student', $id)->get();
        return response()->json($loans);
    });

    Route::get('profile-student', [App\Http\Controllers\Api\StudentController::class, 'profile'])->name('api.profile');
    Route::post('profile-update', [App\Http\Controllers\Api\StudentController::class, 'editProfile'])->name('api.profile.update');

    // Route::get('cek-location', [App\Http\Controllers\Api\StudentController::class, 'cekLocation'])->name('api.cek.location');
    Route::get('book-loan/{id}', [App\Http\Controllers\Api\BooksController::class, 'index'])->name('api.book-loan');

    Route::get('payments-history', [App\Http\Controllers\Api\PaymentsController::class, 'index'])->name('api.payments.history');
});
