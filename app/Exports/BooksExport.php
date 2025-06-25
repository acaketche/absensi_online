<?php

namespace App\Exports;

use App\Models\Book;
use App\Models\BookCopy;
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
        return Book::all();
    }

    public function headings(): array
    {
        return [
            'Kode Buku',
            'Judul Buku',
            'Nama Penulis',
            'Nama Penerbit',
            'Tahun Terbit',
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
            $book->stock,
        ];
    }

    public function title(): string
    {
        return 'Books';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Apply styling to header
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => Color::COLOR_WHITE],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3490dc'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];

                // Apply header style
                $event->sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

                // Apply styling to data rows
                $dataStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                // Apply data style to all data rows
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A2:F'.$lastRow)->applyFromArray($dataStyle);

                // Set alternate row colors
                for ($i = 2; $i <= $lastRow; $i++) {
                    $fillColor = $i % 2 == 0 ? 'f8f9fa' : 'e9ecef';
                    $event->sheet->getStyle('A'.$i.':F'.$i)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($fillColor);
                }

                // Auto-size columns
                foreach (range('A', 'F') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Center align specific columns
                $centerColumns = ['A', 'E', 'F'];
                foreach ($centerColumns as $column) {
                    $event->sheet->getStyle($column)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
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
            'Status Ketersediaan (Tersedia / Dipinjam)',
        ];
    }

    public function map($copy): array
    {
        return [
            $copy->book ? $copy->book->code : 'Tidak Diketahui',
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
                // Apply header style
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => Color::COLOR_WHITE],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '38a169'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

                // Data row style
                $dataStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A2:C' . $lastRow)->applyFromArray($dataStyle);

                // Alternate row colors
                for ($i = 2; $i <= $lastRow; $i++) {
                    $fillColor = $i % 2 == 0 ? 'f0fff4' : 'e6ffed';
                    $event->sheet->getStyle("A{$i}:C{$i}")
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($fillColor);
                }

                // Auto-size columns
                foreach (range('A', 'C') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Center alignment for A, B, C
                foreach (['A', 'B', 'C'] as $column) {
                    $event->sheet->getStyle($column)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Conditional color for status (column C)
                $statusColumn = 'C';
                for ($i = 2; $i <= $lastRow; $i++) {
                    $cellValue = $event->sheet->getCell($statusColumn . $i)->getValue();
                    $color = $cellValue == 'Tersedia' ? '2f855a' : 'c53030';

                    $event->sheet->getStyle("{$statusColumn}{$i}")
                        ->getFont()
                        ->getColor()
                        ->setRGB($color);
                }
            },
        ];
    }
}
