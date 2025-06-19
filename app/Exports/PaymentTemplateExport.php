<?php

namespace App\Exports;

use App\Models\Spp;
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

class PaymentTemplateExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithColumnFormatting, WithEvents
{
    protected $sppId;
    protected $className;
    protected $students;
    protected $sppAmount;
    protected $monthOptions;

    public function __construct($sppId)
    {
        $this->sppId = $sppId;
        $spp = Spp::with(['classes.students.payments'])->findOrFail($sppId);

        $this->className = $spp->classes->class_name ?? 'Kelas Tidak Diketahui';
        $this->sppAmount = $spp->amount;

        $this->monthOptions = $this->getMonthOptions();

        $this->students = $spp->classes->students->map(function ($student) use ($sppId) {
           $latestPayment = $student->payments
    ->where('id_spp', $sppId) // ✅ sesuai nama kolom di tabel payments
    ->sortByDesc('created_at')
    ->first();


            return (object)[
                'nipd' => $student->id_student,
                'nama_siswa' => $student->fullname,
                'bulan' => $latestPayment?->month ?? '',
                'status' => $latestPayment?->status ?? '',
            ];
        });
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
            $student->bulan,
            $student->status,
            $this->sppAmount,
            json_encode(array_values($this->monthOptions)) // kolom tersembunyi
        ];
    }

    public function headings(): array
    {
        return [
            'NIPD',
            'Nama Siswa',
            'Bulan (1-12)',
            'Status Pembayaran',
            'Nominal',
            '_month_options'
        ];
    }

    public function title(): string
    {
        $cleanName = preg_replace('/[\/:*?"<>|]/', '', $this->className);
        return 'Template ' . substr($cleanName, 0, 31);
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Sembunyikan kolom validasi
                $sheet->getColumnDimension('F')->setVisible(false);

                // Atur lebar kolom
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(15);

                // Gaya header
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4267B2']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ];
                $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

                // Format angka
                $sheet->getStyle('E2:E' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Bekukan baris header
                $sheet->freezePane('A2');

                // Data validasi dropdown
                $monthOptions = array_values($this->monthOptions);
                $statusOptions = ['Lunas', 'Belum Lunas'];

                for ($row = 2; $row <= $highestRow; $row++) {
                    // Dropdown bulan
                    $validationC = $sheet->getCell("C{$row}")->getDataValidation();
                    $validationC->setType(DataValidation::TYPE_LIST);
                    $validationC->setErrorStyle(DataValidation::STYLE_STOP);
                    $validationC->setAllowBlank(false);
                    $validationC->setShowInputMessage(true);
                    $validationC->setErrorTitle('Bulan tidak valid');
                    $validationC->setError('Pilih bulan dari 1-12');
                    $validationC->setFormula1('"' . implode(',', $monthOptions) . '"');

                    // Dropdown status
                    $validationD = $sheet->getCell("D{$row}")->getDataValidation();
                    $validationD->setType(DataValidation::TYPE_LIST);
                    $validationD->setErrorStyle(DataValidation::STYLE_STOP);
                    $validationD->setAllowBlank(false);
                    $validationD->setShowInputMessage(true);
                    $validationD->setErrorTitle('Status tidak valid');
                    $validationD->setError('Pilih status dari dropdown (Lunas/Belum Lunas)');
                    $validationD->setFormula1('"' . implode(',', $statusOptions) . '"');
                }

                // Petunjuk pengisian
                $sheet->setCellValue('H1', 'PETUNJUK PENGISIAN:');
                $sheet->setCellValue('H2', '1. Isi semua kolom yang tersedia');
                $sheet->setCellValue('H3', '2. Kolom Bulan: pilih dari dropdown');
                $sheet->setCellValue('H4', '3. Kolom Status: pilih dari dropdown');
                $sheet->setCellValue('H5', '4. Nominal diisi otomatis, jangan diubah');

                $sheet->getStyle('H1:H5')->getFont()->setBold(true);
                $sheet->getStyle('H1')->getFont()->setSize(12);
                $sheet->getStyle('H1')->getFont()->getColor()->setRGB('4267B2');
                $sheet->getColumnDimension('H')->setAutoSize(true);
            }
        ];
    }
}
