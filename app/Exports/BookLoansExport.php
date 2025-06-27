<?php

namespace App\Exports;

use App\Models\Book;
use App\Models\Classes;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class BookLoansExport implements FromCollection, WithHeadings, WithTitle, WithStrictNullComparison
{
    protected $classId;

    public function __construct($classId = null)
    {
        $this->classId = $classId;
    }

    public function title(): string
    {
        return $this->classId ? Classes::find($this->classId)->class_name : 'All_Classes';
    }

    public function headings(): array
    {
        $headings = ['Nama Siswa'];

        // Get all books (or books for this class if specified)
        $books = $this->classId
            ? Book::where('class_id', $this->classId)->orderBy('title')->get()
            : Book::orderBy('title')->get();

        foreach ($books as $book) {
            $headings[] = $book->title;
        }

        return $headings;
    }

    public function collection()
    {
        // Get students for the class (or all students if no class specified)
        $students = $this->classId
            ? Student::where('class_id', $this->classId)->orderBy('name')->get()
            : Student::orderBy('name')->get();

        // Get all books (or books for this class if specified)
        $books = $this->classId
            ? Book::where('class_id', $this->classId)->orderBy('title')->get()
            : Book::orderBy('title')->get();

        $data = [];

        foreach ($students as $student) {
            $row = ['Nama Siswa' => $student->name];

            foreach ($books as $book) {
                // Get active loan for this student and book
                $loan = $student->bookLoans()
                    ->where('book_id', $book->id)
                    ->where('status', 'Dipinjam')
                    ->with('copy')
                    ->first();

                $row[$book->title] = $loan ? $loan->copy->copy_code : '';
            }

            $data[] = $row;
        }

        return collect($data);
    }
}
