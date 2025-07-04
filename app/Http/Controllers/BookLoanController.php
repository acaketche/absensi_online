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
use App\Models\StudentSemester;
use Carbon\Carbon;
use App\Exports\BookLoansExport;
use App\Exports\SingleClassBookLoanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BookLoansImport;
use App\Imports\BookLoansMultiSheetImport;

class BookLoanController extends Controller
{
    public function index()
{
    // Ambil tahun ajaran dan semester aktif
    $activeAcademicYear = AcademicYear::where('is_active', true)->first();
    $activeSemester = Semester::where('is_active', true)->first();

    if (!$activeAcademicYear || !$activeSemester) {
        return redirect()->back()->with('error', 'Tahun ajaran atau semester aktif tidak ditemukan.');
    }

    // Ambil kelas yang sesuai dengan tahun ajaran aktif saja
    $classes = Classes::with(['students' => function ($query) {
        $query->withCount(['borrowedBooks']);
    }, 'academicYear', 'employee'])
    ->where('academic_year_id', $activeAcademicYear->id)
    ->get();

    // Hitung total pinjaman per kelas
    $classLoans = [];
    foreach ($classes as $class) {
        $totalLoans = $class->students->sum('borrowed_books_count');
        $classLoans[$class->class_id] = $totalLoans;
    }

    return view('books.booksloans', compact('classes', 'classLoans', 'activeAcademicYear', 'activeSemester'));
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

    // Ambil tahun ajaran dan semester aktif
    $activeAcademicYear = AcademicYear::where('is_active', true)->first();
    $activeSemester = Semester::where('is_active', true)->first();

    // Ambil data siswa dari tabel student_semester
    $studentIds = StudentSemester::where('class_id', $classId)
                    ->where('academic_year_id', optional($activeAcademicYear)->id)
                    ->where('semester_id', optional($activeSemester)->id)
                    ->pluck('student_id');

    $students = Student::withCount(['borrowedBooks', 'returnedBooks'])
                    ->whereIn('id_student', $studentIds)
                    ->get();

    $totalBookLoans = $students->sum('borrowed_books_count');
    $totalBookReturns = $students->sum('returned_books_count');

    return view('books.classtudent', compact(
        'class',
        'students',
        'totalBookLoans',
        'totalBookReturns',
        'activeAcademicYear',
        'activeSemester'
    ));
}
public function studentBooks($id)
{
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $student = Student::with(['studentSemester.class'])->findOrFail($id);

    $studentSemester = $student->studentSemesters()
        ->where('academic_year_id', $activeAcademicYear->id)
        ->where('semester_id', $activeSemester->id)
        ->with('class')
        ->first();

    $class = $studentSemester?->class;

    $bookLoans = BookLoan::with('book', 'copy')
        ->where('id_student', $student->id_student)
        ->orderByDesc('loan_date')
        ->get();

    // Ambil buku berdasarkan level kelas siswa
    $books = Book::whereHas('class', function ($query) use ($class) {
        $query->where('class_level', $class->class_level ?? null);
    })->get();

    $academicYears = AcademicYear::all();
    $semesters = Semester::all();

    return view('books.studentbook', compact(
        'student', 'bookLoans', 'books',
        'academicYears', 'semesters',
        'activeAcademicYear', 'activeSemester', 'class'
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
    public function exportTemplate()
{
    return Excel::download(new BookLoansExport, 'template_peminjaman_buku.xlsx');
}
public function exportByClass($classId)
{
   $class = Classes::findOrFail($classId);
return Excel::download(new SingleClassBookLoanExport($classId, $class->class_level), 'peminjaman-' . $class->class_name . '.xlsx');
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
    ]);

    Excel::import(new BookLoansMultiSheetImport, $request->file('file'));

    return back()->with('success', 'Import berhasil dilakukan.');
}
}
