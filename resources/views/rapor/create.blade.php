<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Rapor | E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            padding: 16px 20px;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary-color);
        }

        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .student-info {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .student-details {
            flex: 1;
        }

        .student-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .student-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }

        .student-meta-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .student-meta-item i {
            margin-right: 5px;
            color: var(--primary-color);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: translateX(-5px);
            color: var(--secondary-color);
        }

        .back-button i {
            margin-right: 8px;
        }

        .file-upload {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .file-upload input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .file-upload-text {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }

        .file-upload-hint {
            font-size: 14px;
            color: #888;
        }

        .file-preview {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .file-preview-icon {
            font-size: 24px;
            color: #e63946;
        }

        .file-preview-info {
            flex: 1;
        }

        .file-preview-name {
            font-weight: 500;
            margin-bottom: 3px;
            color: #333;
        }

        .file-preview-size {
            font-size: 12px;
            color: #888;
        }

        .file-preview-remove {
            color: #e63946;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-preview-remove:hover {
            transform: scale(1.2);
        }

        .alert {
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border: none;
        }

        .alert-danger {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--warning-color);
        }

        @media (max-width: 768px) {
            .student-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .student-photo {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
@if(Auth::guard('employee')->check())
<body>
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

        <!-- Back Button -->
        @if($student && $student->class)
            <a href="{{ route('rapor.students', $student->class->class_id) }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Siswa
            </a>
        @else
            <a href="{{ route('rapor.classes') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelas
            </a>
        @endif

        <!-- Header Section -->
        <div class="mb-4">
            <h2 class="fs-4 fw-bold mb-1">Upload Rapor</h2>
            <p class="text-muted mb-0">Upload file rapor untuk siswa</p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Student Info (if student is selected) -->
        @if($student)
        <div class="student-info">
            <div>
                @if($student->photo_path)
                    <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto {{ $student->name }}" class="student-photo">
                @else
                    <img src="{{ asset('images/default-avatar.jpg') }}" alt="Foto Default" class="student-photo">
                @endif
            </div>
            <div class="student-details">
                <h3 class="student-name">{{ $student->name }}</h3>
                <div class="student-meta">
                    <div class="student-meta-item">
                        <i class="fas fa-id-card"></i> NIS: {{ $student->nis }}
                    </div>
                    <div class="student-meta-item">
                        <i class="fas fa-id-badge"></i> NISN: {{ $student->nisn }}
                    </div>
                    <div class="student-meta-item">
                        <i class="fas fa-venus-mars"></i> {{ $student->gender }}
                    </div>
                    @if($student->class)
                    <div class="student-meta-item">
                        <i class="fas fa-chalkboard"></i> {{ $student->class->name }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Upload Form -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-upload me-2"></i> Form Upload Rapor
            </div>
            <div class="card-body">
                <form action="{{ route('rapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if(!$student)
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Pilih Siswa</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            @if($class)
                                @foreach($class->students as $s)
                                    <option value="{{ $s->id_student }}">{{ $s->name }} ({{ $s->nis }})</option>
                                @endforeach
                            @else
                                <option disabled>Kelas belum dipilih atau data tidak ditemukan.</option>
                            @endif
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="student_id" value="{{ $student->id_student }}">
                    @endif

                    <input type="hidden" name="class_id" value="{{ $class->class_id ?? '' }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="academic_year_id" name="academic_year_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="semester_id" class="form-label">Semester</label>
                            <select class="form-select" id="semester_id" name="semester_id" required>
                                <option value="">-- Pilih Semester --</option>
                                @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" id="report_date" name="report_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan deskripsi atau catatan tambahan..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">File Rapor (PDF)</label>
                        <div class="file-upload" id="fileUpload">
                            <input type="file" name="rapor_file" id="raporFile" accept=".pdf" required>
                            <div class="file-upload-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="file-upload-text">Klik atau seret file PDF ke sini</div>
                            <div class="file-upload-hint">Maksimal 10MB</div>
                        </div>
                        <div class="file-preview" id="filePreview" style="display: none;">
                            <div class="file-preview-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="file-preview-info">
                                <div class="file-preview-name" id="fileName"></div>
                                <div class="file-preview-size" id="fileSize"></div>
                            </div>
                            <button type="button" class="file-preview-remove" id="removeFile">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        @if($student && $student->class)
                            <a href="{{ route('rapor.students', $student->class->class_id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        @else
                            <a href="{{ route('rapor.classes') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('raporFile');
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');

    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];

            // Display file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Show preview, hide upload area
            filePreview.style.display = 'flex';
            fileUpload.style.borderColor = '#e0e0e0';
            fileUpload.style.backgroundColor = '#f8f9fa';
        }
    });

    // Handle file removal
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.style.display = 'none';
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUpload.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUpload.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUpload.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        fileUpload.style.borderColor = '#4361ee';
        fileUpload.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
    }

    function unhighlight() {
        fileUpload.style.borderColor = '#e0e0e0';
        fileUpload.style.backgroundColor = '#f8f9fa';
    }

    fileUpload.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;

            // Trigger change event
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }

    // Set default date to today
    const today = new Date();
    const formattedDate = today.toISOString().substr(0, 10);
    document.getElementById('report_date').value = formattedDate;
});
</script>
</body>
</html>
@endif
