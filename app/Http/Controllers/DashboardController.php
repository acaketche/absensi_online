<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function Superadmin()
    {
        return view('auth.dashboard'); // View untuk Super Admin & Pegawai TU
    }
    public function TataUsaha()
    {
        return view('auth.dashboard'); // View untuk Pegawai Piket
    }
    public function piket()
    {
        return view('auth.dashboard_piket'); // View untuk Pegawai Piket
    }

    public function perpus()
    {
        return view('auth.dashboard_perpus'); // View untuk Pegawai Perpus
    }
    public function default()
    {
        return view('dashboard.default'); // ✅ Tambahkan tampilan default
    }
}
