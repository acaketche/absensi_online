<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookCopyController extends Controller
{
     // Helper method to sync stock with available copies
    protected function syncBookStock(Book $book)
    {
        $availableCopies = $book->copies()->where('is_available', 1)->count();
        $book->stock = $availableCopies;
        $book->save();
    }

    public function showCopies(Book $book)
    {
        // Mengambil data buku spesifik beserta salinannya
        $book->loadCount([
            'copies',
            'available_copies' => function($query) {
                $query->where('is_available', 1);
            }
        ]);

        // Mengambil daftar salinan buku
        $copies = $book->copies()->latest()->get();

        return view('books.books_copiess', compact('book', 'copies'));
    }

    public function storeCopies(Request $request, Book $book)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100', // Batasi maksimal 100 salinan sekaligus
        ]);

        // Mulai transaction untuk memastikan konsistensi data
        \DB::transaction(function () use ($request, $book) {
            $latestCopy = $book->copies()->latest()->first();
            $lastNumber = $latestCopy ? (int) substr($latestCopy->copy_code, -3) : 0;

            for ($i = 1; $i <= $request->quantity; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'copy_code' => strtoupper($book->code . '-' . sprintf('%03d', $lastNumber + $i)),
                    'is_available' => 1, // Menggunakan tinyint (1 untuk tersedia)
                    'status' => 'Tersedia', // Status teks
                ]);
            }

            // Update stok buku
            $book->increment('stock', $request->quantity);
        });

        return redirect()
            ->route('books.copies.show', $book)
            ->with('success', $request->quantity . ' salinan buku berhasil ditambahkan.');
    }
    public function availableCopies(Book $book)
    {
        $copies = $book->copies()
            ->where('is_available', 1)
            ->select('id', 'copy_code', 'status')
            ->get();

        return response()->json($copies);
    }
}
