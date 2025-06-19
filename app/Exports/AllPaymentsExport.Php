<?php

namespace App\Exports;

use App\Models\Classes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class AllPaymentsExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithColumnFormatting, WithEvents
{
    protected $students;
    protected $monthOptions;
    protected $tingkat;
    protected $isAllClasses;

    public function __construct($tingkat = null)
    {
        $this->tingkat = $tingkat;
        $this->isAllClasses = is_null($tingkat);
        $this->loadStudentsData();
        $this->monthOptions = $this->getMonthOptions();
    }

    protected function loadStudentsData()
    {
        $query = Classes::with(['students.payments' => function($query) {
            $query->latest()->take(1);
        }, 'spp']);

        if (!$this->isAllClasses) {
            $query->where('class_name', 'like', $this->tingkat . '%');
        }

        $classes = $query->get();

        $this->students = collect();

        foreach ($classes as $class) {
            $sppAmount = $class->spp->first()->amount ?? 0;
            $sppId = $class->spp->first()->id ?? null;

            foreach ($class->students as $student) {
                $latestPayment = $student->payments->first();

                $this->students->push((object)[
                    'nipd' => $student->id_student,
                    'nama_siswa' => $student->fullname,
                    'class_name' => $class->class_name,
                    'bulan' => $latestPayment ? $this->getMonthName($latestPayment->month) : '',
                    'status' => $latestPayment->status ?? '',
                    'spp_amount' => $sppAmount,
                    'tingkat' => $this->extractGradeLevel($class->class_name),
                    'spp_id' => $sppId
                ]);
            }
        }
    }

    protected function getMonthName($monthNumber)
    {
        $months = $this->getMonthOptions();
        return $months[$monthNumber] ?? $monthNumber;
    }

    protected function extractGradeLevel($className)
    {
        if (preg_match('/^(X|XI|XII)/', $className, $matches)) {
            return $matches[1];
        }
        return '';
    }

    protected function getMonthOptions(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }

    public function collection()
    {
        return $this->students;
    }

    public function map($student): array
    {
        return [
            $student->nipd,
            $student->nama_siswa,
            $student->class_name,
            $student->tingkat,
            $student->bulan,
            $student->status,
            $student->spp_amount,
            json_encode(array_values($this->monthOptions)),
            $student->spp_id
        ];
    }

    public function headings(): array
    {
        return [
            'NIPD',
            'Nama Siswa',
            'Kelas',
            'Tingkat',
            'Bulan (1-12)',
            'Status Pembayaran',
            'Nominal',
            '_month_options',
            '_spp_id'
        ];
    }

    public function title(): string
    {
        $title = $this->isAllClasses ? 'Semua Kelas' : 'Kelas ' . $this->tingkat;
        return substr(preg_replace('/[\/:*?"<>|]/', '', $title), 0, 31);
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Hide validation columns
                $sheet->getColumnDimension('H')->setVisible(false);
                $sheet->getColumnDimension('I')->setVisible(false);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(10);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(15);

                // Header style
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4267B2']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ];
                $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

                // Number format
                $sheet->getStyle('G2:G' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Freeze header row
                $sheet->freezePane('A2');

                // Data validation
                $monthOptions = array_values($this->monthOptions);
                $statusOptions = ['Lunas', 'Belum Lunas'];

                for ($row = 2; $row <= $highestRow; $row++) {
                    // Month dropdown
                    $validationE = $sheet->getCell("E{$row}")->getDataValidation();
                    $validationE->setType(DataValidation::TYPE_LIST);
                    $validationE->setErrorStyle(DataValidation::STYLE_STOP);
                    $validationE->setAllowBlank(false);
                    $validationE->setShowInputMessage(true);
                    $validationE->setErrorTitle('Bulan tidak valid');
                    $validationE->setError('Pilih bulan dari 1-12');
                    $validationE->setFormula1('"' . implode(',', $monthOptions) . '"');

                    // Status dropdown
                    $validationF = $sheet->getCell("F{$row}")->getDataValidation();
                    $validationF->setType(DataValidation::TYPE_LIST);
                    $validationF->setErrorStyle(DataValidation::STYLE_STOP);
                    $validationF->setAllowBlank(false);
                    $validationF->setShowInputMessage(true);
                    $validationF->setErrorTitle('Status tidak valid');
                    $validationF->setError('Pilih status dari dropdown (Lunas/Belum Lunas)');
                    $validationF->setFormula1('"' . implode(',', $statusOptions) . '"');
                }

                // Instructions
                $sheet->setCellValue('J1', 'PETUNJUK PENGISIAN:');
                $sheet->setCellValue('J2', '1. Isi semua kolom yang tersedia');
                $sheet->setCellValue('J3', '2. Kolom Bulan: pilih dari dropdown');
                $sheet->setCellValue('J4', '3. Kolom Status: pilih dari dropdown');
                $sheet->setCellValue('J5', '4. Nominal diisi otomatis, jangan diubah');
                $sheet->setCellValue('J6', '5. Pastikan kelas dan tingkat sesuai dengan siswa');

                if ($this->isAllClasses) {
                    $sheet->setCellValue('J7', 'CATATAN: Template ini berisi semua siswa dari semua kelas');
                } else {
                    $sheet->setCellValue('J7', 'CATATAN: Template ini berisi semua siswa dari kelas ' . $this->tingkat);
                }

                $sheet->setCellValue('J8', 'Data bulan dan status sudah mencerminkan pembayaran terakhir di database');

                $sheet->getStyle('J1:J8')->getFont()->setBold(true);
                $sheet->getStyle('J1')->getFont()->setSize(12);
                $sheet->getStyle('J1')->getFont()->getColor()->setRGB('4267B2');
                $sheet->getStyle('J7:J8')->getFont()->getColor()->setRGB('FF0000');
                $sheet->getColumnDimension('J')->setAutoSize(true);
            }
        ];
    }
}
