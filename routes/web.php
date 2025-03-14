<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('students', StudentController::class);
Route::resource('classes', ClassesController::class);

Route::get('/login', function () {
    return view('auth.Login');
});
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SemesterController;

// Routes untuk Tahun Akademik
Route::resource('academicyear', AcademicYearController::class);

// Routes untuk Semester
Route::resource('semesters', SemesterController::class);

use App\Http\Controllers\StatusController;

// Endpoint untuk mengubah status tahun ajaran
Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);

// Endpoint untuk mengubah status semester
Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);

use App\Http\Controllers\SubjectController;

Route::resource('subjects', SubjectController::class);

use App\Http\Controllers\HolidaysController;

Route::resource('holidays', HolidaysController::class);
