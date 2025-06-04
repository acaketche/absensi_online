<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
}
