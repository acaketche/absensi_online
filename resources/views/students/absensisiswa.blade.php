<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kehadiran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
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

        .filter-section {
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .student-details {
            display: flex;
            flex-direction: column;
        }

        .student-name {
            font-weight: bold;
            margin-bottom: 0;
        }

        .student-id {
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Efek transisi pada tab kehadiran */
        .attendance-tabs button {
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        /* Dropdown animasi */
        .dropdown-menu {
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.3s ease-in-out;
            display: block;
            visibility: hidden;
        }

        .dropdown-menu.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        /* Efek hover pada tab */
        .attendance-tabs button:hover {
            background-color: #365796 !important;
            color: white !important;
        }

        /* Efek klik */
        button:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
   @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Header dengan Profil Admin -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0"></h2>
            <div class="dropdown">
                <div class="admin-profile d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex flex-column text-end me-2">
                        <span class="admin-name">{{ Auth::guard('employee')->user()->fullname }}</span>
                        <small class="admin-role text-muted">
                            {{ Auth::guard('employee')->user()->role->role_name ?? 'Tidak ada role' }}
                        </small>
                    </div>
                    <div class="admin-avatar">
                        <img src="{{ Auth::guard('employee')->user()->photo ? asset('storage/' . Auth::guard('employee')->user()->photo) : 'https://via.placeholder.com/150' }}"
                             alt="Admin Profile" class="w-100 h-100 object-fit-cover">
                    </div>
                    <i class="fas fa-chevron-down ms-2 text-muted"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="fas fa-key"></i> Ubah Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form id="logout-form" action="{{ route('logout.employee') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Data Kehadiran Siswa</h2>
            <div class="d-flex align-items-center">
                <input type="text" placeholder="Cari nama siswa" class="form-control me-3" style="width: 200px;" id="searchInput">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                    <i class="fas fa-plus me-1"></i> Input Kehadiran
                </button>
            </div>
        </header>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <h5 class="mb-3">Filter Data Kehadiran</h5>
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="filterTanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-select" id="filterTanggal" name="tanggal">
                </div>
                <div class="col-md-3">
                    <label for="filterKelas" class="form-label">Kelas</label>
                    <select name="class_id" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->class_id }}"
                                {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterWaktu" class="form-label">Waktu</label>
                    <select class="form-select" id="filterWaktu" name="waktu">
                        <option value="">Semua Waktu</option>
                        <option value="pagi" selected>Pagi</option>
                        <option value="sore">Sore</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select class="form-select" id="filterStatus" name="status">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ strtolower($status->status_name) }}">{{ ucfirst($status->status_name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                    <button type="reset" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendance Tabs -->
        <ul class="nav nav-tabs mb-4 attendance-tabs" id="attendanceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="morning-tab" data-bs-toggle="tab" data-bs-target="#morning" type="button" role="tab" aria-controls="morning" aria-selected="true">
                    Absensi Pagi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="afternoon-tab" data-bs-toggle="tab" data-bs-target="#afternoon" type="button" role="tab" aria-controls="afternoon" aria-selected="false">
                    Absensi Sore
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Kehadiran Pagi -->
            <div class="tab-pane fade show active" id="morning" role="tabpanel" aria-labelledby="morning-tab">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h5 class="mb-0">Kehadiran Pagi (07:00 - 12:00)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $index => $attendance)
                                    @php
                                        $checkInTime = \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i');
                                        $isPagi = $checkInTime >= '07:00' && $checkInTime <= '12:00';
                                    @endphp
                                    @if ($isPagi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendance->student->fullname ?? '-' }}</td>
                                            <td>{{ $attendance->class->name ?? '-' }}</td>
                                            <td>{{ $checkInTime }}</td>
                                            <td><span class="badge bg-{{ $attendance->status->id == 1 ? 'success' : 'warning' }}">{{ $attendance->status->name ?? '-' }}</span></td>
                                            <td>
                                                @if ($attendance->document)
                                                    <a href="{{ asset('storage/' . $attendance->document) }}" target="_blank">Lihat Bukti</a>
                                                @else
                                                    Tidak Ada Bukti
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kehadiran Sore -->
            <div class="tab-pane fade" id="afternoon" role="tabpanel" aria-labelledby="afternoon-tab">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-secondary text-white text-center py-3">
                        <h5 class="mb-0">Kehadiran Sore (12:30 - 16:00)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $index => $attendance)
                                    @php
                                        $checkOutTime = \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i');
                                        $isSore = $checkOutTime >= '12:30' && $checkOutTime <= '16:00';
                                    @endphp
                                    @if ($isSore)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendance->student->fullname ?? '-' }}</td>
                                            <td>{{ $attendance->class->name ?? '-' }}</td>
                                            <td>{{ $checkOutTime }}</td>
                                            <td><span class="badge bg-{{ $attendance->status->id == 1 ? 'success' : 'warning' }}">{{ $attendance->status->name ?? '-' }}</span></td>
                                            <td>
                                                @if ($attendance->document)
                                                    <a href="{{ asset('storage/' . $attendance->document) }}" target="_blank">Lihat Bukti</a>
                                                @else
                                                    Tidak Ada Bukti
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Attendance Modal -->
        <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAttendanceModalLabel">Tambah Data Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Search Student -->
                        <div class="mb-4">
                            <div class="d-flex gap-2 mb-3">
                                <div class="flex-grow-1">
                                    <label for="nisSearch" class="form-label">NIS Siswa</label>
                                    <input type="text" class="form-control" id="nisSearch" placeholder="Masukkan NIS">
                                </div>
                                <div class="align-self-end">
                                    <button type="button" class="btn btn-primary" id="searchStudentBtn">Cari</button>
                                </div>
                            </div>

                            <div id="studentInfo" class="bg-light p-3 rounded mb-3 d-none">
                                <h6 class="fw-bold">Data Siswa:</h6>
                                <div class="row g-2 mt-2">
                                    <div class="col-md-4">
                                        <span class="text-muted small">NIS:</span>
                                        <p id="studentNIS" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small">Nama:</span>
                                        <p id="studentName" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small">Kelas:</span>
                                        <p id="studentClass" class="mb-0">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="attendanceForm" class="d-none">
                            <hr>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="attendanceDate" class="form-label">Tanggal Absensi</label>
                                    <input type="date" class="form-control" id="attendanceDate" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="attendanceTime" class="form-label">Waktu Absensi</label>
                                    <select class="form-select" id="attendanceTime" required>
                                        <option value="morning">Pagi</option>
                                        <option value="afternoon">Sore</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="checkInTime" class="form-label">Waktu Masuk</label>
                                    <input type="time" class="form-control" id="checkInTime">
                                </div>
                                <div class="col-md-6">
                                    <label for="checkOutTime" class="form-label">Waktu Keluar</label>
                                    <input type="time" class="form-control" id="checkOutTime">
                                </div>

                                <div class="col-md-6">
                                    <label for="attendanceStatus" class="form-label">Status Kehadiran</label>
                                    <select class="form-select" id="attendanceStatus" required>
                                        <option value="1">Hadir</option>
                                        <option value="2">Sakit</option>
                                        <option value="3">Izin</option>
                                        <option value="4">Alpa</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="attendanceDocument" class="form-label">Dokumen (opsional)</label>
                                    <input type="file" class="form-control" id="attendanceDocument">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Lokasi</label>
                                    <div class="d-flex gap-2">
                                        <div class="row flex-grow-1">
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="latitude" placeholder="Latitude" readonly>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="longitude" placeholder="Longitude" readonly>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="getLocationBtn">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Dapatkan Lokasi
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="academicYear" class="form-label">Tahun Akademik</label>
                                    <select class="form-select" id="academicYear" required>
                                        <option value="1">2023/2024</option>
                                        <option value="2">2024/2025</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-select" id="semester" required>
                                        <option value="1">Ganjil</option>
                                        <option value="2">Genap</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="saveAttendanceBtn" disabled>Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Attendance Modal -->
        <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAttendanceModalLabel">Edit Data Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Student Info (display only) -->
                        <div class="mb-4">
                            <div id="editStudentInfo" class="bg-light p-3 rounded mb-3">
                                <h6 class="fw-bold">Data Siswa:</h6>
                                <div class="row g-2 mt-2">
                                    <div class="col-md-4">
                                        <span class="text-muted small">NIS:</span>
                                        <p id="editStudentNIS" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small">Nama:</span>
                                        <p id="editStudentName" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small">Kelas:</span>
                                        <p id="editStudentClass" class="mb-0">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="editAttendanceForm">
                            <input type="hidden" id="editAttendanceId">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editAttendanceDate" class="form-label">Tanggal Absensi</label>
                                    <input type="date" class="form-control" id="editAttendanceDate" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editAttendanceTime" class="form-label">Waktu Absensi</label>
                                    <select class="form-select" id="editAttendanceTime" required>
                                        <option value="morning">Pagi</option>
                                        <option value="afternoon">Sore</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="editCheckInTime" class="form-label">Waktu Masuk</label>
                                    <input type="time" class="form-control" id="editCheckInTime">
                                </div>
                                <div class="col-md-6">
                                    <label for="editCheckOutTime" class="form-label">Waktu Keluar</label>
                                    <input type="time" class="form-control" id="editCheckOutTime">
                                </div>

                                <div class="col-md-6">
                                    <label for="editAttendanceStatus" class="form-label">Status Kehadiran</label>
                                    <select class="form-select" id="editAttendanceStatus" required>
                                        <option value="1">Hadir</option>
                                        <option value="2">Sakit</option>
                                        <option value="3">Izin</option>
                                        <option value="4">Alpa</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="editAttendanceDocument" class="form-label">Dokumen (opsional)</label>
                                    <input type="file" class="form-control" id="editAttendanceDocument">
                                    <div id="currentDocument" class="mt-2 small text-muted"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Lokasi</label>
                                    <div class="d-flex gap-2">
                                        <div class="row flex-grow-1">
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="editLatitude" placeholder="Latitude">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="editLongitude" placeholder="Longitude">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="editGetLocationBtn">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Perbarui Lokasi
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="editAcademicYear" class="form-label">Tahun Akademik</label>
                                    <select class="form-select" id="editAcademicYear" required>
                                        <option value="1">2023/2024</option>
                                        <option value="2">2024/2025</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="editSemester" class="form-label">Semester</label>
                                    <select class="form-select" id="editSemester" required>
                                        <option value="1">Ganjil</option>
                                        <option value="2">Genap</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editUpdateLocation">
                                        <label class="form-check-label" for="editUpdateLocation">
                                            Perbarui lokasi saat ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="updateAttendanceBtn">Perbarui</button>
                        <button type="button" class="btn btn-danger" id="deleteAttendanceBtn">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Kehadiran -->
        <div class="modal fade" id="deleteAttendanceModal" tabindex="-1" aria-labelledby="deleteAttendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAttendanceModalLabel">Konfirmasi Hapus Data Kehadiran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data kehadiran siswa <strong id="delete_siswa_nama">Ahmad Rizky</strong>?</p>
                        <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteAttendanceForm" action="{{ route('attendance.destroy', 1) }}" method="POST">
                  action="{{ route('attendance.destroy', 1) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" id="delete_attendance_id" name="id" value="1">
                            <input type="hidden" id="delete_waktu" name="waktu" value="pagi">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('searchStudentBtn').addEventListener('click', function () {
                let nis = document.getElementById('nisSearch').value;

                if (nis.trim() === '') {
                    alert('Masukkan NIS terlebih dahulu!');
                    return;
                }

                fetch(`/search-student?nis=${nis}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Nama: ${data.student.fullname}\nKelas: ${data.student.class_name}`);
                        } else {
                            alert('Siswa tidak ditemukan');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            document.addEventListener("DOMContentLoaded", function () {
                // Bootstrap tabs already handle the tab switching automatically
                // No need for custom tab switching code

                // Get location button functionality
                document.getElementById('getLocationBtn')?.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            document.getElementById('latitude').value = position.coords.latitude;
                            document.getElementById('longitude').value = position.coords.longitude;
                        }, function(error) {
                            console.error("Error getting location:", error);
                            alert("Gagal mendapatkan lokasi: " + error.message);
                        });
                    } else {
                        alert("Geolocation tidak didukung oleh browser ini.");
                    }
                });
            });
        </script>
    </main>
</div>
</body>
</html>
