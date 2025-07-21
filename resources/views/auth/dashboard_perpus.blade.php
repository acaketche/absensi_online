<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perpustakaan - E-School</title>
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

        .book-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .book-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }

        .book-item:hover {
            background-color: #f8f9fa;
        }

        .book-cover {
            width: 40px;
            height: 60px;
            border-radius: 4px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            margin-right: 12px;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-info {
            flex: 1;
        }

        .book-title {
            font-weight: 600;
            margin-bottom: 2px;
            color: #333;
        }

        .book-author {
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
            .payment-stats {
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
                <h2 class="fs-4 fw-bold mb-1">Dashboard Perpustakaan</h2>
                <p class="text-muted mb-0">Selamat datang, {{ Auth::guard('employee')->user()->fullname }}!</p>
                @if($activeAcademicYear)
                    <small class="text-primary">Tahun Ajaran: {{ $activeAcademicYear->year_name }} - Semester: {{ $activeSemester->semester_name ?? '' }}</small>
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
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-title">Total Buku</div>
                    <div class="stat-value">{{ number_format($totalBooks) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-layer-group me-1"></i> Koleksi buku perpustakaan
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card success h-100">
                    <div class="stat-icon">
                        <i class="fas fa-copy"></i>
                    </div>
                    <div class="stat-title">Total Eksemplar</div>
                    <div class="stat-value">{{ number_format($totalCopies) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-book-open me-1"></i> Salinan buku tersedia
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card stat-card warning h-100">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-title">Peminjaman Aktif</div>
                    <div class="stat-value">{{ number_format($activeLoans) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-clock me-1"></i> Belum dikembalikan
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card danger h-100">
                    <div class="stat-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="stat-title">Peminjaman Hari Ini</div>
                    <div class="stat-value">{{ number_format($todayLoans) }}</div>
                    <div class="stat-desc">
                        <i class="fas fa-calendar-day me-1"></i> {{ now()->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Book Statistics -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i> Statistik Buku per Tingkat Kelas
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="bookLevelChart"></canvas>
                        </div>
                        <div class="mt-3">
                            @foreach($booksPerLevel->take(3) as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Kelas {{ $item->class_level }}</span>
                                <span class="fw-bold">{{ $item->total }} buku</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $totalBooks > 0 ? ($item->total / $totalBooks) * 100 : 0 }}%"></div>
                            </div>
                            @endforeach
                            @if($booksPerLevel->count() > 3)
                            <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary w-100">
                                Lihat Semua Tingkat Kelas
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Statistics -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-exchange-alt me-2"></i> Status Peminjaman
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="loanStatusChart"></canvas>
                        </div>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <span class="status-badge" style="background-color: #36B37E20; color: #36B37E;">
                                <i class="fas fa-circle"></i> Dikembalikan: {{ $returnedLoans }}
                            </span>
                            <span class="status-badge" style="background-color: #FFAB0020; color: #FFAB00;">
                                <i class="fas fa-circle"></i> Dipinjam: {{ $activeLoans }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability Statistics -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-copy me-2"></i> Ketersediaan Eksemplar
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="copyAvailabilityChart"></canvas>
                        </div>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <span class="status-badge" style="background-color: #36B37E20; color: #36B37E;">
                                <i class="fas fa-circle"></i> Tersedia: {{ $availableCopies }}
                            </span>
                            <span class="status-badge" style="background-color: #FF563020; color: #FF5630;">
                                <i class="fas fa-circle"></i> Dipinjam: {{ $totalCopies - $availableCopies }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Books -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-book me-2"></i> Buku Terbaru
                        </div>
                        <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="book-list">
                            @forelse($recentBooks as $book)
                            <div class="book-item">
                                <div class="book-cover">
                                    @if($book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
                                    @else
                                        <i class="fas fa-book"></i>
                                    @endif
                                </div>
                                <div class="book-info">
                                    <div class="book-title">{{ $book->title }}</div>
                                    <div class="book-author">{{ $book->author }}</div>
                                    <small class="text-muted">{{ $book->publisher }} ({{ $book->year_published }})</small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $book->stock > 0 ? 'success' : 'danger' }}">
                                        {{ $book->stock }} tersedia
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <i class="fas fa-book text-muted mb-3" style="font-size: 48px;"></i>
                                <p class="text-muted">Belum ada data buku</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Loans -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-exchange-alt me-2"></i> Peminjaman Terakhir
                        </div>
                        <a href="{{ route('book-loans.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="book-list">
                            @forelse($recentLoans as $loan)
                            <div class="book-item">
                                <div class="book-cover">
                                    @if($loan->book->cover)
                                        <img src="{{ asset('storage/' . $loan->book->cover) }}" alt="{{ $loan->book->title }}">
                                    @else
                                        <i class="fas fa-book"></i>
                                    @endif
                                </div>
                                <div class="book-info">
                                    <div class="book-title">{{ $loan->book->title }}</div>
                                    <div class="book-author">{{ $loan->student->fullname ?? 'N/A' }}</div>
                                    <small class="text-muted">Pinjam: {{ $loan->loan_date->format('d M Y') }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $loan->status == 'Dikembalikan' ? 'success' : 'warning' }}">
                                        {{ $loan->status }}
                                    </span>
                                    @if($loan->status == 'Dikembalikan')
                                    <div class="text-muted small mt-1">Kembali: {{ $loan->return_date->format('d M Y') }}</div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <i class="fas fa-exchange-alt text-muted mb-3" style="font-size: 48px;"></i>
                                <p class="text-muted">Belum ada data peminjaman</p>
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
    let bookLevelChart, loanStatusChart, copyAvailabilityChart;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all charts
        initBookLevelChart();
        initLoanStatusChart();
        initCopyAvailabilityChart();
    });

    function initBookLevelChart() {
        const ctx = document.getElementById('bookLevelChart').getContext('2d');
        bookLevelChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($booksPerLevel as $item)
                        'Kelas {{ $item->class_level }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($booksPerLevel as $item)
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
                                return `${label}: ${percentage}% (${value} buku)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    function initLoanStatusChart() {
        const ctx = document.getElementById('loanStatusChart').getContext('2d');
        loanStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Dikembalikan', 'Dipinjam'],
                datasets: [{
                    data: [{{ $returnedLoans }}, {{ $activeLoans }}],
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
                                return `${label}: ${percentage}% (${value} peminjaman)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    function initCopyAvailabilityChart() {
        const ctx = document.getElementById('copyAvailabilityChart').getContext('2d');
        copyAvailabilityChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Dipinjam'],
                datasets: [{
                    data: [{{ $availableCopies }}, {{ $totalCopies - $availableCopies }}],
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
                                return `${label}: ${percentage}% (${value} eksemplar)`;
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
