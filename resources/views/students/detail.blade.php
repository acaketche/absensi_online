<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            background-color: #4266B9;
            color: white;
            position: fixed;
            height: 100vh;
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

        .main-content {
            margin-left: 250px;
            padding: 30px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .profile-header {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .tab-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .nav-tabs {
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            color: #666;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            border-radius: 5px;
        }

        .nav-tabs .nav-link.active {
            background: #4266B9;
            color: white;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 500;
        }

        .table th {
            font-weight: 500;
            color: #666;
        }

        .attendance-day {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .attendance-day h4 {
            margin-bottom: 15px;
            color: #4266B9;
        }

        .attendance-table {
            width: 100%;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .attendance-table th {
            background-color: #e9ecef;
            font-weight: 600;
        }

        .badge-present {
            background-color: #28a745;
        }

        .badge-absent {
            background-color: #dc3545;
        }

        .badge-late {
            background-color: #ffc107;
        }

        .badge-excused {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">E</div>
            <div class="logo-text">SCHOOL</div>
        </div>
        <nav>
            <a href="#" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-user-graduate"></i>
                <span>Data Siswa</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Data Guru</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Absensi</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-book"></i>
                <span>Mata Pelajaran</span>
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="profile-header">
            <div class="row">
                <div class="col-md-2 text-center">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-pfPNotdSrTvBmfJ23h8xQHdvt3OWea.png" alt="Student Photo" class="profile-image">
                </div>
                <div class="col-md-10">
                    <h2>Putri Adellia</h2>
                    <p class="text-muted mb-2">NIS: 211013012</p>
                    <p class="text-muted">Kelas: 10 IPA</p>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="studentTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#personal">Data Pribadi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#attendance">Kehadiran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#achievement">Nilai</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#books">Peminjaman Buku</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#payments">Pembayaran</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Personal Information Tab -->
            <div class="tab-pane fade show active" id="personal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">Putri Adellia</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Tempat Lahir</div>
                            <div class="info-value">Medan</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Tanggal Lahir</div>
                            <div class="info-value">10 April 2003</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value">Perempuan</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">No. Telepon Orang Tua</div>
                            <div class="info-value">081234567891</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Kelas</div>
                            <div class="info-value">10 IPA</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Tab -->
            <div class="tab-pane fade" id="attendance">
                <div class="attendance-day">
                    <h4>Senin, 24 Februari 2025</h4>
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>07:30 - 09:00</td>
                                <td>Matematika</td>
                                <td><span class="badge badge-present">Hadir</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>09:15 - 10:45</td>
                                <td>Bahasa Indonesia</td>
                                <td><span class="badge badge-present">Hadir</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>11:00 - 12:30</td>
                                <td>Fisika</td>
                                <td><span class="badge badge-late">Terlambat</span></td>
                                <td>Terlambat 5 menit</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="attendance-day">
                    <h4>Selasa, 25 Februari 2025</h4>
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>07:30 - 09:00</td>
                                <td>Kimia</td>
                                <td><span class="badge badge-present">Hadir</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>09:15 - 10:45</td>
                                <td>Bahasa Inggris</td>
                                <td><span class="badge badge-present">Hadir</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>11:00 - 12:30</td>
                                <td>Biologi</td>
                                <td><span class="badge badge-absent">Tidak Hadir</span></td>
                                <td>Sakit (dengan surat keterangan)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Achievement Tab -->
            <div class="tab-pane fade" id="achievement">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Semester</th>
                            <th>Nilai</th>
                            <th>Peringkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Matematika</td>
                            <td>Ganjil</td>
                            <td>85.5</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Bahasa Inggris</td>
                            <td>Ganjil</td>
                            <td>90.0</td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Book Loans Tab -->
            <div class="tab-pane fade" id="books">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>To Kill a Mockingbird</td>
                            <td>15 Feb 2025</td>
                            <td>28 Feb 2025</td>
                            <td><span class="badge bg-success">Dikembalikan</span></td>
                        </tr>
                        <tr>
                            <td>1984</td>
                            <td>16 Feb 2025</td>
                            <td>-</td>
                            <td><span class="badge bg-warning">Dipinjam</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payments Tab -->
            <div class="tab-pane fade" id="payments">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah Bayar</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01 Feb 2025</td>
                            <td>Rp 500.000</td>
                            <td>Rp 500.000</td>
                            <td><span class="badge bg-success">Lunas</span></td>
                        </tr>
                        <tr>
                            <td>01 Jan 2025</td>
                            <td>Rp 500.000</td>
                            <td>Rp 500.000</td>
                            <td><span class="badge bg-success">Lunas</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
