<?php

// app/Exports/StudentTemplateExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'id_student',
            'fullname',
            'password',
            'birth_place',
            'birth_date',
            'gender',
            'parent_phonecell',
            'class_name',
            'photo_path',
            'qr_code_path'
        ];
    }

    public function array(): array
    {
        return [
            ['ST001', 'Contoh Siswa', 'password123', 'Jakarta', '2005-05-15', 'L', '081234567890', 'X IPA 1', '', '']
        ];
    }
}
