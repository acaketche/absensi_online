<?php

use App\Http\Controllers\API\StudentApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/students', [StudentApiController::class, 'getStudents']);
Route::get('/students/{id}', [StudentApiController::class, 'getStudentById']);

