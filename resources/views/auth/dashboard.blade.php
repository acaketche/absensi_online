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
        }

        .main-content {
            padding: 2rem;
            background: transparent;
        }

        /* Enhanced Header */
        .dashboard-header {
            background: linear-gradient(135deg, #4266B9 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
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
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .dashboard-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 400;
            max-width: 800px;
        }

        /* Improved Metric Cards */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: var(--shadow-sm);
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-left: 4px solid;
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
            width: 50px;
            height: 50px;
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
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0;
            color: var(--text-dark);
        }

        /* Improved Chart Containers */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
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
    max-height: 300px;
}

        /* Improved Activity Table */
        .activity-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            height: 100%;
            border-top: 3px solid #4266B9;
        }

        .activity-table {
            width: 100%;
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
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
        }

        .calendar-controls {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .calendar-controls select {
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-badge i {
            margin-right: 0.25rem;
            font-size: 0.65rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .main-content {
                padding: 1.5rem;
            }

            .dashboard-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .dashboard-header {
                padding: 1.5rem;
                text-align: center;
            }

            .metric-value {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 1.5rem;
            }

            .dashboard-subtitle {
                font-size: 0.9rem;
            }

            .calendar-controls {
                flex-direction: column;
            }
        }

        /* FullCalendar Customization */
        .fc-event {
            font-size: 0.75rem;
            padding: 2px 4px;
            margin: 1px 2px;
            border-radius: 4px;
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
                <section class="row g-4 mb-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="metric-card students">
                            <div class="metric-icon students">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <p class="metric-title">Total Siswa</p>
                            <p class="metric-value">{{ number_format($totalSiswa) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-opacity-10 text-success">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="metric-card teachers">
                            <div class="metric-icon teachers">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <p class="metric-title">Total Pegawai</p>
                            <p class="metric-value">{{ number_format($totalPegawai) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="metric-card present-staff">
                            <div class="metric-icon present-staff">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <p class="metric-title">Pegawai Hadir</p>
                            <p class="metric-value">{{ number_format($pegawaiHadir) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-opacity-10 text-primary">
                                    {{ round(($pegawaiHadir/$totalPegawai)*100) }}% Kehadiran
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
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
                <section class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie"></i> Presensi Pegawai Hari Ini
                            </h5>
                            <canvas id="employeeChart" height="100"></canvas>
                            <div class="mt-3">
                                @foreach(array_keys($employeeChartData ?? []) as $i => $status)
                                <span class="status-badge me-2 mb-2"
                                      style="background-color: {{ ['#4caf50', '#2196f3', '#ff9800', '#f44336'][$i % 4] }}20;
                                             color: {{ ['#4caf50', '#2196f3', '#ff9800', '#f44336'][$i % 4] }};">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i>
                                    {{ $status }}: {{ $employeeChartData[$status] ?? 0 }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie"></i> Presensi Siswa Hari Ini
                            </h5>
                            <canvas id="studentChart" height="100"></canvas>
                            <div class="mt-3">
                                @foreach(array_keys($studentChartData ?? []) as $i => $status)
                                <span class="status-badge me-2 mb-2"
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
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="activity-container">
                            <h5 class="chart-title">
                                <i class="fas fa-list-ul"></i> Aktivitas Terkini
                            </h5>
                            <div class="table-responsive">
                                <table class="table activity-table">
                                    <thead>
                                        <tr>
                                            <th width="25%">Program</th>
                                            <th width="50%">Aktivitas</th>
                                            <th width="25%">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activities as $activity)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $activity['program'] ?? 'Sistem' }}</span>
                                            </td>
                                            <td>{{ $activity['aktivitas'] ?? 'Aktivitas tidak diketahui' }}</td>
                                            <td class="text-nowrap">
                                                @if(isset($activity['waktu']))
                                                    {{ \Carbon\Carbon::parse($activity['waktu'])->diffForHumans() }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                Tidak ada aktivitas terbaru.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
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
                            <div class="mt-3 d-flex flex-wrap gap-3">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
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
        headerToolbar: false,
        initialDate: new Date({{ $year }}, {{ $month }} - 1, 1),
        eventDisplay: 'block',
        height: 'auto'
    });
    calendar.render();

    // ======= Chart Setup =======
const employeeChartData = {!! json_encode(array_values($employeeChartData)) !!};
const studentChartData = {!! json_encode(array_values($studentChartData)) !!};
const labels = {!! json_encode(array_keys($employeeChartData)) !!};

const backgroundColors = [
    '#4caf50', '#03a9f4', '#ffc107', '#f44336', '#9c27b0',
    '#ff9800', '#00bcd4', '#e91e63', '#8bc34a', '#607d8b'
];

const options = {
    plugins: {
        legend: {
            display: false // Tidak tampilkan legend bawaan Chart.js
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    return `${context.label}: ${context.parsed}`;
                }
            },
            bodyFont: {
                weight: 'bold' // Tooltip bold
            }
        }
    }
};

// Render Employee Chart
new Chart(document.getElementById('employeeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            label: 'Presensi Pegawai',
            data: employeeChartData,
            backgroundColor: backgroundColors.slice(0, labels.length),
            borderWidth: 1
        }]
    },
    options: options
});

// Render Student Chart
new Chart(document.getElementById('studentChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            label: 'Presensi Siswa',
            data: studentChartData,
            backgroundColor: backgroundColors.slice(0, labels.length),
            borderWidth: 1
        }]
    },
    options: options
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
