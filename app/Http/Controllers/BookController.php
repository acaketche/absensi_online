<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index()
{
    $books = Book::withCount(['copies as available_copies_count' => function($query) {
        $query->where('is_available', 1);
    }])->latest()->get();

    // ✅ Tambahkan logika update stock
    foreach ($books as $book) {
        $book->stock = $book->available_copies_count;
        $book->save();
    }

    return view('books.books', compact('books'));
}

    public function create()
    {
        return view('books.bookscreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:books,code',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'required|string|max:100',
            'year_published' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
        ]);

        // Initial stock will be 0 until copies are added
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
        ]);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {
            // Delete all copies first
            $book->copies()->delete();
            // Then delete the book
            $book->delete();
        });

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus');
    }

}

