<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Portal Siswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/stylesiswa.css') }}" rel="stylesheet">
    <style>
        /* Estilos base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header y perfil del estudiante */
        .header {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .student-info {
            display: flex;
            align-items: center;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            background-color: #4266B9;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin-right: 15px;
        }

        .student-details h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .student-details p {
            margin: 5px 0 0;
            color: #666;
        }

        /* Navegación por pestañas */
        .nav-pills {
            background-color: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .nav-pills .nav-link {
            color: #555;
            border-radius: 5px;
            padding: 10px 15px;
            margin: 0 5px;
            display: flex;
            align-items: center;
        }

        .nav-pills .nav-link i {
            margin-right: 8px;
        }

        .nav-pills .nav-link.active {
            background-color: #4266B9;
            color: white;
        }

        /* Contenido de las pestañas */
        .content-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        /* Tarjetas de estadísticas */
        .stat-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Calendario de asistencia */
        .attendance-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 20px;
        }

        .calendar-header {
            text-align: center;
            font-weight: 600;
            padding: 5px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .calendar-day {
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            background-color: #f8f9fa;
            font-weight: 500;
        }

        .calendar-day.empty {
            background-color: transparent;
        }

        .calendar-day.weekend {
            background-color: #f0f0f0;
            color: #999;
        }

        .calendar-day.hadir {
            background-color: #d4edda;
            color: #155724;
        }

        .calendar-day.sakit {
            background-color: #fff3cd;
            color: #856404;
        }

        .calendar-day.izin {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .calendar-day.alpa {
            background-color: #f8d7da;
            color: #721c24;
        }

        .calendar-day.today {
            border: 2px solid #4266B9;
            font-weight: bold;
        }

        /* Badges para estados */
        .badge {
            padding: 6px 10px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-hadir {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-sakit {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-izin {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .badge-alpa {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-lunas {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-jatuh-tempo {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-belum-lunas {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-dipinjam {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Elementos de pago */
        .payment-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .payment-month {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .payment-amount {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .payment-date {
            font-size: 0.85rem;
            color: #666;
        }

        /* Elementos de libro */
        .book-item {
            display: flex;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .book-cover {
            width: 60px;
            height: 80px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            margin-right: 15px;
        }

        .book-details {
            flex: 1;
        }

        .book-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .book-author {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        /* Tarjetas de documento */
        .document-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .document-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .document-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .document-date {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .student-avatar {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .student-details h2 {
                font-size: 1.2rem;
            }

            .student-details p {
                font-size: 0.85rem;
            }

            .nav-pills .nav-link {
                padding: 8px 10px;
                margin: 0 2px;
                font-size: 0.9rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .calendar-day {
                height: 35px;
                font-size: 0.85rem;
            }
        }

        /* Estilos para impresión */
        @media print {
            body {
                background-color: white;
            }

            .main-container {
                max-width: 100%;
                padding: 0;
            }

            .nav-pills, #printBtn, #logoutBtn {
                display: none;
            }

            .tab-pane {
                display: block !important;
                opacity: 1 !important;
                margin-bottom: 30px;
            }

            .content-section {
                box-shadow: none;
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header with Student Info -->
        <div class="header">
            <div class="row">
                <div class="col-md-8">
                    <div class="student-info">
                        <div class="student-avatar">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->fullname }}" class="w-100 h-100 rounded-circle">
                            @else
                                {{ substr($student->fullname, 0, 1) }}
                            @endif
                        </div>
                        <div class="student-details">
                            <h2>{{ $student->fullname }}</h2>
                            <p>NIS: {{ $student->id_student }} | Kelas: {{ $student->class ? $student->class->class_name : 'Tidak Ada Kelas' }} |
                               Tahun Ajaran: {{ $student->academicYear ? $student->academicYear->year_name : '2024/2025' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                    <button class="btn btn-outline-secondary me-2" id="printBtn">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-3" id="studentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                    <i class="fas fa-user"></i> Profil Siswa
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="attendance-tab" data-bs-toggle="pill" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="false">
                    <i class="fas fa-calendar-check"></i> Absensi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false">
                    <i class="fas fa-money-bill-wave"></i> Pembayaran SPP
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="books-tab" data-bs-toggle="pill" data-bs-target="#books" type="button" role="tab" aria-controls="books" aria-selected="false">
                    <i class="fas fa-book"></i> Buku Paket
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="studentTabsContent">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="content-section">
                    <h3 class="section-title">Informasi Siswa</h3>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="text-center mb-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->fullname }}" class="img-fluid rounded" style="max-height: 200px;">
                                @else
                                    <div style="width: 150px; height: 150px; background-color: #4266B9; color: white; font-size: 60px; display: flex; align-items: center; justify-content: center; border-radius: 10px; margin: 0 auto;">
                                        {{ substr($student->fullname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">NIS</div>
                                    <div>{{ $student->id_student }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Nama Lengkap</div>
                                    <div>{{ $student->fullname }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Tempat Lahir</div>
                                    <div>{{ $student->birth_place }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Tanggal Lahir</div>
                                    <div>{{ $student->birth_date->format('d F Y') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Jenis Kelamin</div>
                                    <div>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">No. Telepon Orang Tua</div>
                                    <div>{{ $student->parent_phonecell }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Kelas</div>
                                    <div>{{ $student->class ? $student->class->class_name : 'Tidak Ada Kelas' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Tahun Ajaran</div>
                                    <div>{{ $student->academicYear ? $student->academicYear->year_name : '2024/2025' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="fw-bold">Semester</div>
                                    <div>{{ $student->semester ? $student->semester->semester_name : 'Semester Ganjil' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('students.edit', $student->id_student) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Data Siswa
                        </a>
                    </div>
                </div>
            </div>

            <!-- Attendance Tab -->
            <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                <div class="content-section">
                    <h3 class="section-title">Rekap Absensi</h3>

                    <!-- Attendance Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-value text-success">95%</div>
                                <div class="stat-label">Kehadiran</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-value text-primary">57</div>
                                <div class="stat-label">Hadir</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-value text-warning">2</div>
                                <div class="stat-label">Sakit</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-value text-info">1</div>
                                <div class="stat-label">Izin</div>
                            </div>
                        </div>
                    </div>

                    <!-- Month Selector -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <select class="form-select" id="attendanceMonth">
                                    <option value="1">Januari 2025</option>
                                    <option value="2">Februari 2025</option>
                                    <option value="3" selected>Maret 2025</option>
                                    <option value="4">April 2025</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Calendar -->
                    <h5 class="mb-3">Maret 2025</h5>
                    <div class="attendance-calendar">
                        <div class="calendar-header">Min</div>
                        <div class="calendar-header">Sen</div>
                        <div class="calendar-header">Sel</div>
                        <div class="calendar-header">Rab</div>
                        <div class="calendar-header">Kam</div>
                        <div class="calendar-header">Jum</div>
                        <div class="calendar-header">Sab</div>

                        <!-- Week 1 -->
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day hadir">1</div>
                        <div class="calendar-day weekend">2</div>

                        <!-- Week 2 -->
                        <div class="calendar-day weekend">3</div>
                        <div class="calendar-day hadir">4</div>
                        <div class="calendar-day hadir">5</div>
                        <div class="calendar-day hadir">6</div>
                        <div class="calendar-day hadir">7</div>
                        <div class="calendar-day hadir">8</div>
                        <div class="calendar-day weekend">9</div>

                        <!-- Week 3 -->
                        <div class="calendar-day weekend">10</div>
                        <div class="calendar-day hadir">11</div>
                        <div class="calendar-day hadir">12</div>
                        <div class="calendar-day hadir">13</div>
                        <div class="calendar-day hadir">14</div>
                        <div class="calendar-day hadir">15</div>
                        <div class="calendar-day weekend">16</div>

                        <!-- Week 4 -->
                        <div class="calendar-day weekend">17</div>
                        <div class="calendar-day hadir">18</div>
                        <div class="calendar-day izin">19</div>
                        <div class="calendar-day hadir">20</div>
                        <div class="calendar-day hadir">21</div>
                        <div class="calendar-day hadir">22</div>
                        <div class="calendar-day weekend">23</div>

                        <!-- Week 5 -->
                        <div class="calendar-day weekend">24</div>
                        <div class="calendar-day sakit">25</div>
                        <div class="calendar-day sakit">26</div>
                        <div class="calendar-day hadir">27</div>
                        <div class="calendar-day today hadir">28</div>
                        <div class="calendar-day">29</div>
                        <div class="calendar-day weekend">30</div>

                        <!-- Week 6 -->
                        <div class="calendar-day weekend">31</div>
                    </div>

                    <!-- Legend -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-hadir me-2">H</span>
                                    <span>Hadir</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-sakit me-2">S</span>
                                    <span>Sakit</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-izin me-2">I</span>
                                    <span>Izin</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-alpa me-2">A</span>
                                    <span>Alpa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Attendance -->
                    <h5 class="mb-3">Riwayat Absensi Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Hari</th>
                                    <th>Status</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>28 Maret 2025</td>
                                    <td>Jumat</td>
                                    <td><span class="badge badge-hadir">Hadir</span></td>
                                    <td>07:25</td>
                                    <td>15:30</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>27 Maret 2025</td>
                                    <td>Kamis</td>
                                    <td><span class="badge badge-hadir">Hadir</span></td>
                                    <td>07:30</td>
                                    <td>15:30</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>26 Maret 2025</td>
                                    <td>Rabu</td>
                                    <td><span class="badge badge-hadir">Hadir</span></td>
                                    <td>07:28</td>
                                    <td>15:30</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>25 Maret 2025</td>
                                    <td>Selasa</td>
                                    <td><span class="badge badge-sakit">Sakit</span></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>Demam (Surat dokter terlampir)</td>
                                </tr>
                                <tr>
                                    <td>24 Maret 2025</td>
                                    <td>Senin</td>
                                    <td><span class="badge badge-sakit">Sakit</span></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>Demam (Surat dokter terlampir)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Tab -->
            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                <div class="content-section">
                    <h3 class="section-title">Status Pembayaran SPP</h3>

                    <!-- Payment Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">Rp 7.200.000</div>
                                <div class="stat-label">Total Tagihan Tahunan</div>
                                <div class="mt-2">
                                    <span class="badge badge-lunas">Lunas 6 Bulan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">Rp 600.000</div>
                                <div class="stat-label">Tagihan Bulan Ini (Maret)</div>
                                <div class="mt-2">
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">Rp 600.000</div>
                                <div class="stat-label">Tagihan Bulan Depan (April)</div>
                                <div class="mt-2">
                                    <span class="badge badge-jatuh-tempo">Jatuh Tempo 10 April</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <h5 class="mb-3">Riwayat Pembayaran</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Maret 2025</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">Dibayar: 15 Maret 2025</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Februari 2025</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">Dibayar: 10 Februari 2025</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Januari 2025</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">Dibayar: 12 Januari 2025</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Info -->
                    <div class="alert alert-info mt-3">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Pembayaran</h5>
                        <p>Pembayaran SPP dapat dilakukan melalui:</p>
                        <ul>
                            <li>Transfer Bank ke rekening BNI 0123456789 a.n. SMA Negeri 1</li>
                            <li>Pembayaran QRIS melalui e-wallet atau mobile banking</li>
                            <li>Pembayaran tunai di loket pembayaran sekolah (Senin-Jumat, 08.00-15.00)</li>
                        </ul>
                        <p class="mb-0">Untuk informasi lebih lanjut, silakan hubungi Bagian Keuangan di (021) 1234567.</p>
                    </div>
                </div>
            </div>

            <!-- Books Tab -->
            <div class="tab-pane fade" id="books" role="tabpanel" aria-labelledby="books-tab">
                <div class="content-section">
                    <h3 class="section-title">Buku Paket yang Dipinjam</h3>

                    <!-- Books Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value text-primary">8</div>
                                <div class="stat-label">Total Buku Paket</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value text-info">8</div>
                                <div class="stat-label">Buku Dipinjam</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value text-success">0</div>
                                <div class="stat-label">Buku Dikembalikan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Currently Borrowed Books -->
                    <h5 class="mb-3">Buku yang Sedang Dipinjam</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="book-item">
                                <div class="book-cover">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                </div>
                                <div class="book-details">
                                    <div class="book-title">Matematika Kelas X</div>
                                    <div class="book-author">Penulis: Prof. Siti Aminah</div>
                                    <div class="book-status">
                                        <span class="badge badge-dipinjam">Dipinjam</span>
                                    </div>
                                    <div class="mt-2">
                                        <small>Tanggal Pinjam: 15 Juli 2024</small><br>
                                        <small>Tanggal Kembali: Akhir Tahun Ajaran</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="book-item">
                                <div class="book-cover">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                </div>
                                <div class="book-details">
                                    <div class="book-title">Fisika Kelas X</div>
                                    <div class="book-author">Penulis: Dr. Budi Santoso</div>
                                    <div class="book-status">
                                        <span class="badge badge-dipinjam">Dipinjam</span>
                                    </div>
                                    <div class="mt-2">
                                        <small>Tanggal Pinjam: 15 Juli 2024</small><br>
                                        <small>Tanggal Kembali: Akhir Tahun Ajaran</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Book Return Info -->
                    <div class="alert alert-warning mt-3">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Informasi Pengembalian</h5>
                        <p>Semua buku paket harus dikembalikan pada akhir tahun ajaran dalam kondisi baik. Buku yang rusak atau hilang akan dikenakan denda sesuai dengan ketentuan sekolah.</p>
                        <p class="mb-0">Untuk informasi lebih lanjut, silakan hubungi Perpustakaan di ext. 123.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Print button
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });

            // Attendance month selector
            document.getElementById('attendanceMonth').addEventListener('change', function() {
                alert('Menampilkan data absensi untuk bulan yang dipilih');
                // In a real application, this would load the attendance data for the selected month
            });
        });
    </script>
</body>
</html>
