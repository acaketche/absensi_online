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
</head>
<body>
    <div class="main-container">
        <!-- Header with Student Info -->
        <div class="header">
            <div class="row">
                <div class="col-md-8">
                    <div class="student-info">
                        <div class="student-avatar">A</div>
                        <div class="student-details">
                            <h2>Ahmad Rizky</h2>
                            <p>NIS: 2023001 | Kelas: 10 IPA 1 | Tahun Ajaran: 2024/2025</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                    <button class="btn btn-outline-secondary me-2" id="printBtn">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                    <button class="btn btn-outline-danger" id="logoutBtn">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-3" id="studentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="attendance-tab" data-bs-toggle="pill" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="true">
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
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="grades-tab" data-bs-toggle="pill" data-bs-target="#grades" type="button" role="tab" aria-controls="grades" aria-selected="false">
                    <i class="fas fa-graduation-cap"></i> Nilai Akademik
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="studentTabsContent">
            <!-- Attendance Tab -->
            <div class="tab-pane fade show active" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
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
                        <div class="col-md-4 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Desember 2024</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">Dibayar: 8 Desember 2024</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">November 2024</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">Dibayar: 5 November 2024</div>
                                    <span class="badge badge-lunas">Lunas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="payment-item">
                                <div class="payment-month">Oktober 2024</div>
                                <div class="payment-amount">Rp 600.000</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="payment-date">Dibayar: 10 Oktober 2024</div>
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
                        <div class="col-md-6 mb-3">
                            <div class="book-item">
                                <div class="book-cover">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                </div>
                                <div class="book-details">
                                    <div class="book-title">Kimia Kelas X</div>
                                    <div class="book-author">Penulis: Dr. Dewi Kartika</div>
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
                                    <div class="book-title">Biologi Kelas X</div>
                                    <div class="book-author">Penulis: Dr. Eko Prasetyo</div>
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
                                    <div class="book-title">Bahasa Indonesia Kelas X</div>
                                    <div class="book-author">Penulis: Dra. Ratna Sari</div>
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
                                    <div class="book-title">Bahasa Inggris Kelas X</div>
                                    <div class="book-author">Penulis: Dr. John Smith</div>
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
                                    <div class="book-title">Sejarah Indonesia Kelas X</div>
                                    <div class="book-author">Penulis: Prof. Agus Setiawan</div>
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
                                    <div class="book-title">Pendidikan Kewarganegaraan Kelas X</div>
                                    <div class="book-author">Penulis: Dr. Maya Indah</div>
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

            <!-- Grades Tab -->
            <div class="tab-pane fade" id="grades" role="tabpanel" aria-labelledby="grades-tab">
                <div class="content-section">
                    <h3 class="section-title">Nilai Akademik</h3>

                    <!-- Grades Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value text-success">85.7</div>
                                <div class="stat-label">Nilai Rata-rata</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value text-primary">3</div>
                                <div class="stat-label">Peringkat Kelas</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value text-info">10</div>
                                <div class="stat-label">Mata Pelajaran</div>
                            </div>
                        </div>
                    </div>

                    <!-- Semester Selector -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <select class="form-select" id="gradeSemester">
                                    <option value="1" selected>Semester Ganjil 2024/2025</option>
                                    <option value="2">Semester Genap 2024/2025</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Documents -->
                    <h5 class="mb-3">Dokumen Nilai</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="document-card text-center">
                                <div class="document-icon text-danger">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-title">Rapor Semester Ganjil</div>
                                <div class="document-date">Diunggah: 20 Desember 2024</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="document-card text-center">
                                <div class="document-icon text-primary">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-title">Hasil UTS Semester Ganjil</div>
                                <div class="document-date">Diunggah: 15 Oktober 2024</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="document-card text-center">
                                <div class="document-icon text-success">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-title">Hasil Ulangan Harian</div>
                                <div class="document-date">Diunggah: 30 September 2024</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="document-card text-center">
                                <div class="document-icon text-warning">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-title">Nilai Tugas Terstruktur</div>
                                <div class="document-date">Diunggah: 15 September 2024</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="document-card text-center">
                                <div class="document-icon text-info">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-title">Nilai Praktikum</div>
                                <div class="document-date">Diunggah: 5 November 2024</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Info -->
                    <div class="alert alert-info mt-3">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Nilai</h5>
                        <p>Dokumen nilai diupload secara berkala oleh guru mata pelajaran dan wali kelas. Nilai akhir semester akan diupload setelah rapat dewan guru.</p>
                        <p class="mb-0">Jika ada pertanyaan mengenai nilai, silakan hubungi wali kelas atau guru mata pelajaran yang bersangkutan.</p>
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

            // Logout button
            document.getElementById('logoutBtn').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    window.location.href = 'login.html';
                }
            });

            // Attendance month selector
            document.getElementById('attendanceMonth').addEventListener('change', function() {
                alert('Menampilkan data absensi untuk bulan yang dipilih');
                // In a real application, this would load the attendance data for the selected month
            });

            // Grade semester selector
            document.getElementById('gradeSemester').addEventListener('change', function() {
                alert('Menampilkan data nilai untuk semester yang dipilih');
                // In a real application, this would load the grade data for the selected semester
            });
        });
    </script>
</body>
</html>
