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

        .btn-success {
            background: linear-gradient(135deg, #20bf55, #01baef);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #01baef, #20bf55);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(32, 191, 85, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #f72585);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #f72585, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(247, 37, 133, 0.2);
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

        .status-badge {
            padding: 8px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .status-badge i {
            margin-right: 5px;
        }

        .status-available {
            background-color: rgba(32, 191, 85, 0.1);
            color: #20bf55;
        }

        .status-unavailable {
            background-color: rgba(247, 37, 133, 0.1);
            color: #f72585;
        }

        /* Modal Styles */
        .modal-xl {
            max-width: 1140px;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-footer {
            border-top: none;
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

        .info-card {
            background-color: rgba(67, 97, 238, 0.05);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-card-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .info-card-title i {
            margin-right: 8px;
        }

        .info-card-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 0;
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
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: none;
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

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Cari nama siswa atau NIS/NISN..." class="form-control" id="searchInput">
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
                <div class="d-flex">
                    <input type="text" id="searchStudent" class="form-control form-control-sm me-2" placeholder="Cari siswa...">
                    <button class="btn btn-sm btn-light" id="printStudentList">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover m-0" id="siswaTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="25%">Nama Siswa</th>
                                    <th width="15%">NIS/NISN</th>
                                    <th width="10%">Jenis Kelamin</th>
                                    <th width="15%">Status Rapor</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}"
                                                    alt="Foto {{ $student->fullname }}"
                                                    class="rounded-circle student-avatar me-2" width="50" height="60">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white text-center me-2" >
                                                    {{ strtoupper(substr($student->fullname, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="student-name">{{ $student->fullname }}</div>
                                        </div>
                                    </td>
                                    <td class="student-id-table">{{ $student->id_student }}</td>
                                    <td>
                                        @if($student->gender == 'L')
                                            <span class="text-info"><i class="fas fa-male me-1"></i> Laki-laki</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-female me-1"></i> Perempuan</span>
                                        @endif
                                    </td>
                                                                    <td>
                                    @if($student->rapor && $student->rapor->count() > 0)
                                        @php
                                            $status = $student->rapor->first()->status_report;
                                        @endphp

                                        @if($status === 'Sudah Ada')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Sudah Ada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle me-1"></i> Belum Ada
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle me-1"></i> Belum Ada
                                        </span>
                                    @endif
                                </td>
                                    <td>
                                        @if($student->rapor && count($student->rapor) > 0)
                                            {{ \Carbon\Carbon::parse($student->rapor[0]->report_date)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if($student->rapor && count($student->rapor) > 0)
                                                <a href="{{ route('rapor.edit', $student->rapor[0]->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('rapor.destroy', $student->rapor[0]->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rapor ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-success upload-rapor-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadRaporModal"
                                                        data-student-id="{{ $student->id_student }}"
                                                        data-student-name="{{ $student->fullname }}"
                                                        data-student-nis="{{ $student->id_student }}"
                                                        data-student-gender="{{ $student->gender }}"
                                                        data-student-photo="{{ $student->photo ? asset('storage/' . $student->photo) : '' }}"
                                                        data-class-id="{{ $class->class_id }}"
                                                        data-class-name="{{ $class->class_name }}"
                                                        data-academic-year="{{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}"
                                                        data-academic-year-id="{{ $class->academic_year_id }}"
                                                        data-semester="{{ $student->semester->semester_name ?? 'Semester' }}"
                                                        data-semester-id="{{ $student->semester_id }}">
                                                    <i class="fas fa-upload"></i> Upload Rapor
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-user-graduate fa-2x mb-2 text-muted"></i>
                                            <h5>Belum Ada Siswa</h5>
                                            <p class="text-muted">Belum ada data siswa di kelas ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadRaporModalLabel">
                    <i class="fas fa-upload me-2"></i> Upload Rapor Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Student Information -->
                <div class="student-info">
                    <img src="/placeholder.svg" alt="Foto Siswa" class="student-photo" id="modalStudentPhoto">
                    <div class="student-details">
                        <h3 class="student-name" id="modalStudentName"></h3>
                        <div class="student-meta">
                            <div class="student-meta-item">
                                <i class="fas fa-id-card"></i> <span id="modalStudentNis"></span>
                            </div>
                            <div class="student-meta-item">
                                <i class="fas fa-users"></i> <span id="modalClassName"></span>
                            </div>
                            <div class="student-meta-item">
                                <i class="fas fa-calendar-alt"></i> <span id="modalAcademicYear"></span>
                            </div>
                            <div class="student-meta-item">
                                <i class="fas fa-book"></i> <span id="modalSemester"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-info-circle"></i> Informasi Upload
                    </div>
                    <p class="info-card-text">
                        Rapor akan diupload untuk siswa <strong id="infoStudentName"></strong> pada
                        tahun ajaran <strong id="infoAcademicYear"></strong> semester <strong id="infoSemester"></strong>.
                        Pastikan file yang diupload sudah benar.
                    </p>
                </div>

                <!-- Upload Form -->
                <form id="uploadRaporForm" action="{{ route('rapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden Fields -->
                    <input type="hidden" name="id_student" id="formStudentId">
                    <input type="hidden" name="academic_year_id" id="formAcademicYearId">
                    <input type="hidden" name="semester_id" id="formSemesterId">
                    <input type="hidden" name="class_id" id="formClassId">

                    <!-- Report Date -->
                    <div class="mb-3">
                        <label for="report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" id="report_date" name="report_date" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan deskripsi atau catatan tambahan..."></textarea>
                    </div>

                    <input type="hidden" name="status_report" value="{{ old('status_report') }}">

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="form-label">File Rapor</label>
                        <div class="file-upload" id="fileUpload">
                            <input type="file" name="report_file" id="reportFile" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-upload-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="file-upload-text">Klik atau seret file ke sini</div>
                            <div class="file-upload-hint">Format: PDF, JPG, JPEG, PNG (Maks. 5MB)</div>
                        </div>
                        <div class="file-preview" id="filePreview">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="submitRaporBtn">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchStudent').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');
        let visibleRows = 0;

        tableRows.forEach(row => {
            const studentName = row.querySelector('.student-name')?.textContent.toLowerCase() || '';
            const studentId = row.querySelector('.student-id-table')?.textContent.toLowerCase() || '';
            const gender = row.cells[3]?.textContent.toLowerCase() || '';

            if (studentName.includes(searchValue) || studentId.includes(searchValue) || gender.includes(searchValue)) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Upload Rapor Modal
    const uploadRaporModal = document.getElementById('uploadRaporModal');
    const uploadRaporBtns = document.querySelectorAll('.upload-rapor-btn');

    uploadRaporBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Get student data from button attributes
            const studentId = this.getAttribute('data-student-id');
            const studentName = this.getAttribute('data-student-name');
            const studentNis = this.getAttribute('data-student-nis');
            const studentNisn = this.getAttribute('data-student-nisn');
            const studentPhoto = this.getAttribute('data-student-photo');
            const classId = this.getAttribute('data-class-id');
            const className = this.getAttribute('data-class-name');
            const academicYear = this.getAttribute('data-academic-year');
            const academicYearId = this.getAttribute('data-academic-year-id');
            const semester = this.getAttribute('data-semester');
            const semesterId = this.getAttribute('data-semester-id');

            // Set student photo
            const photoElement = document.getElementById('modalStudentPhoto');
            if (studentPhoto) {
                photoElement.src = studentPhoto;
            } else {
                photoElement.src = "{{ asset('images/default-student.png') }}";
            }

            // Set student information in modal
            document.getElementById('modalStudentName').textContent = studentName;
            document.getElementById('modalStudentNis').textContent = studentNis + (studentNisn ? ' / ' + studentNisn : '');
            document.getElementById('modalClassName').textContent = className;
            document.getElementById('modalAcademicYear').textContent = academicYear;
            document.getElementById('modalSemester').textContent = semester;

            // Set info card text
            document.getElementById('infoStudentName').textContent = studentName;
            document.getElementById('infoAcademicYear').textContent = academicYear;
            document.getElementById('infoSemester').textContent = semester;

            // Set form hidden fields
            document.getElementById('formStudentId').value = studentId;
            document.getElementById('formAcademicYearId').value = academicYearId;
            document.getElementById('formSemesterId').value = semesterId;
            document.getElementById('formClassId').value = classId;

            // Set default date to today
            const today = new Date();
            const formattedDate = today.toISOString().substr(0, 10);
            document.getElementById('report_date').value = formattedDate;
        });
    });

    // File Upload Handling
    const fileInput = document.getElementById('reportFile');
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');

    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];

            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                this.value = '';
                return;
            }

            // Check file type
            const fileType = file.type.toLowerCase();
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

            if (!validTypes.includes(fileType)) {
                alert('Format file tidak valid. Gunakan PDF, JPG, JPEG, atau PNG.');
                this.value = '';
                return;
            }

            // Update icon based on file type
            const fileIcon = document.querySelector('.file-preview-icon i');
            if (fileType === 'application/pdf') {
                fileIcon.className = 'fas fa-file-pdf';
            } else {
                fileIcon.className = 'fas fa-file-image';
            }

            // Display file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Show preview
            filePreview.style.display = 'flex';
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

    // Submit form when the submit button is clicked
    document.getElementById('submitRaporBtn').addEventListener('click', function() {
        // Validate form
        const form = document.getElementById('uploadRaporForm');
        if (!form.checkValidity()) {
            // Trigger browser's native validation
            const submitBtn = document.createElement('button');
            submitBtn.type = 'submit';
            form.appendChild(submitBtn);
            submitBtn.click();
            form.removeChild(submitBtn);
            return;
        }

        // Submit the form
        form.submit();
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
</body>
</html>
@endif
