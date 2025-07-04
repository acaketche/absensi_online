<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4267b2;
            --secondary-color: #6c757d;
        }

        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(66, 103, 178, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
        }

        .preview-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            margin-top: 10px;
        }

        #preview_photo {
            display: none;
        }

        .file-upload-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            position: relative;
        }

        .file-upload-container:hover {
            border-color: var(--primary-color);
            background-color: rgba(66, 103, 178, 0.05);
        }

        .file-input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            cursor: pointer;
            z-index: 10;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
@if(Auth::guard('employee')->check())
<div class="d-flex">
    @include('components.sidebar')

    <main class="flex-grow-1 p-4">
        @include('components.profiladmin')

        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Tambah Siswa Baru</h2>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </header>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-plus me-2"></i> Form Tambah Siswa
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Informasi Pribadi -->
                    <h5 class="section-title">Informasi Pribadi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">NIPD</label>
                            <input type="text" class="form-control" name="id_student" required>

                            <label class="form-label mt-3">Nama Lengkap</label>
                            <input type="text" class="form-control" name="fullname" required>

                            <label class="form-label mt-3">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class="input-group-text password-toggle" id="togglePassword">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="birth_place" required>

                            <label class="form-label mt-3">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="birth_date" required>

                            <label class="form-label mt-3">Jenis Kelamin</label>
                            <select class="form-control" name="gender" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Informasi Kontak -->
                    <h5 class="section-title">Informasi Kontak</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon Orang Tua</label>
                            <input type="text" class="form-control" name="parent_phonecell" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <select class="form-control" name="class_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Upload Dokumen -->
                  <!-- Upload Dokumen -->
<h5 class="section-title">Upload Dokumen</h5>
<div class="row">
    <div class="col-md-6">
        <label class="form-label">Foto Siswa</label>
        <input type="file" class="form-control" name="photo" accept="image/jpeg, image/png">
        <small class="text-muted">Format: JPG/PNG (Maks. 2MB)</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">QR Code (Opsional)</label>
        <input type="file" class="form-control" name="qr_code" accept="image/png">
        <small class="text-muted">Format: PNG (Maks. 2MB)</small>
    </div>
</div>
                    <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                    <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Add jQuery before custom scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Toggle password
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });

</script>

</body>
</html>
@endif
