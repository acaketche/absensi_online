<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - E-School</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4267b2;
            --secondary-color: #3a5795;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-color);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(66, 103, 178, 0.15);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            background-color: #f8fafc;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .preview-container:hover {
            border-color: var(--accent-color);
        }

        .preview-image-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .preview-image {
            max-height: 150px;
            max-width: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .section-title {
            position: relative;
            padding-bottom: 0.5rem;
            margin: 1.5rem 0;
            color: var(--primary-color);
            font-weight: 600;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            border-radius: 3px;
        }

        .input-group-text {
            background-color: #f1f3f9;
            border-radius: 8px 0 0 8px;
        }

        .password-toggle {
            cursor: pointer;
            border-radius: 0 8px 8px 0;
        }

        .file-upload-label {
            display: block;
            padding: 0.5rem 1rem;
            background-color: #f1f3f9;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .file-upload-label:hover {
            background-color: #e2e6ee;
        }

        .file-upload-icon {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .media-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .media-col {
            flex: 1;
            min-width: 250px;
        }

        .alert-notification {
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-light">
@if(Auth::guard('employee')->check())
<div class="d-flex">
    @include('components.sidebar')

    <main class="flex-grow-1 p-4">
        @include('components.profiladmin')

        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fs-4 fw-bold mb-1" style="color: var(--primary-color);">
                        <i class="fas fa-user-plus me-2"></i> Tambah Siswa Baru
                    </h2>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-notification alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-pen me-2"></i> Formulir Tambah Siswa
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Pribadi -->
                        <h5 class="section-title">Informasi Pribadi</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIPD</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" name="id_student" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="fullname" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button type="button" class="btn btn-outline-secondary password-toggle" id="togglePassword">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimal 8 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" name="birth_place" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" name="birth_date" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <select class="form-select" name="gender" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kontak -->
                        <h5 class="section-title">Informasi Kontak</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon Orang Tua</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control" name="parent_phonecell" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                        <select class="form-select" name="class_id" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Dokumen -->
                        <h5 class="section-title">Upload Dokumen</h5>
                        <div class="media-row">
                            <div class="media-col">
                                <label class="form-label">Foto Siswa</label>
                                <div class="preview-container">
                                    <div class="preview-image-container">
                                        <img id="preview_photo" class="preview-image">
                                    </div>
                                    <label for="photo" class="file-upload-label">
                                        <i class="fas fa-camera file-upload-icon"></i>
                                        Pilih Foto
                                    </label>
                                    <input type="file" name="photo" id="photo" class="d-none" accept="image/jpeg, image/png">
                                    <small class="text-muted d-block mt-2">Format: JPG/PNG (Maks. 2MB)</small>
                                </div>
                            </div>

                            <div class="media-col">
                                <label class="form-label">QR Code (Opsional)</label>
                                <div class="preview-container">
                                    <div class="preview-image-container">
                                        <img id="preview_qr"class="preview-image">
                                    </div>
                                    <label for="qr_code" class="file-upload-label">
                                        <i class="fas fa-qrcode file-upload-icon"></i>
                                        Pilih QR Code
                                    </label>
                                    <input type="file" name="qr_code" id="qr_code" class="d-none" accept="image/png">
                                    <small class="text-muted d-block mt-2">Format: PNG (Maks. 2MB)</small>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                        <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button type="reset" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap & JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    // Preview Foto
    document.getElementById('photo').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview_photo');
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran file terlalu besar',
                    text: 'Maksimal ukuran file adalah 2MB',
                });
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview QR Code
    document.getElementById('qr_code').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview_qr');
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran file terlalu besar',
                    text: 'Maksimal ukuran file adalah 2MB',
                });
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // File upload label display
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const label = this.previousElementSibling;
            if (this.files.length > 0) {
                label.innerHTML = `<i class="${label.querySelector('i').className}"></i> ${this.files[0].name}`;
            } else {
                label.innerHTML = `<i class="${label.querySelector('i').className}"></i> Pilih File`;
            }
        });
    });
</script>

</body>
</html>
@endif
