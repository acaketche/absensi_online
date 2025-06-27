<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\BooksExport;
use App\Imports\BooksImport;
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

    return view('books.books', compact('books', 'classes'));
}

   public function create()
{
    // Ambil hanya class_level yang unik
    $classLevels = Classes::select('class_level')->distinct()->pluck('class_level');

    return view('books.bookscreate', compact('classLevels'));
}

    public function store(Request $request)
    {
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
        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Book $book)
    {
        $classes = Classes::all();
        return view('books.edit', compact('book', 'classes'));
    }

    public function update(Request $request, Book $book)
    {
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

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $book->copies()->delete();
            $book->delete();
        });

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new BooksExport, 'books-and-copies-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        DB::transaction(function () use ($request) {
            Excel::import(new BooksImport, $request->file('file'));
        });

        return redirect()->route('books.index')
            ->with('success', 'Data buku dan salinan berhasil diimport');
    }

    public function downloadTemplate()
    {
        return Excel::download(new BooksExport, 'template-import-buku-dan-salinan.xlsx');
    }
}
