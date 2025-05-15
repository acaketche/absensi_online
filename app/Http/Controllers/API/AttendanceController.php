<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

class AttendanceController extends Controller
{
    public function studentAttendance(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $lat = (float) $request->latitude;
            $lng = (float) $request->longitude;

            $geotools = new Geotools();
            $user = new Coordinate([$lat, $lng]);
            $office = new Coordinate([-6.950503233824411, 108.48821126111385]);
            $validRadius = 10; // 10 meter

            $distance = $geotools->distance()->setFrom($user)->setTo($office);
            $km = $distance->in('km')->haversine();

            if ($km <= $validRadius / 1000) {
                return response()->json([
                    'message' => 'Absensi berhasil. Anda berada dalam radius.',
                    'distance_m' => round($km * 1000, 2) . ' meter'
                ]);
            } else {
                return response()->json([
                    'message' => 'Gagal absensi. Anda berada di luar radius.',
                    'distance_m' => round($km * 1000, 2) . ' meter'
                ], 403);
            }
        } catch (Exception $err) {
            return response()->json([
                'message' => "Terjadi kesalahan: $err",
            ], 403);
        }
    }
}
