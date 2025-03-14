
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #4266B9;
            color: white;
            padding: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: #ff6b35;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Content Styles */
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

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
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
            background: rgba(75, 107, 251, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4B6BFB;
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
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">E</div>
                <div class="logo-text">SCHOOL</div>
            </div>
            <nav>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-home me-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-users me-2"></i>
                    <span class="nav-text">Data User</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-user-graduate me-2"></i>
                    <span class="nav-text">Data Siswa</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <span class="nav-text">Data Pegawai</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span class="nav-text">Absensi</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-user-check me-2"></i>
                        <span class="nav-text">Absensi Siswa</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-user-tie me-2"></i>
                        <span class="nav-text">Absensi Pegawai</span>
                    </a>
                </div>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-database me-2"></i>
                    <span class="nav-text">Master Data</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span class="nav-text">Tahun Ajaran</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-school me-2"></i>
                        <span class="nav-text">Kelas</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-book me-2"></i>
                        <span class="nav-text">Mata Pelajaran</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-calendar-day me-2"></i>
                        <span class="nav-text">Hari Libur</span>
                    </a>
                </div>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    <span class="nav-text">Data SPP</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-book-reader me-2"></i>
                    <span class="nav-text">Data Buku Paket</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-book-open me-2"></i>
                        <span class="nav-text">Peminjaman Buku Paket</span>
                    </a>
                </div>
            </nav>
        </div>
          <!-- Main Content -->
          <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="right-section" style="display: flex; gap: 20px; align-items: center;">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search here...">
                    </div>
                    <div class="profile">
                        <div class="profile-pic"></div>
                        <span>Admin</span>
                    </div>
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
                            March 2021
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
</body>
</html>
