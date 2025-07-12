<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Employee;
use App\Models\Semester;
use Illuminate\Support\Facades\{Cache, Auth, Log};
use Illuminate\Validation\Rule;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        // Cache academic years for 1 hour
        $academicYears = Cache::remember('academic_years', 3600, function () {
            return AcademicYear::orderBy('year_name', 'desc')->get();
        });

        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();

        // Validate filter input
        $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'class_level' => 'nullable|in:X,XI,XII'
        ]);

        // Base query with eager loading
        $query = Classes::with(['employee', 'academicYear'])
            ->orderBy('class_level')
            ->orderBy('class_name');

        // Apply filters
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        } elseif ($activeAcademicYear) {
            $query->where('academic_year_id', $activeAcademicYear->id);
        }

        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }

        // Get filtered classes
        $classes = $query->get();

        // Cache homeroom teachers for 1 hour
      $waliKelas = Classes::with('employee')
    ->whereNotNull('id_employee')
    ->get()
    ->pluck('employee')
    ->unique('id_employee');

        return view('classes.classes', compact(
            'classes',
            'academicYears',
            'waliKelas',
            'activeAcademicYear'
        ));
    }

 public function store(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $activeAcademicYear = AcademicYear::where('is_active', 1)->firstOrFail();

        $validated = $request->validate([
            'class_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^(X|XI|XII)\s.+$/i',
                Rule::unique('classes')->where(function ($query) use ($activeAcademicYear) {
                    return $query->where('academic_year_id', $activeAcademicYear->id);
                })
            ],
            'id_employee' => 'required|exists:employees,id_employee'
        ], [
            'class_name.regex' => 'Format nama kelas harus diawali dengan X, XI, atau XII diikuti spasi dan nama kelas',
            'class_name.unique' => 'Kelas dengan nama ini sudah ada di tahun ajaran aktif'
        ]);

        try {
            $class = Classes::create([
                'class_id' => uniqid(),
                'class_name' => $request->class_name,
                'class_level' => strtoupper(strtok($request->class_name, ' ')),
                'id_employee' => $request->id_employee,
                'academic_year_id' => $activeAcademicYear->id,
            ]);

            Log::info('Berhasil menambahkan kelas baru', [
                'program' => 'Kelas',
                'aktivitas' => 'Menambahkan kelas',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'class_id' => $class->class_id,
                'data' => $validated
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Kelas berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan kelas', [
                'program' => 'Kelas',
                'aktivitas' => 'Gagal menambahkan kelas',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }
public function show($classId)
{
    // Ambil data kelas lengkap
    $class = Classes::with(['employee', 'academicYear', 'semester'])->findOrFail($classId);

    // Ambil tahun ajaran & semester aktif (default)
    $activeYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    // Gunakan tahun ajaran & semester dari kelas jika tersedia, jika tidak pakai yang aktif
    $selectedYear = $class->academic_year_id ?? $activeYear?->id;
    $selectedSemester = $class->semester_id ?? $activeSemester?->id;

    // Ambil siswa yang ada di kelas ini, tahun ajaran & semester yang dipilih
    $students = Student::whereHas('studentSemesters', function ($q) use ($classId, $selectedYear, $selectedSemester) {
        $q->where('class_id', $classId)
          ->where('academic_year_id', $selectedYear);

        if ($selectedSemester) {
            $q->where('semester_id', $selectedSemester);
        }
    })
    ->with(['studentSemesters' => function ($q) use ($selectedYear, $selectedSemester) {
        $q->where('academic_year_id', $selectedYear);
        if ($selectedSemester) {
            $q->where('semester_id', $selectedSemester);
        }
        $q->with('class'); // include nama kelas
    }])
    ->orderBy('fullname')
    ->get();

return view('classes.classesshow', compact(
    'class',
    'students',
    'selectedYear',
    'selectedSemester',
    'activeYear',      // tambahkan ini
    'activeSemester'   // dan ini
));
}

   public function update(Request $request, $id)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $class = Classes::where('class_id', $id)->firstOrFail();

        $validated = $request->validate([
            'class_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^(X|XI|XII)\s.+$/i',
                Rule::unique('classes', 'class_name')
                    ->ignore($class->class_id, 'class_id')
                    ->where(function ($query) use ($class) {
                        return $query->where('academic_year_id', $class->academic_year_id);
                    })
            ],
            'id_employee' => 'required|exists:employees,id_employee'
        ], [
            'class_name.regex' => 'Format nama kelas harus diawali dengan X, XI, atau XII diikuti spasi dan nama kelas',
            'class_name.unique' => 'Kelas dengan nama ini sudah ada di tahun ajaran ini'
        ]);

        try {
            $class->update([
                'class_name' => $request->class_name,
                'class_level' => strtoupper(strtok($request->class_name, ' ')),
                'id_employee' => $request->id_employee,
            ]);

            Log::info('Berhasil memperbarui kelas', [
                'program' => 'Kelas',
                'aktivitas' => 'Memperbarui kelas',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'class_id' => $id,
                'data' => $validated
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Data kelas berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui kelas', [
                'program' => 'Kelas',
                'aktivitas' => 'Gagal memperbarui kelas',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'class_id' => $id,
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $class = Classes::where('class_id', $id)->firstOrFail();

        try {
            if ($class->students()->exists()) {
                Log::warning('Gagal menghapus kelas karena masih memiliki siswa', [
                    'program' => 'Kelas',
                    'aktivitas' => 'Gagal menghapus kelas',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => $employee->id_employee,
                    'role' => $roleName,
                    'ip' => request()->ip(),
                    'class_id' => $id,
                    'reason' => 'Masih memiliki siswa'
                ]);

                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus kelas karena masih memiliki siswa');
            }

            $class->delete();

            Log::info('Berhasil menghapus kelas', [
                'program' => 'Kelas',
                'aktivitas' => 'Menghapus kelas',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => request()->ip(),
                'class_id' => $id
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Kelas berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus kelas', [
                'program' => 'Kelas',
                'aktivitas' => 'Gagal menghapus kelas',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => request()->ip(),
                'class_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }

    public function getClassData($id)
    {
        $class = Classes::with('employee')
            ->where('class_id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'class_name' => $class->class_name,
                'employee_nip' => $class->id_employee,
                'academic_year_id' => $class->academic_year_id
            ]
        ]);
    }

  public function getClasses($academicYearId)
{
    $classes = Classes::where('academic_year_id', $academicYearId)
        ->orderBy('class_name')
        ->select('class_id', 'class_name') // pastikan hanya ambil field yang dibutuhkan
        ->get();

    return response()->json([
        'success' => true,
        'data' => $classes
    ]);

    }
}
