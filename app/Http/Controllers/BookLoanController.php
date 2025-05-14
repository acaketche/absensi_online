<?php

namespace App\Http\Controllers;

use App\Models\BookLoan;
use App\Models\Book;
use App\Models\Student;
use App\Models\Classes; // Perubahan nama model Kelas
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Semester;



class BookLoanController extends Controller
{
    public function index()
        {
            $loans = BookLoan::with([
                'student.classes',
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
        return view('book_loans.edit', compact('loan', 'books', 'students'));
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
                'due_date' => 'required|date',
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

    public function kelasSiswa()
    {
        $classes = Classes::all(); // Ambil semua data kelas dari model Classes
        return view('books.kelas_siswa_peminjaman', compact('classes')); // Tampilkan view dengan data kelas
    }
}
