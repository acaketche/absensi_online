<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Rapor Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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

        .btn-primary, .bg-primary {
            background-color: #4266B9 !important;
            border-color: #4266B9 !important;
        }

        .btn-primary:hover {
            background-color: #365796 !important;
            border-color: #365796 !important;
        }

        .text-primary {
            color: #4266B9 !important;
        }
    </style>
</head>
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Upload Rapor Siswa</h2>
            <a href="{{ route('Rapor.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- @if ($errors->any()) -->
        <div class="alert alert-danger">
            <ul class="mb-0">
                <!-- @foreach ($errors->all() as $error) -->
                <li><!-- {{ $error }} --></li>
                <!-- @endforeach -->
            </ul>
        </div>
        <!-- @endif -->

        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-upload me-2"></i> Form Upload Rapor
            </div>
            <div class="card-body">
                <form action="{{ route('Rapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                            <select class="form-select @error('academic_year_id') is-invalid @enderror" id="academic_year_id" name="academic_year_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <!-- @foreach($academicYears as $year) -->
                                <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    <!-- {{ $year->name }} -->
                                </option>
                                <!-- @endforeach -->
                            </select>
                            <!-- @error('academic_year_id') -->
                            <div class="invalid-feedback"><!-- {{ $message }} --></div>
                            <!-- @enderror -->
                        </div>
                        <div class="col-md-6">
                            <label for="semester_id" class="form-label">Semester</label>
                            <select class="form-select @error('semester_id') is-invalid @enderror" id="semester_id" name="semester_id" required>
                                <option value="">-- Pilih Semester --</option>
                                <!-- @foreach($semesters as $semester) -->
                                <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                    <!-- {{ $semester->name }} -->
                                </option>
                                <!-- @endforeach -->
                            </select>
                            <!-- @error('semester_id') -->
                            <div class="invalid-feedback"><!-- {{ $message }} --></div>
                            <!-- @enderror -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="class_id" class="form-label">Kelas</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                <!-- @foreach($classes as $class) -->
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    <!-- {{ $class->name }} -->
                                </option>
                                <!-- @endforeach -->
                            </select>
                            <!-- @error('class_id') -->
                            <div class="invalid-feedback"><!-- {{ $message }} --></div>
                            <!-- @enderror -->
                        </div>
                        <div class="col-md-6">
                            <label for="id_student" class="form-label">Siswa</label>
                            <select class="form-select @error('id_student') is-invalid @enderror" id="id_student" name="id_student" required>
                                <option value="">-- Pilih Siswa --</option>
                                <!-- @foreach($students as $student) -->
                                <option value="{{ $student->id }}" {{ old('id_student', request()->get('student_id')) == $student->id ? 'selected' : '' }}>
                                    <!-- {{ $student->name }} ({{ $student->nis }}) -->
                                </option>
                                <!-- @endforeach -->
                            </select>
                            <!-- @error('id_student') -->
                            <div class="invalid-feedback"><!-- {{ $message }} --></div>
                            <!-- @enderror -->
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control @error('report_date') is-invalid @enderror" id="report_date" name="report_date" value="{{ old('report_date') }}" required>
                        <!-- @error('report_date') -->
                        <div class="invalid-feedback"><!-- {{ $message }} --></div>
                        <!-- @enderror -->
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        <!-- @error('description') -->
                        <div class="invalid-feedback"><!-- {{ $message }} --></div>
                        <!-- @enderror -->
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Rapor (PDF)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf" required>
                        <div class="form-text">Upload file dalam format PDF. Maksimal ukuran file 2MB.</div>
                        <!-- @error('file') -->
                        <div class="invalid-feedback"><!-- {{ $message }} --></div>
                        <!-- @enderror -->
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Data Rapor
                        </button>
                        <a href="{{ route('Rapor.index') }}" class="btn btn-secondary">
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
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('student_id');

    // If student_id is in URL, select that student
    if (studentId) {
        const studentSelect = document.getElementById('id_student');
        if (studentSelect) {
            const option = studentSelect.querySelector(`option[value="${studentId}"]`);
            if (option) {
                option.selected = true;
            }
        }
    }

    // Fungsi untuk mengubah daftar siswa berdasarkan kelas yang dipilih
    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('id_student');

    if (classSelect && studentSelect) {
        classSelect.addEventListener('change', function() {
            const classId = this.value;

            // In a real implementation, this would fetch students by class via AJAX
            // For now, we'll just simulate the behavior

            // Reset student dropdown
            studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';

            // Fetch students for the selected class (this would be an AJAX call)
            if (classId) {
                fetch(`/api/students?class_id=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.name} (${student.nis})`;
                            studentSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching students:', error);
                    });
            }
        });
    }
});
</script>
</body>
</html>
