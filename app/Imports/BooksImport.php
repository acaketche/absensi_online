<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Classes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;

class BooksImport implements WithMultipleSheets, WithEvents
{
    use Importable, SkipsFailures;

    protected $failedRows = [];
    protected $importedRowCount = 0;

    public function sheets(): array
    {
        return [
            // Sheet: Books
            'Books' => new class($this) implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows {
                use SkipsFailures;

                protected $import;

                public function __construct(BooksImport $import)
                {
                    $this->import = $import;
                }

                public function model(array $row)
                {
                    $classLevel = $this->normalizeClassLevel($row['kelas'] ?? '');
                    $class = Classes::firstOrCreate(['class_level' => $classLevel]);

                    $book = Book::where('code', $row['kode_buku'])->first();

                    if ($book) {
                        $book->update([
                            'title' => $row['judul_buku'],
                            'author' => $row['nama_penulis'],
                            'publisher' => $row['nama_penerbit'],
                            'year_published' => (int) $row['tahun_terbit'],
                            'class_id' => $class->class_id,
                        ]);
                    } else {
                        Book::create([
                            'code' => $row['kode_buku'],
                            'title' => $row['judul_buku'],
                            'author' => $row['nama_penulis'],
                            'publisher' => $row['nama_penerbit'],
                            'year_published' => (int) $row['tahun_terbit'],
                            'class_id' => $class->class_id,
                            'cover' => null,
                            'stock' => 0, // Default stock 0, will be updated from copies
                        ]);
                    }

                    $this->import->incrementImportedCount();
                }

                public function rules(): array
                {
                    return [
                        '*.kode_buku' => 'required|string|max:20',
                        '*.judul_buku' => 'required|string|max:255',
                        '*.nama_penulis' => 'required|string|max:100',
                        '*.nama_penerbit' => 'required|string|max:100',
                        '*.tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                        '*.kelas' => 'required|string',
                    ];
                }

                public function onFailure(Failure ...$failures)
                {
                    $this->failures = array_merge($this->failures, $failures);
                }

                private function normalizeClassLevel($level)
                {
                    $level = strtoupper(trim($level));
                    $map = [
                        '10' => 'X', '11' => 'XI', '12' => 'XII',
                        'KELAS 10' => 'X', 'KELAS 11' => 'XI', 'KELAS 12' => 'XII',
                        'X' => 'X', 'XI' => 'XI', 'XII' => 'XII',
                    ];
                    return $map[$level] ?? $level;
                }
            },

            // Sheet: Copies
            'Copies' => new class($this) implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows {
                use SkipsFailures;

                protected $import;

                public function __construct(BooksImport $import)
                {
                    $this->import = $import;
                }

                public function model(array $row)
                {
                    $book = Book::where('code', $row['kode_buku_induk'])->first();

                    if (!$book) {
                        $this->import->addFailedRow(0, 'Copies', 'Buku induk tidak ditemukan', $row);
                        return null;
                    }

                    BookCopy::updateOrCreate(
                        ['copy_code' => $row['kode_salinan_buku']],
                        [
                            'book_id' => $book->id,
                            'is_available' => strtolower($row['status_ketersediaan']) === 'tersedia',
                        ]
                    );

                    // Update stock from available copies count
                    $book->update([
                        'stock' => $book->copies()->where('is_available', true)->count()
                    ]);

                    $this->import->incrementImportedCount();
                }

                public function rules(): array
                {
                    return [
                        '*.kode_buku_induk' => 'required|exists:books,code',
                        '*.kode_salinan_buku' => 'required|string|max:255|unique:book_copies,copy_code',
                        '*.status_ketersediaan' => ['required', Rule::in(['Tersedia', 'Dipinjam'])],
                    ];
                }

                public function onFailure(Failure ...$failures)
                {
                    $this->failures = array_merge($this->failures, $failures);
                }
            }
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function () {
                $this->failedRows = [];
                $this->importedRowCount = 0;
            },
            AfterImport::class => function () {
                Log::info('Import completed', [
                    'imported_rows' => $this->importedRowCount,
                    'failed_rows' => count($this->failedRows),
                ]);
            },
        ];
    }

    public function addFailedRow(int $row, string $sheet, string $message, array $values = [])
    {
        $this->failedRows[] = compact('row', 'sheet', 'message', 'values');
    }

    public function incrementImportedCount()
    {
        $this->importedRowCount++;
    }

    public function getFailedRows(): array
    {
        return $this->failedRows;
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }
}
