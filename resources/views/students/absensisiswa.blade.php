<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kehadiran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        /* Status badges */
        .badge-hadir { background-color: #28a745; }
        .badge-sakit { background-color: #ffc107; color: #212529; }
        .badge-izin { background-color: #17a2b8; }
        .badge-alpa { background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Header dengan Profil Admin -->
        @include('components.profiladmin')

        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Data Kehadiran Siswa</h2>
            <div class="d-flex align-items-center">
                <input type="text" placeholder="Cari nama siswa" class="form-control me-3" style="width: 200px;" id="searchInput">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                    <i class="fas fa-plus me-1"></i> Input Kehadiran
                </button>
            </div>
        </header>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan!</strong> Silakan periksa form berikut:
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="filter-section mb-4">
            <h5 class="mb-3">Filter Data Kehadiran</h5>
            <form id="filterForm" action="{{ route('student-attendance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="class_id" class="form-label">Kelas</label>
                    <select name="class_id" id="class_id" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status_id" class="form-label">Status</label>
                    <select name="status_id" id="status_id" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->status_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('student-attendance.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
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
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $morningCount = 1; @endphp
                                    @forelse ($attendances as $attendance)
                                        @php
                                            $checkInTime = $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : null;
                                            $isPagi = $checkInTime && $checkInTime >= '07:00' && $checkInTime <= '12:00';
                                        @endphp
                                        @if ($isPagi)
                                            <tr>
                                                <td>{{ $morningCount++ }}</td>
                                                <td>{{ $attendance['student']['fullname'] ?? '-' }}</td>
                                                <td>{{ $attendance['class_name'] ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($attendance['attendance_date'])->format('d/m/Y') }}</td>
                                                <td>{{ $checkInTime }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = 'badge-hadir';
                                                        if ($attendance['status_id'] == 2) $statusClass = 'badge-sakit';
                                                        elseif ($attendance['status_id'] == 3) $statusClass = 'badge-izin';
                                                        elseif ($attendance['status_id'] == 4) $statusClass = 'badge-alpa';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ $attendance['status']['status_name'] ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($attendance['document'])
                                                        <a href="{{ asset('storage/' . $attendance['document']) }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-file-alt"></i> Lihat
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-warning btn-sm edit-attendance"
                                                            data-id="{{ $attendance['id'] }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAttendanceModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('student-attendance.destroy', $attendance['id']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data absensi pagi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tanggal</th>
                                        <th>Jam Keluar</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $afternoonCount = 1; @endphp
                                    @forelse ($attendances as $attendance)
                                        @php
                                            $checkOutTime = $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : null;
                                            $isSore = $checkOutTime && $checkOutTime >= '12:30' && $checkOutTime <= '16:00';
                                        @endphp
                                        @if ($isSore)
                                            <tr>
                                                <td>{{ $afternoonCount++ }}</td>
                                                <td>{{ $attendance['student']['fullname'] ?? '-' }}</td>
                                                <td>{{ $attendance['class_name'] ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($attendance['attendance_date'])->format('d/m/Y') }}</td>
                                                <td>{{ $checkOutTime }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = 'badge-hadir';
                                                        if ($attendance['status_id'] == 2) $statusClass = 'badge-sakit';
                                                        elseif ($attendance['status_id'] == 3) $statusClass = 'badge-izin';
                                                        elseif ($attendance['status_id'] == 4) $statusClass = 'badge-alpa';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ $attendance['status']['status_name'] ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($attendance['document'])
                                                        <a href="{{ asset('storage/' . $attendance['document']) }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-file-alt"></i> Lihat
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-warning btn-sm edit-attendance"
                                                            data-id="{{ $attendance['id'] }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAttendanceModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('student-attendance.destroy', $attendance['id']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data absensi sore</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
                                    <input type="text" class="form-control" id="nisSearch" placeholder="Masukkan NIS" autocomplete="off">
                                </div>
                                <div class="align-self-end">
                                    <button type="button" class="btn btn-primary" id="searchStudentBtn">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>
                                </div>
                            </div>

                            <div id="studentInfo" class="bg-light p-3 rounded mb-3 d-none">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">Data Siswa:</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="hideStudentInfo()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-md-4">
                                        <span class="text-muted small">NIS:</span>
                                        <p id="studentNIS" class="mb-0 fw-semibold">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small">Nama:</span>
                                        <p id="studentName" class="mb-0 fw-semibold">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small">Kelas:</span>
                                        <p id="studentClass" class="mb-0 fw-semibold">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="attendanceForm" action="{{ route('student-attendance.store') }}" method="POST" enctype="multipart/form-data" class="d-none">
                            @csrf
                            <input type="hidden" id="id_student" name="id_student">
                            <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                            <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

                            <hr>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="attendance_date" class="form-label">Tanggal Absensi</label>
                                    <input type="date" class="form-control" id="attendance_date" name="attendance_date" required value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="check_in_time" class="form-label">Waktu Masuk</label>
                                    <input type="time" class="form-control" id="check_in_time" name="check_in_time">
                                </div>
                                <div class="col-md-6">
                                    <label for="check_out_time" class="form-label">Waktu Keluar</label>
                                    <input type="time" class="form-control" id="check_out_time" name="check_out_time">
                                </div>

                                <div class="col-md-6">
                                    <label for="status_id" class="form-label">Status Kehadiran</label>
                                    <select class="form-select" id="status_id" name="status_id" required>
                                        <option value="">-- Pilih Status --</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="document" class="form-label">Dokumen (opsional)</label>
                                    <input type="file" class="form-control" id="document" name="document">
                                    <small class="text-muted">Format: JPG, JPEG, PNG, PDF. Maks: 2MB</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Lokasi</label>
                                    <div class="d-flex gap-2">
                                        <div class="row flex-grow-1">
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude" readonly>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude" readonly>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="getLocationBtn">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Dapatkan Lokasi
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                </div>
                            </div>
                        </form>
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
                        <form id="editAttendanceForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <div class="bg-light p-3 rounded">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <span class="text-muted small">NIS:</span>
                                            <p id="editStudentNIS" class="mb-0 fw-semibold">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small">Nama:</span>
                                            <p id="editStudentName" class="mb-0 fw-semibold">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small">Kelas:</span>
                                            <p id="editStudentClass" class="mb-0 fw-semibold">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_attendance_date" class="form-label">Tanggal Absensi</label>
                                    <input type="date" class="form-control" id="edit_attendance_date" name="attendance_date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_status_id" class="form-label">Status Kehadiran</label>
                                    <select class="form-select" id="edit_status_id" name="status_id" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="edit_check_in_time" class="form-label">Waktu Masuk</label>
                                    <input type="time" class="form-control" id="edit_check_in_time" name="check_in_time">
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_check_out_time" class="form-label">Waktu Keluar</label>
                                    <input type="time" class="form-control" id="edit_check_out_time" name="check_out_time">
                                </div>

                                <div class="col-md-6">
                                    <label for="edit_document" class="form-label">Dokumen (opsional)</label>
                                    <input type="file" class="form-control" id="edit_document" name="document">
                                    <small class="text-muted">Format: JPG, JPEG, PNG, PDF. Maks: 2MB</small>
                                    <div id="currentDocument" class="mt-2"></div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Search student by NIS
                document.getElementById('searchStudentBtn').addEventListener('click', function() {
                    const nis = document.getElementById('nisSearch').value.trim();

                    if (!nis) {
                        alert('Masukkan NIS siswa terlebih dahulu!');
                        return;
                    }

                    // Tampilkan loading state
                    const searchBtn = this;
                    searchBtn.disabled = true;
                    searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...';

                    fetch(`/student/search?id_student=${encodeURIComponent(nis)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.student) {
                                // Tampilkan informasi siswa
                                const studentInfo = document.getElementById('studentInfo');
                                studentInfo.classList.remove('d-none');

                                // Isi data siswa
                                document.getElementById('studentNIS').textContent = data.student.id_student;
                                document.getElementById('studentName').textContent = data.student.fullname;
                                document.getElementById('studentClass').textContent = data.student.class_name;

                                // Set nilai untuk form
                                document.getElementById('id_student').value = data.student.id_student;
                                document.getElementById('attendanceForm').classList.remove('d-none');
                            } else {
                                alert(data.message || 'Siswa tidak ditemukan');
                                hideStudentInfo();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mencari data siswa');
                            hideStudentInfo();
                        })
                        .finally(() => {
                            searchBtn.disabled = false;
                            searchBtn.innerHTML = '<i class="fas fa-search me-1"></i> Cari';
                        });
                });

                // Fungsi untuk menyembunyikan info siswa
                function hideStudentInfo() {
                    document.getElementById('studentInfo').classList.add('d-none');
                    document.getElementById('attendanceForm').classList.add('d-none');
                }

                // Get location button functionality
                document.getElementById('getLocationBtn')?.addEventListener('click', function() {
                    const locationBtn = this;
                    locationBtn.disabled = true;
                    locationBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mendapatkan...';

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                document.getElementById('latitude').value = position.coords.latitude;
                                document.getElementById('longitude').value = position.coords.longitude;
                                locationBtn.disabled = false;
                                locationBtn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Dapatkan Lokasi';
                            },
                            function(error) {
                                console.error("Error getting location:", error);
                                alert("Gagal mendapatkan lokasi: " + error.message);
                                locationBtn.disabled = false;
                                locationBtn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Dapatkan Lokasi';
                            }
                        );
                    } else {
                        alert("Geolocation tidak didukung oleh browser ini.");
                        locationBtn.disabled = false;
                        locationBtn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Dapatkan Lokasi';
                    }
                });

                // Search functionality for tables
                document.getElementById('searchInput').addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tables = document.querySelectorAll('.tab-pane.active table tbody tr');

                    tables.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });

                // Edit Attendance Modal
                document.querySelectorAll('.edit-attendance').forEach(button => {
                    button.addEventListener('click', function() {
                        const attendanceId = this.getAttribute('data-id');

                        fetch(`/api/attendances/${attendanceId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const attendance = data.data;

                                    // Set form action
                                    document.getElementById('editAttendanceForm').action = `/student-attendance/${attendanceId}`;

                                    // Fill student info
                                    document.getElementById('editStudentNIS').textContent = attendance.student?.id_student || '-';
                                    document.getElementById('editStudentName').textContent = attendance.student?.fullname || '-';
                                    document.getElementById('editStudentClass').textContent = attendance.class_name || '-';

                                    // Fill form fields
                                    document.getElementById('edit_attendance_date').value = attendance.attendance_date;
                                    document.getElementById('edit_status_id').value = attendance.status_id;
                                    document.getElementById('edit_check_in_time').value = attendance.check_in_time || '';
                                    document.getElementById('edit_check_out_time').value = attendance.check_out_time || '';

                                    // Show current document if exists
                                    const currentDocDiv = document.getElementById('currentDocument');
                                    if (attendance.document) {
                                        currentDocDiv.innerHTML = `
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">Dokumen saat ini:</span>
                                                <a href="/storage/${attendance.document}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                            </div>
                                        `;
                                    } else {
                                        currentDocDiv.innerHTML = '<span class="text-muted">Tidak ada dokumen</span>';
                                    }
                                } else {
                                    alert('Gagal memuat data absensi');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memuat data');
                            });
                    });
                });
            });
        </script>
    </main>
</div>
</body>
</html>
