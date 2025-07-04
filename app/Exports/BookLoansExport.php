<?php

namespace App\Exports;

use App\Models\{AcademicYear, Book, BookLoan, BookCopy, Student};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection, WithHeadings, WithTitle,
    ShouldAutoSize, WithEvents, WithStyles,
    WithMultipleSheets
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class BookLoansExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new BookLoanRecapExport('X'),
            new BookLoanRecapExport('XI'),
            new BookLoanRecapExport('XII'),
        ];
    }
}

class BookLoanRecapExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents, WithStyles
{
    protected string $level;
    protected $activeYear;
    protected $books;

    public function __construct(string $level)
    {
        $this->level = $level;
        $this->activeYear = AcademicYear::where('is_active', 1)->first();

        $this->books = Book::whereHas('class', function ($q) {
            $q->where('class_level', $this->level);
        })->get();
    }

    public function collection(): Collection
    {
        if (!$this->activeYear) return collect();

        $students = Student::whereHas('studentSemesters.class', function ($q) {
            $q->where('academic_year_id', $this->activeYear->id)
              ->where('class_level', $this->level);
        })->with(['studentSemesters.class' => function ($q) {
            $q->where('academic_year_id', $this->activeYear->id);
        }])->get();

        $students = $students->sortBy(function ($student) {
            return optional($student->studentSemesters->firstWhere('class.academic_year_id', $this->activeYear->id))
                ?->class?->class_name ?? '';
        });

        $data = [];
        $no = 1;

        foreach ($students as $student) {
            $className = optional($student->studentSemesters->firstWhere('class.academic_year_id', $this->activeYear->id))
                ?->class?->class_name ?? '-';

            $row = [
                $no++,
                $student->id_student,
                $student->fullname,
                $className,
            ];

            foreach ($this->books as $book) {
                $loan = BookLoan::with('copy')
                    ->where('id_student', $student->id_student)
                    ->where('book_id', $book->id)
                    ->where('academic_year_id', $this->activeYear->id)
                    ->latest()
                    ->first();

                $row[] = $loan?->copy?->copy_code ?? '';
                $row[] = $loan?->status ?? '';
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $base = ['No', 'NIPD', 'Nama Siswa', 'Kelas'];
        $bookColumns = [];

        foreach ($this->books as $book) {
            $bookColumns[] = "{$book->code} - {$book->title}";
            $bookColumns[] = 'Status';
        }

        return array_merge($base, $bookColumns);
    }

    public function title(): string
    {
        return 'Kelas ' . $this->level;
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');

                $rowCount = $sheet->getHighestRow();
                $colCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                    $sheet->getHighestColumn()
                );
                $range = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount) . $rowCount;

                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Set data validation untuk copy code dan status
                $startCol = 5;

                foreach ($this->books as $index => $book) {
                    $copyCol = $startCol + ($index * 2);
                    $statusCol = $copyCol + 1;

                    $copyColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($copyCol);
                    $statusColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($statusCol);

                    // Copy code dropdown
                    $copyCodes = BookCopy::where('book_id', $book->id)
                        ->pluck('copy_code')->unique()->filter()->take(20)->toArray();

                    if ($copyCodes) {
                        $list = '"' . implode(',', array_map('trim', $copyCodes)) . '"';

                        for ($row = 2; $row <= $rowCount; $row++) {
                            $validation = $sheet->getCell("{$copyColLetter}{$row}")->getDataValidation();
                            $validation->setType(DataValidation::TYPE_LIST);
                            $validation->setFormula1($list);
                            $validation->setAllowBlank(true);
                            $validation->setShowDropDown(true);
                        }
                    }

                    // Status dropdown
                    for ($row = 2; $row <= $rowCount; $row++) {
                        $validation = $sheet->getCell("{$statusColLetter}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setFormula1('"Dipinjam,Dikembalikan"');
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                    }
                }
            },
        ];
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
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
        ];
    }
}
