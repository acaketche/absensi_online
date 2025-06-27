<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\BooksExport;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index()
    {
        $classes = Classes::all();
        $books = Book::withCount(['copies as available_copies_count' => function($query) {
            $query->where('is_available', 1);
        }])->latest()->get();

        foreach ($books as $book) {
            $book->stock = $book->available_copies_count;
            $book->save();
        }

        return view('books.books', compact('books','classes'));
    }

    public function create()
    {
        $classes = Classes::all();
return view('books.bookscreate', compact('classes'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
    'code' => 'required|string|max:20|unique:books,code',
    'title' => 'required|string|max:255',
    'author' => 'required|string|max:100',
    'publisher' => 'required|string|max:100',
    'year_published' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
    'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    'class_id' => 'nullable|exists:classes,class_id', // ✅ Tambahkan ini
]);

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $coverPath;
        }

        $validated['stock'] = 0;
        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
       $validated = $request->validate([
    'code' => 'required|string|max:20|unique:books,code,'.$book->id,
    'title' => 'required|string|max:255',
    'author' => 'required|string|max:100',
    'publisher' => 'required|string|max:100',
    'year_published' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
    'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    'class_id' => 'nullable|exists:classes,class_id', // ✅ Tambahkan ini
]);
        // Ganti cover jika diunggah ulang
        if ($request->hasFile('cover')) {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $coverPath = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $coverPath;
        }

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {
            // Hapus file cover jika ada
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
    return Excel::download(new BooksExport, 'books-and-copies-'.date('Y-m-d').'.xlsx');
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
