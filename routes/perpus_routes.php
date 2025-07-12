<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    BookController,
    BookLoanController,
    BookCopyController
};

Route::middleware(['web', 'auth:employee', 'role:Admin Perpustakaan'])->group(function () {
    Route::get('/perpus/dashboard', [DashboardController::class, 'perpus'])->name('dashboard.perpus');

    // Library Management
    Route::resource('books', BookController::class)->except('show');
    Route::get('/export', [BookController::class, 'export'])->name('books.export');
    Route::post('/import', [BookController::class, 'import'])->name('books.import');
    // Download template
    Route::get('/template', [BookController::class, 'downloadTemplate'])->name('books.download-template');

    Route::get('/books/{book}/copies', [BookCopyController::class, 'showCopies'])->name('books.copies.show');
    Route::post('/books/{book}/copies/store', [BookCopyController::class, 'storeCopies'])->name('books.copies.store');
    Route::get('/api/books/{book}/available-copies', [BookCopyController::class, 'availableCopies']);
    Route::put('/book-copies/{bookCopy}', [BookCopyController::class, 'update'])->name('book-copies.update');
    Route::delete('/book-copies/{bookCopy}', [BookCopyController::class, 'destroy'])->name('book-copies.destroy');

    // Book Loans
    Route::get('/book-loans', [BookLoanController::class, 'index'])->name('book-loans.index');
    Route::get('/book-loans/classes/{classId}/students', [BookLoanController::class, 'classStudents'])->name('book-loans.class-students');
    Route::get('/book-loans/students/{studentId}/books', [BookLoanController::class, 'studentBooks'])->name('book-loans.student-books');
    Route::get('/book-loans/student/{id}', [BookLoanController::class, 'studentLoans'])->name('book-loans.student');
    Route::put('/book-loans/return/{id}', [BookLoanController::class, 'markAsReturned'])->name('book.return');
    Route::put('/book-loans/unreturn/{id}', [BookLoanController::class, 'markAsUnreturned'])->name('book.unreturn');
    Route::get('/book-loans/print/{id_student}', [BookLoanController::class, 'print'])->name('book-loans.print');
    Route::post('/book-loans', [BookLoanController::class, 'store'])->name('book-loans.store');
    Route::get('/book-loans/export-template', [BookLoanController::class, 'exportTemplate'])->name('book-loans.export');
    Route::get('/bookloans/export/class/{classId}', [BookLoanController::class, 'exportByClass'])->name('export.bookloan.class');
    Route::post('/book-loans/import', [BookLoanController::class, 'import'])->name('book-loans.import');
});
