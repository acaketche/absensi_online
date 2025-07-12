<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Absensi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .status-hadir { background-color: #D1FAE5; color: #059669; }
        .status-terlambat { background-color: #FEF3C7; color: #D97706; }
        .status-izin { background-color: #E0E7FF; color: #4F46E5; }
        .status-sakit { background-color: #FEE2E2; color: #DC2626; }
        .status-alpha { background-color: #1F2937; color: #F9FAFB; }

        .attendance-modal .modal-body { padding: 2rem; }
        .employee-select-container {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 1.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        .attendance-btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .attendance-info {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        .current-time {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0d6efd;
        }
        .status-preview {
            font-size: 1rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    @include('components.sidebar')
    <main class="flex-grow-1 p-4">
        @include('components.profiladmin')

        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Absensi Pegawai</h2>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" placeholder="Cari..." class="form-control me-3" style="width: 200px;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeAttendanceModal">
                    <i class="fas fa-plus me-2"></i>Tambah Absensi
                </button>
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-filter me-2"></i>Filter Absensi
            </div>
            <div class="card-body">
                <form action="{{ route('attendance.index') }}" method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->status_id }}" {{ request('status') == $status->status_id ? 'selected' : '' }}>
                                        {{ $status->status_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>Filter
                            </button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list me-2"></i>Data Absensi</span>
                <div>
                    <a href="{{ route('attendance.export.pdf') }}?{{ http_build_query(request()->query()) }}"
                       class="btn btn-sm btn-danger me-2">
                        <i class="fas fa-file-pdf me-1"></i>PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="attendanceTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->employee->fullname ?? 'N/A' }}</td>
                                <td>{{ date('d-m-Y', strtotime($attendance->attendance_date)) }}</td>
                                <td>{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : '-' }}</td>
                                <td>{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : '-' }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($attendance->status->status_name) }}">
                                        {{ $attendance->status->status_name }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-attendance-btn"
                                        data-id="{{ $attendance->id }}"
                                        data-employee-id="{{ $attendance->employee->id_employee }}"
                                        data-employee-name="{{ $attendance->employee->fullname }}"
                                        data-attendance-date="{{ $attendance->attendance_date }}"
                                        data-check-in="{{ $attendance->check_in }}"
                                        data-check-out="{{ $attendance->check_out }}"
                                        data-status-id="{{ $attendance->status_id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editEmployeeAttendanceModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-attendance-btn"
                                        data-id="{{ $attendance->id }}"
                                        data-name="{{ $attendance->employee->fullname }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteEmployeeAttendanceModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Attendance Modal -->
<div class="modal fade attendance-modal" id="addEmployeeAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Absen Masuk Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('attendance.store') }}" method="POST" id="addAttendanceForm">
                    @csrf
                    <input type="hidden" name="attendance_date" id="attendance_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="check_in" id="check_in">

                    <div class="attendance-info">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Tanggal:</span>
                            <strong id="current_date_display">{{ date('d-m-Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Waktu Sekarang:</span>
                            <strong class="current-time" id="current_time_display"></strong>
                        </div>
                        <div id="statusFeedback" class="status-preview mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label for="employee_search" class="form-label">Cari Pegawai</label>
                        <input type="text" class="form-control" id="employee_search" placeholder="Cari nama pegawai...">
                    </div>

                    <div class="employee-select-container">
                        <div class="list-group" id="employeeList">
                            @foreach ($employees as $employee)
                                <label class="list-group-item d-flex align-items-center">
                                    <input class="form-check-input me-3" type="radio" name="employee_id"
                                           value="{{ $employee->id_employee }}" required>
                                    <div>
                                        <h6 class="mb-1">{{ $employee->fullname }}</h6>
                                        <small class="text-muted">{{ $employee->id_employee }}</small>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Status Khusus</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="izinCheckbox" name="izin" value="1">
                            <label class="form-check-label" for="izinCheckbox">Izin</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="sakitCheckbox" name="sakit" value="1">
                            <label class="form-check-label" for="sakitCheckbox">Sakit</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary attendance-btn mt-3" id="absenMasukBtn">
                        <i class="fas fa-sign-in-alt me-2"></i> Absen Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div class="modal fade attendance-modal" id="editEmployeeAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Absen Keluar Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form id="editEmployeeAttendanceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_employee_id" name="employee_id">
                    <input type="hidden" id="edit_attendance_date" name="attendance_date">
                    <input type="hidden" id="edit_check_in" name="check_in">
                    <input type="hidden" name="check_out" id="edit_check_out">

                    <div class="attendance-info">
                        <div class="mb-3">
                            <label class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="edit_employee_name" readonly>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Tanggal:</span>
                            <strong id="edit_date_display"></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Jam Masuk:</span>
                            <strong id="edit_check_in_display"></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Waktu Sekarang:</span>
                            <strong class="current-time" id="edit_current_time_display"></strong>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary attendance-btn mt-3" id="absenKeluarBtn">
                        <i class="fas fa-sign-out-alt me-2"></i> Absen Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteEmployeeAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data absensi untuk <strong id="employee_name"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteEmployeeAttendanceForm" method="POST">
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
    // Update current time every second
    function updateCurrentTime() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}`;
        const displayTimeString = `${hours}:${minutes}`;
        const dateString = now.toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});

        // Update time displays
        const timeElements = [
            'current_time_display',
            'edit_current_time_display'
        ];

        timeElements.forEach(id => {
            if (document.getElementById(id)) {
                document.getElementById(id).textContent = displayTimeString;
            }
        });

        // Set hidden time values
        if (document.getElementById('check_in')) {
            document.getElementById('check_in').value = timeString;
        }

        if (document.getElementById('edit_check_out')) {
            document.getElementById('edit_check_out').value = timeString;
        }

        // Update status preview for add modal
        updateStatusPreview(timeString);
    }

    function determineStatus(checkInTime) {
    if (!checkInTime) return 4; // Alpha

    const [hours, minutes] = checkInTime.split(':').map(Number);
    const totalMinutes = (hours * 60) + minutes;

    const hadirStart = 6 * 60 + 30;  // 06:30
    const hadirEnd   = 7 * 60 + 30;  // 07:30
    const terlambatEnd = 8 * 60;    // 08:00

    if (totalMinutes >= hadirStart && totalMinutes <= hadirEnd) return 1; // Hadir
    if (totalMinutes > hadirEnd && totalMinutes <= terlambatEnd) return 5; // Terlambat
    return 4; // Alpha
}

    function updateStatusPreview(timeString) {
    const statusElement = document.getElementById('statusFeedback');
    if (!statusElement) return;

    const izin = document.getElementById('izinCheckbox')?.checked;
    const sakit = document.getElementById('sakitCheckbox')?.checked;

    let statusId;

    if (izin) {
        statusId = 2; // Izin
    } else if (sakit) {
        statusId = 3; // Sakit
    } else {
        statusId = determineStatus(timeString);
    }

    const statusMap = {
        1: {text: 'Hadir', class: 'status-hadir'},
        5: {text: 'Terlambat', class: 'status-terlambat'},
        2: {text: 'Izin', class: 'status-izin'},
        3: {text: 'Sakit', class: 'status-sakit'},
        4: {text: 'Alpha', class: 'status-alpha'}
    };

    statusElement.textContent = `Status: ${statusMap[statusId].text}`;
    statusElement.className = `status-badge ${statusMap[statusId].class} status-preview`;
}
    // Employee search functionality
    const employeeSearch = document.getElementById('employee_search');
    if (employeeSearch) {
        employeeSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const employeeItems = document.querySelectorAll('#employeeList .list-group-item');

            employeeItems.forEach(item => {
                const employeeName = item.querySelector('h6').textContent.toLowerCase();
                const employeeId = item.querySelector('small').textContent.toLowerCase();

                if (employeeName.includes(searchTerm) || employeeId.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Table search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Edit modal setup
   const editModal = document.getElementById('editEmployeeAttendanceModal');
if (editModal) {
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const form = document.getElementById('editEmployeeAttendanceForm');

        // Set form action sesuai route check-out
        form.action = `/student-attendance/${button.getAttribute('data-id')}/check-out`;

            // Fill form data
            document.getElementById('edit_employee_id').value = button.getAttribute('data-employee-id');
            document.getElementById('edit_employee_name').value = button.getAttribute('data-employee-name');
            document.getElementById('edit_attendance_date').value = button.getAttribute('data-attendance-date');
            const rawCheckIn = button.getAttribute('data-check-in') ?? '';
            const formattedCheckIn = rawCheckIn.length >= 5 ? rawCheckIn.substring(0, 5) : '';
            document.getElementById('edit_check_in').value = formattedCheckIn;


            // Format dates for display
            const dateObj = new Date(button.getAttribute('data-attendance-date'));
            document.getElementById('edit_date_display').textContent = dateObj.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            // Format check-in time
            const checkInTime = button.getAttribute('data-check-in');
            document.getElementById('edit_check_in_display').textContent = checkInTime ? checkInTime.substring(0, 5) : '-';

            // Disable button if already checked out
            const checkOutBtn = document.getElementById('absenKeluarBtn');
            if (button.getAttribute('data-check-out')) {
                checkOutBtn.disabled = true;
                checkOutBtn.textContent = 'Sudah Absen Keluar';
                checkOutBtn.classList.remove('btn-primary');
                checkOutBtn.classList.add('btn-secondary');
            } else {
                checkOutBtn.disabled = false;
                checkOutBtn.textContent = 'Absen Keluar';
                checkOutBtn.classList.remove('btn-secondary');
                checkOutBtn.classList.add('btn-primary');
            }
        });
    }
// Handle form submission dengan AJAX
document.getElementById('editEmployeeAttendanceForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tutup modal
            const modal = bootstrap.Modal.getInstance(editModal);
            modal.hide();

            // Tampilkan notifikasi
            alert(data.message);

            // Redirect atau refresh data
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});
    // Delete modal setup
    const deleteModal = document.getElementById('deleteEmployeeAttendanceModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('employee_name').textContent = button.getAttribute('data-name');
            document.getElementById('deleteEmployeeAttendanceForm').action = `/attendance/${button.getAttribute('data-id')}`;
        });
    }

    // Form submission handlers
    document.getElementById('addAttendanceForm')?.addEventListener('submit', function() {
        // Ensure time is captured at submission moment
        const now = new Date();
        document.getElementById('check_in').value =
            now.getHours().toString().padStart(2, '0') + ':' +
            now.getMinutes().toString().padStart(2, '0');
    });

    document.getElementById('editEmployeeAttendanceForm')?.addEventListener('submit', function() {
        // Ensure check_out time is captured at submission moment
        const now = new Date();
        document.getElementById('edit_check_out').value =
            now.getHours().toString().padStart(2, '0') + ':' +
            now.getMinutes().toString().padStart(2, '0');
    });

    // Initialize time display
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    ['izinCheckbox', 'sakitCheckbox'].forEach(id => {
    const checkbox = document.getElementById(id);
    if (checkbox) {
        checkbox.addEventListener('change', updateCurrentTime);
    }
});

});
</script>
</body>
</html>
