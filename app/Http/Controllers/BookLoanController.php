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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'id_student' => 'required|string',
                'book_id' => 'required|integer',
                'copy_id' => 'required|integer',
                'loan_date' => 'required|date',
                'return_date' => 'nullable|date',
                'status' => 'required|string|in:Dipinjam,Dikembalikan',
                'academic_year_id' => 'required|integer',
                'semester_id' => 'required|integer',
            ]);

            $loan = BookLoan::create($validated);
            $this->syncBookCopyAvailability($validated['copy_id']);

            DB::commit();

            Log::info('Membuat peminjaman buku baru', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Membuat peminjaman',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_peminjaman' => $loan->id,
                    'id_siswa' => $loan->id_student,
                    'id_buku' => $loan->book_id,
                    'id_salinan' => $loan->copy_id,
                    'status' => $loan->status
                ]
            ]);

            if ($request->ajax()) {
                return response()->json(['message' => 'Berhasil disimpan']);
            }

            return redirect()->back()->with('success', 'Berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal membuat peminjaman buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Membuat peminjaman',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage())
                ->withInput();
        }
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
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'id_student' => 'required|string',
                'book_id' => 'required|integer',
                'copy_id' => 'required|integer',
                'loan_date' => 'required|date',
                'return_date' => 'required|date',
                'status' => 'required|string|in:Dipinjam,Dikembalikan',
                'academic_year_id' => 'required|integer',
                'semester_id' => 'required|integer',
            ]);

            $loan = BookLoan::findOrFail($id);
            $oldData = $loan->toArray();
            $loan->update($validated);
            $this->syncBookCopyAvailability($validated['copy_id']);

            DB::commit();

            Log::info('Memperbarui data peminjaman', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Memperbarui peminjaman',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'perubahan' => [
                    'id_siswa' => ['dari' => $oldData['id_student'], 'menjadi' => $loan->id_student],
                    'id_buku' => ['dari' => $oldData['book_id'], 'menjadi' => $loan->book_id],
                    'id_salinan' => ['dari' => $oldData['copy_id'], 'menjadi' => $loan->copy_id],
                    'status' => ['dari' => $oldData['status'], 'menjadi' => $loan->status]
                ]
            ]);

            return redirect()->route('book-loans.index')->with('success', 'Data peminjaman berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui peminjaman', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Memperbarui peminjaman',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $loan = BookLoan::findOrFail($id);
            $copyId = $loan->copy_id;
            $loanData = $loan->toArray();
            $loan->delete();

            $this->syncBookCopyAvailability($copyId);

            DB::commit();

            Log::info('Menghapus data peminjaman', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus peminjaman',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data_peminjaman' => $loanData
            ]);

            return redirect()->route('book-loans.index')->with('success', 'Data peminjaman berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus peminjaman', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus peminjaman',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus data peminjaman: ' . $e->getMessage());
        }
    }

    public function returnBook($id, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $loan = BookLoan::findOrFail($id);
            $oldStatus = $loan->status;

            $loan->update([
                'status' => 'Dikembalikan',
                'return_date' => now(),
            ]);

            $this->syncBookCopyAvailability($loan->copy_id);

            DB::commit();

            Log::info('Mengembalikan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Mengembalikan buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'perubahan_status' => ['dari' => $oldStatus, 'menjadi' => 'Dikembalikan']
            ]);

            return redirect()->route('book-loans.index')->with('success', 'Buku berhasil dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengembalikan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Mengembalikan buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }

    public function markAsReturned($id, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $loan = BookLoan::findOrFail($id);

            if ($loan->status === 'Dipinjam') {
                $loan->status = 'Dikembalikan';
                $loan->return_date = Carbon::now();
                $loan->save();

                $this->syncBookCopyAvailability($loan->copy_id);

                DB::commit();

                Log::info('Menandai buku sebagai dikembalikan', [
                    'program' => 'Perpustakaan',
                    'aktivitas' => 'Menandai dikembalikan',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => $employee->id_employee,
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'id_peminjaman' => $id
                ]);

                return redirect()->back()->with('success', 'Buku berhasil ditandai sebagai dikembalikan.');
            }

            DB::rollBack();
            return redirect()->back()->with('info', 'Status buku sudah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menandai buku sebagai dikembalikan', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menandai dikembalikan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menandai buku sebagai dikembalikan: ' . $e->getMessage());
        }
    }

    public function markAsUnreturned($id, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $loan = BookLoan::findOrFail($id);

            if ($loan->status === 'Dikembalikan') {
                $loan->status = 'Dipinjam';
                $loan->return_date = null;
                $loan->save();

                $this->syncBookCopyAvailability($loan->copy_id);

                DB::commit();

                Log::info('Mengubah status menjadi dipinjam', [
                    'program' => 'Perpustakaan',
                    'aktivitas' => 'Mengubah status ke dipinjam',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => $employee->id_employee,
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'id_peminjaman' => $id
                ]);

                return redirect()->back()->with('success', 'Status dikembalikan menjadi Dipinjam.');
            }

            DB::rollBack();
            return redirect()->back()->with('info', 'Status buku sudah dipinjam.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengubah status menjadi dipinjam', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Mengubah status ke dipinjam',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_peminjaman' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
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

   public function print($id_student, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $student = Student::where('id_student', $id_student)->firstOrFail();
        $bookLoans = BookLoan::with(['book', 'student'])
            ->where('id_student', $id_student)
            ->orderBy('loan_date', 'desc')
            ->get();

        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();

        Log::info('Mencetak laporan peminjaman', [
            'program' => 'Perpustakaan',
            'aktivitas' => 'Mencetak laporan',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'id_siswa' => $student->id_student,
            'nama_siswa' => $student->fullname,
            'jumlah_peminjaman' => $bookLoans->count()
        ]);

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
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        DB::beginTransaction();
        try {
            $import = new BookLoansMultiSheetImport;
            Excel::import($import, $request->file('file'));

            DB::commit();

            Log::info('Import data peminjaman berhasil', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Import data',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'file' => $request->file('file')->getClientOriginalName(),
                'total_data' => $import->getRowCount()
            ]);

            return back()->with('success', 'Import berhasil dilakukan. Total data: ' . $import->getRowCount());

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal import data peminjaman', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Import data',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
