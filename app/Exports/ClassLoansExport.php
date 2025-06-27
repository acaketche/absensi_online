<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassLoansExport implements FromArray, WithHeadings
{
    protected $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function array(): array
    {
        $students = Student::where('class_id', $this->classId)->get();
        $books = Book::where('class_id', $this->classId)->get();

        $data = [];

        foreach ($students as $student) {
            $row = [
                'id_student' => $student->id_student,
                'fullname' => $student->fullname,
            ];

            // Tambahkan kolom kosong untuk setiap buku di kelas
            foreach ($books as $book) {
                $row[$book->title] = ''; // Akan diisi kode salinan
            }

            $data[] = $row;
        }

        return $data;
    }

    public function headings(): array
    {
        $headers = ['id_student', 'fullname'];

        $books = Book::where('class_id', $this->classId)->get();
        foreach ($books as $book) {
            $headers[] = $book->title;
        }

        return $headers;
    }
}
