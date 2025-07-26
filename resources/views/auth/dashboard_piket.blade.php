<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Dashboard Piket - Sistem Manajemen Sekolah">
    <title>Dashboard Piket - E-School</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
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

        .metric-card.present-students {
            border-left-color: #4facfe;
        }

        .metric-card.absent-students {
            border-left-color: #fa709a;
        }

        .metric-card.late-students {
            border-left-color: #FF9800;
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
        .metric-icon.present-students { background-color: #4facfe; }
        .metric-icon.absent-students { background-color: #fa709a; }
        .metric-icon.late-students { background-color: #FF9800; }

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
        .attendance-table-container {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            height: 100%;
            border-top: 3px solid #4266B9;
        }

        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .attendance-table thead th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--text-dark);
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .attendance-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .attendance-table tbody tr:last-child td {
            border-bottom: none;
        }

        .attendance-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .attendance-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-present {
            background-color: #E6F7EE;
            color: #00A651;
        }

        .status-absent {
            background-color: #FFEBEE;
            color: #F44336;
        }

        .status-late {
            background-color: #FFF3E0;
            color: #FF9800;
        }

        .status-permit {
            background-color: #E3F2FD;
            color: #2196F3;
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

            .chart-legend {
                justify-content: center;
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
                            <h1 class="dashboard-title">Dashboard Piket</h1>
                            <p class="dashboard-subtitle">
                                Monitoring kehadiran dan ketidakhadiran siswa hari ini.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="text-white">
                                <div id="current-date" class="fw-bold"></div>
                                <div id="current-time" class="small"></div>
                            </div>
                        </div>
                        @if($todayPicketSchedule)
    <div class="alert alert-success">
        <strong>Hari ini Anda bertugas sebagai pegawai piket.</strong><br>
        Tanggal: {{ \Carbon\Carbon::parse($todayPicketSchedule->picket_date)->translatedFormat('l, d F Y') }}
    </div>
@else
    <div class="alert alert-warning">
        <strong>Hari ini Anda tidak dijadwalkan sebagai pegawai piket.</strong>
    </div>
@endif

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
                        <div class="metric-card present-students">
                            <div class="metric-icon present-students">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <p class="metric-title">Hadir</p>
                            <p class="metric-value">{{ number_format($siswaHadir) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-success bg-opacity-10 text-success">
                                    {{ round(($siswaHadir/$totalSiswa)*100) }}% Kehadiran
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card absent-students">
                            <div class="metric-icon absent-students">
                                <i class="fas fa-user-times"></i>
                            </div>
                            <p class="metric-title">Tidak Hadir</p>
                            <p class="metric-value">{{ number_format($siswaTidakHadir) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-danger bg-opacity-10 text-danger">
                                    {{ round(($siswaTidakHadir/$totalSiswa)*100) }}% Ketidakhadiran
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="metric-card late-students">
                            <div class="metric-icon late-students">
                                <i class="fas fa-clock"></i>
                            </div>
                            <p class="metric-title">Terlambat</p>
                            <p class="metric-value">{{ number_format($siswaTerlambat) }}</p>
                            <div class="mt-2">
                                <span class="status-badge bg-warning bg-opacity-10 text-warning">
                                    {{ round(($siswaTerlambat/$totalSiswa)*100) }}% Keterlambatan
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Attendance Charts Section -->
                <section class="row g-3 mb-4">
                    <!-- Student Attendance Chart -->
                    <div class="col-12 col-lg-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie"></i> Statistik Kehadiran Siswa
                            </h5>
                            <div class="chart-tabs">
                                <div class="chart-tab active" onclick="switchTab('student', 'daily')">Hari Ini</div>
                                <div class="chart-tab" onclick="switchTab('student', 'monthly')">Bulan Ini</div>
                                <div class="chart-tab" onclick="switchTab('student', 'weekly')">Minggu Ini</div>
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

                            <div id="student-weekly" class="chart-tab-content">
                                <canvas id="studentWeekChart"></canvas>
                                <div class="chart-legend mt-3">
                                    @foreach($studentChartWeek as $status => $count)
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

                    <!-- Class Attendance Summary -->
                    <div class="col-12 col-lg-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-bar"></i> Kehadiran per Kelas
                            </h5>
                            <canvas id="classAttendanceChart"></canvas>
                            <div class="chart-legend mt-3">
                                <span class="status-badge" style="background-color: #4CAF5020; color: #4CAF50;">
                                    <i class="fas fa-circle"></i> Hadir
                                </span>
                                <span class="status-badge" style="background-color: #F4433620; color: #F44336;">
                                    <i class="fas fa-circle"></i> Tidak Hadir
                                </span>
                                <span class="status-badge" style="background-color: #FF980020; color: #FF9800;">
                                    <i class="fas fa-circle"></i> Terlambat
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

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
let studentTodayChart, studentMonthChart, studentWeekChart, classAttendanceChart;

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

    // Initialize all charts
    initCharts();

    // Handle window resize
    window.addEventListener('resize', function() {
        if (studentTodayChart) studentTodayChart.resize();
        if (studentMonthChart) studentMonthChart.resize();
        if (studentWeekChart) studentWeekChart.resize();
        if (classAttendanceChart) classAttendanceChart.resize();
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
    initStudentTodayChart();
    initStudentMonthChart();
    initStudentWeekChart();
    initClassAttendanceChart();
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

function initStudentWeekChart() {
    if (document.getElementById('studentWeekChart')) {
        const ctx = document.getElementById('studentWeekChart').getContext('2d');
        const labels = {!! json_encode(array_keys($studentChartWeek)) !!};
        const data = {!! json_encode(array_values($studentChartWeek)) !!};

        studentWeekChart = new Chart(ctx, {
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

function initClassAttendanceChart() {
    if (document.getElementById('classAttendanceChart')) {
        const ctx = document.getElementById('classAttendanceChart').getContext('2d');
        const classData = {!! json_encode($classAttendanceData) !!};

        const classes = classData.map(item => item.class_name);
        const presentData = classData.map(item => item.present);
        const absentData = classData.map(item => item.absent);
        const lateData = classData.map(item => item.late);

        classAttendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: classes,
                datasets: [
                    {
                        label: 'Hadir',
                        data: presentData,
                        backgroundColor: '#4CAF50',
                        borderColor: '#4CAF50',
                        borderWidth: 1
                    },
                    {
                        label: 'Tidak Hadir',
                        data: absentData,
                        backgroundColor: '#F44336',
                        borderColor: '#F44336',
                        borderWidth: 1
                    },
                    {
                        label: 'Terlambat',
                        data: lateData,
                        backgroundColor: '#FF9800',
                        borderColor: '#FF9800',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
}

function switchTab(type, period) {
    // Hide all tabs and remove active class
    document.querySelectorAll(`#${type}-daily, #${type}-monthly, #${type}-weekly`).forEach(el => {
        el.classList.remove('active');
    });
    document.querySelectorAll(`.chart-tabs .chart-tab`).forEach(el => {
        el.classList.remove('active');
    });

    // Show selected tab and add active class
    document.getElementById(`${type}-${period}`).classList.add('active');
    document.querySelector(`.chart-tabs [onclick="switchTab('${type}', '${period}')"]`).classList.add('active');
}
</script>
</body>
</html>
@endif
