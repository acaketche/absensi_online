<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Dashboard E-School - Sistem Manajemen Sekolah">
    <title>Dashboard - E-School</title>
  <!-- Bootstrap (harus duluan) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">

<!-- Custom Style HARUS TERAKHIR supaya bisa override yang di atas -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #4266B9;
            --primary-cyan: #4facfe;
            --primary-green: #43e97b;
            --primary-red: #fa709a;
            --primary-purple: #a855f7;
            --primary-orange: #ff9a56;
            --text-dark: #1a202c;
            --text-gray: #718096;
            --bg-light: #f7fafc;
            --bg-gradient: linear-gradient(135deg, #4266B9 0%, #764ba2 100%);
            --border-light: #e2e8f0;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .main-content {
            padding: 1.5rem;
            background: transparent;
        }

        /* Enhanced Header */
        .dashboard-header {
            background: linear-gradient(135deg, #4266B9 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-xl);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .dashboard-title {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-subtitle {
            font-size: clamp(0.9rem, 2vw, 1rem);
            opacity: 0.9;
            font-weight: 400;
            max-width: 800px;
        }

        /* Improved Metric Cards */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: var(--shadow-sm);
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-left: 4px solid;
            margin-bottom: 1rem;
        }

        .metric-card.students {
            border-left-color: #4266B9;
        }

        .metric-card.teachers {
            border-left-color: #4facfe;
        }

        .metric-card.present-staff {
            border-left-color: #43e97b;
        }

        .metric-card.present-students {
            border-left-color: #fa709a;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .metric-icon {
            font-size: 1.5rem;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 1rem;
            color: white;
        }

        .metric-icon.students { background-color: #4266B9; }
        .metric-icon.teachers { background-color: #4facfe; }
        .metric-icon.present-staff { background-color: #43e97b; }
        .metric-icon.present-students { background-color: #fa709a; }

        .metric-title {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-value {
            font-size: clamp(1.5rem, 4vw, 1.8rem);
            font-weight: 700;
            margin-bottom: 0;
            color: var(--text-dark);
        }

        /* Improved Chart Containers */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            height: 100%;
            border-top: 3px solid #4266B9;
            min-height: 350px;
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
        }

        .chart-title i {
            margin-right: 0.5rem;
            color: #4266B9;
        }

        .chart-container canvas {
            width: 100% !important;
            height: auto !important;
            max-height: 300px;
        }

        /* Chart Tabs */
        .chart-tabs {
            display: flex;
            border-bottom: 1px solid var(--border-light);
            margin-bottom: 1rem;
        }

        .chart-tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-gray);
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .chart-tab.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
        }

        .chart-tab-content {
            display: none;
        }

        .chart-tab-content.active {
            display: block;
        }

        /* Chart Legend */
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .status-badge i {
            margin-right: 0.25rem;
            font-size: 0.65rem;
        }

        /* Improved Activity Table */
        .activity-container {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            height: 100%;
            border-top: 3px solid #4266B9;
        }

        .activity-table {
            width: 100%;
            min-width: 600px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .activity-table thead th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--text-dark);
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .activity-table tbody tr:last-child td {
            border-bottom: none;
        }

        .activity-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Improved Calendar */
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            border-top: 3px solid #4266B9;
        }

        .calendar-controls {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .calendar-controls select {
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            font-weight: 500;
            font-size: 0.85rem;
            flex: 1;
            min-width: 120px;
        }

        /* FullCalendar Customization */
        .fc {
            font-size: 0.9rem;
        }

        .fc-event {
            font-size: 0.7rem;
            padding: 2px 4px;
            margin: 1px 2px;
            border-radius: 4px;
            white-space: normal;
            line-height: 1.2;
        }

        .fc-daygrid-event {
            white-space: normal;
        }

        .fc-toolbar-title {
            font-size: 1.1rem;
        }

        .fc-button {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 1199.98px) {
            .dashboard-header {
                padding: 1.25rem;
            }

            .metric-card {
                padding: 1rem;
            }
        }

        @media (max-width: 991.98px) {
            .main-content {
                padding: 1.25rem;
            }

            .chart-container canvas {
                max-height: 250px;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-header {
                text-align: center;
                padding: 1.5rem 1rem;
            }

            .dashboard-header .col-md-8,
            .dashboard-header .col-md-4 {
                width: 100%;
                text-align: center !important;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.6rem;
            }

            .fc-toolbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .fc-toolbar .fc-left,
            .fc-toolbar .fc-center,
            .fc-toolbar .fc-right {
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 1rem;
            }

            .dashboard-title {
                font-size: 1.5rem;
            }

            .dashboard-subtitle {
                font-size: 0.9rem;
            }

            .calendar-controls {
                flex-direction: column;
                gap: 0.5rem;
            }

            .calendar-controls select {
                width: 100%;
            }

            .chart-legend {
                justify-content: center;
            }

            .fc-toolbar-title {
                font-size: 1rem;
            }

            .fc-button {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Animation Effects */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .metric-card {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        .metric-card:nth-child(1) { animation-delay: 0.1s; }
        .metric-card:nth-child(2) { animation-delay: 0.2s; }
        .metric-card:nth-child(3) { animation-delay: 0.3s; }
        .metric-card:nth-child(4) { animation-delay: 0.4s; }

    </style>
</head>
<body class="bg-light">
@if(Auth::guard('employee')->check())
   <div class="d-flex">
        {{-- Sidebar --}}
            @include('components.sidebar')

        {{-- Main Content --}}
            <div class="main-content-wrapper flex-grow-1">
        <main class="p-3">
                @include('components.profiladmin')

                <!-- Dashboard Header -->
                <header class="dashboard-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="dashboard-title">Selamat Datang Kembali!</h1>
                            <p class="dashboard-subtitle">
                                Ringkasan aktivitas dan statistik sekolah hari ini.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="text-white">
                                <div id="current-date" class="fw-bold"></div>
                                <div id="current-time" class="small"></div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Metric Cards -->
                <section class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card students">
                            <div class="metric-icon students">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <p class="metric-title">Total Siswa</p>
                            <p class="metric-value">{{ number_format($totalSiswa) }}</p>
                            <div class="stat-desc">
                        <i class="fas fa-users me-1"></i> Siswa aktif saat ini
                    </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card teachers">
                            <div class="metric-icon teachers">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <p class="metric-title">Total Pegawai dan Guru</p>
                            <p class="metric-value">{{ number_format($totalPegawai) }}</p>
                            <div class="stat-desc">
                        <i class="fas fa-users me-1"></i> Pegawai dan Guru aktif saat ini
                    </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card present-staff">
                            <div class="metric-icon present-staff">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <p class="metric-title">Pegawai Hadir</p>
                            <p class="metric-value">{{ number_format($pegawaiHadir) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-success bg-opacity-10 text-primary">
                                    {{ round(($pegawaiHadir/$totalPegawai)*100) }}% Kehadiran
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card present-students">
                            <div class="metric-icon present-students">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="metric-title">Siswa Hadir</p>
                            <p class="metric-value">{{ number_format($siswaHadir) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-success bg-opacity-10 text-success">
                                    {{ round(($siswaHadir/$totalSiswa)*100) }}% Kehadiran
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Attendance Charts Section -->
                <section class="row g-3 mb-4">
                    <!-- Employee Attendance -->
                    <div class="col-12 col-lg-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-user-tie"></i> Presensi Pegawai
                            </h5>
                            <div class="chart-tabs">
                                <div class="chart-tab active" onclick="switchTab('employee', 'daily')">Hari Ini</div>
                                <div class="chart-tab" onclick="switchTab('employee', 'monthly')">Bulan Ini</div>
                            </div>

                            <div id="employee-daily" class="chart-tab-content active">
                                <canvas id="employeeTodayChart"></canvas>
                                <div class="chart-legend mt-3">
                                    @foreach($employeeChartToday as $status => $count)
                                    <span class="status-badge"
                                          style="background-color: {{ [
                                              'Hadir' => '#4CAF50',
                                              'Izin/Sakit' => '#2196F3',
                                              'Alpa' => '#F44336',
                                              'Terlambat' => '#FF9800'
                                          ][$status] ?? '#9C27B0' }}20;
                                                 color: {{ [
                                                     'Hadir' => '#4CAF50',
                                                     'Izin/Sakit' => '#2196F3',
                                                     'Alpa' => '#F44336',
                                                     'Terlambat' => '#FF9800'
                                                 ][$status] ?? '#9C27B0' }};">
                                        <i class="fas fa-circle"></i>
                                        {{ $status }}: {{ $count }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>

                            <div id="employee-monthly" class="chart-tab-content">
                                <canvas id="employeeMonthChart"></canvas>
                                <div class="chart-legend mt-3">
                                    @foreach($employeeChartMonth as $status => $count)
                                    <span class="status-badge"
                                          style="background-color: {{ [
                                              'Hadir' => '#4CAF50',
                                              'Izin/Sakit' => '#2196F3',
                                              'Alpa' => '#F44336',
                                              'Terlambat' => '#FF9800'
                                          ][$status] ?? '#9C27B0' }}20;
                                                 color: {{ [
                                                     'Hadir' => '#4CAF50',
                                                     'Izin/Sakit' => '#2196F3',
                                                     'Alpa' => '#F44336',
                                                     'Terlambat' => '#FF9800'
                                                 ][$status] ?? '#9C27B0' }};">
                                        <i class="fas fa-circle"></i>
                                        {{ $status }}: {{ $count }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Attendance -->
                    <div class="col-12 col-lg-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-user-graduate"></i> Presensi Siswa
                            </h5>
                            <div class="chart-tabs">
                                <div class="chart-tab active" onclick="switchTab('student', 'daily')">Hari Ini</div>
                                <div class="chart-tab" onclick="switchTab('student', 'monthly')">Bulan Ini</div>
                            </div>

                            <div id="student-daily" class="chart-tab-content active">
                                <canvas id="studentTodayChart"></canvas>
                                <div class="chart-legend mt-3">
                                    @foreach($studentChartToday as $status => $count)
                                    <span class="status-badge"
                                          style="background-color: {{ [
                                              'Hadir' => '#4CAF50',
                                              'Izin/Sakit' => '#2196F3',
                                              'Alpa' => '#F44336',
                                              'Terlambat' => '#FF9800'
                                          ][$status] ?? '#9C27B0' }}20;
                                                 color: {{ [
                                                     'Hadir' => '#4CAF50',
                                                     'Izin/Sakit' => '#2196F3',
                                                     'Alpa' => '#F44336',
                                                     'Terlambat' => '#FF9800'
                                                 ][$status] ?? '#9C27B0' }};">
                                        <i class="fas fa-circle"></i>
                                        {{ $status }}: {{ $count }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>

                            <div id="student-monthly" class="chart-tab-content">
                                <canvas id="studentMonthChart"></canvas>
                                <div class="chart-legend mt-3">
                                    @foreach($studentChartMonth as $status => $count)
                                    <span class="status-badge"
                                          style="background-color: {{ [
                                              'Hadir' => '#4CAF50',
                                              'Izin/Sakit' => '#2196F3',
                                              'Alpa' => '#F44336',
                                              'Terlambat' => '#FF9800'
                                          ][$status] ?? '#9C27B0' }}20;
                                                 color: {{ [
                                                     'Hadir' => '#4CAF50',
                                                     'Izin/Sakit' => '#2196F3',
                                                     'Alpa' => '#F44336',
                                                     'Terlambat' => '#FF9800'
                                                 ][$status] ?? '#9C27B0' }};">
                                        <i class="fas fa-circle"></i>
                                        {{ $status }}: {{ $count }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bottom Section -->
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="activity-container">
                            <h5 class="chart-title">
                                <i class="fas fa-list-ul"></i> Aktivitas Terkini
                            </h5>
                            <div class="table-responsive">
                                <table class="table activity-table">
                                    <thead>
                                        <tr>
                                            <th width="20%">Program</th>
                                            <th width="30%">Aktivitas</th>
                                            <th width="30%">Oleh</th>
                                            <th width="20%">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activities as $activity)
                                        <tr>
                                            <td>{{ $activity['program'] ?? 'Sistem' }}</td>
                                            <td>{{ $activity['aktivitas'] ?? 'Aktivitas tidak diketahui' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $activity['role'] ?? 'Tidak diketahui' }}</span>
                                            </td>
                                            <td class="text-nowrap">
                                                @if(isset($activity['waktu']))
                                                    {{ \Carbon\Carbon::parse($activity['waktu'])->locale('id')->diffForHumans() }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Tidak ada aktivitas terbaru.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="calendar-container">
                            <h5 class="chart-title">
                                <i class="fas fa-calendar-alt"></i> Kalender Akademik
                            </h5>
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
                </div>
            </div>
        </div>
    </div>
</main>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
// Global variables to store chart instances
let employeeTodayChart, studentTodayChart, employeeMonthChart, studentMonthChart;

document.addEventListener('DOMContentLoaded', function() {
    // Update current date and time
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
        document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Initialize Calendar
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
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialDate: new Date({{ $year }}, {{ $month }} - 1, 1),
        eventDisplay: 'block',
        height: 'auto',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });
    calendar.render();

    // Initialize all charts
    initCharts();

    // Handle window resize
    window.addEventListener('resize', function() {
        if (employeeTodayChart) employeeTodayChart.resize();
        if (studentTodayChart) studentTodayChart.resize();
        if (employeeMonthChart) employeeMonthChart.resize();
        if (studentMonthChart) studentMonthChart.resize();
    });
});

