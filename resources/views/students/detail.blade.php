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
    <style>
        :root {
            --primary-color: #4266B9;
            --secondary-color: #3A5A9A;
            --accent-color: #4CC9F0;
            --success-color: #28A745;
            --warning-color: #FFC107;
            --danger-color: #DC3545;
            --info-color: #17A2B8;
            --light-color: #F8F9FA;
            --dark-color: #343A40;
            --text-color: #2B2D42;
            --text-muted: #6C757D;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header Section */
        .student-header {
            background-color: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .student-info h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .student-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        /* Navigation Tabs */
        .nav-tabs-container {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        .nav-tabs-container::-webkit-scrollbar {
            display: none;
        }

        .student-tabs {
            background-color: white;
            border-radius: 10px;
            padding: 0.5rem;
            display: inline-flex;
            min-width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .student-tabs .nav-link {
            color: var(--text-muted);
            border-radius: 5px;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .student-tabs .nav-link i {
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .student-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Content Sections */
        .content-section {
            background-color: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #EEE;
        }

        /* Stats Cards */
        .stat-card {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Attendance Calendar */
        .attendance-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .calendar-header {
            text-align: center;
            font-weight: 600;
            padding: 0.5rem;
            font-size: 0.7rem;
            background-color: #F0F0F0;
            border-radius: 5px;
        }

        .calendar-day {
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            background-color: #F8F9FA;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .calendar-day.empty {
            background-color: transparent;
        }

        .calendar-day.weekend {
            background-color: #F0F0F0;
            color: #999;
        }

        .calendar-day.hadir {
            background-color: #D4EDDA;
            color: #155724;
        }

        .calendar-day.sakit {
            background-color: #FFF3CD;
            color: #856404;
        }

        .calendar-day.izin {
            background-color: #D1ECF1;
            color: #0C5460;
        }

        .calendar-day.alpa {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .calendar-day.today {
            border: 2px solid var(--primary-color);
            font-weight: bold;
        }

        /* Badges */
        .badge {
            padding: 0.35rem 0.6rem;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-hadir {
            background-color: #D4EDDA;
            color: #155724;
        }

        .badge-sakit {
            background-color: #FFF3CD;
            color: #856404;
        }

        .badge-izin {
            background-color: #D1ECF1;
            color: #0C5460;
        }

        .badge-alpa {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .badge-lunas {
            background-color: #D4EDDA;
            color: #155724;
        }

        .badge-jatuh-tempo {
            background-color: #FFF3CD;
            color: #856404;
        }

        .badge-belum-lunas {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .badge-dipinjam {
            background-color: #D1ECF1;
            color: #0C5460;
        }

        /* Payment Items */
        .payment-item {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .payment-month {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .payment-amount {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .payment-date {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Book Items */
        .book-item {
            display: flex;
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .book-cover {
            width: 50px;
            height: 70px;
            background-color: #E9ECEF;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .book-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .book-author {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        /* Buttons */
        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            border-radius: 5px;
        }

        /* Responsive Adjustments */
        @media (min-width: 768px) {
            .student-info h2 {
                font-size: 1.5rem;
            }

            .student-info p {
                font-size: 0.9rem;
            }

            .student-tabs .nav-link {
                font-size: 0.9rem;
                padding: 0.75rem 1.25rem;
                margin: 0 0.5rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .stat-value {
                font-size: 1.8rem;
            }

            .stat-label {
                font-size: 0.9rem;
            }

            .calendar-day {
                height: 3rem;
                font-size: 0.9rem;
            }

            .payment-month {
                font-size: 1rem;
            }

            .payment-amount {
                font-size: 1.25rem;
            }

            .book-title {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .student-avatar {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .btn-action {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .attendance-calendar {
                gap: 0.1rem;
            }

            .calendar-day {
                height: 2rem;
                font-size: 0.7rem;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                font-size: 12pt;
            }

            .student-header, .content-section {
                box-shadow: none;
                border: 1px solid #DDD;
                page-break-inside: avoid;
            }

            .btn-action, .nav-tabs-container {
                display: none;
            }

            .tab-pane {
                display: block !important;
                opacity: 1 !important;
                margin-bottom: 20pt;
            }
        }
    </style>
</head>
<body>
    <div class="container py-3">
        <!-- Header with Student Info -->
        <div class="student-header">
            <div class="d-flex align-items-center">
                <div class="student-avatar">
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->fullname }}" class="w-100 h-100 rounded-circle">
                    @else
                        {{ substr($student->fullname, 0, 1) }}
                    @endif
                </div>
                <div class="student-info">
                    <h2>{{ $student->fullname }}</h2>
                    <p>NIS: {{ $student->id_student }} | Kelas: {{ $student->class ? $student->class->class_name : '-' }}</p>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-2">
                <button class="btn btn-outline-secondary btn-action" id="printBtn">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-primary btn-action">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="nav-tabs-container">
            <ul class="nav student-tabs" id="studentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                        <i class="fas fa-user"></i> Profil
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">
                        <i class="fas fa-calendar-check"></i> Absensi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                        <i class="fas fa-money-bill-wave"></i> SPP
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="books-tab" data-bs-toggle="tab" data-bs-target="#books" type="button" role="tab">
                        <i class="fas fa-book"></i> Buku
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="studentTabsContent">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="content-section">
                    <h3 class="section-title">Informasi Siswa</h3>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center mb-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->fullname }}" class="img-fluid rounded" style="max-height: 200px;">
                                @else
                                    <div style="width: 150px; height: 150px; background-color: var(--primary-color); color: white; font-size: 60px; display: flex; align-items: center; justify-content: center; border-radius: 10px; margin: 0 auto;">
                                        {{ substr($student->fullname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">NIS</div>
                                    <div>{{ $student->id_student }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Nama Lengkap</div>
                                    <div>{{ $student->fullname }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Tempat/Tgl Lahir</div>
                                    <div>{{ $student->birth_place }}, {{ $student->birth_date->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Jenis Kelamin</div>
                                    <div>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">No. Telepon Orang Tua</div>
                                    <div>{{ $student->parent_phonecell }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Kelas</div>
                                    <div>{{ $student->class ? $student->class->class_name : '-' }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Tahun Ajaran</div>
                                    <div>{{ $student->academicYear ? $student->academicYear->year_name : '2024/2025' }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Semester</div>
                                    <div>{{ $student->semester ? $student->semester->semester_name : 'Ganjil' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('students.edit', $student->id_student) }}" class="btn btn-primary btn-action">
                            <i class="fas fa-edit me-1"></i> Edit Data
                        </a>
                    </div>
                </div>
            </div>

            <!-- Attendance Tab -->
            <div class="tab-pane fade" id="attendance" role="tabpanel">
                <div class="content-section">
                    <h3 class="section-title">Rekap Absensi</h3>

                    <!-- Attendance Stats -->
                    <div class="row">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-success">95%</div>
                                <div class="stat-label">Kehadiran</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-primary">57</div>
                                <div class="stat-label">Hadir</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-warning">2</div>
                                <div class="stat-label">Sakit</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-info">1</div>
                                <div class="stat-label">Izin</div>
                            </div>
                        </div>
                    </div>

                    <!-- Month Selector -->
                    <div class="row mb-3">
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
                    <h5 class="mb-2">Maret 2025</h5>
                    <div class="attendance-calendar">
                        <div class="calendar-header">Min</div>
                        <div class="calendar-header">Sen</div>
                        <div class="calendar-header">Sel</div>
                        <div class="calendar-header">Rab</div>
                        <div class="calendar-header">Kam</div>
                        <div class="calendar-header">Jum</div>
                        <div class="calendar-header">Sab</div>

                        <!-- Calendar days would be generated dynamically in a real app -->
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day hadir">1</div>
                        <div class="calendar-day weekend">2</div>

                        <!-- More days... -->
                    </div>

                    <!-- Legend -->
                    <div class="d-flex flex-wrap gap-3 mb-3">
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

                    <!-- Recent Attendance -->
                    <h5 class="mb-2">Riwayat Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>28/03/2025</td>
                                    <td><span class="badge badge-hadir">Hadir</span></td>
                                    <td>07:25 - 15:30</td>
                                </tr>
                                <tr>
                                    <td>27/03/2025</td>
                                    <td><span class="badge badge-hadir">Hadir</span></td>
                                    <td>07:30 - 15:30</td>
                                </tr>
                                <tr>
                                    <td>26/03/2025</td>
                                    <td><span class="badge badge-sakit">Sakit</span></td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Tab -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="content-section">
                    <h3 class="section-title">Status Pembayaran SPP</h3>

                    <!-- Payment Summary -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value">Rp 7.2jt</div>
                                <div class="stat-label">Total Tagihan</div>
                                <div class="mt-2">
                                    <span class="badge badge-lunas">Lunas 6 Bulan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value">Rp 600rb</div>
                                <div class="stat-label">Tagihan Bulan Ini</div>
                                <div class="mt-2">
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value">Rp 600rb</div>
                                <div class="stat-label">Tagihan Bulan Depan</div>
                                <div class="mt-2">
                                    <span class="badge badge-jatuh-tempo">Jatuh Tempo 10 Apr</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <h5 class="mb-2">Riwayat Pembayaran</h5>
                    <div class="row">
                        <div class="col-md-4 col-12 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Maret 2025</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">15 Mar 2025</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Februari 2025</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">10 Feb 2025</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Januari 2025</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">12 Jan 2025</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-info-circle me-1"></i>Informasi Pembayaran</h6>
                        <p class="mb-1">Transfer ke BNI 0123456789 a.n. SMA Negeri 1</p>
                        <p class="mb-0">Loket sekolah: Senin-Jumat, 08.00-15.00</p>
                    </div>
                </div>
            </div>

            <!-- Books Tab -->
            <div class="tab-pane fade" id="books" role="tabpanel">
                <div class="content-section">
                    <h3 class="section-title">Buku Paket</h3>

                    <!-- Books Summary -->
                    <div class="row">
                        <div class="col-4 col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-primary">8</div>
                                <div class="stat-label">Total</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-info">8</div>
                                <div class="stat-label">Dipinjam</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-success">0</div>
                                <div class="stat-label">Dikembalikan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Borrowed Books -->
                    <h5 class="mb-2">Buku Dipinjam</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="book-item">
                                <div class="book-cover">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div class="book-details">
                                    <div class="book-title">Matematika Kelas X</div>
                                    <div class="book-author">Prof. Siti Aminah</div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small>Pinjam: 15/07/2024</small>
                                        </div>
                                        <span class="badge badge-dipinjam">Dipinjam</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="book-item">
                                <div class="book-cover">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div class="book-details">
                                    <div class="book-title">Fisika Kelas X</div>
                                    <div class="book-author">Dr. Budi Santoso</div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small>Pinjam: 15/07/2024</small>
                                        </div>
                                        <span class="badge badge-dipinjam">Dipinjam</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Info -->
                    <div class="alert alert-warning mt-3">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Informasi Pengembalian</h6>
                        <p class="mb-0">Kembalikan semua buku pada akhir tahun ajaran.</p>
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
                // In a real app, this would load the attendance data for the selected month
                console.log('Selected month:', this.value);
            });
        });
    </script>
</body>
</html>
