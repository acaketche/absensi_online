<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\BookLoan;
use Carbon\Carbon;

class BookLoanController extends Controller
{
    public function index()
    {
        $classes = Classes::with('employee')
            ->withCount('students')
            ->withCount(['students as borrowed_books_count' => function($query) {
                $query->whereHas('bookLoans', function($q) {
                    $q->where('status', 'borrowed');
                });
            }])
            ->withCount(['students as overdue_loans_count' => function($query) {
                $query->whereHas('bookLoans', function($q) {
                    $q->where('status', 'borrowed')
                      ->whereDate('due_date', '<', Carbon::now());
                });
            }])
            ->get();

        return view('books.booksloans', compact('classes'));
    }

    public function classStudents($classId)
    {
        $class = Classes::with('employee')->findOrFail($classId);

        $students = Student::where('class_id', $classId)
            ->withCount(['bookLoans' => function($query) {
                $query->where('status', 'borrowed');
            }])
            ->withCount(['bookLoans as overdue_loans_count' => function($query) {
                $query->where('status', 'borrowed')
                      ->whereDate('due_date', '<', Carbon::now());
            }])
            ->get();

        $totalBookLoans = $students->sum('book_loans_count');

        return view('books.classtudent', compact('class', 'students', 'totalBookLoans'));
    }

    public function studentBooks($studentId)
    {
        $student = Student::with('class')->where('id_student', $studentId)->firstOrFail();

        $bookLoans = BookLoan::with('book')
            ->where('id_student', $studentId)
            ->orderBy('status')
            ->orderBy('due_date')
            ->get();

        $overdueCount = $bookLoans->where('status', 'borrowed')
            ->filter(function($loan) {
                return Carbon::now()->gt($loan->due_date);
            })
            ->count();

        return view('books.studentbook', compact('student', 'bookLoans', 'overdueCount'));
    }
}
