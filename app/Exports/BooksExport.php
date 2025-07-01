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
        return Book::with('class')->get();
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
            'Jumlah Stok Tersedia',
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
            $book->stock,
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
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Apply styles
                $this->applyStyles($event);

                // Setup class dropdown
                $this->setupClassDropdown($event, $lastRow);
            },
        ];
    }

    private function applyStyles(AfterSheet $event): void
    {
        $sheet = $event->sheet;
        $lastRow = $sheet->getHighestRow();

        // Header style
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => Color::COLOR_WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3490dc']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Data style
        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A2:G'.$lastRow)->applyFromArray($dataStyle);

        // Alternate row colors
        for ($i = 2; $i <= $lastRow; $i++) {
            $fillColor = $i % 2 == 0 ? 'f8f9fa' : 'e9ecef';
            $sheet->getStyle('A'.$i.':G'.$i)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($fillColor);
        }

        // Auto-size and center columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
            if (in_array($column, ['A', 'E', 'F', 'G'])) {
                $sheet->getStyle($column)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        }
    }

    private function setupClassDropdown(AfterSheet $event, int $lastRow): void
    {
        $sheet = $event->sheet;
        $classLevels = Classes::pluck('class_level')
            ->map(fn($level) => $this->formatClassLevel($level))
            ->unique()
            ->values()
            ->toArray();

        // Add dropdown validation
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

        // Add hidden sheet with class values
        $classSheet = new Worksheet($sheet->getParent(), 'Class_Data');
        $sheet->getParent()->addSheet($classSheet);
        $classSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        foreach ($classLevels as $index => $level) {
            $classSheet->setCellValue('A'.($index+1), $level);
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
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Header style
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => Color::COLOR_WHITE]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '38a169']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ];
                $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

                // Data style
                $dataStyle = [
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ];
                $sheet->getStyle('A2:C'.$lastRow)->applyFromArray($dataStyle);

                // Alternate row colors
                for ($i = 2; $i <= $lastRow; $i++) {
                    $fillColor = $i % 2 == 0 ? 'f0fff4' : 'e6ffed';
                    $sheet->getStyle("A{$i}:C{$i}")
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($fillColor);
                }

                // Auto-size and center columns
                foreach (['A', 'B', 'C'] as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    $sheet->getStyle($column)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Status column coloring
                for ($i = 2; $i <= $lastRow; $i++) {
                    $status = $sheet->getCell('C'.$i)->getValue();
                    $color = $status == 'Tersedia' ? '2f855a' : 'c53030';
                    $sheet->getStyle("C{$i}")
                        ->getFont()
                        ->getColor()
                        ->setRGB($color);
                }
            },
        ];
    }
}
