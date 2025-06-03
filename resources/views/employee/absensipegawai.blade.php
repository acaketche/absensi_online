<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-hadir {
            background-color: #D1FAE5;
            color: #059669;
        }

        .status-terlambat {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .status-izin {
            background-color: #E0E7FF;
            color: #4F46E5;
        }

        .status-sakit {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        .status-alpha {
            background-color: #1F2937;
            color: #F9FAFB;
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Admin Profile Header -->
       @include('components.profiladmin')

        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Absensi Pegawai</h2>
            <div class="d-flex align-items-center">
                <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeAttendanceModal">+ Tambah Absensi</button>
            </div>
        </header>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

       <!-- Filter Section -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        Filter Absensi Pegawai
    </div>
    <div class="card-body">
        <form action="{{ route('attendance.index') }}" method="GET" class="mb-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}"
                                {{ request('status') == $status->status_id ? 'selected' : '' }}>
                                {{ $status->status_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date"
                        class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date"
                        class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Export PDF Button -->
        <form action="{{ route('attendance.export.pdf') }}" method="GET" target="_blank">
            <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">
            <input type="hidden" name="semester_id" value="{{ $semesterId }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
        </form>
    </div>
</div>

        <!-- Data Absensi Pegawai -->
        <div class="card">
            <div class="card-header bg-primary text-white">Data Absensi Pegawai</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances ?? [] as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->employee->fullname ?? $attendance->employee->id_employee }}</td>
                                <td>{{ date('d-m-Y', strtotime($attendance->attendance_date)) }}</td>
                                <td>{{ date('H:i', strtotime($attendance->check_in)) }}</td>
                                <td>
                                    @if($attendance->check_out)
                                        {{ date('H:i', strtotime($attendance->check_out)) }}
                                    @else
                                        <span class="text-muted">Belum absen keluar</span>
                                    @endif
                                </td>
                               <td>
                                    <span class="status-badge status-{{ strtolower($attendance->status->status_name) }}">
                                        {{ $attendance->status->status_name }}
                                    </span>
                                </td>

                                <td>
                                    <button  class="btn btn-sm btn-warning edit-attendance-btn"
                                        data-id="{{ $attendance->id }}"
                                        data-employee-id="{{ $attendance->employee->id_employee }}"
                                        data-attendance-date="{{ $attendance->attendance_date }}"
                                        data-check-in="{{ $attendance->check_in }}"
                                        data-check-out="{{ $attendance->check_out }}"
                                        data-status-id="{{ $attendance->status->status_id }}"
                                         data-bs-toggle="modal"
                                        data-bs-target="#editEmployeeAttendanceModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEmployeeAttendanceModal" data-id="{{ $attendance->id }}" data-name="{{ $attendance->employee->name ?? $attendance->employee_id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data absensi pegawai</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Tambah Absensi Pegawai -->
<div class="modal fade" id="addEmployeeAttendanceModal" tabindex="-1" aria-labelledby="addEmployeeAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeAttendanceModalLabel">Tambah Absensi Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeAttendanceForm" action="{{ route('attendance.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Pegawai</label>
                        <select class="form-select" id="employee_id" name="employee_id" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees ?? [] as $employee)
                               <option value="{{ $employee->id_employee}}"> {{ $employee->fullname }} (NIP: {{ $employee->id_employee }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="attendance_date" class="form-label">Tanggal Absensi</label>
                        <input type="date" class="form-control" id="attendance_date" name="attendance_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="check_in" class="form-label">Waktu Masuk</label>
                        <input type="time" class="form-control" id="check_in" name="check_in" required>
                    </div>
                    <div class="mb-3">
                        <label for="check_out" class="form-label">Waktu Keluar</label>
                        <input type="time" class="form-control" id="check_out" name="check_out">
                        <small class="text-muted">Kosongkan jika belum absen keluar</small>
                    </div>
                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status</label>
                        <select class="form-select" id="status_id" name="status_id" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                     <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                        <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Absensi Pegawai -->
<div class="modal fade" id="editEmployeeAttendanceModal" tabindex="-1" aria-labelledby="editEmployeeAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editEmployeeAttendanceForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Absensi Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_attendance_id" name="id">

                    <div class="mb-3">
                        <label for="edit_id_employee" class="form-label">Pegawai</label>
                        <select class="form-select" id="edit_employee_id" name="employee_id" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees ?? [] as $employee)
                                <option value="{{ $employee->id_employee }}">{{ $employee->fullname }} (NIP: {{ $employee->id_employee }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_attendance_date" class="form-label">Tanggal Absensi</label>
                        <input type="date" class="form-control" id="edit_attendance_date" name="attendance_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_check_in" class="form-label">Waktu Masuk</label>
                        <input type="time" class="form-control" id="edit_check_in" name="check_in">
                    </div>

                    <div class="mb-3">
                        <label for="edit_check_out" class="form-label">Waktu Keluar</label>
                        <input type="time" class="form-control" id="edit_check_out" name="check_out">
                        <small class="text-muted">Kosongkan jika belum absen keluar</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status_id" class="form-label">Status</label>
                        <select class="form-select" id="edit_status_id" name="status_id" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Absensi Pegawai -->
<div class="modal fade" id="deleteEmployeeAttendanceModal" tabindex="-1" aria-labelledby="deleteEmployeeAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEmployeeAttendanceModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data absensi pegawai <span id="employee_name"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteEmployeeAttendanceForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-attendance-btn");
    const form = document.getElementById("editEmployeeAttendanceForm");

    editButtons.forEach(button => {
        button.addEventListener("click", () => {
            const id = button.getAttribute("data-id");
            const employeeId = button.getAttribute("data-employee-id");
            const date = button.getAttribute("data-attendance-date");
            const checkIn = button.getAttribute("data-check-in");
            const checkOut = button.getAttribute("data-check-out");
            const statusId = button.getAttribute("data-status-id");

            // Set form action dynamically
            form.action = `/attendance/${id}`;

            // Isi form dengan data dari tombol
            document.getElementById("edit_attendance_id").value = id;
            document.getElementById("edit_employee_id").value = employeeId;
            document.getElementById("edit_attendance_date").value = date;
            document.getElementById("edit_check_in").value = checkIn?.slice(0, 5);
            document.getElementById("edit_check_out").value = checkOut?.slice(0, 5);
            document.getElementById("edit_status_id").value = statusId;
        });
    });
});

    // Handle delete employee attendance modal
    const deleteEmployeeAttendanceModal = document.getElementById('deleteEmployeeAttendanceModal');
    if (deleteEmployeeAttendanceModal) {
        deleteEmployeeAttendanceModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            // Set employee name in confirmation message
            document.getElementById('employee_name').textContent = name;

            // Set form action
            document.getElementById('deleteEmployeeAttendanceForm').action = `/employee/attendance/${id}`;
        });
    }
</script>
</body>
</html>
