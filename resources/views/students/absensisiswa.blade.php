<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kehadiran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        /* Custom Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .bg-primary, .btn-primary {
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

        /* Status badges */
        .badge-hadir { background-color: #28a745; }
        .badge-sakit { background-color: #ffc107; color: #212529; }
        .badge-izin { background-color: #17a2b8; }
        .badge-alpa { background-color: #dc3545; }
        .badge-terlambat { background-color: #fd7e14; color: #fff;}

        /* Table styles */
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

        /* Modal styles */
        .student-info-card {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .filter-section .col-md-3 {
                margin-bottom: 1rem;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header h2 {
                margin-bottom: 1rem;
            }
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
        @include('components.profiladmin')

       <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Data Kehadiran Siswa</h2>
            <div class="d-flex align-items-center">
                <div class="input-group me-3" style="width: 200px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" placeholder="Cari siswa..." class="form-control" id="searchInput">
                </div>
                @if(auth('employee')->user()->role->role_name !== 'Admin Tata Usaha')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                    <i class="fas fa-plus me-1"></i> Input Kehadiran
                </button>
                @endif
            </div>
        </header>

        <!-- Alert Messages -->
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

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Data Kehadiran</h5>
            <form id="filterForm" action="{{ route('student-attendance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                           value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                           value="{{ request('end_date') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="filter_class_id" class="form-label">Kelas</label>
                    <select name="class_id" id="filter_class_id" class="form-select">
                        <option value="" {{ request('class_id') == '' ? 'selected' : '' }}>Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_status_id" class="form-label">Status</label>
                    <select name="status_id" id="filter_status_id" class="form-select">
                        <option value="" {{ request('status_id') == '' ? 'selected' : '' }}>Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}" {{ request('status_id') == $status->status_id ? 'selected' : '' }}>
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
                    <a href="{{ route('student-attendance.export.pdf', request()->query()) }}" class="btn btn-danger ms-2">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Attendance Tabs -->
        <ul class="nav nav-tabs mb-4" id="attendanceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="morning-tab" data-bs-toggle="tab" data-bs-target="#morning" type="button" role="tab" aria-controls="morning" aria-selected="true">
                    <i class="fas fa-sun me-1"></i> Absensi Pagi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="afternoon-tab" data-bs-toggle="tab" data-bs-target="#afternoon" type="button" role="tab" aria-controls="afternoon" aria-selected="false">
                    <i class="fas fa-moon me-1"></i> Absensi Sore
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Morning Attendance -->
            <div class="tab-pane fade show active" id="morning" role="tabpanel" aria-labelledby="morning-tab">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-sun me-2"></i>Kehadiran Pagi</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NIPD</th>
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
                                    @forelse ($morningAttendances as $index => $attendance)
                                    @php
                                        $checkInTime = $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : null;
                                        $statusId = $attendance->status_id;
                                        $isLate = $checkInTime && $statusId === 1 && $checkInTime > '07:15';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->student->id_student ?? '-' }}</td>
                                        <td>{{ $attendance->student->fullname ?? '-' }}</td>
                                        <td>{{ $attendance->class->class_name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}</td>
                                        <td>{{ $checkInTime ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusClass = 'badge-hadir';
                                                $statusLabel = $attendance->status->status_name ?? '-';

                                                if ($statusId == 2) {
                                                    $statusClass = 'badge-sakit';
                                                } elseif ($statusId == 3) {
                                                    $statusClass = 'badge-izin';
                                                } elseif ($statusId == 4) {
                                                    $statusClass = 'badge-alpa';
                                                } elseif ($isLate) {
                                                    $statusClass = 'badge-terlambat';
                                                    $statusLabel = 'Terlambat';
                                                }
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($attendance->document)
                                                <a href="{{ asset('storage/' . $attendance->document) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                       @if(auth('employee')->user()->role->role_name !== 'Admin Tata Usaha')
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if(!$attendance->check_out_time)
                                                <button class="btn btn-sm btn-warning btn-check-out"
                                                        data-attendance-id="{{ $attendance->id }}"
                                                        data-student-name="{{ $attendance->student->fullname ?? 'Siswa' }}"
                                                        data-attendance-date="{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}"
                                                        data-check-in="{{ $checkInTime ?? '-' }}">
                                                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                                                </button>
                                                @endif

                                                <button class="btn btn-danger btn-sm delete-btn"
                                                        data-attendance-id="{{ $attendance->id }}"
                                                        data-student-name="{{ $attendance->student->fullname ?? 'Siswa' }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ auth('employee')->user()->role->role_name !== 'TU' ? '9' : '8' }}" class="text-center py-4">Tidak ada data absensi pagi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Afternoon Attendance -->
            <div class="tab-pane fade" id="afternoon" role="tabpanel" aria-labelledby="afternoon-tab">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-moon me-2"></i>Kehadiran Sore</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NIPD</th>
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
                                    @forelse ($afternoonAttendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->student->id_student ?? '-' }}</td>
                                        <td>{{ $attendance->student->fullname ?? '-' }}</td>
                                        <td>{{ $attendance->class->class_name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</td>
                                        <td>
                                            @php
                                                $statusClass = 'badge-hadir';
                                                if ($attendance->status_id == 2) {
                                                    $statusClass = 'badge-sakit';
                                                } elseif ($attendance->status_id == 3) {
                                                    $statusClass = 'badge-izin';
                                                } elseif ($attendance->status_id == 4) {
                                                    $statusClass = 'badge-alpa';
                                                }
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ $attendance->status->status_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($attendance->document)
                                                <a href="{{ asset('storage/' . $attendance->document) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @if(auth('employee')->user()->role->role_name !== 'Admin Tata Usaha')
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-btn"
                                                    data-attendance-id="{{ $attendance->id }}"
                                                    data-student-name="{{ $attendance->student->fullname ?? 'Siswa' }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ auth('employee')->user()->role->role_name !== 'Admin Tata Usaha' ? '9' : '8' }}" class="text-center py-4">Tidak ada data absensi sore</td>
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
         @if(auth('employee')->user()->role->role_name !== 'Admin Tata Usaha')
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
                                    <label for="idStudentSearch" class="form-label">ID Siswa</label>
                                    <input type="text" id="idStudentSearch" class="form-control" placeholder="Masukkan ID Siswa">
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
                                        <span class="text-muted small">NIPD:</span>
                                        <p id="studentNIPD" class="mb-0 fw-semibold">-</p>
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
                            <input type="hidden" name="class_id" id="class_id">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="attendance_date" class="form-label">Tanggal Absensi</label>
                                    <input type="date" class="form-control" id="attendance_date" name="attendance_date" required value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="status_id" class="form-label">Status Kehadiran</label>
                                    <select class="form-select" id="status_id" name="status_id" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="check_in_time" class="form-label">Jam Masuk</label>
                                    <input type="time" class="form-control" id="check_in_time" name="check_in_time" required value="{{ date('H:i') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="document" class="form-label">Dokumen (opsional)</label>
                                    <input type="file" class="form-control" id="document" name="document" accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="text-muted">Format: JPG, JPEG, PNG, PDF. Maks: 2MB</small>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i> Simpan Absensi
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="hideStudentInfo()">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endif
        <!-- Check Out Modal -->
        @if(auth('employee')->user()->role->role_name !== 'Admin Tata Usaha')
        <div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkOutModalLabel"><i class="fas fa-sign-out-alt me-2"></i>Absen Keluar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="checkOutForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="check_out_time" id="check_out_time">

                            <div class="student-info-card mb-3">
                                <div class="mb-2">
                                    <span class="text-muted small">Nama Siswa:</span>
                                    <h6 id="checkOutStudentName" class="fw-semibold mb-0">-</h6>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="text-muted small">Tanggal:</span><br>
                                        <strong id="checkOutDateDisplay">-</strong>
                                    </div>
                                    <div>
                                        <span class="text-muted small">Jam Masuk:</span><br>
                                        <strong id="checkOutCheckInDisplay">-</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jam Keluar</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <input type="time" class="form-control" id="manual_check_out_time"
                                           value="{{ date('H:i') }}" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endif
<script>
// Fungsi untuk menyembunyikan info siswa dan form
function hideStudentInfo() {
    document.getElementById('studentInfo').classList.add('d-none');
    document.getElementById('attendanceForm').classList.add('d-none');
    document.getElementById('idStudentSearch').value = '';
}

// Inisialisasi tombol Check Out
function initCheckOutButtons() {
    document.querySelectorAll('.btn-check-out').forEach(button => {
        button.addEventListener('click', function() {
            const attendanceId = this.getAttribute('data-attendance-id');
            const studentName = this.getAttribute('data-student-name');
            const attendanceDate = this.getAttribute('data-attendance-date');
            const checkInTime = this.getAttribute('data-check-in');

            // Set form action
            document.getElementById('checkOutForm').action = `/student-attendance/${attendanceId}/check-out`;

            // Isi data di modal
            document.getElementById('checkOutStudentName').textContent = studentName;
            document.getElementById('checkOutDateDisplay').textContent = attendanceDate;
            document.getElementById('checkOutCheckInDisplay').textContent = checkInTime;

            // Set waktu sekarang sebagai default jam keluar
            const now = new Date();
            const currentTime = now.toTimeString().substring(0, 5);
            document.getElementById('manual_check_out_time').value = currentTime;

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('checkOutModal'));
            modal.show();
        });
    });
}

// Fungsi untuk handle delete
function handleDelete(attendanceId, studentName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Hapus absensi <strong>${studentName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/student-attendance/${attendanceId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Jika response tidak ok, parse error message
                    return response.json().then(err => {
                        throw new Error(err.message || 'Gagal menghapus data');
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.fire(
                    'Terhapus!',
                    'Data berhasil dihapus',
                    'success'
                ).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error!',
                    error.message || 'Terjadi kesalahan',
                    'error'
                );
            });
        }
    });
}

// Inisialisasi tombol delete
function initDeleteButtons() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const attendanceId = this.getAttribute('data-attendance-id');
            const studentName = this.getAttribute('data-student-name');
            handleDelete(attendanceId, studentName);
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Pencarian siswa berdasarkan NIPD
     @if(auth('employee')->user()->role->role_name !== 'Admin tata Usaha')
    document.getElementById('searchStudentBtn').addEventListener('click', function() {
        const idStudent = document.getElementById('idStudentSearch').value.trim();
        if (!idStudent) {
            alert('Masukkan ID siswa terlebih dahulu!');
            return;
        }

        const searchBtn = this;
        searchBtn.disabled = true;
        searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...';

        fetch(`/student/search?id_student=${encodeURIComponent(idStudent)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.student) {
                    const student = data.student;
                    document.getElementById('studentInfo').classList.remove('d-none');
                    document.getElementById('studentNIPD').textContent = student.id_student || '-';
                    document.getElementById('studentName').textContent = student.fullname || '-';
                    document.getElementById('studentClass').textContent = student.class_name || '-';

                    // Isi form input tersembunyi
                    document.getElementById('id_student').value = student.id_student;
                    document.getElementById('class_id').value = student.class_id || '';

                    // Tampilkan form absensi
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
     @endif

    // Live search filter tabel
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.tab-pane.active table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Submit check-out
    document.getElementById('checkOutForm').addEventListener('submit', function() {
        const manualTime = document.getElementById('manual_check_out_time').value;
        document.getElementById('check_out_time').value = manualTime;
    });

    // Inisialisasi semua tombol pertama kali
    initCheckOutButtons();
    initDeleteButtons();

    // Re-inisialisasi saat tab berpindah
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            initCheckOutButtons();
            initDeleteButtons();
        });
    });
});
</script>
</body>
</html>
