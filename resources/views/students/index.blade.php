<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Data Siswa</title>
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <!-- JS Libraries (at bottom of body) -->
</head>
<style>
     .scrollable-student-table {
        max-height: 700px; /* Atur lebih panjang sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
    }

    .scrollable-student-table::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-student-table::-webkit-scrollbar-thumb {
        background-color: #6c757d;
        border-radius: 4px;
    }

    .table-class-header {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: bold;
    }

    .img-thumbnail {
        max-width: 50px;
        height: auto;
    }
    </style>
@if (Auth::guard('employee')->check())
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Admin Profile -->
            @include('components.profiladmin')

            <!-- Page Header -->
<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Data Siswa</h1>
        @if ($activeYear && $activeSemester)
            <small class="text-muted">
                Tahun Ajaran Aktif: <strong>{{ $activeYear->year_name }}</strong> |
                Semester Aktif: <strong>{{ $activeSemester->semester_name }}</strong>
            </small>
        @endif
    </div>
</header>

            <!-- Filter Card -->
            <div class="card mb-4 border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                    <span><i class="fas fa-filter me-2"></i>Filter Data Siswa</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('students.index') }}" method="GET">
                        <div class="row g-3">
                            <!-- Academic Year -->
                            <div class="col-md-4">
                                <label for="academicYearSelect" class="form-label fw-bold">Tahun Ajaran</label>
                                <select id="academicYearSelect" name="academic_year_id" class="form-select">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($academicYears as $tahun)
                                        <option value="{{ $tahun->id }}" {{ request('academic_year_id') == $tahun->id ? 'selected' : '' }}>
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
                                {{-- Dynamic --}}
                            </select>
                            </div>

                            <!-- Class -->
                            <div class="col-md-4">
                                <label for="classSelect" class="form-label fw-bold">Kelas</label>

                            <select id="classSelect" name="class_id" class="form-select">
                                <option value="">-- Pilih Kelas --</option>
                                {{-- Dynamic --}}
                            </select>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="mt-3 d-flex justify-content-between">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-3">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-3">
                                    <i class="fas fa-sync-alt me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

       <!-- Import Card -->
<div class="card mb-4 border-success shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-2">
        <span><i class="fas fa-file-import me-2"></i>Import Data Siswa</span>
    </div>
    <div class="card-body">
        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="file" class="form-label">File Excel</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls">
                    <small class="text-muted">Format: Excel (.xlsx, .xls), Maksimal 2MB</small>
                </div>

                <div class="col-12 d-flex flex-wrap align-items-center gap-2 mt-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-1"></i> Import Data
                    </button>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-excel me-1"></i> Download Template
                        </button>
                        <ul class="dropdown-menu">
                          <li>
    <a class="dropdown-item" href="{{ route('students.template.page') }}">
        <i class="fas fa-file me-1"></i> Template Kosong
    </a>
</li>

                            <li>
                                <a class="dropdown-item" href="{{ route('students.template.filled') }}">
                                    <i class="fas fa-database me-1"></i> Template dari Data
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

                    </form>

                    <form action="{{ route('students.uploadMediaZip') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="media_zip" class="form-label">Upload ZIP Foto & QR Siswa</label>
                            <input type="file" name="media_zip" class="form-control" accept=".zip" required>
                           <small class="text-muted">
    File ZIP dapat diberi nama bebas, namun <strong>harus berisi satu folder utama</strong> yang di dalamnya terdapat folder <code>photos/</code> dan <code>qrcodes/</code>.
    Setiap file di dalam folder tersebut harus diberi nama dengan format: <code>NIPD_Nama.ext</code>
    (contoh: <code>12345_Andi.png</code>).
</small>

                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-archive me-1"></i> Upload Media
                        </button>
                    </form>

                    <!-- Notifications -->
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif

                   @if ($errors->import->any())
    <div class="alert alert-danger mt-3">
        <h5 class="alert-heading">Gagal Impor Data:</h5>
        <ul class="mb-0">
            @foreach ($errors->import->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    @if(session('upload_errors'))
                        <div class="alert alert-warning mt-3">
                            <h5 class="alert-heading">Beberapa file tidak berhasil diproses:</h5>
                            <ul class="mb-0">
                                @foreach (session('upload_errors') as $type => $messages)
                                    @foreach ($messages as $msg)
                                        <li><strong>{{ ucfirst($type) }}:</strong> {{ $msg }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

         <!-- Card Tabel Data Siswa -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-users me-2"></i> Data Siswa
        </div>
        <div class="d-flex align-items-center gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari siswa..." style="width: 200px;">
            <a href="{{ route('students.create') }}" class="btn btn-sm btn-light text-primary">
                <i class="fas fa-plus me-1"></i> Tambah Siswa
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive scrollable-student-table">
            <table class="table table-bordered table-striped align-middle mb-0" id="studentsTable">
                <thead class="table-primary text-center">
                    <tr>
                        <th>NO</th>
                        <th>NIPD</th>
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
                <tbody id="studentsTbody">
                  @php
    $currentClass = null;
    $rowNumber = 1;
@endphp

@foreach ($students as $student)
    @php
      $className = $student->studentSemesters
    ->where('academic_year_id', $selectedYear)
    ->when($selectedSemester, function ($q) use ($selectedSemester) {
        return $q->where('semester_id', $selectedSemester);
    })
    ->first()?->class?->class_name;

    @endphp

    @if ($className && $className != $currentClass)
        <tr class="table-class-header text-uppercase bg-light fw-bold">
            <td colspan="11">
                <i class="fas fa-chalkboard me-2"></i> Kelas: {{ $className }}
            </td>
        </tr>
        @php
            $currentClass = $className;
            $rowNumber = 1;
        @endphp
    @endif

                        <tr>
                            <td class="text-center">{{ $rowNumber++ }}</td>
                            <td>{{ $student->id_student }}</td>
                            <td>{{ $student->fullname }}</td>
                            <td>{{ $student->birth_place }}</td>
                            <td>{{ $student->birth_date->format('Y-m-d') }}</td>
                            <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $student->parent_phonecell }}</td>
                          <td>
                                {{
                                   $student->studentSemesters
    ->where('academic_year_id', $selectedYear)
    ->when($selectedSemester, function ($q) use ($selectedSemester) {
        return $q->where('semester_id', $selectedSemester);
    })
    ->first()?->class?->class_name ?? '-'

                                }}
                            </td>
                            <td class="text-center">
                                @if ($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" class="img-thumbnail" width="50">
                                @else
                                    <i class="fas fa-qrcode text-muted"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($student->qrcode)
                                    <img src="{{ asset('storage/' . $student->qrcode) }}" class="img-thumbnail" width="50">
                                @else
                                    <i class="fas fa-qrcode text-muted"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('students.show', $student->id_student) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student->id_student) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-student" data-student-id="{{ $student->id_student }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
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

    <div class="card-footer bg-light">
        <strong>Total Siswa:</strong> {{ $students->count() }} orang
    </div>
</div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>
document.addEventListener("DOMContentLoaded", function () {
    const academicYearSelect = document.getElementById("academicYearSelect");
    const semesterSelect = document.getElementById("semesterSelect");
    const classSelect = document.getElementById("classSelect");

    const semesters = @json($semesters);
    const selectedAcademicYear = "{{ $selectedYear }}";
    const selectedSemester = "{{ request('semester_id') }}";
    const selectedClass = "{{ request('class_id') }}";

    // 1. Langsung tampilkan kelas berdasarkan tahun ajaran aktif
    if (selectedAcademicYear) {
        fetch(`/get-classes/${selectedAcademicYear}`)
            .then(res => res.json())
            .then(response => {
                const data = response.data ?? response; // antisipasi struktur json
                classSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
                data.forEach(kelas => {
                    const option = document.createElement('option');
                    option.value = kelas.class_id;
                    option.textContent = kelas.class_name;
                    if (kelas.class_id == selectedClass) option.selected = true;
                    classSelect.appendChild(option);
                });
            });
    }

    // 2. Saat tahun ajaran dipilih, baru tampilkan semester
    academicYearSelect?.addEventListener("change", function () {
        const selectedYearId = this.value;

        semesterSelect.innerHTML = '<option value="">-- Pilih Semester --</option>';
        if (selectedYearId) {
            const filteredSemesters = semesters.filter(sem => sem.academic_year_id == selectedYearId);
            filteredSemesters.forEach(sem => {
                const option = document.createElement("option");
                option.value = sem.id;
                option.textContent = sem.semester_name;
                if (sem.id == selectedSemester) option.selected = true;
                semesterSelect.appendChild(option);
            });
        }
    });
});
    // Konfirmasi hapus siswa
    document.querySelectorAll(".delete-student").forEach(button => {
        button.addEventListener("click", function () {
            const studentId = this.getAttribute("data-student-id");
            const form = document.querySelector(`.delete-student-form[action$='${studentId}']`);

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
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

    // Fitur pencarian siswa
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr:not(.table-secondary)');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
</script>
</body>
@endif
</html>
