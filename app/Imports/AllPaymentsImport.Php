<?php

namespace App\Imports;

use App\Models\Payment;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AllPaymentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $student = Student::where('id_student', $row['nipd'])->first();

        if (!$student) {
            return null;
        }

        return new Payment([
            'student_id' => $student->id,
            'month' => array_search($row['bulan_1_12'], $this->monthOptions()),
            'status' => $row['status_pembayaran'],
            'amount' => $row['nominal'],
            'spp_id' => $row['_spp_id']
        ]);
    }

    public function rules(): array
    {
        return [
            'nipd' => 'required|exists:students,id_student',
            'bulan_1_12' => 'required|string',
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',
            'nominal' => 'required|numeric',
            '_spp_id' => 'required|exists:spp,id'
        ];
    }

    protected function monthOptions()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }
}
