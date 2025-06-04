<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\AttendanceStatus;

class StudentAttendanceController extends Controller
{
    protected $apiBaseUrl;
    protected $apiToken;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.attendance_api.url');
        $this->apiToken = config('services.attendance_api.token');
    }

    /**
     * Menampilkan daftar absensi siswa dari API mobile
     */
    public function index(Request $request)
    {
        try {
            $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
            $activeSemester = Semester::where('is_active', 1)->first();

            // Ambil parameter filter
            $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
            $semesterId = $request->get('semester_id', $activeSemester->id ?? null);
            $search = $request->get('search');
            $type = $request->get('type');
            $date = $request->get('date');

            // Panggil API mobile untuk data absensi
            $response = Http::withToken($this->apiToken)
                ->get("{$this->apiBaseUrl}/api/attendances", [
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'search' => $search,
                    'type' => $type,
                    'date' => $date,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Gagal mengambil data dari API: ' . $response->body());
            }

            $apiData = $response->json();
            $attendances = $apiData['data'] ?? [];

            // Data lokal untuk filter
            $academicYears = AcademicYear::all();
            $semesters = Semester::all();
            $classes = Classes::all();
            $statuses = AttendanceStatus::all();

            // Jika perlu data siswa untuk pencarian
            $students = [];
            if ($search) {
                $students = Student::where('fullname', 'like', "%{$search}%")
                    ->orWhere('id_student', 'like', "%{$search}%")
                    ->get();
            }

            return view('students.absensisiswa', compact(
                'attendances',
                'academicYears',
                'semesters',
                'classes',
                'statuses',
                'students',
                'academicYearId',
                'semesterId',
                'search',
                'type',
                'date'
            ));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan data absensi melalui API mobile
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_student' => 'required|exists:students,id_student',
                'status_id' => 'required|in:1,2,3', // Sesuai dengan API mobile
                'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'latitude' => 'required_if:status_id,1|numeric',
                'longitude' => 'required_if:status_id,1|numeric'
            ]);

            // Upload dokumen jika ada
            $documentPath = null;
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('attendance_proofs', 'public');
            }

            // Persiapkan data untuk API
            $payload = [
                'id_student' => $request->id_student,
                'status_id' => $request->status_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'document' => $documentPath
            ];

            // Panggil API mobile
            $response = Http::withToken($this->apiToken)
                ->post("{$this->apiBaseUrl}/api/attendances", $payload);

            if (!$response->successful()) {
                throw new \Exception('Gagal menyimpan data ke API: ' . $response->body());
            }

            return redirect()
                ->route('student-attendance.index')
                ->with('success', 'Absensi berhasil dicatat');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail absensi dari API mobile
     */
    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get("{$this->apiBaseUrl}/api/attendances/{$id}");

            if (!$response->successful()) {
                throw new \Exception('Data absensi tidak ditemukan');
            }

            $attendance = $response->json()['data'];

            return view('attendances.show', compact('attendance'));

        } catch (\Exception $e) {
            return redirect()
                ->route('student-attendance.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update status absensi melalui API mobile
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status_id' => 'required|exists:attendance_statuses,id',
                'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            $payload = ['status_id' => $request->status_id];

            if ($request->hasFile('document')) {
                $payload['document'] = $request->file('document')->store('attendance_proofs', 'public');
            }

            $response = Http::withToken($this->apiToken)
                ->put("{$this->apiBaseUrl}/api/attendances/{$id}", $payload);

            if (!$response->successful()) {
                throw new \Exception('Gagal memperbarui data');
            }

            return redirect()
                ->route('student-attendance.index')
                ->with('success', 'Absensi berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Pencarian siswa untuk autocomplete
     */
    public function searchStudent(Request $request)
    {
        try {
            $request->validate(['id_student' => 'required']);

            // Cek di database lokal dulu
            $student = Student::with('class')
                ->where('id_student', $request->id_student)
                ->first();

            if (!$student) {
                // Jika tidak ditemukan di lokal, coba dari API mobile
                $response = Http::withToken($this->apiToken)
                    ->get("{$this->apiBaseUrl}/api/students/{$request->id_student}");

                if (!$response->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Siswa tidak ditemukan'
                    ], 404);
                }

                $student = $response->json()['data'];
            }

            return response()->json([
                'success' => true,
                'student' => [
                    'id_student' => $student['id_student'],
                    'fullname' => $student['fullname'],
                    'class_id' => $student['class_id'],
                    'class_name' => $student['class']['class_name'] ?? '-'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
