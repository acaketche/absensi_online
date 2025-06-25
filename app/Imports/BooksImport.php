<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;

class BooksImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'Books' => new BooksSheetImport(),
            'Copies' => new CopiesSheetImport(),
        ];
    }
}

class BooksSheetImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Book([
            'code'           => $row['kode_buku'],
            'title'          => $row['judul_buku'],
            'author'         => $row['nama_penulis'],
            'publisher'      => $row['nama_penerbit'],
            'year_published' => $row['tahun_terbit'],
            'stock'          => $row['jumlah_stok_tersedia'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.kode_buku' => 'required|string|max:255|unique:books,code',
            '*.judul_buku' => 'required|string|max:255',
            '*.nama_penulis' => 'required|string|max:255',
            '*.nama_penerbit' => 'required|string|max:255',
            '*.tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            '*.jumlah_stok_tersedia' => 'required|integer|min:0',
        ];
    }
}

class CopiesSheetImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    public function model(array $row)
    {
        $book = Book::where('code', $row['kode_buku_induk'])->first();

        if (!$book) {
            return null; // Skip if book code not found
        }

       return new BookCopy([
    'book_id'      => $book->id, // tambahkan ini!
    'copy_code'    => $row['kode_salinan_buku'],
    'is_available' => strtolower($row['status_ketersediaan_tersedia_dipinjam']) === 'tersedia',
]);
    }

    public function rules(): array
    {
        return [
            '*.kode_buku_induk' => 'required|string|exists:books,code',
            '*.kode_salinan_buku' => 'required|string|max:255|unique:book_copies,copy_code',
            '*.status_ketersediaan_tersedia_dipinjam' => ['required', Rule::in(['Tersedia', 'Dipinjam'])],
        ];
    }
}
