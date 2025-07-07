<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
        }

        .employee-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #E6E1F9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .employee-photo i {
            font-size: 20px;
            color: #7950F2;
        }

        .table-responsive table {
            min-width: 150%; /* Pastikan tabel lebih lebar dari kontainer */
            border-collapse: collapse;
            table-layout: auto; /* Ukuran kolom disesuaikan otomatis */
        }

    </style>
</head>
@if(Auth::guard('employee')->check())
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Header with Admin Profile -->
           @include('components.profiladmin')

            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold">Data Pegawai</h2>
                <div class="d-flex align-items-center">
                    <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;" id="searchInput">
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">+ Tambah Pegawai</a>
                </div>
            </header>

            <!-- Alert for success message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-header bg-primary text-white">Data Pegawai</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NIP</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Nomor HP</th>
                                    <th>Role</th>
                                    <th>Posisi</th>
                                    <th>Foto</th>
                                    <th>QR Code</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->id_employee }}</td>
                                    <td>{{ $employee->fullname }}</td>
                                    <td>{{ $employee->email ?? '-' }}</td>
                                    <td>{{ $employee->birth_place }}, {{ $employee->birth_date }}</td>
                                    <td>{{ $employee->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $employee->phone_number }}</td>
                                    <td>{{ $employee->role->role_name ?? '--' }}</td>
                                    <td>{{ $employee->position->name ?? '--' }}</td>
                                    <td>
                                        <div class="employee-photo">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}" width="40" height="40" style="object-fit: cover; border-radius: 50%;">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($employee->qr_code)
                                        <img src="{{ asset('storage/' . $employee->qr_code) }}" alt="QR Code" width="40">
                                        @else
                                        <span class="text-muted">No QR</span>
                                        @endif
                                    </td>
                                 <td>
                                    <div class="d-flex">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('employees.edit', $employee->id_employee) }}" class="btn btn-sm btn-warning me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <button class="btn btn-sm btn-danger delete-employee"
                                                data-employee-id="{{ $employee->id_employee }}"
                                                data-employee-name="{{ $employee->fullname }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password minimal 6 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                }
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Delete employee
        const deleteButtons = document.querySelectorAll('.delete-employee');
        if (deleteButtons.length > 0) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const employeeId = this.getAttribute('data-employee-id');
                    const employeeName = this.getAttribute('data-employee-name');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Data pegawai ${employeeName} akan dihapus secara permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create form for deletion
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/employees/${employeeId}`;
                            form.style.display = 'none';

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';

                            form.appendChild(csrfToken);
                            form.appendChild(method);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        }

        // Update change password form action
        var employeeId = "{{ Auth::guard('employee')->user()->id_employee }}"; // Ambil ID karyawan yang login
        if (employeeId) {
            var changePasswordForm = document.getElementById('changePasswordForm');
            if (changePasswordForm) {
                changePasswordForm.action = `/employees/${employeeId}/change-password`;
            }
        }
    });
    </script>
</body>
</html>
@endif
