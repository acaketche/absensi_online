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
            --primary-color: #4266B9;
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
         .scrollable-table {
        max-height: 600px; /* Ubah ini sesuai kebutuhan, misal: 800px untuk lebih panjang */
        overflow-y: auto;
        border: 1px solid #dee2e6;
    }

    /* Tambahan opsional untuk mempercantik scroll */
    .scrollable-table::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-table::-webkit-scrollbar-thumb {
        background-color: #6c757d;
        border-radius: 4px;
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
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body d-flex flex-wrap align-items-center gap-4">
        <!-- Icon -->
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
            <i class="fas fa-chalkboard fa-2x"></i>
        </div>

        <!-- Detail Info -->
        <div class="flex-grow-1">
            <h4 class="mb-1 fw-semibold text-primary">{{ $class->class_name }}</h4>
            <div class="d-flex flex-wrap gap-3 text-muted small">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-tie text-dark"></i>
                    {{ $class->employee->fullname ?? 'Belum ada wali kelas' }}
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-calendar-alt text-dark"></i>
                    {{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-users text-dark"></i>
                    {{ $students->count() }} Siswa
                </div>
            </div>
        </div>

        <!-- Button Upload -->
        <div>
            <button id="massUploadBtn"
                    class="btn btn-outline-primary shadow-sm d-flex align-items-center gap-2"
                    data-bs-toggle="modal"
                    data-bs-target="#massUploadModal">
                <i class="fas fa-file-upload"></i>
                Upload Massal
            </button>
        </div>
    </div>
</div>

        <!-- Data Siswa -->
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-2"></i> Daftar Siswa
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
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-gradient text-white" style="background: linear-gradient(to right, #4e73df, #224abe);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-upload me-2"></i> Upload Rapor Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadRaporForm" action="{{ route('rapor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-3">
                    <input type="hidden" name="id_student" id="formStudentId">
                    <input type="hidden" name="class_id" id="formClassId">
                    <input type="hidden" name="status_report" value="Sudah Ada">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Siswa</label>
                            <input type="text" class="form-control" id="modalStudentName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIPD</label>
                            <input type="text" class="form-control" id="modalStudentNipd" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="form-label fw-semibold">File Rapor</label>
                        <div class="upload-box p-4 border rounded bg-light">
                            <input type="file" name="report_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mt-3 mb-2"></i>
                            <p class="mb-0">Pilih file rapor (PDF/JPG/PNG, max 5MB)</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
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
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-gradient text-white" style="background: linear-gradient(to right, #36b9cc, #1cc88a);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2"></i> Edit Rapor Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRaporForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Siswa</label>
                            <input type="text" class="form-control" id="editStudentName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIPD</label>
                            <input type="text" class="form-control" id="editStudentNipd" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" id="edit_report_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Rapor</label>
                        <div class="file-preview mb-2">
                            <p class="text-muted mb-1">File saat ini:</p>
                            <a href="#" target="_blank" id="currentFileLink">
                                <i class="fas fa-file-pdf me-2"></i> <span id="currentFileName">Tidak ada file</span>
                            </a>
                        </div>
                        <div class="upload-box p-4 border rounded bg-light text-center">
                            <input type="file" name="report_file" id="edit_report_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <i class="fas fa-cloud-upload-alt fa-2x text-success mt-3 mb-2"></i>
                            <p class="mb-0">Pilih file baru (opsional)</p>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
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
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-gradient text-white" style="background: linear-gradient(to right, #f6c23e, #e0a800);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-archive me-2"></i> Upload Rapor Massal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('rapor.upload-massal', ['classId' => $class->class_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->class_id }}">

                <div class="modal-body px-4 py-3">
                    <div class="alert alert-warning small mb-4">
                        <strong><i class="fas fa-info-circle me-1"></i> Petunjuk:</strong>
                        <ul class="mb-0 ps-3">
                            <li>Unggah 1 file <strong>ZIP</strong> berisi file rapor siswa.</li>
                            <li>Nama file di dalam ZIP: <code>NIPD_Nama.pdf</code></li>
                            <li>Ekstensi yang didukung: <code>.pdf, .jpg, .jpeg, .png</code></li>
                            <li>Ukuran maksimal ZIP: <strong>50MB</strong></li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">File ZIP</label>
                        <input type="file" id="massReportFile" name="rapor_zip" class="form-control" accept=".zip" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Umum</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Contoh: Rapor semester genap tahun 2025..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-upload me-1"></i> Upload Massal
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
            input.addEventListener('change', function () {
                const file = this.files[0];

                // Gunakan ID untuk membedakan antara upload massal dan upload biasa
                const isMassUpload = this.id === 'massReportFile';
                const maxSize = isMassUpload ? 50 * 1024 * 1024 : 5 * 1024 * 1024;

                if (file && file.size > maxSize) {
                    Swal.fire({
                        title: 'File Terlalu Besar',
                        text: `Ukuran file maksimal adalah ${maxSize / (1024 * 1024)}MB`,
                        icon: 'error'
                    });
                    this.value = '';
                    return;
                }

                const validExtensions = isMassUpload ? ['zip'] : ['pdf', 'jpg', 'jpeg', 'png'];
                const fileExt = file.name.split('.').pop().toLowerCase();

                if (file && !validExtensions.includes(fileExt)) {
                    Swal.fire({
                        title: 'Format File Tidak Valid',
                        text: isMassUpload
                            ? 'Hanya file ZIP yang diperbolehkan untuk upload massal'
                            : 'Hanya file PDF, JPG, JPEG, dan PNG yang diperbolehkan',
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
