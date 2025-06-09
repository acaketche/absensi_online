<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    BookController,
    BookLoanController
};

Route::middleware(['web', 'auth:employee', 'role:Admin Perpustakaan'])->group(function () {
    Route::get('/perpus/dashboard', [DashboardController::class, 'perpus'])->name('dashboard.perpus');

    // Book Management
    Route::resource('books', BookController::class);

    // Book Loans
    Route::get('/book-loans', [BookLoanController::class, 'index'])->name('book-loans.index');
    Route::get('/book-loans/classes/{classId}/students', [BookLoanController::class, 'classStudents'])->name('book-loans.class-students');
    Route::get('/book-loans/students/{studentId}/books', [BookLoanController::class, 'studentBooks'])->name('book-loans.student-books');
    Route::get('/book-loans/student/{id}', [BookLoanController::class, 'studentLoans'])->name('book-loans.student');
    Route::put('/book-loans/return/{id}', [BookLoanController::class, 'markAsReturned'])->name('book.return');
    Route::put('/book-loans/unreturn/{id}', [BookLoanController::class, 'markAsUnreturned'])->name('book.unreturn');
    Route::get('/book-loans/print/{id_student}', [BookLoanController::class, 'print'])->name('book-loans.print');
    Route::post('/book-loans', [BookLoanController::class, 'store'])->name('book-loans.store');
});
