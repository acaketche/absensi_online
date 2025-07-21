<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Kelas - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            margin-right: 12px;
            overflow: hidden;
        }

        .student-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-info {
            flex: 1;
        }

        .student-name {
            font-weight: 600;
            margin-bottom: 2px;
            color: #333;
        }

        .student-nis {
            font-size: 13px;
            color: #6c757d;
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

        @media (max-width: 768px) {
            .stats-row {
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
                <h2 class="fs-4 fw-bold mb-1">Dashboard Wali Kelas</h2>
                <p class="text-muted mb-0">Selamat datang, {{ Auth::guard('employee')->user()->fullname }}!</p>
                @if($activeAcademicYear)
                    <small class="text-primary">Tahun Ajaran: {{ $activeAcademicYear->name }} - Semester: {{ $activeSemester->name ?? '' }}</small>
                @endif
            </div>
            <div>
                <span class="badge bg-primary p-2">
                    <i class="fas fa-calendar-day me-1"></i> {{ now()->format('d F Y') }}
                </span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row stats-row mb-4">
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card primary h-100">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value">{{ number_format($totalStudents) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-user-graduate me-1"></i> Siswa di kelas Anda
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card success h-100">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-title">Rapor Terupload</div>
                    <div class="stat-value">{{ number_format($uploadedReports) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-check-circle me-1"></i> Sudah mengumpulkan
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card warning h-100">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-title">Belum Upload</div>
                    <div class="stat-value">{{ number_format($pendingReports) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-exclamation-circle me-1"></i> Belum mengumpulkan
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card danger h-100">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-title">Rapor Hari Ini</div>
                    <div class="stat-value">{{ number_format($todayReports) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-calendar-day me-1"></i> {{ now()->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Report Status -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i> Status Pengumpulan Rapor
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="reportStatusChart"></canvas>
                        </div>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <span class="status-badge" style="background-color: #36B37E20; color: #36B37E;">
                                <i class="fas fa-circle"></i> Terupload: {{ $uploadedReports }}
                            </span>
                            <span class="status-badge" style="background-color: #FFAB0020; color: #FFAB00;">
                                <i class="fas fa-circle"></i> Belum: {{ $pendingReports }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-upload me-2"></i> Rapor Terbaru
                        </div>
                        <a href="{{ route('rapor.students', ['classId' => $class->class_id]) }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="student-list">
                            @forelse($recentReports as $report)
                            <div class="student-item">
                                <div class="student-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="student-info">
                                    <div class="student-name">{{ $report->student->fullname }}</div>
                                    <div class="student-nis">NIS: {{ $report->student->id_student }}</div>
                                    <small class="text-muted">Upload: {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $report->status_report == 'Sudah Ada' ? 'success' : 'warning' }}">
                                        {{ $report->status_report }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <i class="fas fa-file-alt text-muted mb-3" style="font-size: 48px;"></i>
                                <p class="text-muted">Belum ada rapor terupload</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Students Without Reports -->
            <div class="col-lg-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-exclamation-triangle me-2"></i> Siswa Belum Mengumpulkan Rapor
                        </div>
                       <a href="{{ route('rapor.students', ['classId' => $class->class_id]) }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="student-list">
                            @forelse($studentsWithoutReports as $student)
                            <div class="student-item">
                                <div class="student-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="student-info">
                                    <div class="student-name">{{ $student->fullname }}</div>
                                    <div class="student-nis">NIS: {{ $student->id_student }}</div>
                                </div>
                                <div>
                                    <span class="badge bg-warning">
                                        Belum Ada
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <i class="fas fa-check-circle text-success mb-3" style="font-size: 48px;"></i>
                                <p class="text-success">Semua siswa sudah mengumpulkan rapor</p>
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
<script>
    // Global chart variables
    let reportStatusChart;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all charts
        initReportStatusChart();
    });

    function initReportStatusChart() {
        const ctx = document.getElementById('reportStatusChart').getContext('2d');
        reportStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Terupload', 'Belum Upload'],
                datasets: [{
                    data: [{{ $uploadedReports }}, {{ $pendingReports }}],
                    backgroundColor: ['#36B37E', '#FFAB00'],
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
</script>
</body>
</html>
