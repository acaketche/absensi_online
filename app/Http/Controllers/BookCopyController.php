<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Support\Facades\DB;

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
        $this->syncCopyAvailability($book);

        $book->loadCount([
            'copies',
            'available_copies' => function($query) {
                $query->where('is_available', 1);
            }
        ]);

        $copies = $book->copies()->latest()->get();

        return view('books.books_copiess', compact('book', 'copies'));
    }

    public function storeCopies(Request $request, Book $book)
    {
        // Tidak ada lagi validasi quantity
        // Langsung buat 1 salinan baru

        \DB::transaction(function () use ($book) {
            $latestCopy = $book->copies()->latest()->first();
            $lastNumber = $latestCopy ? (int) substr($latestCopy->copy_code, -3) : 0;

            BookCopy::create([
                'book_id' => $book->id,
                'copy_code' => strtoupper($book->code . '-' . sprintf('%03d', $lastNumber + 1)),
                'is_available' => 1,
                'status' => 'Tersedia',
            ]);

            // Tambahkan stok buku
            $book->increment('stock', 1);
        });

        return redirect()
            ->route('books.copies.show', $book)
            ->with('success', '1 salinan buku berhasil ditambahkan.');
    }

    public function availableCopies(Book $book)
    {
        $copies = $book->copies()
            ->where('is_available', 1)
            ->with(['loans' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function ($copy) {
                $latestLoan = $copy->loans->first();
                $status = $latestLoan ? $latestLoan->status : 'Tersedia';

                return [
                    'id' => $copy->id,
                    'copy_code' => $copy->copy_code,
                    'status' => $status
                ];
            });

        return response()->json($copies);
    }

    // Sinkronisasi is_available berdasarkan status peminjaman terakhir
    protected function syncCopyAvailability(Book $book)
    {
        foreach ($book->copies as $copy) {
            $latestLoan = $copy->loans()->latest()->first();

            if ($latestLoan) {
                $copy->is_available = ($latestLoan->status === 'Dikembalikan') ? 1 : 0;
            } else {
                $copy->is_available = 1;
            }

            $copy->save();
        }

        $this->syncBookStock($book);
    }
    // Update a book copy
    public function update(Request $request, BookCopy $bookCopy)
    {
        $request->validate([
            'copy_code' => 'required|string|max:50|unique:book_copies,copy_code,'.$bookCopy->id,
            'is_available' => 'required|boolean'
        ]);

        DB::transaction(function () use ($request, $bookCopy) {
            $bookCopy->update([
                'copy_code' => $request->copy_code,
                'is_available' => $request->is_available
            ]);

            // Update book stock
            $this->updateBookStock($bookCopy->book);
        });

        return redirect()->route('books.copies.show', $bookCopy->book_id)
            ->with('success', 'Salinan buku berhasil diperbarui');
    }

    // Delete a book copy
    public function destroy(BookCopy $bookCopy)
    {
        // Check if copy is currently borrowed
        if (!$bookCopy->is_available) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus salinan yang sedang dipinjam');
        }

        DB::transaction(function () use ($bookCopy) {
            $book = $bookCopy->book;
            $bookCopy->delete();
            $this->updateBookStock($book);
        });

        return redirect()->back()
            ->with('success', 'Salinan buku berhasil dihapus');
    }

    // Helper method to update book stock
    protected function updateBookStock(Book $book)
    {
        $availableCopies = $book->copies()->where('is_available', true)->count();
        $book->stock = $availableCopies;
        $book->save();
    }

}
