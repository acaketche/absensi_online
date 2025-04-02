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
    min-width: 120%; /* Pastikan tabel lebih lebar dari kontainer */
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
                <h2 class="fs-4 fw-bold">Data Pegawai</h2>
                <div class="d-flex align-items-center">
                    <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">+ Tambah Pegawai</button>
                </div>
            </header>
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
                                    <td>{{ $employee->role->role_name ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="employee-photo">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}" width="50">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($employee->qr_code)
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $employee->qr_code }}" alt="QR Code"width="50">
                                    @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <!-- Tombol Detail -->
                                            <button class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#employeeDetailModal{{ $employee->id_employee }}">
                                                <i class="fas fa-eye me-1"></i>
                                            </button>

                                            <!-- Tombol Edit -->
                                            <button class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editEmployeeModal"
                                                    data-employee-id="{{ $employee->id_employee }}">
                                                <i class="fas fa-edit me-1"></i>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-sm btn-danger delete-employee"
                                                    data-employee-id="{{ $employee->id_employee }}">
                                                <i class="fas fa-trash me-1"></i>
                                            </button>
                                        </div>

                                        <form class="delete-employee-form" action="{{ route('employees.destroy', $employee->id_employee) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    <!-- Modal Tambah Pegawai -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="id_employee" class="form-label">ID Pegawai</label>
                            <input type="text" class="form-control" id="id_employee" name="id_employee" required>
                        </div>
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="birth_place" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="birth_place" name="birth_place" required>
                        </div>
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles ?? [] as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                        </div>
                        <div class="mb-3">
                            <label for="generate_qr" class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="generate_qr" name="generate_qr" checked>
                                Generate QR Code
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 <!-- Modal Detail Pegawai -->
 <div class="modal fade" id="employeeDetailModal{{ $employee->id_employee }}" tabindex="-1" aria-labelledby="employeeDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeDetailModalLabel">Detail Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID Pegawai:</strong> {{ $employee->id_employee }}</p>
                <p><strong>Nama:</strong> {{ $employee->fullname }}</p>
                <p><strong>Email:</strong> {{ $employee->email }}</p>
                <p><strong>Tempat, Tanggal Lahir:</strong> {{ $employee->birth_place }}, {{ $employee->birth_date }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $employee->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                <p><strong>Nomor HP:</strong> {{ $employee->phone_number }}</p>
                <p><strong>Role:</strong> {{ $employee->role->role_name }}</p>
                @if($employee->photo)
                    <p><strong>Foto:</strong></p>
                    <img src="{{ asset('storage/employees/' . $employee->photo) }}" alt="Foto Pegawai" class="img-fluid">
                @endif
            </div>
        </div>
    </div>
</div>
    <!-- Modal Edit Pegawai -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_id_employee" class="form-label">ID Pegawai</label>
                            <input type="text" class="form-control" id="edit_id_employee" name="id_employee" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_fullname" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="edit_fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="edit_birth_place" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="edit_birth_place" name="birth_place" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="edit_birth_date" name="birth_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="edit_gender" name="gender" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone_number" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_role_id" class="form-label">Role</label>
                            <select class="form-control" id="edit_role_id" name="role_id" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles ?? [] as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="edit_password" name="password" placeholder="Isi jika ingin mengubah password">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="edit_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_photo" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="edit_photo" name="photo">
                            <div class="mt-2" id="current_photo_container">
                                <small class="text-muted">Foto saat ini:</small>
                                <div class="employee-photo mt-1">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="regenerate_qr" class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="regenerate_qr" name="regenerate_qr">
                                Generate Ulang QR Code
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Pegawai -->
    <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEmployeeModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pegawai ini?</p>
                    <p class="fw-bold" id="delete_employee_name">Nama Pegawai</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteEmployeeForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">QR Code Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrCodeContainer" class="mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=employee_id" alt="QR Code" class="img-fluid">
                    </div>
                    <p class="mb-1" id="qrEmployeeId">ID: <span>EMP001</span></p>
                    <p id="qrEmployeeName">Nama: <span>John Doe</span></p>
                    <button class="btn btn-sm btn-primary" onclick="downloadQRCode()">
                        <i class="fas fa-download me-1"></i> Download
                    </button>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div class="rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; background-color: #E6E1F9; overflow: hidden;">
                                <img src="{{ Auth::guard('employee')->user()->photo ?? 'https://via.placeholder.com/100' }}" alt="Profile Photo" class="w-100 h-100 object-fit-cover" id="profilePreview">
                            </div>
                            <label for="profilePhoto" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer">
                                <i class="fas fa-camera"></i>
                                <input type="file" id="profilePhoto" name="photo" class="d-none" onchange="previewImage(this)">
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ Auth::guard('employee')->user()->fullname }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::guard('employee')->user()->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role ID</label>
                        <input type="text" class="form-control" id="role_id" name="role_id" value="{{ Auth::guard('employee')->user()->role_id }}" readonly>
                    </div>
                    <div class="text-center mb-3">
                        <label class="form-label">QR Code</label>
                        <div class="border p-2 d-inline-block">
                            <img src="{{ Auth::guard('employee')->user()->qr_code ?? 'https://via.placeholder.com/100' }}" alt="QR Code" id="qrCodePreview" class="w-100">
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        <small class="text-muted">Password minimal 8 karakter</small>
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

