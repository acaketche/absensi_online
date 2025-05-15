<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index(Request $request)
    {
        try {
             $books = BookLoan::where('id_student', $request->user()->id_student)
            ->with(['book', 'semester'])
            ->get()
            ->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'student_id' => $loan->id_student,
                    'book' => $loan->book->title ?? null, // Nama buku
                    'loan_date' => $loan->loan_date,
                    'due_date' => $loan->due_date,
                    'return_date' => $loan->return_date,
                    'status' => $loan->status,
                    'academic_year' => $loan->academicYear->year_name ?? null,
                    'semester' => $loan->semester->semester_name ?? null,
                ];
            });


            return response()->json([
                'message' => 'Get History Books Loan',
                'data' => [
                    'books' => $books
                ],
                'code' => 200,
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'code' => 400,
                'status' => 'error'
            ]);
        }
    }
}
