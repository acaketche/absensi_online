<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BookLoansMultiSheetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Kelas X' => new BookLoansImport('X'),
            'Kelas XI' => new BookLoansImport('XI'),
            'Kelas XII' => new BookLoansImport('XII'),
        ];
    }
}
