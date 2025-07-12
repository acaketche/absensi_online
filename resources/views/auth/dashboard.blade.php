<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard E-School - Sistem Manajemen Sekolah">
    <title>Dashboard - E-School</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            line-height: 1.6;
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

@if(Auth::guard('employee')->check())
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-2 sidebar p-0">
            @include('components.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-md-10 p-0">
            <div class="main-content">
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
                            <div class="mt-2">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card teachers">
                            <div class="metric-icon teachers">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <p class="metric-title">Total Pegawai</p>
                            <p class="metric-value">{{ number_format($totalPegawai) }}</p>
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

                <!-- Charts Section -->
                <section class="row g-3 mb-4">
                    <div class="col-12 col-lg-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie"></i> Presensi Pegawai Hari Ini
                            </h5>
                            <canvas id="employeeChart"></canvas>
                            <div class="chart-legend mt-3">
                                @foreach(array_keys($employeeChartData ?? []) as $i => $status)
                                <span class="status-badge"
                                      style="background-color: {{ ['#4caf50', '#2196f3', '#ff9800', '#f44336'][$i % 4] }}20;
                                             color: {{ ['#4caf50', '#2196f3', '#ff9800', '#f44336'][$i % 4] }};">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i>
                                    {{ $status }}: {{ $employeeChartData[$status] ?? 0 }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie"></i> Presensi Siswa Hari Ini
                            </h5>
                            <canvas id="studentChart"></canvas>
                            <div class="chart-legend mt-3">
                                @foreach(array_keys($studentChartData ?? []) as $i => $status)
                                <span class="status-badge"
                                      style="background-color: {{ ['#4caf50', '#2196f3', '#ff9800', '#f44336'][$i % 4] }}20;
                                             color: {{ ['#4caf50', '#2196f3', '#ff9800', '#f44336'][$i % 4] }};">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i>
                                    {{ $status }}: {{ $studentChartData[$status] ?? 0 }}
                                </span>
                                @endforeach
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
</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
// Global variables to store chart instances
let employeeChart, studentChart;

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

    // Initialize Charts with responsive configuration
    const employeeCtx = document.getElementById('employeeChart').getContext('2d');
    const studentCtx = document.getElementById('studentChart').getContext('2d');

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw}`;
                    }
                },
                bodyFont: {
                    weight: 'bold'
                }
            }
        }
    };

    employeeChart = new Chart(employeeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($employeeChartData)) !!},
            datasets: [{
                data: {!! json_encode(array_values($employeeChartData)) !!},
                backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    studentChart = new Chart(studentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($studentChartData)) !!},
            datasets: [{
                data: {!! json_encode(array_values($studentChartData)) !!},
                backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (employeeChart) employeeChart.resize();
        if (studentChart) studentChart.resize();
    });
});

function updateCalendar() {
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;
    window.location.href = window.location.pathname + '?month=' + month + '&year=' + year;
}
</script>
</body>
</html>
@endif
