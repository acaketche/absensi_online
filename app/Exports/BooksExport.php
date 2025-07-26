<?php

namespace App\Exports;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Classes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new BooksSheet(),
            new CopiesSheet(),
        ];
    }
}

class BooksSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents
{
    public function collection()
    {
        return Book::with(['class'])->get();
    }

    public function headings(): array
    {
        return [
            'Kode Buku',
            'Judul Buku',
            'Nama Penulis',
            'Nama Penerbit',
            'Tahun Terbit',
            'Kelas',
        ];
    }

    public function map($book): array
    {
        return [
            $book->code,
            $book->title,
            $book->author,
            $book->publisher,
            $book->year_published,
            $book->class ? $this->formatClassLevel($book->class->class_level) : '-',
        ];
    }

    private function formatClassLevel($level): string
    {
        $level = strtoupper(trim($level));

        $mapping = [
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
            'X' => 'X',
            'XI' => 'XI',
            'XII' => 'XII',
            'KELAS 10' => 'X',
            'KELAS 11' => 'XI',
            'KELAS 12' => 'XII',
        ];

        return $mapping[$level] ?? $level;
    }

    public function title(): string
    {
        return 'Books';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->applyStyles($event);
                $this->setupClassDropdown($event);
                $this->adjustColumnWidths($event->sheet);
            },
        ];
    }

    private function applyStyles(AfterSheet $event): void
    {
        $sheet = $event->sheet->getDelegate();
        $lastRow = $sheet->getHighestRow();

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => Color::COLOR_WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3490dc']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A2:F'.$lastRow)->applyFromArray($dataStyle);

        for ($i = 2; $i <= $lastRow; $i++) {
            $fillColor = $i % 2 == 0 ? 'f8f9fa' : 'e9ecef';
            $sheet->getStyle("A{$i}:F{$i}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($fillColor);
        }
    }

    private function adjustColumnWidths(\Maatwebsite\Excel\Sheet $sheet): void
    {
        $worksheet = $sheet->getDelegate();

        // Set custom column widths
        $worksheet->getColumnDimension('A')->setWidth(20);  // Kode Buku
        $worksheet->getColumnDimension('B')->setWidth(50);  // Judul Buku (wider)
        $worksheet->getColumnDimension('C')->setWidth(30);  // Nama Penulis
        $worksheet->getColumnDimension('D')->setWidth(30);  // Nama Penerbit
        $worksheet->getColumnDimension('E')->setWidth(15);  // Tahun Terbit
        $worksheet->getColumnDimension('F')->setWidth(15);  // Kelas

        // Enable text wrapping for long text
        $worksheet->getStyle('B')->getAlignment()->setWrapText(true);
        $worksheet->getStyle('C')->getAlignment()->setWrapText(true);
        $worksheet->getStyle('D')->getAlignment()->setWrapText(true);
    }

    private function setupClassDropdown(AfterSheet $event): void
    {
        $sheet = $event->sheet->getDelegate();
        $lastRow = $sheet->getHighestRow();

        $classLevels = Classes::pluck('class_level')
            ->map(fn($level) => $this->formatClassLevel($level))
            ->unique()
            ->values()
            ->toArray();

        $validation = $sheet->getDataValidation('F2:F'.$lastRow);
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Nilai tidak valid. Harap pilih dari dropdown.');
        $validation->setPromptTitle('Pilih Kelas');
        $validation->setPrompt('Silakan pilih kelas dari daftar yang tersedia');
        $validation->setFormula1('"'.implode(',', $classLevels).'"');

        $spreadsheet = $sheet->getParent();
        $classSheet = new Worksheet($spreadsheet, 'Class_Data');
        $spreadsheet->addSheet($classSheet);
        $classSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        foreach ($classLevels as $index => $level) {
            $classSheet->setCellValue('A'.($index + 1), $level);
        }
    }
}

class CopiesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents
{
    public function collection()
    {
        return BookCopy::with('book')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Buku Induk',
            'Kode Salinan Buku',
            'Status Ketersediaan',
        ];
    }

    public function map($copy): array
    {
        return [
            $copy->book?->code ?? 'Tidak Diketahui',
            $copy->copy_code,
            $copy->is_available ? 'Tersedia' : 'Dipinjam',
        ];
    }

    public function title(): string
    {
        return 'Copies';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => Color::COLOR_WHITE]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '38a169']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ];
                $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

                $dataStyle = [
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ];
                $sheet->getStyle('A2:C'.$lastRow)->applyFromArray($dataStyle);

                for ($i = 2; $i <= $lastRow; $i++) {
                    $fillColor = $i % 2 == 0 ? 'f0fff4' : 'e6ffed';
                    $sheet->getStyle("A{$i}:C{$i}")
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($fillColor);

                    $status = $sheet->getCell('C'.$i)->getValue();
                    $color = $status == 'Tersedia' ? '2f855a' : 'c53030';
                    $sheet->getStyle("C{$i}")
                        ->getFont()
                        ->getColor()
                        ->setRGB($color);
                }

                $this->adjustColumnWidths($event->sheet);
            },
        ];
    }

    private function adjustColumnWidths(\Maatwebsite\Excel\Sheet $sheet): void
    {
        $worksheet = $sheet->getDelegate();

        // Set wider columns for better readability
        $worksheet->getColumnDimension('A')->setWidth(30);  // Kode Buku Induk
        $worksheet->getColumnDimension('B')->setWidth(30);  // Kode Salinan Buku
        $worksheet->getColumnDimension('C')->setWidth(20);  // Status Ketersediaan

        // Center align all columns
        foreach (['A', 'B', 'C'] as $column) {
            $worksheet->getStyle($column)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
}
