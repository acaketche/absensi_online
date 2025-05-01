<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-School - Edit Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        .employee-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #E6E1F9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .employee-photo i {
            font-size: 40px;
            color: #7950F2;
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Header with Admin Profile -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold mb-0"></h2>
                <div class="dropdown">
                    <div class="admin-profile d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex flex-column text-end me-2">
                            <span class="admin-name">{{ Auth::guard('employee')->user()->fullname ?? 'Admin User' }}</span>
                            <small class="admin-role text-muted">
                                {{ Auth::guard('employee')->user()->role->role_name ?? 'Administrator' }}
                            </small>
                        </div>
                        <div class="admin-avatar">
                            <img src="{{ Auth::guard('employee')->user()->photo ? asset('storage/' . Auth::guard('employee')->user()->photo) : 'https://via.placeholder.com/150' }}"
                                alt="Admin Profile" class="w-100 h-100 object-fit-cover">
                        </div>
                        <i class="fas fa-chevron-down ms-2 text-muted"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-key"></i> Ubah Password</a></li>
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold">Edit Pegawai</h2>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user-edit me-2"></i> Form Edit Pegawai
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.update', $employee->id_employee) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_employee" class="form-label">ID Pegawai</label>
                                <input type="text" class="form-control" id="id_employee" name="id_employee" value="{{ $employee->id_employee }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" value="{{ old('fullname', $employee->fullname) }}" required>
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_place" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place', $employee->birth_place) }}" required>
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $employee->birth_date) }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" {{ old('gender', $employee->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $employee->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Nomor HP</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $employee->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Isi jika ingin mengubah password">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label">Role</label>
                                <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position_id" class="form-label">Posisi</label>
                                <select class="form-control @error('position_id') is-invalid @enderror" id="position_id" name="position_id">
                                    <option value="">-- Pilih Posisi --</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Foto</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Upload foto dalam format JPEG, PNG, atau JPG. Maksimal 2MB.</small>
                                @if($employee->photo)
                                <div class="mt-2">
                                    <small class="text-muted">Foto saat ini:</small>
                                    <div class="mt-1">
                                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto Pegawai" width="100" height="100" style="object-fit: cover; border-radius: 5px;">
                                    </div>
                                </div>
                                @endif
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="qr_code" class="form-label">QR Code</label>
                                <input type="file" class="form-control @error('qr_code') is-invalid @enderror" id="qr_code" name="qr_code" accept="image/png">
                                <small class="form-text text-muted">Upload QR code dalam format PNG. Maksimal 1MB.</small>
                                @if($employee->qr_code)
                                <div class="mt-2">
                                    <small class="text-muted">QR Code saat ini:</small>
                                    <div class="mt-1">
                                        <img src="{{ asset('storage/' . $employee->qr_code) }}" alt="QR Code" width="100">
                                    </div>
                                </div>
                                @endif
                                @error('qr_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
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
    });
    </script>
</body>
</html>
