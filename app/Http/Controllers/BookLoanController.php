<?php

namespace App\Http\Controllers;

use App\Models\BookLoan;
use App\Models\Book;
use App\Models\BookCopy;
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
        $classes = Classes::with('students')->get();
        $students = Student::withCount('borrowedBooks')->get();

        $classLoans = [];
        foreach ($classes as $class) {
            $studentsInClass = $students->where('class_id', $class->id);
            $totalLoans = $studentsInClass->sum('borrowed_books_count');
            $classLoans[$class->id] = $totalLoans;
        }

        return view('books.booksloans', compact('classes', 'classLoans'));
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
            'copy_id' => 'required|integer', // ✅ Tambah validasi copy_id
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date',
            'status' => 'required|string|in:Dipinjam,Dikembalikan',
            'academic_year_id' => 'required|integer',
            'semester_id' => 'required|integer',
        ]);

        $loan = BookLoan::create($validated);

        $this->syncBookCopyAvailability($validated['copy_id']); // ✅ Sync berdasarkan copy_id

        if ($request->ajax()) {
            return response()->json(['message' => 'Berhasil disimpan']);
        }

        return redirect()->back()->with('success', 'Berhasil disimpan');
    }

    public function edit($id)
    {
        $loan = BookLoan::findOrFail($id);
        $books = Book::all();
        $students = Student::all();
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('book_loans.edit', compact('loan', 'books', 'students', 'academicYears', 'semesters'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_student' => 'required|string',
            'book_id' => 'required|integer',
            'copy_id' => 'required|integer', // ✅ Pastikan copy_id tetap dipakai
            'loan_date' => 'required|date',
            'return_date' => 'required|date',
            'status' => 'required|string|in:Dipinjam,Dikembalikan',
            'academic_year_id' => 'required|integer',
            'semester_id' => 'required|integer',
        ]);

        $loan = BookLoan::findOrFail($id);
        $loan->update($validated);

        $this->syncBookCopyAvailability($validated['copy_id']);

        return redirect()->route('book-loans.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $loan = BookLoan::findOrFail($id);
        $copyId = $loan->copy_id;
        $loan->delete();

        $this->syncBookCopyAvailability($copyId);

        return redirect()->route('book-loans.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function returnBook($id)
    {
        $loan = BookLoan::findOrFail($id);
        $loan->update([
            'status' => 'Dikembalikan',
            'return_date' => now(),
        ]);

        $this->syncBookCopyAvailability($loan->copy_id);

        return redirect()->route('book-loans.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function markAsReturned($id)
    {
        $loan = BookLoan::findOrFail($id);

        if ($loan->status === 'Dipinjam') {
            $loan->status = 'Dikembalikan';
            $loan->return_date = Carbon::now();
            $loan->save();

            $this->syncBookCopyAvailability($loan->copy_id);
        }

        return redirect()->back()->with('success', 'Buku berhasil ditandai sebagai dikembalikan.');
    }

    public function markAsUnreturned($id)
    {
        $loan = BookLoan::findOrFail($id);

        if ($loan->status === 'Dikembalikan') {
            $loan->status = 'Dipinjam';
            $loan->return_date = null;
            $loan->save();

            $this->syncBookCopyAvailability($loan->copy_id);
        }

        return redirect()->back()->with('success', 'Status dikembalikan menjadi Dipinjam.');
    }

    public function classStudents($classId)
    {
        $class = Classes::findOrFail($classId);
        $students = Student::withCount(['borrowedBooks', 'returnedBooks'])
            ->where('class_id', $classId)
            ->get();

        $totalBookLoans = $students->sum('borrowed_books_count');
        $totalBookReturns = $students->sum('returned_books_count');

        return view('books.classtudent', compact('class', 'students', 'totalBookLoans', 'totalBookReturns'));
    }

    public function studentBooks($id)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();
        $student = Student::with('class')->findOrFail($id);

        $bookLoans = BookLoan::with('book','copy')
            ->where('id_student', $student->id_student)
            ->orderByDesc('loan_date')
            ->get();

        $books = Book::all();
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('books.studentbook', compact(
            'student', 'bookLoans', 'books',
            'academicYears', 'semesters',
            'activeAcademicYear', 'activeSemester'
        ));
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

    // ✅ Update status salinan berdasarkan status terakhir peminjaman
    protected function syncBookCopyAvailability($copyId)
    {
        $copy = BookCopy::find($copyId);

        if ($copy) {
            $latestLoan = $copy->loans()->latest()->first();

            if ($latestLoan) {
                $copy->is_available = $latestLoan->status === 'Dikembalikan' ? 1 : 0;
            } else {
                $copy->is_available = 1; // tidak ada loan, artinya tersedia
            }

            $copy->save();
        }
    }
}
