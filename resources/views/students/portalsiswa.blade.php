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
                    <p>NIPD: {{ $student->id_student }} | Kelas: {{ $student->currentClass ? $student->currentClass->class_name : '-' }}</p>
                </div>
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
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rapor-tab" data-bs-toggle="tab" data-bs-target="#rapor" type="button" role="tab">
                        <i class="fas fa-file-alt"></i> Rapor
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
                            @if($student->qr_code)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $student->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                    <p class="small text-muted mt-1">ID: {{ $student->id_student }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">NIPD</div>
                                    <div>{{ $student->id_student }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Nama Lengkap</div>
                                    <div>{{ $student->fullname }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Tempat/Tgl Lahir</div>
                                    <div>{{ $student->birth_place }}, {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</div>
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
                                    <div>{{ $student->currentClass ? $student->currentClass->class_name : '-' }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Tahun Ajaran</div>
                                    <div>{{ $activeAcademicYear->year_name ?? '2024/2025' }}</div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="fw-bold">Semester</div>
                                    <div>{{ $activeSemester->semester_name ?? 'Ganjil' }}</div>
                                </div>
                            </div>
                        </div>
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
                                <div class="stat-value text-success">{{ $attendanceStats['present_percentage'] }}%</div>
                                <div class="stat-label">Kehadiran</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-primary">{{ $attendanceStats['present'] }}</div>
                                <div class="stat-label">Hadir</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-warning">{{ $attendanceStats['sick'] }}</div>
                                <div class="stat-label">Sakit</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-info">{{ $attendanceStats['permit'] }}</div>
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
                                    @foreach($months as $month)
                                        <option value="{{ $month['number'] }}" {{ $currentMonth == $month['number'] ? 'selected' : '' }}>
                                            {{ $month['name'] }} {{ $month['year'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Calendar -->
                    <h5 class="mb-2">{{ $currentMonthName }} {{ $currentYear }}</h5>
                    <div class="attendance-calendar">
                        <div class="calendar-header">Min</div>
                        <div class="calendar-header">Sen</div>
                        <div class="calendar-header">Sel</div>
                        <div class="calendar-header">Rab</div>
                        <div class="calendar-header">Kam</div>
                        <div class="calendar-header">Jum</div>
                        <div class="calendar-header">Sab</div>

                        @foreach($calendarDays as $day)
                            @if($day['empty'])
                                <div class="calendar-day empty"></div>
                            @else
                                <div class="calendar-day
                                    {{ $day['weekend'] ? 'weekend' : '' }}
                                    {{ $day['today'] ? 'today' : '' }}
                                    {{ $day['status'] ? strtolower($day['status']) : '' }}">
                                    {{ $day['day'] }}
                                </div>
                            @endif
                        @endforeach
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
                                @foreach($recentAttendances as $attendance)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge{{ strtolower($attendance->status->status_name) }}">
                                                {{ $attendance->status->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->check_in_time)
                                                {{ $attendance->check_in_time }}
                                                @if($attendance->check_out_time)
                                                    - {{ $attendance->check_out_time }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
                                <div class="stat-value">Rp {{ number_format($paymentStats['total_amount'], 0, ',', '.') }}</div>
                                <div class="stat-label">Total Tagihan</div>
                                <div class="mt-2">
                                    <span class="badge badge-{{ $paymentStats['total_paid'] >= $paymentStats['total_amount'] ? 'lunas' : 'belum-lunas' }}">
                                        {{ $paymentStats['total_paid'] >= $paymentStats['total_amount'] ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value">Rp {{ number_format($paymentStats['monthly_amount'], 0, ',', '.') }}</div>
                                <div class="stat-label">Tagihan Bulan Ini</div>
                                <div class="mt-2">
                                    <span class="badge badge-{{ $paymentStats['current_month_paid'] ? 'lunas' : 'belum-lunas' }}">
                                        {{ $paymentStats['current_month_paid'] ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value">Rp {{ number_format($paymentStats['monthly_amount'], 0, ',', '.') }}</div>
                                <div class="stat-label">Tagihan Bulan Depan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <h5 class="mb-2">Riwayat Pembayaran</h5>
                    <div class="row">
                        @foreach($payments as $payment)
                            <div class="col-md-4 col-12 mb-3">
                                <div class="payment-item">
                                    <div class="payment-month">{{ $payment['month_name'] }} {{ $payment['year'] }}</div>
                                    <div class="payment-amount">Rp {{ number_format($payment['amount'], 0, ',', '.') }}</div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="payment-date">{{ \Carbon\Carbon::parse($payment['last_update'])->format('d M Y') }}</div>
                                        <span class="badge badge-lunas">Lunas</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Payment Info -->
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-info-circle me-1"></i>Informasi Pembayaran</h6>
                        <p class="mb-1">Transfer ke ###</p>
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
                                <div class="stat-value text-primary">{{ $bookStats['total'] }}</div>
                                <div class="stat-label">Total</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-info">{{ $bookStats['borrowed'] }}</div>
                                <div class="stat-label">Dipinjam</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-value text-success">{{ $bookStats['returned'] }}</div>
                                <div class="stat-label">Dikembalikan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Borrowed Books -->
                    <h5 class="mb-2">Buku Dipinjam</h5>
                    <div class="row">
                        @foreach($bookLoans as $loan)
                            <div class="col-12 mb-3">
                                <div class="book-item">
                                    <div class="book-cover">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div class="book-details">
                                        <div class="book-title">{{ $loan->book->title }}</div>
                                        <div class="book-author">{{ $loan->book->author }}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small>Pinjam: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</small>
                                            </div>
                                            <span class="badge badge-{{ $loan->status == 'Dikembalikan' ? 'success' : 'dipinjam' }}">
                                                {{ $loan->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Return Info -->
                    <div class="alert alert-warning mt-3">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Informasi Pengembalian</h6>
                        <p class="mb-0">Kembalikan semua buku pada akhir tahun ajaran.</p>
                    </div>
                </div>
            </div>

        <!-- Rapor Tab -->
<div class="tab-pane fade" id="rapor" role="tabpanel">
    <div class="content-section">
        <h3 class="section-title">Rapor Akademik</h3>

        <!-- Rapor List -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Rapor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($rapor)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($rapor->report_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($rapor->file_path)
                                    <!-- Button to view document -->
                                    <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#viewRaporModal">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <!-- Button to download -->
                                    <a href="{{ route('rapor.download', $rapor->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">Data rapor belum tersedia.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Rapor Upload Info -->
        <div class="alert alert-info mt-3">
            <h6><i class="fas fa-info-circle me-1"></i>Informasi Rapor</h6>
            <p class="mb-0">Rapor akan diupload oleh wali kelas setelah ujian semester selesai.</p>
        </div>
    </div>
</div>

<!-- Modal for viewing rapor document -->
@if($rapor && $rapor->file_path)
<div class="modal fade" id="viewRaporModal" tabindex="-1" aria-labelledby="viewRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-alt me-2 fs-4"></i>
                    <h5 class="modal-title mb-0" id="viewRaporModalLabel">
                        Rapor {{ $student->fullname }} - {{ $rapor->academicYear->year_name }} ({{ $rapor->semester->semester_name }})
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="document-viewer">
                    @if(pathinfo($rapor->file_path, PATHINFO_EXTENSION) === 'pdf')
                        <div class="pdf-container">
                            <iframe src="{{ asset('storage/' . $rapor->file_path) }}" style="width:100%; height:70vh;" frameborder="0"></iframe>
                        </div>
                    @else
                        <div class="image-container text-center p-3">
                            <img src="{{ asset('storage/' . $rapor->file_path) }}" class="img-fluid rounded shadow-sm" alt="Rapor {{ $student->fullname }}" style="max-height: 70vh;">
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="document-info small text-muted me-auto">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ strtoupper(pathinfo($rapor->file_path, PATHINFO_EXTENSION)) }} •
                    {{ \Carbon\Carbon::parse($rapor->report_date)->format('d F Y') }}
                </div>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <a href="{{ route('rapor.download', $rapor->id) }}" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Unduh
                </a>
            </div>
        </div>
    </div>
</div>
@endif
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
            const month = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('month', month);
            window.location.href = url.toString();
        });
    });
    </script>
</body>
</html>
