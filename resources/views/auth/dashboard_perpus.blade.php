<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #e6f3ff;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --dark: #212529;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #4a4a4a;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            transition: all 0.3s;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        /* Metric Cards */
        .metrics-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid var(--primary);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .metric-card-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .metric-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 22px;
        }

        .metric-info h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-info p {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: var(--dark);
        }

        /* Chart Container */
        .chart-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .chart-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .chart-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .metrics-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .metrics-container {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .metric-card {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .metric-card:nth-child(1) { animation-delay: 0.1s; }
        .metric-card:nth-child(2) { animation-delay: 0.2s; }
        .metric-card:nth-child(3) { animation-delay: 0.3s; }
        .metric-card:nth-child(4) { animation-delay: 0.4s; }
        .metric-card:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
@if(Auth::guard('employee')->check())
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-book-open me-2"></i> Dashboard Perpustakaan</h1>
                <div class="dropdown">
                   @include('components.profiladmin')
                </div>
            </div>

            <!-- Metrics Cards -->
            <div class="metrics-container">
                <!-- Total Buku -->
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Total Buku</h3>
                            <p>{{ number_format($books->count()) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Buku Yang Dipinjam -->
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Dipinjam</h3>
                            <p>{{ number_format($bookLoans->where('status', 'Dipinjam')->count()) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Buku Yang Dikembalikan -->
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Dikembalikan</h3>
                            <p>{{ number_format($bookLoans->where('status', 'Dikembalikan')->count()) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Siswa -->
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Total Siswa</h3>
                            <p>{{ number_format($totalSiswa) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pegawai -->
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Total Pegawai</h3>
                            <p>{{ number_format($totalPegawai) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="chart-container">
                <div class="chart-card">
                    <h3><i class="fas fa-chart-pie me-2"></i> Status Peminjaman Buku</h3>
                    <canvas id="bookLoanChart" height="100"></canvas>
                </div>


            <!-- Change Password Modal -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    @error('new_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                  // Pie Chart: Status Peminjaman Buku
                const pieCtx = document.getElementById('bookLoanChart').getContext('2d');
                const bookLoanChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Dipinjam', 'Dikembalikan'],
                        datasets: [{
                            data: [{{ $borrowedCount }}, {{ $returnedCount }}],
                            backgroundColor: ['#f94144', '#43aa8b'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 10,
                                    padding: 10,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            </script>
        </div>
    </div>
</body>
</html>
@endif
