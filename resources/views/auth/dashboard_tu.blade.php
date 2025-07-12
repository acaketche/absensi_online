<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tata Usaha - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 24px;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: -15px;
            top: -15px;
            font-size: 100px;
            opacity: 0.1;
            transform: rotate(15deg);
            color: var(--primary-color);
        }

        .stat-card .stat-title {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #333;
        }

        .stat-card .stat-desc {
            font-size: 13px;
            color: #6c757d;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .stat-card.primary .stat-title,
        .stat-card.primary .stat-value,
        .stat-card.primary .stat-desc {
            color: white;
        }

        .stat-card.primary .stat-icon {
            color: rgba(255, 255, 255, 0.2);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #36B37E, #2E9E6B);
            color: white;
        }

        .stat-card.success .stat-title,
        .stat-card.success .stat-value,
        .stat-card.success .stat-desc {
            color: white;
        }

        .stat-card.success .stat-icon {
            color: rgba(255, 255, 255, 0.2);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #FFAB00, #FF8B00);
            color: white;
        }

        .stat-card.warning .stat-title,
        .stat-card.warning .stat-value,
        .stat-card.warning .stat-desc {
            color: white;
        }

        .stat-card.warning .stat-icon {
            color: rgba(255, 255, 255, 0.2);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #FF5630, #DE350B);
            color: white;
        }

        .stat-card.danger .stat-title,
        .stat-card.danger .stat-value,
        .stat-card.danger .stat-desc {
            color: white;
        }

        .stat-card.danger .stat-icon {
            color: rgba(255, 255, 255, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            padding: 16px 20px;
            border: none;
        }

        .card-header-light {
            background-color: #fff;
            color: #333;
            font-weight: 600;
            padding: 16px 20px;
            border-bottom: 1px solid #eee;
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 12px;
            background-color: #fff;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
            height: 100%;
        }

        .quick-action:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            color: var(--primary-color);
        }

        .quick-action .icon {
            font-size: 32px;
            margin-bottom: 12px;
            color: var(--primary-color);
        }

        .quick-action .label {
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        .chart-container {
            position: relative;
            height: 300px;
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

        .student-item:last-child {
            border-bottom: none;
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

        .student-badge {
            padding: 4px 8px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 500;
            background-color: rgba(54, 179, 126, 0.1);
            color: var(--success-color);
        }

        .payment-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .payment-stat {
            text-align: center;
            flex: 1;
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

        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }

            .btn {
                width: 100%;
            }

            .payment-stats {
                flex-direction: column;
                gap: 15px;
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
                <div class="card stat-card primary">
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
                <div class="card stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-title">Pembayaran Lunas</div>
                    <div class="stat-value">{{ number_format($siswaLunas) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-arrow-up me-1"></i> {{ $totalSiswa > 0 ? round(($siswaLunas / $totalSiswa) * 100, 1) : 0 }}% dari total siswa
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-title">Pegawai Aktif</div>
                    <div class="stat-value">{{ number_format($totalPegawai) }}</div>
                    <div class="stat-desc">
                        Absensi hari ini: {{ $absensiHarian }}
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card danger">
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
                    <div class="card-header-light">
                        <i class="fas fa-chart-pie me-2"></i> Statistik Siswa per Angkatan
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="studentChart"></canvas>
                        </div>
                        <div class="mt-3">
                            @foreach($siswaPerAngkatan->take(3) as $angkatan)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Angkatan {{ $angkatan->angkatan }}</span>
                                <span class="fw-bold">{{ $angkatan->total }} siswa</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $totalSiswa > 0 ? ($angkatan->total / $totalSiswa) * 100 : 0 }}%"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Statistics -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header-light">
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

                        <div class="chart-container" style="height: 200px;">
                            <canvas id="paymentChart"></canvas>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Persentase Lunas</span>
                                <span class="fw-bold text-success">{{ $totalSiswa > 0 ? round(($siswaLunas / $totalSiswa) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $totalSiswa > 0 ? ($siswaLunas / $totalSiswa) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Calendar -->
            <div class="col-lg-4 mb-4">
                @include('components.calendar')
            </div>
        </div> --}}

        <div class="row">
            <!-- New Students Today -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header-light d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-plus me-2"></i> Siswa Baru Hari Ini
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ $siswaBaru->count() }}</span>
                        </div>
                    </div>
                    <div class="student-list">
                        @forelse($siswaBaru as $siswa)
                        <div class="student-item">
                            <div class="student-avatar">
                                {{ strtoupper(substr($siswa->fullname, 0, 2)) }}
                            </div>
                            <div class="student-info">
                                <div class="student-name">{{ $siswa->fullname }}</div>
                                <div class="student-class">{{ $siswa->class->class_name ?? 'Belum ada kelas' }} • {{ $siswa->angkatan }}</div>
                            </div>
                            <div class="student-badge">
                                Baru
                            </div>
                        </div>
                        @empty
                        <div class="text-center p-4">
                            <i class="fas fa-users text-muted mb-3" style="font-size: 48px;"></i>
                            <p class="text-muted">Tidak ada siswa baru hari ini</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Employee Attendance -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header-light">
                        <i class="fas fa-clock me-2"></i> Rekap Absensi Pegawai
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <div class="col-6">
                                <div class="border-end">
                                    <h3 class="text-primary mb-1">{{ $absensiHarian }}</h3>
                                    <small class="text-muted">Hari Ini</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success mb-1">{{ $absensiBulanan }}</h3>
                                <small class="text-muted">Bulan Ini</small>
                            </div>
                        </div>

                        <div class="chart-container" style="height: 200px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Tingkat Kehadiran</span>
                                <span class="fw-bold text-primary">
                                    {{ $totalPegawai > 0 ? round(($absensiHarian / $totalPegawai) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $totalPegawai > 0 ? ($absensiHarian / $totalPegawai) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Kelola Pembayaran Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="studentSelect" class="form-label">Pilih Siswa</label>
                            <select class="form-select" id="studentSelect">
                                <option value="">Pilih siswa...</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="paymentType" class="form-label">Jenis Pembayaran</label>
                            <select class="form-select" id="paymentType">
                                <option value="spp">SPP</option>
                                <option value="uang_gedung">Uang Gedung</option>
                                <option value="seragam">Seragam</option>
                                <option value="buku">Buku</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label">Jumlah Pembayaran</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="paymentAmount" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="paymentDate" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="paymentDate" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paymentNote" class="form-label">Catatan</label>
                        <textarea class="form-control" id="paymentNote" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Generate Laporan Administrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="reportType" class="form-label">Jenis Laporan</label>
                        <select class="form-select" id="reportType">
                            <option value="">Pilih jenis laporan...</option>
                            <option value="siswa">Laporan Data Siswa</option>
                            <option value="pembayaran">Laporan Pembayaran</option>
                            <option value="absensi">Laporan Absensi Pegawai</option>
                            <option value="dokumen">Laporan Dokumen</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="reportStartDate" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="reportStartDate" value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="reportEndDate" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="reportEndDate" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reportFormat" class="form-label">Format Output</label>
                        <select class="form-select" id="reportFormat">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Generate Laporan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Student Chart
    const studentCtx = document.getElementById('studentChart').getContext('2d');

    const studentData = {
        labels: [
            @foreach($siswaPerAngkatan as $angkatan)
                '{{ $angkatan->angkatan }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($siswaPerAngkatan as $angkatan)
                    {{ $angkatan->total }},
                @endforeach
            ],
            backgroundColor: [
                '#4266B9',
                '#36B37E',
                '#FFAB00',
                '#FF5630',
                '#6554C0',
                '#00B8D9'
            ],
            borderWidth: 0
        }]
    };

    const studentChartConfig = {
        type: 'doughnut',
        data: studentData,
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
                            const label = 'Angkatan ' + context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${percentage}% (${value} siswa)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    };

    new Chart(studentCtx, studentChartConfig);

    // Payment Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');

    const paymentData = {
        labels: ['Lunas', 'Belum Lunas'],
        datasets: [{
            data: [{{ $siswaLunas }}, {{ $siswaBelumLunas }}],
            backgroundColor: ['#36B37E', '#FF5630'],
            borderWidth: 0
        }]
    };

    const paymentChartConfig = {
        type: 'doughnut',
        data: paymentData,
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
            cutout: '60%'
        }
    };

    new Chart(paymentCtx, paymentChartConfig);

    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');

    const attendanceData = {
        labels: ['Hadir', 'Tidak Hadir'],
        datasets: [{
            data: [{{ $absensiHarian }}, {{ $totalPegawai - $absensiHarian }}],
            backgroundColor: ['#4266B9', '#FF5630'],
            borderWidth: 0
        }]
    };

    const attendanceChartConfig = {
        type: 'doughnut',
        data: attendanceData,
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
                            return `${label}: ${percentage}% (${value} pegawai)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    };

    new Chart(attendanceCtx, attendanceChartConfig);
});
</script>
</body>
</html>
