<?php

namespace App\Imports;

use App\Models\BookLoan;
use App\Models\Book;
use App\Models\Student;
use App\Models\BookCopy;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class BookLoansImport implements ToCollection, WithHeadingRow
{
    protected $academicYearId;
    protected $semesterId;
    protected $classId;
    protected $books = [];
    protected $headerMapping = [];

    public function __construct($academicYearId, $semesterId, $classId = null)
    {
        $this->academicYearId = $academicYearId;
        $this->semesterId = $semesterId;
        $this->classId = $classId;

        $this->preloadBooks();
    }

    protected function preloadBooks()
    {
        $query = Book::query();

        if ($this->classId) {
            $query->where('class_id', $this->classId);
        }

        $allBooks = $query->get();

        foreach ($allBooks as $book) {
            $this->books[$book->title] = $book;
            $this->headerMapping[$book->title] = $book->id;
        }
    }

    public function collection(Collection $rows)
    {
        $loanDate = Carbon::now()->format('Y-m-d');
        $studentsProcessed = [];

        foreach ($rows as $row) {
            $studentName = $row['nama_siswa'] ?? null;

            if (!$studentName) continue;

            // Cari siswa berdasarkan nama dan kelas (jika classId ada)
            $studentQuery = Student::where('name', 'like', "%$studentName%");

            if ($this->classId) {
                $studentQuery->where('class_id', $this->classId);
            }

            $student = $studentQuery->first();

            if (!$student) {
                \Log::warning("Siswa dengan nama {$studentName} tidak ditemukan");
                continue;
            }

            // Skip jika siswa sudah diproses (untuk menghindari duplikat)
            if (in_array($student->id_student, $studentsProcessed)) continue;

            $studentsProcessed[] = $student->id_student;

            // Proses setiap kolom (buku)
            foreach ($row as $header => $copyCode) {
                // Skip kolom nama siswa dan kolom kosong
                if ($header == 'nama_siswa' || empty($copyCode)) continue;

                // Cari buku berdasarkan header
                $bookId = $this->headerMapping[$header] ?? null;

                if (!$bookId) {
                    \Log::warning("Buku dengan judul {$header} tidak ditemukan");
                    continue;
                }

                // Cari salinan buku
                $copy = BookCopy::where('book_id', $bookId)
                    ->where('copy_code', $copyCode)
                    ->first();

                if (!$copy) {
                    \Log::warning("Salinan buku {$copyCode} untuk {$header} tidak ditemukan");
                    continue;
                }

                // Cek apakah sudah ada pinjaman aktif untuk salinan ini
                $existingLoan = BookLoan::where('copy_id', $copy->id)
                    ->where('status', 'Dipinjam')
                    ->first();

                if ($existingLoan) {
                    \Log::warning("Salinan buku {$copyCode} sudah dipinjam oleh siswa lain");
                    continue;
                }

                // Buat record peminjaman
                BookLoan::create([
                    'id_student' => $student->id_student,
                    'book_id' => $bookId,
                    'copy_id' => $copy->id,
                    'loan_date' => $loanDate,
                    'return_date' => null,
                    'status' => 'Dipinjam',
                    'academic_year_id' => $this->academicYearId,
                    'semester_id' => $this->semesterId,
                ]);

                // Update status salinan
                $copy->update(['is_available' => 0]);
            }
        }
    }
}
