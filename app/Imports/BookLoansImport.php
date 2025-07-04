<?php

namespace App\Imports;

use App\Models\{AcademicYear, Book, BookCopy, BookLoan, Classes, Student, Semester};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\{ToCollection, WithHeadingRow};
use Carbon\Carbon;

class BookLoansImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $successCount = 0;
    protected $failCount = 0;
    protected $processedBooks = [];
    protected $activeYear;
    protected $activeSemester;
    protected $currentSheetLevel;

    public function __construct(string $sheetLevel = null)
    {
        $allowedLevels = ['X', 'XI', 'XII'];
        if ($sheetLevel && !in_array($sheetLevel, $allowedLevels)) {
            throw new \InvalidArgumentException("Sheet level harus salah satu dari: " . implode(', ', $allowedLevels));
        }

        $this->currentSheetLevel = $sheetLevel;
        $this->activeYear = AcademicYear::where('is_active', 1)->first();
        $this->activeSemester = Semester::where('is_active', 1)->first();
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::info("Sheet {$this->currentSheetLevel} kosong, diabaikan");
            return;
        }

        if (!$this->activeYear || !$this->activeSemester) {
            $this->addError('Tahun ajaran atau semester aktif tidak ditemukan.');
            $this->flashResult();
            return;
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $this->processRow($row, $rowNumber);
        }

        $this->flashResult();
    }

    protected function processRow($row, $rowNumber)
    {
        $nipd = trim($row['nipd'] ?? $row['id_student'] ?? '');
        $className = trim($row['kelas'] ?? '');
        $studentClassLevel = $this->extractGradeLevel($className);

        if (!$nipd) return;

        if (!$studentClassLevel && $this->currentSheetLevel) {
            $studentClassLevel = $this->currentSheetLevel;
        }

        if ($this->currentSheetLevel && $studentClassLevel !== $this->currentSheetLevel) {
            Log::info("Baris $rowNumber: Kelas siswa $studentClassLevel tidak cocok dengan sheet {$this->currentSheetLevel}, tetap diproses.");
            $studentClassLevel = $this->currentSheetLevel;
        }

        $student = $this->findStudent($nipd, $studentClassLevel, $rowNumber);
        if (!$student) return;

        $this->processBookLoans($row, $rowNumber, $student, $studentClassLevel);
    }

    protected function findStudent($nipd, $classLevel, $rowNumber)
    {
        $classIds = Classes::where('academic_year_id', $this->activeYear->id)
            ->when($classLevel, fn($q) => $q->where('class_level', $classLevel))
            ->pluck('class_id')
            ->toArray();

        if (empty($classIds)) {
            $this->addError("Baris $rowNumber: Tidak ditemukan kelas untuk level $classLevel");
            return null;
        }

        $student = Student::where('id_student', $nipd)
            ->whereHas('studentSemesters', fn($q) => $q->whereIn('class_id', $classIds))
            ->first();

        if (!$student) {
            $this->addError("Baris $rowNumber: Siswa dengan NIPD $nipd tidak terdaftar di Kelas $classLevel");
            return null;
        }

        return $student;
    }

    protected function processBookLoans($row, $rowNumber, $student, $classLevel)
{
    $hasValidData = false;
    $columns = array_keys($row->toArray());

    for ($i = 4; $i < count($columns); $i += 2) {
        $bookColumn = $columns[$i];
        $statusColumn = $columns[$i + 1] ?? null;

        // Normalisasi kode buku dari header kolom
        $bookCode = $this->normalizeBookCode($bookColumn);

        if (!$bookCode) {
            $this->addError("Baris $rowNumber: Header kolom '$bookColumn' tidak memiliki kode buku.");
            continue;
        }

        $copyCode = trim($row[$bookColumn] ?? '');
        $status = trim($row[$statusColumn] ?? 'Dipinjam');

        if ($copyCode === '') continue;

        $book = Book::whereRaw("REPLACE(UPPER(code), '-', '') = ?", [str_replace('-', '', strtoupper($bookCode))])
            ->whereHas('class', fn($q) => $q->where('class_level', $classLevel))
            ->first();

        if (!$book) {
            $this->addError("Baris $rowNumber: Buku dengan kode '$bookCode' tidak ditemukan untuk kelas $classLevel.");
            continue;
        }

        $copy = BookCopy::where('copy_code', $copyCode)
            ->where('book_id', $book->id)
            ->first();

        if (!$copy) {
            $this->addError("Baris $rowNumber: Copy code '$copyCode' tidak ditemukan untuk buku '$bookCode'.");
            continue;
        }

        if (!$this->isValidStatus($status)) {
            $this->addError("Baris $rowNumber: Status tidak valid untuk buku '$bookCode'.");
            continue;
        }

        $this->saveLoanData($student, $book, $copy, $status, $rowNumber);
        $hasValidData = true;
    }

    if ($hasValidData) {
        $this->successCount++;
    }
}

    protected function saveLoanData($student, $book, $copy, $status, $rowNumber)
    {
        try {
            $loanData = [
                'id_student' => $student->id_student,
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'loan_date' => Carbon::now(),
                'return_date' => strtolower($status) === 'dikembalikan' ? Carbon::now() : null,
                'status' => $status,
                'academic_year_id' => $this->activeYear->id,
                'semester_id' => $this->activeSemester->id,
            ];

            BookLoan::updateOrCreate([
                'id_student' => $student->id_student,
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'academic_year_id' => $this->activeYear->id,
                'semester_id' => $this->activeSemester->id,
            ], $loanData);

            $copy->is_available = strtolower($status) === 'dikembalikan';
            $copy->save();

            $this->processedBooks[] = [
                'book' => $book->title,
                'copy' => $copy->copy_code,
                'status' => $status
            ];
        } catch (\Exception $e) {
            $this->addError("Baris $rowNumber: Gagal menyimpan data peminjaman - " . $e->getMessage());
        }
    }

    protected function extractGradeLevel($className)
    {
        return preg_match('/^(X|XI|XII)/i', $className, $matches) ? strtoupper($matches[1]) : '';
    }

    protected function isValidStatus($status)
    {
        return in_array(strtolower($status), ['dipinjam', 'dikembalikan']);
    }

    protected function addError(string $message)
    {
        $this->errors[] = $message;
        $this->failCount++;
        Log::warning($message);
    }

    protected function flashResult()
    {
        $messages = [];

        if ($this->successCount > 0) {
            $messages['success'] = "Berhasil memproses {$this->successCount} data peminjaman.";

            if (count($this->processedBooks) > 0) {
                $uniqueBooks = collect($this->processedBooks)
                    ->groupBy('book')
                    ->map(function ($items, $book) {
                        $copies = $items->pluck('copy')->unique()->implode(', ');
                        $statuses = $items->pluck('status')->unique()->implode(', ');
                        return "$book (Copy: $copies, Status: $statuses)";
                    })
                    ->implode('<br>');

                $messages['info'] = "Buku yang diproses:<br>$uniqueBooks";
            }
        }

        if (!empty($this->errors)) {
            $messages['error'] = "Gagal memproses {$this->failCount} data.";
            $shownErrors = array_slice($this->errors, 0, 5);
            if (count($this->errors) > 5) {
                $shownErrors[] = "Dan " . (count($this->errors) - 5) . " error lainnya...";
            }
            $messages['import_errors'] = $shownErrors;
        }

        foreach ($messages as $type => $message) {
            Session::flash($type, $message);
        }
    }
    protected function normalizeBookCode($columnHeader)
{
    // Ambil bagian sebelum ' - '
    $codePart = strtoupper(trim(explode(' - ', $columnHeader)[0] ?? ''));

    // Ubah dari underscore (_) ke dash (-)
    $normalized = str_replace('_', '-', $codePart);

    // Hanya ambil bagian yang sesuai format kode buku (misal: IND-2021-03)
    if (preg_match('/^[A-Z]{2,4}-\d{4}-\d{2}/', $normalized, $match)) {
        return $match[0];
    }

    return $normalized;
}

}
