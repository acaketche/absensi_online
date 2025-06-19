<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Siswa {{ $class->class_name }} | E-School</title>
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
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-weight: bold;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-weight: 500;
        }

        .btn-success {
            background: var(--success-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
        }

        .class-info {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .search-container {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 10px;
            color: #6c757d;
        }

        .search-input {
            padding-left: 35px;
        }

        /* Modal Styles */
        .modal-header {
            background: var(--primary-color);
            color: white;
        }

        .file-upload {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
        }
        .class-info {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .class-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .class-details {
            flex: 1;
        }

        .class-name {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .class-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }

        .class-meta-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .class-meta-item i {
            margin-right: 5px;
            color: var(--primary-color);
        }
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
            display: block;
        }

        /* Preview file styles */
        .file-preview {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .file-preview a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .file-preview a:hover {
            text-decoration: underline;
        }

        /* Mass upload specific styles */
        .mass-upload-info {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin-bottom: 20px;
        }

        .mass-upload-info ul {
            margin-bottom: 0;
            padding-left: 20px;
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
        @include('components.profiladmin')

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(isset($errors) && count($errors) > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Beberapa rapor gagal diupload:
            <ul class="mt-2 mb-0">
                @foreach($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Cari nama siswa atau NIPD..." class="form-control search-input" id="searchInput">
                </div>
            </div>

            <a href="{{ route('rapor.classes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <!-- Class Info -->
        <div class="class-info">
            <div class="class-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="class-details">
                <h3 class="class-name">{{ $class->class_name }}</h3>
                <div class="class-meta">
                    <div class="class-meta-item">
                        <i class="fas fa-user-tie"></i> {{ $class->employee->fullname ?? 'Belum ada wali kelas' }}
                    </div>
                    <div class="class-meta-item">
                        <i class="fas fa-calendar-alt"></i> {{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}
                    </div>
                    <div class="class-meta-item">
                        <i class="fas fa-users"></i> {{ $students->count() }} Siswa
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-2"></i> Daftar Siswa
                </div>
                <div>
                    <button id="massUploadBtn" class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#massUploadModal">
                        <i class="fas fa-file-upload me-1"></i> Upload Massal
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive scrollable-table">
                        <table class="table table-bordered table-hover" id="siswaTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="25%">Nama Siswa</th>
                                    <th width="15%">NIPD</th>
                                    <th width="10%">Jenis Kelamin</th>
                                    <th width="15%">Status Rapor</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $index => $student)
                                    @php
                                        // Ambil rapor pertama yang cocok dengan tahun ajaran dan semester yang sesuai
                                        $rapor = $student->rapor->first();
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($student->photo)
                                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                                        alt="Foto {{ $student->fullname }}"
                                                        class="student-avatar me-2">
                                                @else
                                                    <div class="student-avatar bg-secondary text-white d-flex align-items-center justify-content-center me-2">
                                                        {{ strtoupper(substr($student->fullname, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>{{ $student->fullname }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $student->id_student }}</td>
                                        <td>
                                            @if($student->gender == 'L')
                                                <span class="text-primary"><i class="fas fa-male me-1"></i> Laki-laki</span>
                                            @else
                                                <span class="text-danger"><i class="fas fa-female me-1"></i> Perempuan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rapor)
                                                <span class="badge bg-success ms-2">Sudah Ada</span>
                                            @else
                                                <span class="badge bg-secondary">Belum Ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rapor && $rapor->report_date)
                                                {{ \Carbon\Carbon::parse($rapor->report_date)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if($rapor)
                                                    @if($rapor->file_path)
                                                        <a href="{{ asset('storage/' . $rapor->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" class="btn btn-sm btn-warning edit-rapor-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editRaporModal"
                                                            data-rapor-id="{{ $rapor->id }}"
                                                            data-student-id="{{ $student->id_student }}"
                                                            data-student-name="{{ $student->fullname }}"
                                                            data-student-nipd="{{ $student->id_student }}"
                                                            data-report-date="{{ $rapor->report_date }}"
                                                            data-description="{{ $rapor->description }}"
                                                            data-file-path="{{ $rapor->file_path ? asset('storage/' . $rapor->file_path) : '' }}">
                                                        <i class="fas fa-edit me-1"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-danger delete-rapor"
                                                            data-rapor-id="{{ $rapor->id }}">
                                                        <i class="fas fa-trash me-1"></i>
                                                    </button>

                                                    <form class="delete-rapor-form" action="{{ route('rapor.destroy', $rapor->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success upload-rapor-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#uploadRaporModal"
                                                            data-student-id="{{ $student->id_student }}"
                                                            data-student-name="{{ $student->fullname }}"
                                                            data-student-nipd="{{ $student->id_student }}"
                                                            data-class-id="{{ $class->class_id }}">
                                                        <i class="fas fa-upload me-1"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i> Belum ada siswa yang terdaftar.
                    </div>
                @endif
            </div>

            <div class="card-footer bg-light d-flex justify-content-between">
                <div><strong>Total Siswa:</strong> {{ $students->count() }} orang</div>
            </div>
        </div>
    </main>
</div>

<!-- Upload Rapor Modal -->
<div class="modal fade" id="uploadRaporModal" tabindex="-1" aria-labelledby="uploadRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadRaporModalLabel">
                    <i class="fas fa-upload me-2"></i> Upload Rapor Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="uploadRaporForm" action="{{ route('rapor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Hidden Fields -->
                    <input type="hidden" name="id_student" id="formStudentId">
                    <input type="hidden" name="class_id" id="formClassId">
                    <input type="hidden" name="status_report" value="Sudah Ada">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="modalStudentName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIPD</label>
                            <input type="text" class="form-control" id="modalStudentNipd" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi atau catatan tambahan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="reportFile" class="form-label">File Rapor</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <input type="file" name="report_file" id="reportFile" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="mt-2">
                                <i class="fas fa-file-upload fa-2x text-primary mb-2"></i>
                                <p class="mb-1 fw-bold">Pilih file rapor untuk diunggah</p>
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG • Maks: 5MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Rapor Modal -->
<div class="modal fade" id="editRaporModal" tabindex="-1" aria-labelledby="editRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editRaporModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Rapor Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editRaporForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="editStudentName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIPD</label>
                            <input type="text" class="form-control" id="editStudentNipd" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" id="edit_report_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3" placeholder="Masukkan deskripsi atau catatan tambahan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_report_file" class="form-label">File Rapor</label>

                        <!-- Current file preview -->
                        <div id="currentFilePreview" class="file-preview mb-3">
                            <p class="mb-1">File saat ini:</p>
                            <a href="#" id="currentFileLink" target="_blank" class="d-flex align-items-center">
                                <i class="fas fa-file-pdf me-2"></i>
                                <span id="currentFileName">Tidak ada file</span>
                            </a>
                        </div>

                        <div class="border rounded p-3 text-center bg-light">
                            <input type="file" name="report_file" id="edit_report_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="mt-2">
                                <i class="fas fa-file-upload fa-2x text-primary mb-2"></i>
                                <p class="mb-1 fw-bold">Pilih file baru untuk mengganti rapor</p>
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG • Maks: 5MB</small>
                                <small class="d-block text-muted mt-1">Kosongkan jika tidak ingin mengubah file</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mass Upload Modal -->
<div class="modal fade" id="massUploadModal" tabindex="-1" aria-labelledby="massUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="massUploadModalLabel">
                    <i class="fas fa-file-upload me-2"></i> Upload Rapor Massal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="massUploadForm" action="{{ route('rapor.mass-upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="class_id" value="{{ $class->class_id }}">

                    <div class="mass-upload-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Petunjuk Upload Massal:</h6>
                        <ul>
                            <li>File harus dalam format ZIP</li>
                            <li>Maksimal ukuran file 50MB</li>
                            <li>Nama file harus sesuai dengan NIPD siswa (contoh: 12345.pdf)</li>
                            <li>Format file yang didukung: PDF, JPG, JPEG, PNG</li>
                            <li>File akan otomatis dicocokkan dengan siswa berdasarkan NIPD</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="massReportFile" class="form-label">File ZIP Berisi Rapor</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <input type="file" name="mass_report_file" id="massReportFile" class="form-control" accept=".zip" required>
                            <div class="mt-2">
                                <i class="fas fa-file-archive fa-2x text-primary mb-2"></i>
                                <p class="mb-1 fw-bold">Pilih file ZIP yang berisi rapor siswa</p>
                                <small class="text-muted">Format: ZIP • Maks: 50MB • Nama file harus sesuai NIPD (contoh: 12345.pdf)</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mass_description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" id="mass_description" rows="3" placeholder="Masukkan deskripsi atau catatan tambahan untuk semua rapor..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Upload Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JSZip Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');

        tableRows.forEach(row => {
            const studentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const studentId = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            if (studentName.includes(searchValue) || studentId.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Upload Rapor Modal
    const uploadRaporBtns = document.querySelectorAll('.upload-rapor-btn');
    uploadRaporBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('formStudentId').value = this.getAttribute('data-student-id');
            document.getElementById('formClassId').value = this.getAttribute('data-class-id');
            document.getElementById('modalStudentName').value = this.getAttribute('data-student-name');
            document.getElementById('modalStudentNipd').value = this.getAttribute('data-student-nipd');

            // Set default date to today
            document.querySelector('#uploadRaporModal input[name="report_date"]').valueAsDate = new Date();
        });
    });

    // Edit Rapor Modal
    const editRaporBtns = document.querySelectorAll('.edit-rapor-btn');
    editRaporBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const raporId = this.getAttribute('data-rapor-id');
            const studentName = this.getAttribute('data-student-name');
            const studentNipd = this.getAttribute('data-student-nipd');
            const reportDate = this.getAttribute('data-report-date');
            const description = this.getAttribute('data-description');
            const filePath = this.getAttribute('data-file-path');

            // Set form action
            document.getElementById('editRaporForm').action = `/rapor/${raporId}`;

            // Set form values
            document.getElementById('editStudentName').value = studentName;
            document.getElementById('editStudentNipd').value = studentNipd;
            document.getElementById('edit_report_date').value = reportDate;
            document.getElementById('edit_description').value = description || '';

            // Set current file preview
            const currentFileLink = document.getElementById('currentFileLink');
            const currentFileName = document.getElementById('currentFileName');

            if (filePath) {
                currentFileLink.href = filePath;
                const fileName = filePath.split('/').pop();
                currentFileName.textContent = fileName;

                // Set appropriate icon based on file extension
                const fileExt = fileName.split('.').pop().toLowerCase();
                const fileIcon = document.querySelector('#currentFileLink i');

                if (fileExt === 'pdf') {
                    fileIcon.className = 'fas fa-file-pdf me-2';
                } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                    fileIcon.className = 'fas fa-file-image me-2';
                } else {
                    fileIcon.className = 'fas fa-file me-2';
                }

                document.getElementById('currentFilePreview').style.display = 'block';
            } else {
                document.getElementById('currentFilePreview').style.display = 'none';
            }
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-rapor').forEach(button => {
        button.addEventListener('click', function () {
            const raporId = this.dataset.raporId;
            const form = this.closest('td').querySelector('.delete-rapor-form');

            Swal.fire({
                title: 'Hapus Rapor?',
                text: "Data rapor yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // File upload validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = this.id === 'massReportFile' ? 50 * 1024 * 1024 : 5 * 1024 * 1024; // 50MB for mass upload, 5MB for single

            if (file && file.size > maxSize) {
                Swal.fire({
                    title: 'File Terlalu Besar',
                    text: `Ukuran file maksimal adalah ${maxSize/(1024*1024)}MB`,
                    icon: 'error'
                });
                this.value = '';
                return;
            }

            const validExtensions = this.id === 'massReportFile' ? ['zip'] : ['pdf', 'jpg', 'jpeg', 'png'];
            const fileExt = file.name.split('.').pop().toLowerCase();

            if (file && !validExtensions.includes(fileExt)) {
                Swal.fire({
                    title: 'Format File Tidak Valid',
                    text: this.id === 'massReportFile' ? 'Hanya file ZIP yang diperbolehkan untuk upload massal' : 'Hanya file PDF, JPG, JPEG, dan PNG yang diperbolehkan',
                    icon: 'error'
                });
                this.value = '';
            }
        });
    });

    // Set default date to today in mass upload modal
    document.getElementById('massUploadBtn').addEventListener('click', function() {
        document.querySelector('#massUploadModal input[name="report_date"]').valueAsDate = new Date();
    });
});
</script>
</body>
</html>
@endif
