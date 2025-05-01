<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Menampilkan semua data buku
    public function index()
    {
        $books = Book::all();
        return view('books.books', compact('books'));
    }

    // Menampilkan form tambah buku
    public function create()
    {
        return view('books.bookscreate');
    }

    // Menyimpan data buku baru
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:books,code',
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'year_published' => 'required|digits:4|integer',
            'stock' => 'required|integer',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Menampilkan detail buku berdasarkan ID
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.bookshow', compact('book'));
    }

    // Menampilkan form edit buku
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.booksedit', compact('book'));
    }

    // Menyimpan update buku
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'code' => 'required|string|unique:books,code,' . $book->id,
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'year_published' => 'required|digits:4|integer',
            'stock' => 'required|integer',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    // Menghapus buku
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Data buku berhasil dihapus.');
    }
}
