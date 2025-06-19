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
</head>
@if (Auth::guard('employee')->check())

    <body class="bg-light">
        <div class="d-flex">
            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content -->
            <main class="flex-grow-1 p-4">
                <!-- Header dengan Profil Admin -->
                @include('components.profiladmin')
                <!-- Bagian Header -->
                <header class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fs-4 fw-bold mb-0">Data Siswa</h2>
                </header>

                <!-- Card Filter -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Filter Data Siswa</span>
                        <i class="fas fa-filter"></i>
                    </div>
                    <div class="card-body">
                        <!-- Form Filter -->
                        <form action="{{ route('students.index') }}" method="GET">
                            <div class="row g-3">
                                <!-- Tahun Ajaran -->
                                <div class="col-md-4">
                                    <label for="academicYearSelect" class="form-label fw-bold">Tahun Ajaran</label>
                                    <select id="academicYearSelect" name="academic_year_id" class="form-select">
                                        <option value="">-- Pilih Tahun --</option>
                                        @foreach ($academicYears as $tahun)
                                            <option value="{{ $tahun->id }}"
                                                {{ request('academic_year_id') == $tahun->id ? 'selected' : '' }}>
                                                {{ $tahun->year_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Semester -->
                                <div class="col-md-4">
                                    <label for="semesterSelect" class="form-label fw-bold">Semester</label>
                                    <select id="semesterSelect" name="semester_id" class="form-select">
                                        <option value="">-- Pilih Semester --</option>
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}"
                                                {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                                {{ $semester->semester_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Kelas -->
                                <div class="col-md-4">
                                    <label for="classSelect" class="form-label fw-bold">Kelas</label>
                                    <select name="class_id" id="classSelect" class="form-select">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->class_id }}"
                                                {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Tombol Filter -->
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-sync-alt me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Import Data -->
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <span>Import Data Siswa</span>
                        <i class="fas fa-file-import"></i>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- Excel File Input (now optional) -->
                                <div class="col-md-12">
                                    <label for="file" class="form-label">File Excel (Opsional)</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                    <small class="text-muted">Format file harus Excel (.xlsx, .xls). Maksimal ukuran file: 2MB.</small>
                                </div>

                                <!-- Photos Input -->
                                <div class="col-md-6">
                                    <label for="photos" class="form-label">Foto Siswa (jpg/png, multiple)</label>
                                    <input type="file" name="photos[]" id="photos" class="form-control" multiple>
                                    <small class="text-muted">Nama file harus sama dengan NIPD siswa</small>
                                </div>

                                <!-- QR Codes Input -->
                                <div class="col-md-6">
                                    <label for="qr_codes" class="form-label">QR Code Siswa (jpg/png/pdf, multiple)</label>
                                    <input type="file" name="qr_codes[]" id="qr_codes" class="form-control" multiple>
                                    <small class="text-muted">Nama file harus sama dengan NIPD siswa</small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-upload me-1"></i> Import Data
                                    </button>
                                </div>

                                <div class="col-md-4 text-end">
                                    <a href="{{ route('students.template.page') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-file-excel me-1"></i> Download Template
                                    </a>
                                </div>

                                <!-- Notifications -->
                                <div class="col-12">
                                   @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if(session('photo_success'))
                                        <div class="alert alert-success">{{ session('photo_success') }}</div>
                                    @endif

                                    @if(session('qr_success'))
                                        <div class="alert alert-success">{{ session('qr_success') }}</div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    @if(session('errors'))
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach((array) session('errors') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Data Siswa -->
              <div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span>Daftar Siswa</span>

        <div class="d-flex align-items-center gap-2">
            <!-- Form Pencarian -->
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari..." style="width: 200px;">

            <!-- Tombol Tambah Siswa -->
            <a href="{{ route('students.create') }}" class="btn btn-sm btn-light text-primary">
                <i class="fas fa-plus me-1"></i> Tambah Siswa
            </a>
        </div>
    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>NO</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Jenis Kelamin</th>
                                        <th>No Orang Tua</th>
                                        <th>Kelas</th>
                                        <th>Foto</th>
                                        <th>QrCode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentClass = null;
                                        $rowNumber = 1;
                                    @endphp

                                    @foreach ($students as $student)
                                        @if ($student->class && $student->class->class_name != $currentClass)
                                            <tr class="table-secondary">
                                                <td colspan="11" class="fw-bold">
                                                    Kelas: {{ $student->class->class_name ?? 'Tidak Ada Kelas' }}
                                                </td>
                                            </tr>
                                            @php
                                                $currentClass = $student->class->class_name ?? null;
                                                $rowNumber = 1; // Reset nomor urut untuk kelas baru
                                            @endphp
                                        @endif

                                        <tr>
                                            <td>{{ $rowNumber++ }}</td>
                                            <td>{{ $student->id_student }}</td>
                                            <td>{{ $student->fullname }}</td>
                                            <td>{{ $student->birth_place }}</td>
                                            <td>{{ $student->birth_date->format('Y-m-d') }}</td>
                                            <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            <td>{{ $student->parent_phonecell }}</td>
                                            <td>{{ $student->class ? $student->class->class_name : 'Tidak Ada Kelas' }}</td>
                                            <td>
                                                @if ($student->photo)
                                                    <img src="{{ asset('storage/' . $student->photo) }}" width="50" class="img-thumbnail">
                                                @else
                                                    <i class="fas fa-user text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($student->qr_code)
                                                    <img src="{{ asset('storage/' . $student->qr_code) }}" width="50" class="img-thumbnail">
                                                @else
                                                    <i class="fas fa-qrcode text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('students.show', $student->id_student) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye me-1"></i>
                                                    </a>
                                                    <a href="{{ route('students.edit', $student->id_student) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit me-1"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger delete-student" data-student-id="{{ $student->id_student }}">
                                                        <i class="fas fa-trash me-1"></i>
                                                    </button>
                                                </div>
                                                <form class="delete-student-form" action="{{ route('students.destroy', $student->id_student) }}" method="POST" style="display: none;">
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

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        let academicYearSelect = document.getElementById("academicYearSelect");
                        let semesterSelect = document.getElementById("semesterSelect");

                        // Ambil data semester dari server
                        let semesters = @json($semesters);

                        academicYearSelect.addEventListener("change", function() {
                            let selectedYearId = this.value;
                            semesterSelect.innerHTML =
                            '<option value="">-- Pilih Semester --</option>'; // Reset dropdown semester

                            if (selectedYearId) {
                                let filteredSemesters = semesters.filter(sem => sem.academic_year_id == selectedYearId);

                                filteredSemesters.forEach(sem => {
                                    let option = document.createElement("option");
                                    option.value = sem.id;
                                    option.textContent = sem.semester_name;
                                    semesterSelect.appendChild(option);
                                });
                            }
                        });

                        // Konfirmasi hapus siswa
                        document.querySelectorAll(".delete-student").forEach(button => {
                            button.addEventListener("click", function() {
                                const studentId = this.getAttribute("data-student-id");
                                const form = document.querySelector(
                                    `.delete-student-form[action$='${studentId}']`);

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "Data siswa akan dihapus secara permanen!",
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
                </script>
            </main>
        </div>
    </body>
</html>
@endif
