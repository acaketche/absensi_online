<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->get();
        return view('books.books', compact('books'));
    }

    public function create()
    {
        return view('books.bookscreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'required|string|max:100',
            'year_published' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'stock' => 'required|integer|min:1',
        ]);

        // Generate book code
        $validated['code'] = 'BK-' . strtoupper(substr(md5(uniqid()), 0, 8));

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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'required|string|max:100',
            'year_published' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'stock' => 'required|integer|min:1',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus');
    }

    public function showCopies(Book $book)
{
    $book->load('copies');
    return view('books.books_copiess', compact('book'));
}

     public function storeCopies(Request $request, Book $book)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        for ($i = 1; $i <= $request->quantity; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'copy_code' => strtoupper($book->code . '-' . sprintf('%03d', $i)),
                'status' => 'Tersedia',
            ]);
        }

        return redirect()->route('books.copies.show')->with('success', 'Salinan buku berhasil ditambahkan.');
    }
}
