<?php

namespace App\Exports;

use App\Models\{Student, Classes, AcademicYear};
use Maatwebsite\Excel\Concerns\{
    FromArray, WithHeadings, WithEvents, WithStyles,
    WithColumnWidths, WithTitle
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentTemplateExport implements
    FromArray,
    WithHeadings,
    WithEvents,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected $mode;
    protected $classes;
    protected $year;
    protected $activeYear;

    public function __construct($mode = 'empty')
    {
        $this->mode = $mode;
        $this->activeYear = AcademicYear::where('is_active', 1)->first();
        $this->classes = $this->activeYear
            ? Classes::where('academic_year_id', $this->activeYear->id)->pluck('class_name')->toArray()
            : [];
        $this->year = $this->activeYear?->year_name ?? date('Y');
    }

    public function title(): string
    {
        return 'Siswa';
    }

    public function headings(): array
    {
        return [
            'NIPD',
            'Nama Siswa',
            'Password',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'No Orang Tua',
            'Kelas'
        ];
    }

   public function array(): array
{
    if ($this->mode === 'filled') {
        $previousYear = $this->activeYear
            ? AcademicYear::where('id', '<', $this->activeYear->id)->orderByDesc('id')->first()
            : null;

        if (!$previousYear) {
            return [];
        }

        $students = Student::whereHas('studentSemesters', function ($query) use ($previousYear) {
                $query->where('academic_year_id', $previousYear->id);
            })
            ->whereHas('classes', function ($query) {
                $query->where('class_level', '!=', 'XII');
            })
            ->with(['studentSemesters.class' => function ($query) use ($previousYear) {
                $query->where('academic_year_id', $previousYear->id);
            }])
            ->get()
            ->sortBy(function ($student) use ($previousYear) {
                return $student->studentSemesters
                    ->where('academic_year_id', $previousYear->id)
                    ->first()?->class->class_name ?? '';
            });

        return $students->map(function ($student) use ($previousYear) {
            $class = $student->studentSemesters
                ->where('academic_year_id', $previousYear->id)
                ->first()?->class;

            return [
                $student->id_student,
                $student->fullname,
                $student->id_student,
                $student->birth_place,
                $student->birth_date ? $student->birth_date->format('d-m-Y') : '',
                $student->gender,
                $student->parent_phonecell,
                $class?->class_name ?? '',
            ];
        })->toArray();
    }

    // Template kosong
    return [[
        '12345',
        'Contoh Nama',
        '12345',
        'Kota Contoh',
        '01-01-2010',
        'L',
        '081234567890',
        ''
    ]];
}

    public function styles(Worksheet $sheet)
    {
        // Menebalkan baris header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Memberi warna latar belakang header
        $sheet->getStyle('A1:H1')->getFill()->setFillType('solid')->getStartColor()->setRGB('D9E1F2');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // NIPD
            'B' => 30,  // Nama
            'C' => 10,  // Password
            'D' => 20,  // Tempat Lahir
            'E' => 20,  // Tanggal Lahir
            'F' => 10,  // Jenis Kelamin
            'G' => 20,  // No Orang Tua
            'H' => 15,  // Kelas
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            $colKelas = 'Z';
            $rowStart = 2;

            // Tulis semua nama kelas ke kolom Z tersembunyi
            foreach ($this->classes as $index => $className) {
                $sheet->setCellValue("{$colKelas}" . ($rowStart + $index), $className);
            }

            // Sembunyikan kolom Z
            $sheet->getColumnDimension($colKelas)->setVisible(false);

            // Hitung jumlah kelas untuk menentukan rentang validasi
            $rowEnd = $rowStart + count($this->classes) - 1;
            $kelasRange = count($this->classes) > 0 ? "\${$colKelas}\${$rowStart}:\${$colKelas}\${$rowEnd}" : null;

            // Tambahkan validasi ke baris 2 - 1000
            for ($row = 2; $row <= 1000; $row++) {
                // Validasi Jenis Kelamin
                $genderValidation = $sheet->getCell("F{$row}")->getDataValidation();
                $genderValidation->setType(DataValidation::TYPE_LIST);
                $genderValidation->setFormula1('"L,P"');
                $genderValidation->setAllowBlank(true);
                $genderValidation->setShowDropDown(true);

                // Validasi Kelas
                if ($kelasRange) {
                    $classValidation = $sheet->getCell("H{$row}")->getDataValidation();
                    $classValidation->setType(DataValidation::TYPE_LIST);
                    $classValidation->setFormula1("={$kelasRange}");
                    $classValidation->setAllowBlank(true);
                    $classValidation->setShowDropDown(true);
                }
            }
        }
    ];
}
}