<!-- SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var employeeId = "{{ Auth::guard('employee')->user()->id_employee }}"; // Ambil ID karyawan yang login

        if (!employeeId) {
            console.error("Employee ID tidak ditemukan!");
            return;
        }

        // Update form action untuk Edit Profil
        var editProfileForm = document.getElementById('editProfileForm');
        if (editProfileForm) {
            editProfileForm.action = `/employees/${employeeId}`;
        }

        // Update form action untuk Ubah Password
        var changePasswordForm = document.getElementById('changePasswordForm');
        if (changePasswordForm) {
            changePasswordForm.action = `/employees/${employeeId}/change-password`;
        }

        // Script untuk menampilkan preview gambar
        window.previewImage = function (input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };

        // Script untuk toggle visibility password
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                var target = document.getElementById(this.getAttribute('data-target'));
                if (target.type === 'password') {
                    target.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                } else {
                    target.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                }
            });
        });

        // Script untuk edit employee
        const editEmployeeModal = document.getElementById('editEmployeeModal');
        if (editEmployeeModal) {
            editEmployeeModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-employee-id');

                // Set form action
                document.getElementById('editEmployeeForm').action = `/employees/${id}`;

                // Fetch the employee data via AJAX
                fetch(`/employees/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_id_employee').value = data.id_employee;
                        document.getElementById('edit_fullname').value = data.fullname;
                        document.getElementById('edit_email').value = data.email || '';
                        document.getElementById('edit_birth_place').value = data.birth_place;
                        document.getElementById('edit_birth_date').value = data.birth_date;
                        document.getElementById('edit_gender').value = data.gender;
                        document.getElementById('edit_phone_number').value = data.phone_number;
                        document.getElementById('edit_role_id').value = data.role_id;

                        // Update current photo if exists
                        if (data.photo) {
                            const photoContainer = document.getElementById('current_photo_container');
                            photoContainer.innerHTML = `
                                <small class="text-muted">Foto saat ini:</small>
                                <div class="employee-photo mt-1">
                                    <img src="/storage/${data.photo}" width="50" height="50" style="object-fit: cover;">
                                </div>
                            `;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }

        // Script untuk delete employee
        const deleteEmployeeModal = document.getElementById('deleteEmployeeModal');
        if (deleteEmployeeModal) {
            deleteEmployeeModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-employee-id');

                // Set form action
                document.getElementById('deleteEmployeeForm').action = `/employees/${id}`;

                // Get employee name for confirmation message
                fetch(`/employees/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('delete_employee_name').textContent = data.fullname;
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-employee").forEach(button => {
            button.addEventListener("click", function () {
                const employeeId = this.getAttribute("data-employee-id");
                const form = document.querySelector(`.delete-employee-form[action$='${employeeId}']`);

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data pegawai akan dihapus secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
</main>
</div>
</body>
</html>
@endif
