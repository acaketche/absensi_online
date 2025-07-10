<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HolidaysController extends Controller
{
    // Menampilkan daftar hari libur
public function index(Request $request)
{
    $academicYearId = $request->get('academic_year');
    $bulan = $request->get('bulan');

    // Data lokal dari database
    $localHolidays = Holiday::with('academicYear')
                        ->when($academicYearId, function ($query) use ($academicYearId) {
                            return $query->where('academic_year_id', $academicYearId);
                        })
                        ->when($bulan, function ($query) use ($bulan) {
                            return $query->whereMonth('holiday_date', $bulan);
                        })
                        ->get()
                        ->map(function ($holiday) {
                            return [
                                'source' => 'local',
                                'holiday_date' => $holiday->holiday_date,
                                'holiday_name' => $holiday->description,
                                'id' => $holiday->id,
                                'academic_year_name' => $holiday->academicYear->year_name ?? '-'
                            ];
                        });

    // Ambil data dari API (cache 1 hari)
    $apiHolidays = Cache::remember('external_holidays', 86400, function () {
        $response = Http::get('https://api-harilibur.netlify.app/api');
        return $response->successful() ? $response->json() : [];
    });

    // Filter duplikat berdasarkan tanggal
    $localDates = $localHolidays->pluck('holiday_date')->toArray();

    $filteredApiHolidays = collect($apiHolidays)
        ->filter(function ($holiday) use ($bulan, $localDates) {
            $isSameMonth = !$bulan || \Carbon\Carbon::parse($holiday['holiday_date'])->month == $bulan;
            $isUnique = !in_array($holiday['holiday_date'], $localDates);
            return $isSameMonth && $isUnique;
        })
        ->map(function ($holiday) {
            return [
                'source' => 'api',
                'holiday_date' => $holiday['holiday_date'],
                'holiday_name' => $holiday['holiday_name'],
            ];
        });

    $allHolidays = $localHolidays->concat($filteredApiHolidays)
                                 ->sortBy('holiday_date')
                                 ->values();

   $academicYears = AcademicYear::all();
$activeAcademicYearId = AcademicYear::where('is_active', true)->value('id');

return view('holidays.holidays', compact(
    'allHolidays',
    'academicYears',
    'academicYearId',
    'bulan',
    'activeAcademicYearId'
));
}
    // Menampilkan form tambah hari libur
    public function create()
    {
        return view('holidays.create');
    }

    // Menyimpan data hari libur
    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'description' => 'required|string|max:255',
            'academic_year_id' => 'required|integer'
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil ditambahkan');
    }

    // Menampilkan detail hari libur
    public function show($id)
    {
        $holiday = Holiday::with('academicYear')->findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    // Menampilkan form edit hari libur
    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    // Memperbarui data hari libur
    public function update(Request $request, $id)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'description' => 'required|string|max:255',
            'academic_year_id' => 'required|integer'
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil diperbarui');
    }

    // Menghapus hari libur
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil dihapus');
    }
}
