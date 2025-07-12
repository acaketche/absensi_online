<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $latestCopy = $book->copies()->latest()->first();
            $lastNumber = $latestCopy ? (int) substr($latestCopy->copy_code, -3) : 0;
            $newCopyNumber = $lastNumber + 1;
            $copyCode = strtoupper($book->code . '-' . sprintf('%03d', $newCopyNumber));

            $copy = BookCopy::create([
                'book_id' => $book->id,
                'copy_code' => $copyCode,
                'is_available' => 1,
                'status' => 'Tersedia',
            ]);

            $book->increment('stock', 1);

            DB::commit();

            Log::info('Menambahkan salinan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menambahkan salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_buku' => $book->id,
                    'judul_buku' => $book->title,
                    'kode_salinan' => $copyCode,
                    'id_salinan' => $copy->id
                ]
            ]);

            return redirect()
                ->route('books.copies.show', $book)
                ->with('success', '1 salinan buku berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menambahkan salinan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menambahkan salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_buku' => $book->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('books.copies.show', $book)
                ->with('error', 'Gagal menambahkan salinan: ' . $e->getMessage());
        }
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
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate([
            'copy_code' => 'required|string|max:50|unique:book_copies,copy_code,'.$bookCopy->id,
            'is_available' => 'required|boolean'
        ]);

        DB::beginTransaction();
        try {
            $oldData = $bookCopy->toArray();

            $bookCopy->update([
                'copy_code' => $request->copy_code,
                'is_available' => $request->is_available
            ]);

            $this->updateBookStock($bookCopy->book);

            DB::commit();

            Log::info('Memperbarui salinan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Memperbarui salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_salinan' => $bookCopy->id,
                    'perubahan' => [
                        'copy_code' => ['dari' => $oldData['copy_code'], 'menjadi' => $bookCopy->copy_code],
                        'is_available' => ['dari' => $oldData['is_available'], 'menjadi' => $bookCopy->is_available]
                    ],
                    'id_buku' => $bookCopy->book_id
                ]
            ]);

            return redirect()->route('books.copies.show', $bookCopy->book_id)
                ->with('success', 'Salinan buku berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui salinan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Memperbarui salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_salinan' => $bookCopy->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui salinan: ' . $e->getMessage());
        }
    }

    // Delete a book copy
    public function destroy(BookCopy $bookCopy, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        // Check if copy is currently borrowed
        if (!$bookCopy->is_available) {
            Log::warning('Gagal menghapus salinan yang sedang dipinjam', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_salinan' => $bookCopy->id,
                'status' => 'Sedang dipinjam'
            ]);

            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus salinan yang sedang dipinjam');
        }

        DB::beginTransaction();
        try {
            $book = $bookCopy->book;
            $copyData = $bookCopy->toArray();
            $bookCopy->delete();
            $this->updateBookStock($book);

            DB::commit();

            Log::info('Menghapus salinan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data_salinan' => $copyData,
                'id_buku' => $book->id
            ]);

            return redirect()->back()
                ->with('success', 'Salinan buku berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus salinan buku', [
                'program' => 'Perpustakaan',
                'aktivitas' => 'Menghapus salinan',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'id_salinan' => $bookCopy->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus salinan: ' . $e->getMessage());
        }
    }

    // Helper method to update book stock
    protected function updateBookStock(Book $book)
    {
        $availableCopies = $book->copies()->where('is_available', true)->count();
        $book->stock = $availableCopies;
        $book->save();
    }

}
