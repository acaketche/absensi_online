<?php

namespace App\Imports;

use App\Models\Payment;
use App\Models\Spp;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

        // Map nama bulan ke angka
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
            'desember' => 12
        ];

        foreach ($rows as $index => $row) {
            try {
                // Ambil nilai dari kolom, perhatikan berbagai kemungkinan nama header
                $nipd = $row['nipd'] ?? $row['NIPD'] ?? null;
                $bulanRaw = $row['bulan_1_12'] ?? $row['bulan'] ?? $row['Bulan'] ?? $row['month'] ?? '';
                $status = $row['status_pembayaran'] ?? $row['Status Pembayaran'] ?? $row['status'] ?? null;

                // Validasi minimal
                if (empty($nipd)) {
                    $errors[] = "Baris " . ($index + 2) . ": NIPD kosong.";
                    continue;
                }

                if (empty($bulanRaw) || empty($status)) {
                    continue; // Tidak diproses, tapi tidak dianggap error
                }

                // Validasi apakah siswa ada
                $student = Student::where('id_student', $nipd)->first();
                if (!$student) {
                    $errors[] = "Baris " . ($index + 2) . ": Siswa dengan NIPD $nipd tidak ditemukan.";
                    continue;
                }

                // Konversi bulan ke angka
                $bulanText = strtolower(trim(preg_replace('/\s+/', '', $bulanRaw)));
                $month = null;

                if (is_numeric($bulanText) && $bulanText >= 1 && $bulanText <= 12) {
                    $month = (int) $bulanText;
                } elseif (isset($bulanMap[$bulanText])) {
                    $month = $bulanMap[$bulanText];
                }

                if (!$month) {
                    continue; // Lewati jika bulan tidak dikenali
                }

                // Normalisasi status
                $status = strtolower(trim($status)) === 'lunas' ? 'Lunas' : 'Belum';

                // Simpan pembayaran
                Payment::updateOrCreate(
                    [
                        'id_student' => $nipd,
                        'id_spp' => $this->sppId,
                        'month' => $month,
                    ],
                    [
                        'academic_year_id' => $spp->academic_year_id,
                        'semester_id' => $spp->semester_id,
                        'amount' => $spp->amount,
                        'status' => $status,
                        'last_update' => now(),
                        'notes' => 'Imported from Excel'
                    ]
                );

                // Tambahkan siswa ke student_semester jika belum ada
                DB::table('student_semester')->updateOrInsert(
                    [
                        'student_id' => $nipd,
                        'academic_year_id' => $spp->academic_year_id,
                        'semester_id' => $spp->semester_id
                    ],
                    [
                        'class_id' => $spp->class_id
                    ]
                );

                $processedCount++;

            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                continue;
            }
        }

        // Jika ada error, lempar exception agar bisa ditampilkan di controller
        if (!empty($errors)) {
            throw new \Exception(
                "Import selesai. {$processedCount} data berhasil disimpan.\n" .
                "Terdapat kesalahan:\n" . implode("\n", array_slice($errors, 0, 10)) // tampilkan max 10 error
            );
        }
    }
}
