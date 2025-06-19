<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'nipd',
            'nama_siswa',
            'password',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'no_orang_tua',
            'kelas'
        ];
    }

    public function array(): array
    {
        $recentStudents = Student::with('class')
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->map(function ($student) {
                return [
                    $student->id_student,
                    $student->fullname,
                    'password123',
                    $student->birth_place,
                    $student->birth_date ? $student->birth_date->format('d-m-Y') : '',
                    $student->gender,
                    $student->parent_phonecell,
                    $student->class->class_name ?? 'Kelas Tidak Ditemukan'
                ];
            })->toArray();

        // Tambahkan satu baris kosong agar bisa digunakan sebagai template
        $recentStudents[] = ['', '', '', '', '', '', '', ''];

        return $recentStudents;
    }

    public function styles(Worksheet $sheet)
    {
        // Hanya header (baris 1) yang di-bold dan diberi warna
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType('solid')->getStartColor()->setRGB('E6E6FA');

        // Validasi dropdown untuk kolom jenis kelamin (F)
        for ($row = 2; $row <= 100; $row++) {
            $validation = $sheet->getCell("F{$row}")->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setFormula1('"L,P"');
            $validation->setShowDropDown(true);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // nipd
            'B' => 30,  // nama_siswa
            'C' => 25,  // password
            'D' => 20,  // tempat_lahir
            'E' => 20,  // tanggal_lahir
            'F' => 15,  // jenis_kelamin
            'G' => 20,  // no_orang_tua
            'H' => 20   // kelas
        ];
    }
}
