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

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Filter Absensi Pegawai</div>
            <div class="card-body">
                <form action="{{ route('attendance.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Jabatan</label>
                            <select name="position" class="form-control">
                                <option value="">-- Semua Jabatan --</option>
                                @foreach($positions ?? [] as $position)
                                    <option value="{{ $position }}" {{ request('position') == $position ? 'selected' : '' }}>
                                        {{ $position }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">-- Semua Status --</option>
                                <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Alpha" {{ request('status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary ms-2">Reset</a>
                    </div>
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
                                <th>Jabatan</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances ?? [] as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->employee->name ?? $attendance->employee_id }}</td>
                                <td>{{ $attendance->employee->position ?? '-' }}</td>
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
                                    <span class="status-badge status-{{ strtolower($attendance->status) }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($attendance->latitude && $attendance->longitude)
                                        <a href="https://maps.google.com/?q={{ $attendance->latitude }},{{ $attendance->longitude }}" target="_blank">
                                            <i class="fas fa-map-marker-alt"></i> Lihat Lokasi
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada data</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editEmployeeAttendanceModal" data-id="{{ $attendance->id }}">
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
                                <option value="{{ $employee->id_employee }}">{{ $employee->fullname }} </option>
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
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude">
                    </div>
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Absensi Pegawai -->
<div class="modal fade" id="editEmployeeAttendanceModal" tabindex="-1" aria-labelledby="editEmployeeAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeAttendanceModalLabel">Edit Absensi Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeAttendanceForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_employee_id" class="form-label">Pegawai</label>
                        <select class="form-select" id="edit_employee_id" name="employee_id" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees ?? [] as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_attendance_date" class="form-label">Tanggal Absensi</label>
                        <input type="date" class="form-control" id="edit_attendance_date" name="attendance_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_check_in" class="form-label">Waktu Masuk</label>
                        <input type="time" class="form-control" id="edit_check_in" name="check_in" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_check_out" class="form-label">Waktu Keluar</label>
                        <input type="time" class="form-control" id="edit_check_out" name="check_out">
                        <small class="text-muted">Kosongkan jika belum absen keluar</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="edit_latitude" name="latitude">
                    </div>
                    <div class="mb-3">
                        <label for="edit_longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="edit_longitude" name="longitude">
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit employee attendance modal
    const editEmployeeAttendanceModal = document.getElementById('editEmployeeAttendanceModal');
    if (editEmployeeAttendanceModal) {
        editEmployeeAttendanceModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');

            // Set form action
            document.getElementById('editEmployeeAttendanceForm').action = `/employee/attendance/${id}`;

            // Fetch the attendance data via AJAX
            fetch(`/employee/attendance/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_employee_id').value = data.employee_id;
                    document.getElementById('edit_attendance_date').value = data.attendance_date;
                    document.getElementById('edit_check_in').value = data.check_in;
                    document.getElementById('edit_check_out').value = data.check_out || '';
                    document.getElementById('edit_status').value = data.status;
                    document.getElementById('edit_latitude').value = data.latitude || '';
                    document.getElementById('edit_longitude').value = data.longitude || '';
                    document.getElementById('edit_notes').value = data.notes || '';
                })
                .catch(error => console.error('Error:', error));
        });
    }

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

    // Get current location for add employee attendance form
    const addEmployeeAttendanceModal = document.getElementById('addEmployeeAttendanceModal');
    if (addEmployeeAttendanceModal) {
        addEmployeeAttendanceModal.addEventListener('show.bs.modal', function() {
            // Set current date and time
            const now = new Date();
            const date = now.toISOString().split('T')[0];
            const time = now.toTimeString().split(' ')[0].substring(0, 5);

            document.getElementById('attendance_date').value = date;
            document.getElementById('check_in').value = time;

            // Get current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        });
    }
});
</script>
</body>
</html>
