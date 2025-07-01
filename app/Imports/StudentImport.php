<?php

namespace App\Imports;

use App\Models\{Student, Classes, AcademicYear, Semester, StudentSemester};
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\{
    OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure
};
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Carbon\Carbon;

class StudentImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $importedCount = 0;
    private $failedRows = [];

    private $activeYear;
    private $activeSemester;
    private $allowedClassNames = [];

    public function __construct()
    {
        $this->activeYear = AcademicYear::where('is_active', true)->first();
        $this->activeSemester = Semester::where('is_active', true)->first();

        if (!$this->activeYear || !$this->activeSemester) {
            throw new \Exception("Tahun akademik atau semester aktif tidak ditemukan.");
        }

        // Ambil semua nama kelas untuk tahun ajaran aktif, kecuali kelas XII
        $this->allowedClassNames = Classes::where('academic_year_id', $this->activeYear->id)
            ->where('class_level', '!=', 'XII')
            ->pluck('class_name')
            ->toArray();
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $data = $this->normalizeRow($row->toArray());

        try {
            $this->validateRequiredFields($data);

            $nipd = $data['nipd'];
            $fullname = $data['nama_siswa'];
            $class = Classes::where('class_name', $data['kelas'])
                ->where('academic_year_id', $this->activeYear->id)
                ->where('class_level', '!=', 'XII')
                ->first();

            if (!$class) {
                throw new \Exception("Kelas '{$data['kelas']}' tidak ditemukan di tahun ajaran aktif atau merupakan kelas XII.");
            }

            $birthDate = $this->parseDate($data['tanggal_lahir']);
            $password = $this->resolvePassword($data['password'], $nipd);

            $student = Student::updateOrCreate(
                ['id_student' => $nipd],
                [
                    'fullname'         => $fullname,
                    'password'         => Hash::make($password),
                    'birth_place'      => $data['tempat_lahir'],
                    'birth_date'       => $birthDate->format('Y-m-d'),
                    'gender'           => $this->normalizeGender($data['jenis_kelamin']),
                    'parent_phonecell' => $this->formatPhoneNumber($data['no_orang_tua']),
                    'academic_year_id' => $this->activeYear->id,
                    'semester_id'      => $this->activeSemester->id,
                ]
            );

            StudentSemester::updateOrCreate(
                [
                    'student_id'       => $student->id_student,
                    'academic_year_id' => $this->activeYear->id,
                    'semester_id'      => $this->activeSemester->id,
                ],
                [
                    'class_id' => $class->class_id,
                ]
            );

            $this->importedCount++;

        } catch (\Exception $e) {
            $this->failedRows[] = [
                'row'    => $rowIndex,
                'message'=> $e->getMessage(),
                'data'   => $data,
            ];
        }
    }

    private function resolvePassword($value, $nipd): string
{
    // Gunakan NIPD jika kolom password kosong
    return $value ?: $nipd;
}

    private function normalizeRow(array $row): array
    {
        $clean = fn($v) => is_string($v) ? trim($v) : $v;

        return [
            'nipd'          => $clean($row['nipd'] ?? ''),
            'nama_siswa'    => $clean($row['nama_siswa'] ?? ''),
            'password'      => $clean($row['password'] ?? ''),
            'tempat_lahir'  => $clean($row['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $clean($row['tanggal_lahir'] ?? ''),
            'jenis_kelamin' => strtoupper($clean($row['jenis_kelamin'] ?? '')),
            'no_orang_tua'  => $clean($row['no_orang_tua'] ?? ''),
            'kelas'         => $clean($row['kelas'] ?? ''),
        ];
    }

    private function validateRequiredFields(array $data): void
    {
        $requiredFields = [
            'nipd' => 'NIPD',
            'nama_siswa' => 'Nama Siswa',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'kelas' => 'Kelas'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                throw new \Exception("Kolom '$label' harus diisi.");
            }
        }

        $this->normalizeGender($data['jenis_kelamin']);
    }

    private function parseDate($dateString): Carbon
    {
        try {
            if (is_numeric($dateString)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateString));
            }

            $formats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'm/d/Y'];
            foreach ($formats as $format) {
                try {
                    return Carbon::createFromFormat($format, $dateString);
                } catch (\Exception) {
                    continue;
                }
            }

            throw new \Exception("Format tanggal tidak valid: $dateString");
        } catch (\Exception) {
            throw new \Exception("Format tanggal tidak valid: $dateString");
        }
    }

    private function normalizeGender(string $gender): string
    {
        $gender = strtoupper(trim($gender));
        return match (true) {
            in_array($gender, ['L', 'LAKI-LAKI', 'MALE']) => 'L',
            in_array($gender, ['P', 'PEREMPUAN', 'FEMALE']) => 'P',
            default => throw new \Exception("Jenis kelamin harus 'L' atau 'P'.")
        };
    }

    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return (!empty($phone) && substr($phone, 0, 1) !== '0') ? '0' . $phone : $phone;
    }

    public function rules(): array
    {
        return [
            '*.nipd' => 'required',
            '*.nama_siswa' => 'required',
            '*.tempat_lahir' => 'required',
            '*.tanggal_lahir' => 'required',
            '*.jenis_kelamin' => 'required|in:L,P',
            '*.kelas' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->allowedClassNames)) {
                        $fail("Kelas '$value' tidak tersedia di tahun ajaran aktif atau merupakan kelas XII.");
                    }
                }
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nipd.required' => 'NIPD harus diisi.',
            '*.nama_siswa.required' => 'Nama siswa harus diisi.',
            '*.tempat_lahir.required' => 'Tempat lahir harus diisi.',
            '*.tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            '*.jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            '*.kelas.required' => 'Kelas harus diisi.',
        ];
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getFailedRows(): array
    {
        return $this->failedRows;
    }
}
