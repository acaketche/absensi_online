<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Carbon\Carbon;

class StudentImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $importedCount = 0;
    private $failedRows = [];
    private $headingRow = 1;

    private $currentAcademicYear;
    private $currentSemester;
    private $photoPath;
    private $qrPath;

    public function __construct($photoPath = null, $qrPath = null)
    {
        $this->currentAcademicYear = AcademicYear::where('is_active', true)->first();
        $this->currentSemester = Semester::where('is_active', true)->first();
        $this->photoPath = $photoPath;
        $this->qrPath = $qrPath;

        if (!$this->currentAcademicYear || !$this->currentSemester) {
            throw new \Exception("Tahun akademik atau semester aktif tidak ditemukan.");
        }
    }

   public function model(array $row)
{
    try {
        $row = $this->normalizeRow($row);
        $this->validateRequiredFields($row);

        $class = Classes::where('class_name', $row['kelas'])->first();
        if (!$class) {
            throw new \Exception("Kelas '{$row['kelas']}' tidak ditemukan.");
        }

        $birthDate = $this->parseDate($row['tanggal_lahir']);
        $nipd = $row['nipd'];

        // Get photo and QR code paths from session
        $photoPath = null;
        $qrPath = null;

        $photoMap = session('photo_map', []);
        $qrMap = session('qrcode_map', []);

        // Handle photo - match by NIPD
        if (isset($photoMap[$nipd])) {
            $photoPath = $photoMap[$nipd];
        }

        // Handle QR code - match by NIPD
        if (isset($qrMap[$nipd])) {
            $qrPath = $qrMap[$nipd];
        }

        $this->importedCount++;

        return new Student([
            'id_student'        => $nipd,
            'fullname'          => $row['nama_siswa'],
            'password'          => Hash::make($row['password']),
            'birth_place'       => $row['tempat_lahir'],
            'birth_date'        => $birthDate->format('Y-m-d'),
            'gender'            => $this->normalizeGender($row['jenis_kelamin']),
            'parent_phonecell'  => $this->formatPhoneNumber($row['no_orang_tua']),
            'class_id'          => $class->class_id,
            'academic_year_id'  => $this->currentAcademicYear->id,
            'semester_id'       => $this->currentSemester->id,
            'photo'             => $photoPath,
            'qrcode'            => $qrPath,
        ]);

    } catch (\Exception $e) {
        $this->failedRows[] = [
            'row' => $this->getCurrentRowNumber(),
            'message' => $e->getMessage(),
            'data' => $row
        ];
        return null;
    }
}

    public function rules(): array
    {
        return [
            '*.nipd' => 'required|unique:students,id_student',
            '*.nama_siswa' => 'required',
            '*.password' => 'required|min:6',
            '*.tempat_lahir' => 'required',
            '*.tanggal_lahir' => 'required',
            '*.jenis_kelamin' => 'required|in:L,P,l,p',
            '*.no_orang_tua' => 'required',
            '*.kelas' => 'required|exists:classes,class_name'
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nipd.required' => 'Kolom NIPD harus diisi',
            '*.nama_siswa.required' => 'Kolom Nama Siswa harus diisi',
            '*.password.required' => 'Kolom Password harus diisi (minimal 6 karakter)',
            '*.password.min' => 'Password minimal 6 karakter',
            '*.tempat_lahir.required' => 'Kolom Tempat Lahir harus diisi',
            '*.tanggal_lahir.required' => 'Kolom Tanggal Lahir harus diisi',
            '*.jenis_kelamin.required' => 'Kolom Jenis Kelamin harus diisi (L/P)',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            '*.no_orang_tua.required' => 'Kolom No Orang Tua harus diisi',
            '*.kelas.required' => 'Kolom Kelas harus diisi',
            '*.kelas.exists' => 'Kelas :input tidak ditemukan di sistem'
        ];
    }

    /**
     * Helper methods
     */
    private function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(preg_replace('/[^a-z0-9]/', '', $key));
            $normalized[$normalizedKey] = is_string($value) ? trim($value) : $value;
        }

        return [
            'nipd' => $normalized['nipd'] ?? null,
            'nama_siswa' => $normalized['namasiswa'] ?? null,
            'password' => $normalized['passwordmin6karakter'] ?? $normalized['password'] ?? null,
            'tempat_lahir' => $normalized['tempatlahir'] ?? null,
            'tanggal_lahir' => $normalized['tanggallahiryyyymmdd'] ?? $normalized['tanggallahir'] ?? null,
            'jenis_kelamin' => $normalized['jeniskelaminlp'] ?? $normalized['jeniskelamin'] ?? null,
            'no_orang_tua' => $normalized['noorangtua'] ?? null,
            'kelas' => $normalized['kelas'] ?? null
        ];
    }

    private function validateRequiredFields(array $row): void
    {
        $requiredFields = [
            'nipd' => 'NIPD',
            'nama_siswa' => 'Nama Siswa',
            'password' => 'Password',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'no_orang_tua' => 'No Orang Tua',
            'kelas' => 'Kelas'
        ];

        foreach ($requiredFields as $field => $name) {
            if (empty($row[$field])) {
                throw new \Exception("Kolom $name harus diisi");
            }
        }
    }

    private function parseDate($dateString): Carbon
    {
        if (empty($dateString)) {
            throw new \Exception("Tanggal lahir tidak boleh kosong");
        }

        $dateFormats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'm/d/Y', 'Ymd', 'dmY'];

        foreach ($dateFormats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $dateString);
                if ($date) {
                    return $date;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new \Exception("Format tanggal tidak valid. Gunakan format DD-MM-YYYY atau YYYY-MM-DD");
    }

    private function normalizeGender($gender): string
    {
        $gender = strtoupper(trim($gender));
        if (!in_array($gender, ['L', 'P'])) {
            throw new \Exception("Jenis kelamin harus L atau P");
        }
        return $gender;
    }

    private function formatPhoneNumber($phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    private function getCurrentRowNumber(): int
    {
        return $this->importedCount + count($this->failedRows) + $this->headingRow + 1;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getFailedRows()
    {
        return $this->failedRows;
    }
}
