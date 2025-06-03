<?php

namespace App\Http\Controllers;

use App\Models\BookLoan;
use App\Models\Book;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Semester;
use Carbon\Carbon;


class BookLoanController extends Controller
{
    public function index()
        {
        $loans = BookLoan::with([
            'student.class',
            'book',
            'academicYear',
            'semester'
        ])->get();

            $classes = Classes::all();
            $books = Book::all();
            $students = Student::all();
            $academicYears = AcademicYear::all(); // Tambahkan ini

            return view('books.booksloans', compact('loans', 'classes', 'books', 'students', 'academicYears'));
        }

        public function edit($id)
        {
            $loan = BookLoan::findOrFail($id);
            $books = Book::all();
            $students = Student::all();
            $academicYears = AcademicYear::all();  // Tambahkan ini
            $semesters = Semester::all();          // Tambahkan ini jika butuh

            return view('book_loans.edit', compact('loan', 'books', 'students', 'academicYears', 'semesters'));
        }


    public function update(Request $request, $id)
    {
        $request->validate([
            'id_student' => 'required|string',
            'book_id' => 'required|integer',
            'loan_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'academic_year_id' => 'required|integer',
            'semester_id' => 'required|integer',
        ]);

        $loan = BookLoan::findOrFail($id);
        $loan->update($request->all());

        return redirect()->route('book-loans.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function create()
        {
            $books = Book::all();
            $students = Student::all();
            $academicYears = AcademicYear::all();
            $semesters = Semester::all();

            return view('book_loans.create', compact('books', 'students', 'academicYears', 'semesters'));
        }


        public function store(Request $request)
        {
            $validated = $request->validate([
                'id_student' => 'required|string',
                'book_id' => 'required|integer',
                'loan_date' => 'required|date',
                'due_date' => 'nullable|date',
                'status' => 'required|string|in:Dipinjam,Dikembalikan',
                'academic_year_id' => 'required|integer',
                'semester_id' => 'required|integer',
            ]);

            BookLoan::create([
                'id_student' => $validated['id_student'],
                'book_id' => $validated['book_id'],
                'loan_date' => $validated['loan_date'],
                'due_date' => $validated['due_date'],
                'status' => $validated['status'],
                'academic_year_id' => $validated['academic_year_id'],
                'semester_id' => $validated['semester_id'],
            ]);

            return redirect()->route('book-loans.index')->with('success', 'Peminjaman buku berhasil dicatat.');
        }


    public function show($id)
    {
        $loan = BookLoan::with(['book', 'student'])->findOrFail($id);
        return view('book_loans.show', compact('loan'));
    }

    public function returnBook($id)
    {
        $loan = BookLoan::findOrFail($id);
        $loan->update([
            'status' => 'Dikembalikan',
            'return_date' => now(),
        ]);

        return redirect()->route('book-loans.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function destroy($id)
    {
        $loan = BookLoan::findOrFail($id);
        $loan->delete();

        return redirect()->route('book-loans.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function classStudents($classId)
    {
        $class = Classes::findOrFail($classId);

        // Ambil semua siswa dan relasi peminjaman buku
        $students = Student::withCount(['bookLoans', 'overdueLoans'])
                    ->where('class_id', $classId)
                    ->get();

        // Hitung total semua buku yang dipinjam oleh siswa di kelas ini
        $totalBookLoans = $students->sum('book_loans_count');

        return view('books.classtudent', compact('class', 'students', 'totalBookLoans'));
    }


    public function studentBooks($id)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();
        $student = Student::with('class')->findOrFail($id);

        $bookLoans = BookLoan::with('book')
            ->where('id_student', $student->id_student)
            ->orderByDesc('loan_date')
            ->get();

        $overdueCount = $bookLoans->where('status', 'borrowed')->filter(function ($loan) {
            return Carbon::now()->gt($loan->due_date);
        })->count();

        $books = Book::all();

        // Tambahkan academicYears dan semesters
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('books.studentbook', compact('student', 'bookLoans', 'overdueCount', 'books',
        'academicYears', 'semesters','activeAcademicYear', 'activeSemester'));
    }

public function markAsReturned($id)
{
    $loan = BookLoan::findOrFail($id);

    if ($loan->status === 'Dipinjam') {
        $loan->status = 'Dikembalikan';
        $loan->return_date = Carbon::now();
        $loan->save();
    }

    return redirect()->back()->with('success', 'Buku berhasil ditandai sebagai dikembalikan.');
}

public function markAsUnreturned($id)
{
    $loan = BookLoan::findOrFail($id);

    if ($loan->status === 'Dikembalikan') {
        $loan->status = 'Dipinjam';
        $loan->return_date = null; // kosongkan tanggal pengembalian
        $loan->save();
    }

    return redirect()->back()->with('success', 'Status dikembalikan menjadi Dipinjam.');
}
public function print($id_student)
{
    $student = Student::where('id_student', $id_student)->firstOrFail();
    $bookLoans = BookLoan::with(['book', 'student'])
        ->where('id_student', $id_student)
        ->orderBy('loan_date', 'desc')
        ->get();

    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('books.printbookloans', [
        'student' => $student,
        'bookLoans' => $bookLoans,
        'activeAcademicYear' => $activeAcademicYear,
        'printDate' => now()->format('d F Y')
    ]);

    return $pdf->download('laporan-peminjaman-'.$student->id_student.'.pdf');
}
}
