<?php

namespace App\Exports;

use App\Models\{AcademicYear, Book, BookLoan, BookCopy, Student, Classes};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Events\AfterSheet;

class SingleClassBookLoanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected string $level;
    protected $class;
    protected $students;
    protected $books;
    protected $activeYear;

    public function __construct(int $classId, string $level)
    {
        $this->level = $level;
        $this->activeYear = AcademicYear::where('is_active', 1)->first();
        $this->class = Classes::with('students')->findOrFail($classId);

        $this->students = Student::whereHas('studentSemesters', function ($query) use ($classId) {
            $query->where('class_id', $classId)
                ->where('academic_year_id', optional($this->activeYear)->id);
        })->with(['studentSemesters.class' => function ($query) use ($classId) {
            $query->where('academic_year_id', optional($this->activeYear)->id)
                ->where('class_id', $classId);
        }])->get();

        $this->books = Book::whereHas('class', function ($query) {
            $query->where('class_level', $this->level);
        })->get();
    }

    public function collection(): Collection
    {
        $data = [];
        $no = 1;

        foreach ($this->students as $student) {
            $row = [
                $no++,
                $student->id_student,
                $student->fullname,
                $this->class->class_name,
            ];

            foreach ($this->books as $book) {
                $loan = BookLoan::with('copy')
                    ->where('id_student', $student->id_student)
                    ->where('book_id', $book->id)
                    ->where('academic_year_id', optional($this->activeYear)->id)
                    ->latest()
                    ->first();

                $row[] = $loan?->copy?->copy_code ?? '-';
                $row[] = $loan?->status ?? '-';
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $headings = ['No', 'NIPD', 'Nama Siswa', 'Kelas'];

        foreach ($this->books as $book) {
            $headings[] = $this->formatBookHeader($book);
            $headings[] = 'Status';
        }

        return $headings;
    }

    protected function formatBookHeader($book): string
    {
        return "{$book->code} - {$book->title}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E90FF'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowCount = $this->students->count() + 1;
                $startCol = 5;

                foreach ($this->books as $index => $book) {
                    $copyColIndex = $startCol + ($index * 2);
                    $copyColLetter = Coordinate::stringFromColumnIndex($copyColIndex);

                    $copyCodes = BookCopy::where('book_id', $book->id)
                        ->pluck('copy_code')
                        ->unique()
                        ->filter()
                        ->take(20)
                        ->toArray();

                    if (!empty($copyCodes)) {
                        $dropdownList = '"' . implode(',', array_map('trim', $copyCodes)) . '"';

                        for ($row = 2; $row <= $rowCount; $row++) {
                            $validation = $sheet->getCell("{$copyColLetter}{$row}")->getDataValidation();
                            $validation->setType(DataValidation::TYPE_LIST);
                            $validation->setFormula1($dropdownList);
                            $validation->setAllowBlank(true);
                            $validation->setShowDropDown(true);
                        }
                    }

                    $statusColIndex = $copyColIndex + 1;
                    $statusColLetter = Coordinate::stringFromColumnIndex($statusColIndex);

                    for ($row = 2; $row <= $rowCount; $row++) {
                        $validation = $sheet->getCell("{$statusColLetter}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setFormula1('"Dipinjam,Dikembalikan"');
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setErrorTitle('Input tidak valid');
                        $validation->setError('Silakan pilih antara Dipinjam atau Dikembalikan.');
                    }
                }
            },
        ];
    }
}
