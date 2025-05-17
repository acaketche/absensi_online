<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5f5f5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 10px 35px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
        }

        .search-bar i {
            position: absolute;
            left: 10px;
            color: #666;
        }

        /* Metric Cards */
        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .metric-card-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .metric-icon {
            width: 45px;
            height: 45px;
            background: rgba(66, 102, 185, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4266B9;
        }

        .metric-info h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .metric-info p {
            font-size: 24px;
            font-weight: bold;
        }

        /* Graph Section */
        .graph-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .graph-title {
            margin-bottom: 20px;
        }

        /* Bottom Grid */
        .bottom-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .calendar-section, .activity-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .month-selector {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table th {
            text-align: left;
            color: #666;
            font-weight: normal;
            padding: 10px 0;
        }

        .activity-table td {
            padding: 10px 0;
            border-top: 1px solid #eee;
        }

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #eee;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .btn-primary, .bg-primary {
            background-color: #4266B9 !important;
            border-color: #4266B9 !important;
        }

        .btn-primary:hover {
            background-color: #365796 !important;
            border-color: #365796 !important;
        }

        .text-primary {
            color: #4266B9 !important;
        }

        @media (max-width: 1024px) {
            .metrics {
                grid-template-columns: repeat(2, 1fr);
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo-text, .nav-text {
                display: none;
            }

            .main-content {
                padding: 20px;
            }
        }
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
                <h1>Dashboard</h1>
                <div class="dropdown">
                   @include('components.profiladmin')
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="metrics">
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Siswa</h3>
                            <p>10</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Pegawai</h3>
                            <p>10</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Pegawai hadir hari ini</h3>
                            <p>10</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="metric-info">
                            <h3>Siswa hadir hari ini</h3>
                            <p>10</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graph Section -->
            <div class="graph-section">
                <h2 class="graph-title">Grafik Presensi Hari ini</h2>
                <div style="height: 300px; background: #f9f9f9; border-radius: 8px;">
                    <!-- Placeholder for graph -->
                </div>
            </div>

            <!-- Bottom Grid -->
            <div class="bottom-grid">
                <div class="calendar-section">
                    <div class="calendar-header">
                        <h2>School Calendar</h2>
                        <div class="month-selector">
                            March 2025
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <!-- Calendar content would go here -->
                </div>

                <div class="activity-section">
                    <h2 style="margin-bottom: 20px;">Aktivitas Terakhir</h2>
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Aktivitas Terakhir</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Pegawai TU</td>
                                <td>Login ke aplikasi</td>
                                <td>1 detik yang lalu</td>
                            </tr>
                            <tr>
                                <td>Pegawai Piket</td>
                                <td>Keluar dari aplikasi</td>
                                <td>2 detik yang lalu</td>
                            </tr>
                            <tr>
                                <td>Pegawai Pendidik</td>
                                <td>Login ke aplikasi</td>
                                <td>3 detik yang lalu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endif
