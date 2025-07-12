<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exports\BooksExport;
use App\Imports\BooksImport;
use App\Models\AcademicYear;
use App\Models\Semester;
use Maatwebsite\Excel\Facades\Excel;
class BookController extends Controller
{
    public function index(Request $request)
{
    // Query untuk mendapatkan kelas unik (satu per level)
    $classes = \App\Models\Classes::select(DB::raw('MIN(class_id) as class_id'), 'class_level')
        ->whereNotNull('class_level')
        ->groupBy('class_level')
        ->get();

    // Query dasar untuk buku
    $query = Book::with(['class'])
        ->withCount([
            'copies as available_copies_count' => function ($query) {
                $query->where('is_available', 1);
            }
        ]);

    // Filter berdasarkan class_level jika ada
    if ($request->has('class_level') && $request->class_level != '') {
        $query->whereHas('class', function($q) use ($request) {
            $q->where('class_level', $request->class_level);
        });
    }

    // Eksekusi query
    $books = $query->latest()->get();

    // Perbarui stok dari salinan tersedia
    foreach ($books as $book) {
        if ($book->stock != $book->available_copies_count) {
            $book->update(['stock' => $book->available_copies_count]);
        }
    }
 $activeAcademicYear = AcademicYear::where('is_active', true)->first();
    $activeSemester = Semester::where('is_active', true)->first();

    return view('books.books', compact('books', 'classes','activeAcademicYear','activeSemester'));
}

   public function create()
{
    // Ambil hanya class_level yang unik
    $classLevels = Classes::select('class_level')->distinct()->pluck('class_level');

    return view('books.bookscreate', compact('classLevels'));
}

   public function store(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:books,code',
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:100',
                'publisher' => 'required|string|max:100',
                'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'class_id' => 'nullable|exists:classes,class_id',
            ]);

            if ($request->hasFile('cover')) {
                $validated['cover'] = $request->file('cover')->store('covers', 'public');
            }

            $validated['stock'] = 0;
            $book = Book::create($validated);

            DB::commit();

            Log::info('Menambahkan buku baru', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menambahkan buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_buku' => $book->id,
                    'kode_buku' => $book->code,
                    'judul' => $book->title
                ]
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Buku berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menambahkan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menambahkan buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'input' => $request->except('cover')
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Book $book)
    {
        $classes = Classes::all();
        return view('books.edit', compact('book', 'classes'));
    }

    public function update(Request $request, Book $book)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:books,code,' . $book->id,
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:100',
                'publisher' => 'required|string|max:100',
                'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'class_id' => 'nullable|exists:classes,class_id',
            ]);

            if ($request->hasFile('cover')) {
                if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                }

                $validated['cover'] = $request->file('cover')->store('covers', 'public');
            }

            $book->update($validated);

            DB::commit();

            Log::info('Memperbarui data buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Memperbarui buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_buku' => $book->id,
                'perubahan' => $validated
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Buku berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Memperbarui buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_buku' => $book->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui buku: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Book $book, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $bookData = [
                'id' => $book->id,
                'code' => $book->code,
                'title' => $book->title,
                'total_copies' => $book->copies()->count()
            ];

            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $book->copies()->delete();
            $book->delete();

            DB::commit();

            Log::info('Menghapus buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data_buku' => $bookData
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Buku dan semua salinannya berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_buku' => $book->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $fileName = 'books-and-copies-' . date('Y-m-d') . '.xlsx';

        Log::info('Export data buku', [
            'program' => 'Perpustakaan',
            'aktivitas' => 'Export data buku',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'file' => $fileName
        ]);

        return Excel::download(new BooksExport, $fileName);
    }

    public function import(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        DB::beginTransaction();
        try {
            $import = new BooksImport;
            Excel::import($import, $request->file('file'));

            DB::commit();

            Log::info('Import data buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Import data buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'hasil' => [
                    'buku_baru' => $import->getNewBooksCount(),
                    'salinan_baru' => $import->getNewCopiesCount()
                ]
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Data buku dan salinan berhasil diimport.
                    Buku baru: ' . $import->getNewBooksCount() . ',
                    Salinan baru: ' . $import->getNewCopiesCount());

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal import data buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Import data buku',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $fileName = 'template-import-buku-dan-salinan.xlsx';

        Log::info('Download template import buku', [
            'program' => 'Perpustakaan',
            'aktivitas' => 'Download template import',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee,
            'role' => $roleName,
            'ip' => $request->ip()
        ]);

        return Excel::download(new BooksExport, $fileName);
    }
}
