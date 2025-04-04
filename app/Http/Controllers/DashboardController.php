<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('auth.dashboard'); // View untuk Super Admin & Pegawai TU
    }

    public function piket()
    {
        return view('dashboard.piket'); // View untuk Pegawai Piket
    }

    public function perpus()
    {
        return view('dashboard.perpus'); // View untuk Pegawai Perpus
    }
    public function default()
    {
        return view('dashboard.default'); // ✅ Tambahkan tampilan default
    }
}
