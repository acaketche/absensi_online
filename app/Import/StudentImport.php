<?php

namespace App\Import;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Classes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        $class = Classes::where('name', $row['class_name'])->first();
        if (!$class) {
            throw new \Exception("Class with name {$row['class_name']} not found");
        }

        // Default path
        $photoPath = null;
        $qrPath = null;

        // Download and save photo from URL
        if (!empty($row['photo_url'])) {
            try {
                $photoContent = Http::get($row['photo_url'])->body();
                $photoName = 'photo_siswa/' . $row['id_student'] . '_' . time() . '.jpg';
                Storage::disk('public')->put($photoName, $photoContent);
                $photoPath = $photoName;
            } catch (\Exception $e) {
                // Optional: log error
            }
        }

        // Download and save QR code from URL
        if (!empty($row['qrcode_url'])) {
            try {
                $qrContent = Http::get($row['qrcode_url'])->body();
                $qrName = 'qrcode_siswa/' . $row['id_student'] . '_' . time() . '.png';
                Storage::disk('public')->put($qrName, $qrContent);
                $qrPath = $qrName;
            } catch (\Exception $e) {
                // Optional: log error
            }
        }

        return new Student([
            'id_student'        => $row['id_student'],
            'fullname'          => $row['fullname'],
            'password'          => Hash::make($row['password']),
            'birth_place'       => $row['birth_place'],
            'birth_date'        => $row['birth_date'],
            'gender'            => $row['gender'],
            'parent_phonecell'  => $row['parent_phonecell'],
            'class_id'          => $class->class_id,
            'academic_year_id'  => $activeAcademicYear->id ?? null,
            'semester_id'       => $activeSemester->id ?? null,
            'photo'             => $photoPath,
            'qrcode'            => $qrPath,
        ]);
    }

    public function rules(): array
    {
        return [
            'id_student'       => 'required|unique:students,id_student',
            'fullname'         => 'required|string',
            'password'         => 'required|string|min:6',
            'birth_place'      => 'required|string',
            'birth_date'       => 'required|date',
            'gender'           => 'required|in:L,P',
            'parent_phonecell' => 'required|string|max:15',
            'class_name'       => 'required|exists:classes,name',
            'photo_url'        => 'nullable|url',
            'qrcode_url'       => 'nullable|url',
        ];
    }
}