// Helper function to get color based on status
function getStatusColor(status) {
    const colorMap = {
        'Hadir': '#4CAF50',
        'Izin': '#2196F3',
        'Sakit': '#2196F3',
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

function initCharts() {
    initEmployeeTodayChart();
    initStudentTodayChart();
    initEmployeeMonthChart();
    initStudentMonthChart();
}

function initEmployeeTodayChart() {
    if (document.getElementById('employeeTodayChart')) {
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
}

function initStudentTodayChart() {
    if (document.getElementById('studentTodayChart')) {
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
}

function initEmployeeMonthChart() {
    if (document.getElementById('employeeMonthChart')) {
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
}

function initStudentMonthChart() {
    if (document.getElementById('studentMonthChart')) {
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
}

function switchTab(type, period) {
    // Hide all tabs and remove active class
    document.querySelectorAll(`#${type}-daily, #${type}-monthly`).forEach(el => {
        el.classList.remove('active');
    });
    document.querySelectorAll(`.chart-tabs .chart-tab`).forEach(el => {
        el.classList.remove('active');
    });

    // Show selected tab and add active class
    document.getElementById(`${type}-${period}`).classList.add('active');
    document.querySelector(`.chart-tabs [onclick="switchTab('${type}', '${period}')"]`).classList.add('active');
}

function updateCalendar() {
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;
    window.location.href = window.location.pathname + '?month=' + month + '&year=' + year;
}
</script>
</body>
</html>
@endif
