<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AttendanceStatus;
use App\Models\Student;
use App\Models\StudentAttendance;
use Exception;
use Illuminate\Http\Request;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

class AttendanceController extends Controller
{
    public function studentAttendance(Request $request)
    {
        try {
            $student = Student::findOrFail($request->user()->id_student);
            $statusId = (int) $request->status_id;

            if (!in_array($statusId, [1, 2, 3])) {
                return response()->json(['message' => 'Status tidak valid.'], 400);
            }

            $now = now();
            $attendanceData = [
                'id_student' => $student->id_student,
                'class_id' => $student->class_id,
                'attendance_date' => $now->toDateString(),
                'attendance_time' => $now->format('H:i:s'),
                'check_in_time' => null,
                'check_out_time' => null,
                'status_id' => $statusId,
                'latitude' => null,
                'longitude' => null,
                'academic_year_id' => $student->academic_year_id,
                'semester_id' => $student->semester_id,
                'document' => $request->document ?? null,
            ];

            if ($statusId === 1) {
                $request->validate([
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric',
                ]);

                $existing = StudentAttendance::where('id_student', $student->id_student)
                    ->whereDate('attendance_date', $now->toDateString())
                    ->first();

                if ($existing) {
                    return response()->json([
                        'message' => 'Anda sudah melakukan absensi hari ini.',
                        'data' => $existing,
                    ], 409);
                }

                $distance = $this->distance($request->latitude, $request->longitude);
                $radiusMeter = 100;

                if ($distance > $radiusMeter) {
                    return response()->json([
                        'message' => 'Gagal absensi. Anda berada di luar radius.',
                        'distance_m' => round($distance, 2) . ' meter',
                        'data' => null,
                    ], 403);
                }

                $attendanceData['check_in_time'] = $now->format('H:i:s');
                $attendanceData['latitude'] = (float) $request->latitude;
                $attendanceData['longitude'] = (float) $request->longitude;
            }

            $attendance = StudentAttendance::create($attendanceData);

            return response()->json([
                'message' => match ($statusId) {
                    1 => 'Absensi berhasil. Anda berada dalam radius.',
                    2 => 'Pengajuan sakit berhasil dicatat.',
                    3 => 'Pengajuan izin berhasil dicatat.',
                },
                'distance_m' => $statusId === 1 ? round($distance, 2) . ' meter' : null,
                'data' => $attendance,
            ]);
        } catch (Exception $err) {
            return response()->json([
                'message' => "Terjadi kesalahan: {$err->getMessage()}",
            ], 500);
        }
    }

    public function getHistories(Request $request)
    {
        $oneWeekAgo = \Carbon\Carbon::now()->subDay(7);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'type' => 'nullable|in:keluar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('type'),
            ], 422);
        }

        $type = $request->query('type');

        $attendancesQuery = StudentAttendance::with('status')
            ->where('id_student', $request->user()->id_student)
            ->where('attendance_date', '>=', $oneWeekAgo)
            ->orderBy('attendance_date', 'desc')
            ->orderBy('check_in_time', 'desc');

        if ($type === 'keluar') {
            $attendancesQuery->whereNotNull('check_out_time');
        }

        $attendances = $attendancesQuery->get()
            ->map(function ($attendance) {
                $data = $attendance->toArray();
                unset($data['status_id'], $data['class_id']);
                $data['status'] = $attendance->status?->status_name;
                $data['class_name'] = $attendance->class?->class_name;
                return $data;
            });

        return response()->json([
            'success' => true,
            'message' => 'Attendance history retrieved successfully.',
            'data' => $attendances,
        ]);
    }

    public function getAttendanceNow(Request $request)
    {
        $id = $request->user()->id_student;
        $currentDate = now()->toDateString();

        $attendance = StudentAttendance::where('id_student', $id)->whereDate('attendance_date', $currentDate)->first();

        if ($attendance) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance time now retrieved successfuly.',
                'data' => [
                    'attendance_id' => $attendance->id,
                    'check_in_time' => $attendance->check_in_time,
                    'check_out_time' => $attendance->check_out_time,
                    'status' => $attendance->status->status_name
                ],
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Belum ada data absensi hari ini',
                'data' => [
                    'attendance_id' => null,
                    'check_in_time' => null,
                    'check_out_time' => null,
                    'status' => null,
                ],
            ]);
        }
    }

    public function checkoutAttendance(Request $request)
    {

        $request->validate([
            'attendance_id' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $attendance = StudentAttendance::find($request->attendance_id);

        if (!$attendance) return response()->json([
            'success' => false,
            'message' => 'Absensi tidak ditemukan.',
        ]);

        $distance = $this->distance($request->latitude, $request->longitude);
        $radiusMeter = 100;

        if ($distance > $radiusMeter) {
            return response()->json([
                'message' => 'Gagal absensi. Anda berada di luar radius.',
                'distance_m' => round($distance, 2) . ' meter',
                'data' => null,
            ], 403);
        }

        $attendance->check_out_time = now()->format('H:i:s');

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil dicatat.',
            'data' => $attendance
        ]);
    }

    public function distance($latitude, $longitude): int|float
    {
        $lat = (float) $latitude;
        $lng = (float) $longitude;

        $geotools = new Geotools();
        $userCoord = new Coordinate([$lat, $lng]);
        $officeCoord = new Coordinate([-6.886673415898163, 108.49382131353438]); // TODO: ganti koordinat

        $distanceKm = $geotools->distance()->setFrom($userCoord)->setTo($officeCoord)->in('km')->haversine();
        $distanceMeter = $distanceKm * 1000;

        return $distanceMeter;
    }
}
