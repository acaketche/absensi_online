<?php

namespace App\Imports;

use App\Models\Payment;
use App\Models\Spp;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaymentsImport implements ToCollection, WithHeadingRow
{
    protected $sppId;

    public function __construct($sppId)
    {
        $this->sppId = $sppId;
    }

    public function collection(Collection $rows)
    {
        $spp = Spp::with(['semester', 'academicYear'])->findOrFail($this->sppId);

        $processedCount = 0;
        $errors = [];

        $bulanMap = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        foreach ($rows as $index => $row) {
            try {
                $nipd = $row['nipd'] ?? $row['NIPD'] ?? null;
                $bulanRaw = $row['bulan_1_12'] ?? $row['bulan'] ?? $row['Bulan'] ?? $row['month'] ?? '';
                $status = $row['status_pembayaran'] ?? $row['Status Pembayaran'] ?? $row['status'] ?? null;

                // Lewati jika NIPD kosong
                if (empty($nipd)) {
                    $errors[] = "Baris " . ($index + 2) . ": NIPD kosong";
                    continue;
                }

                // Lewati jika bulan atau status kosong (tanpa error)
                if (empty($bulanRaw) || empty($status)) {
                    continue;
                }

                // Normalisasi dan mapping nama bulan
                $bulanText = strtolower(preg_replace('/\s+/', '', $bulanRaw));
                if (!isset($bulanMap[$bulanText])) {
                    continue; // Lewati jika bulan tidak valid (tanpa error)
                }

                $month = $bulanMap[$bulanText];

                // Normalisasi status pembayaran
                $status = strtolower(trim($status)) === 'lunas' ? 'Lunas' : 'Belum';

                $paymentData = [
                    'academic_year_id' => $spp->academic_year_id,
                    'semester_id' => $spp->semester_id,
                    'amount' => $spp->amount,
                    'status' => $status,
                    'last_update' => now(),
                    'notes' => 'Imported from Excel'
                ];

                Payment::updateOrCreate(
                    [
                        'id_student' => $nipd,
                        'id_spp' => $this->sppId,
                        'month' => $month,
                    ],
                    $paymentData
                );

                $processedCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                continue;
            }
        }

        if (!empty($errors)) {
            throw new \Exception(
                "Import selesai. {$processedCount} data berhasil disimpan.\n" .
                "Terdapat kesalahan:\n" . implode("\n", array_slice($errors, 0, 10))
            );
        }
    }
}
