<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tata Usaha - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4266B9;
            --secondary-color: #365796;
            --accent-color: #5C7AEA;
            --success-color: #36B37E;
            --warning-color: #FFAB00;
            --danger-color: #FF5630;
            --light-bg: #F5F7FB;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            margin-bottom: 24px;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 20px;
            height: 100%;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 50px;
            opacity: 0.2;
            color: white;
        }

        .stat-card .stat-title {
            font-size: 14px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: white;
        }

        .stat-card .stat-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
        }

        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.success {
            background: linear-gradient(135deg, var(--success-color), #2E9E6B);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, var(--warning-color), #FF8B00);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, var(--danger-color), #DE350B);
        }

        .card-header {
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .card-header-light {
            background-color: #fff;
            color: var(--primary-color);
            font-weight: 600;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .student-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .student-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }

        .student-item:hover {
            background-color: #f8f9fa;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 12px;
        }

        .student-info {
            flex: 1;
        }

        .student-name {
            font-weight: 600;
            margin-bottom: 2px;
            color: #333;
        }

        .student-class {
            font-size: 13px;
            color: #6c757d;
        }

        .payment-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 15px;
        }

        .payment-stat {
            text-align: center;
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            background: white;
            box-shadow: var(--card-shadow);
        }

        .payment-stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .payment-stat-label {
            font-size: 12px;
            color: #6c757d;
        }

        .payment-stat.success .payment-stat-value {
            color: var(--success-color);
        }

        .payment-stat.danger .payment-stat-value {
            color: var(--danger-color);
        }

        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        .calendar-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .calendar-controls select {
            flex: 1;
        }

        #calendar {
            margin-top: 15px;
        }

        .fc .fc-toolbar-title {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .fc .fc-button {
            background-color: var(--primary-color);
            border: none;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .progress {
            height: 6px;
            border-radius: 3px;
        }

        .progress-bar {
            border-radius: 3px;
        }

        /* Attendance Section Styles */
        .attendance-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
        }

        .attendance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .attendance-title {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .attendance-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }

        .attendance-tab {
            padding: 8px 16px;
            cursor: pointer;
            font-weight: 500;
            color: #6c757d;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .attendance-tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .attendance-tab-content {
            display: none;
        }

        .attendance-tab-content.active {
            display: block;
        }

        .attendance-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }

        .attendance-summary-item {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .attendance-summary-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .attendance-summary-label {
            font-size: 12px;
            color: #6c757d;
        }

        .attendance-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .payment-stats {
                flex-direction: column;
            }

            .calendar-controls {
                flex-direction: column;
            }

            .attendance-summary {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Header dengan Profil Admin -->
        @include('components.profiladmin')

        <!-- Welcome Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fs-4 fw-bold mb-1">Dashboard Tata Usaha</h2>
                <p class="text-muted mb-0">Selamat datang, {{ Auth::guard('employee')->user()->fullname }}!</p>
                @if($tahunAjaranAktif)
                    <small class="text-primary">Tahun Ajaran: {{ $tahunAjaranAktif->year_name }}</small>
                @endif
            </div>
            <div>
                <span class="badge bg-primary p-2">
                    <i class="fas fa-calendar-day me-1"></i> {{ now()->format('d F Y') }}
                </span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card primary h-100">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value">{{ number_format($totalSiswa) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-users me-1"></i> Siswa aktif saat ini
                    </div>
                </div>
            </div>
             <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card warning h-100">
                    <div class="stat-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-title">Total Pegawai</div>
                    <div class="stat-value">{{ number_format($totalPegawai) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-users me-1"></i> Pegawai aktif saat ini
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card success h-100">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-title">Pembayaran Lunas</div>
                    <div class="stat-value">{{ number_format($siswaLunas) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-arrow-up me-1"></i> {{ $totalSiswa > 0 ? round(($siswaLunas / $totalSiswa) * 100, 1) : 0 }}% dari total
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card danger h-100">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-title">Belum Lunas</div>
                    <div class="stat-value">{{ number_format($siswaBelumLunas) }}</div>
                    <div class="stat-desc">
                        Perlu ditindaklanjuti
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Student Statistics -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i> Statistik Siswa per Tingkat Kelas
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="studentLevelChart"></canvas>
                        </div>
                        <div class="mt-3">
                            @foreach($siswaPerTingkat->take(3) as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Kelas {{ $item->class_level }}</span>
                                <span class="fw-bold">{{ $item->total }} siswa</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $totalSiswa > 0 ? ($item->total / $totalSiswa) * 100 : 0 }}%"></div>
                            </div>
                            @endforeach
                            @if($siswaPerTingkat->count() > 3)
                            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary w-100">
                                Lihat Semua Tingkat Kelas
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Statistics -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-money-check-alt me-2"></i> Status Pembayaran
                    </div>
                    <div class="card-body">
                        <div class="payment-stats">
                            <div class="payment-stat success">
                                <div class="payment-stat-value">{{ number_format($siswaLunas) }}</div>
                                <div class="payment-stat-label">Lunas</div>
                            </div>
                            <div class="payment-stat danger">
                                <div class="payment-stat-value">{{ number_format($siswaBelumLunas) }}</div>
                                <div class="payment-stat-label">Belum Lunas</div>
                            </div>
                        </div>

                        <div class="chart-container">
                            <canvas id="paymentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-calendar-check me-2"></i> Ringkasan Kehadiran
                    </div>
                    <div class="card-body">
                        <div class="attendance-summary">
                            <div class="text-center">
                                <div class="attendance-summary-value text-primary">{{ $employeeChartToday['Hadir'] ?? 0 }}</div>
                                <div class="attendance-summary-label">Pegawai Hadir</div>
                            </div>
                            <div class="text-center">
                                <div class="attendance-summary-value text-success">{{ $studentChartToday['Hadir'] ?? 0 }}</div>
                                <div class="attendance-summary-label">Siswa Hadir</div>
                            </div>
                            <div class="text-center">
                                <div class="attendance-summary-value text-warning">{{ ($employeeChartToday['Izin/Sakit'] ?? 0) + ($studentChartToday['Izin/Sakit'] ?? 0) }}</div>
                                <div class="attendance-summary-label">Izin/Sakit</div>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="attendanceSummaryChart"></canvas>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">Update terakhir: {{ now()->format('H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Section -->
        <div class="row">
            <!-- Employee Attendance -->
            <div class="col-lg-6 mb-4">
                <div class="attendance-container">
                    <div class="attendance-header">
                        <h5 class="attendance-title">
                            <i class="fas fa-user-tie"></i> Presensi Pegawai
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active" onclick="switchAttendanceTab('employee', 'today')">Hari Ini</button>
                            <button class="btn btn-outline-primary" onclick="switchAttendanceTab('employee', 'month')">Bulan Ini</button>
                        </div>
                    </div>

                    <div id="employee-today" class="attendance-tab-content active">
                        <div class="chart-container">
                            <canvas id="employeeTodayChart"></canvas>
                        </div>
                        <div class="attendance-legend">
                            @foreach($employeeChartToday as $status => $count)
                            <span class="status-badge" style="background-color: {{ [
                                'Hadir' => '#4CAF5020',
                                'Izin/Sakit' => '#2196F320',
                                'Alpa' => '#F4433620',
                                'Terlambat' => '#FF980020'
                            ][$status] ?? '#9C27B020' }}; color: {{ [
                                'Hadir' => '#4CAF50',
                                'Izin/Sakit' => '#2196F3',
                                'Alpa' => '#F44336',
                                'Terlambat' => '#FF9800'
                            ][$status] ?? '#9C27B0' }};">
                                <i class="fas fa-circle"></i> {{ $status }}: {{ $count }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <div id="employee-month" class="attendance-tab-content">
                        <div class="chart-container">
                            <canvas id="employeeMonthChart"></canvas>
                        </div>
                        <div class="attendance-legend">
                            @foreach($employeeChartMonth as $status => $count)
                            <span class="status-badge" style="background-color: {{ [
                                'Hadir' => '#4CAF5020',
                                'Izin/Sakit' => '#2196F320',
                                'Alpa' => '#F4433620',
                                'Terlambat' => '#FF980020'
                            ][$status] ?? '#9C27B020' }}; color: {{ [
                                'Hadir' => '#4CAF50',
                                'Izin/Sakit' => '#2196F3',
                                'Alpa' => '#F44336',
                                'Terlambat' => '#FF9800'
                            ][$status] ?? '#9C27B0' }};">
                                <i class="fas fa-circle"></i> {{ $status }}: {{ $count }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Attendance -->
            <div class="col-lg-6 mb-4">
                <div class="attendance-container">
                    <div class="attendance-header">
                        <h5 class="attendance-title">
                            <i class="fas fa-user-graduate"></i> Presensi Siswa
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active" onclick="switchAttendanceTab('student', 'today')">Hari Ini</button>
                            <button class="btn btn-outline-primary" onclick="switchAttendanceTab('student', 'month')">Bulan Ini</button>
                        </div>
                    </div>

                    <div id="student-today" class="attendance-tab-content active">
                        <div class="chart-container">
                            <canvas id="studentTodayChart"></canvas>
                        </div>
                        <div class="attendance-legend">
                            @foreach($studentChartToday as $status => $count)
                            <span class="status-badge" style="background-color: {{ [
                                'Hadir' => '#4CAF5020',
                                'Izin/Sakit' => '#2196F320',
                                'Alpa' => '#F4433620',
                                'Terlambat' => '#FF980020'
                            ][$status] ?? '#9C27B020' }}; color: {{ [
                                'Hadir' => '#4CAF50',
                                'Izin/Sakit' => '#2196F3',
                                'Alpa' => '#F44336',
                                'Terlambat' => '#FF9800'
                            ][$status] ?? '#9C27B0' }};">
                                <i class="fas fa-circle"></i> {{ $status }}: {{ $count }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <div id="student-month" class="attendance-tab-content">
                        <div class="chart-container">
                            <canvas id="studentMonthChart"></canvas>
                        </div>
                        <div class="attendance-legend">
                            @foreach($studentChartMonth as $status => $count)
                            <span class="status-badge" style="background-color: {{ [
                                'Hadir' => '#4CAF5020',
                                'Izin/Sakit' => '#2196F320',
                                'Alpa' => '#F4433620',
                                'Terlambat' => '#FF980020'
                            ][$status] ?? '#9C27B020' }}; color: {{ [
                                'Hadir' => '#4CAF50',
                                'Izin/Sakit' => '#2196F3',
                                'Alpa' => '#F44336',
                                'Terlambat' => '#FF9800'
                            ][$status] ?? '#9C27B0' }};">
                                <i class="fas fa-circle"></i> {{ $status }}: {{ $count }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Calendar Section -->
            <div class="col-lg-6 mb-4">
                <div class="calendar-container">
                    <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt me-2"></i> Kalender Akademik</h5>
                    <div class="calendar-controls">
                        <select id="monthSelect" class="form-select" onchange="updateCalendar()">
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('!m', $i)->translatedFormat('F') }}
                            </option>
                            @endfor
                        </select>
                        <select id="yearSelect" class="form-select" onchange="updateCalendar()">
                            @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div id="calendar"></div>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <span class="status-badge" style="background-color: #FF6B6B20; color: #FF6B6B;">
                            <i class="fas fa-square"></i> Libur Sekolah
                        </span>
                        <span class="status-badge" style="background-color: #FFA72620; color: #FFA726;">
                            <i class="fas fa-square"></i> Libur Nasional
                        </span>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-history me-2"></i> Pembayaran Terakhir
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="student-list">
                            @php
                                $recentPayments = App\Models\Payment::with('student')
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp

                            @forelse($recentPayments as $payment)
                            <div class="student-item">
                                <div class="student-avatar bg-success">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="student-info">
                                    <div class="student-name">{{ $payment->student->fullname ?? 'N/A' }}</div>
                                    <div class="student-class">
                                        {{ ucfirst($payment->payment_type) }} • Rp {{ number_format($payment->amount) }}
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $payment->status == 'lunas' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    <div class="text-muted small mt-1">{{ $payment->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <i class="fas fa-money-bill-wave text-muted mb-3" style="font-size: 48px;"></i>
                                <p class="text-muted">Belum ada data pembayaran</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
    // Global chart variables
    let studentLevelChart, paymentChart, attendanceSummaryChart;
    let employeeTodayChart, employeeMonthChart, studentTodayChart, studentMonthChart;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all charts
        initStudentLevelChart();
        initPaymentChart();
        initAttendanceSummaryChart();
        initEmployeeTodayChart();
        initEmployeeMonthChart();
        initStudentTodayChart();
        initStudentMonthChart();

        // Initialize Calendar
        initCalendar();
    });

    // Helper function to get color based on status
    function getStatusColor(status) {
        const colorMap = {
            'Hadir': '#4CAF50',
            'Izin/Sakit': '#2196F3',
            'Alpa': '#F44336',
            'Terlambat': '#FF9800'
        };
        return colorMap[status] || '#9C27B0'; // Default color
    }

    // Common chart configuration
    function getChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%'
        };
    }

    function initStudentLevelChart() {
        const ctx = document.getElementById('studentLevelChart').getContext('2d');
        studentLevelChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($siswaPerTingkat as $item)
                        'Kelas {{ $item->class_level }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($siswaPerTingkat as $item)
                            {{ $item->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#4266B9', '#36B37E', '#FFAB00', '#FF5630', '#6554C0', '#00B8D9'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${percentage}% (${value} siswa)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    function initPaymentChart() {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        paymentChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Belum Lunas'],
                datasets: [{
                    data: [{{ $siswaLunas }}, {{ $siswaBelumLunas }}],
                    backgroundColor: ['#36B37E', '#FF5630'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${percentage}% (${value} siswa)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    function initAttendanceSummaryChart() {
        const ctx = document.getElementById('attendanceSummaryChart').getContext('2d');
        attendanceSummaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pegawai Hadir', 'Siswa Hadir', 'Izin/Sakit'],
                datasets: [{
                    data: [
                        {{ $employeeChartToday['Hadir'] ?? 0 }},
                        {{ $studentChartToday['Hadir'] ?? 0 }},
                        {{ ($employeeChartToday['Izin/Sakit'] ?? 0) + ($studentChartToday['Izin/Sakit'] ?? 0) }}
                    ],
                    backgroundColor: [
                        '#4266B9',
                        '#36B37E',
                        '#FFAB00'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    function initEmployeeTodayChart() {
        const ctx = document.getElementById('employeeTodayChart').getContext('2d');
        const labels = {!! json_encode(array_keys($employeeChartToday)) !!};
        const data = {!! json_encode(array_values($employeeChartToday)) !!};

        employeeTodayChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: labels.map(label => getStatusColor(label)),
                    borderWidth: 0
                }]
            },
            options: getChartOptions()
        });
    }

    function initStudentTodayChart() {
        const ctx = document.getElementById('studentTodayChart').getContext('2d');
        const labels = {!! json_encode(array_keys($studentChartToday)) !!};
        const data = {!! json_encode(array_values($studentChartToday)) !!};

        studentTodayChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: labels.map(label => getStatusColor(label)),
                    borderWidth: 0
                }]
            },
            options: getChartOptions()
        });
    }

    function initEmployeeMonthChart() {
        const ctx = document.getElementById('employeeMonthChart').getContext('2d');
        const labels = {!! json_encode(array_keys($employeeChartMonth)) !!};
        const data = {!! json_encode(array_values($employeeChartMonth)) !!};

        employeeMonthChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: labels.map(label => getStatusColor(label)),
                    borderWidth: 0
                }]
            },
            options: getChartOptions()
        });
    }

    function initStudentMonthChart() {
        const ctx = document.getElementById('studentMonthChart').getContext('2d');
        const labels = {!! json_encode(array_keys($studentChartMonth)) !!};
        const data = {!! json_encode(array_values($studentChartMonth)) !!};

        studentMonthChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: labels.map(label => getStatusColor(label)),
                    borderWidth: 0
                }]
            },
            options: getChartOptions()
        });
    }

    function switchAttendanceTab(type, period) {
        // Hide all tabs and remove active class from buttons
        document.querySelectorAll(`#${type}-today, #${type}-month`).forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll(`.attendance-header .btn`).forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab and add active class to button
        document.getElementById(`${type}-${period}`).classList.add('active');
        document.querySelector(`.attendance-header [onclick="switchAttendanceTab('${type}', '${period}')"]`).classList.add('active');
    }

    function initCalendar() {
        const calendarEl = document.getElementById('calendar');
        const events = {!! json_encode($calendarEvents) !!}.map(event => ({
            title: event.title,
            start: event.start,
            allDay: true,
            backgroundColor: event.color,
            borderColor: event.color,
            textColor: '#fff'
        }));

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            events: events,
            headerToolbar: false,
            initialDate: new Date({{ $year }}, {{ $month }} - 1, 1),
            eventDisplay: 'block',
            height: 'auto'
        });
        calendar.render();
    }

    function updateCalendar() {
        const month = document.getElementById('monthSelect').value;
        const year = document.getElementById('yearSelect').value;
        window.location.href = window.location.pathname + '?month=' + month + '&year=' + year;
    }
</script>
</body>
</html>
