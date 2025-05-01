<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Nilai Siswa</title>
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

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 10px 35px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
        }

        .search-bar i {
            position: absolute;
            left: 10px;
            color: #666;
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

        .pdf-preview {
            width: 100%;
            height: 500px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .filter-section {
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .student-details {
            display: flex;
            flex-direction: column;
        }

        .student-name {
            font-weight: bold;
            margin-bottom: 0;
        }

        .student-id {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .nilai-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
        }

        .toggle-rapor {
            cursor: pointer;
            color: #4266B9;
        }

        .rapor-row {
            background-color: #f8f9fa;
        }


    </style>
</head>
@if(Auth::guard('employee')->check())
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

        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Data Nilai Siswa</h2>
            <div class="d-flex align-items-center">
                <input type="text" placeholder="Cari nama siswa" class="form-control me-3" style="width: 200px;" id="searchInput">
                <a href="{{ route('Rapor.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Upload Rapor
                </a>
            </div>
        </header>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <h5 class="mb-3">Filter Data Siswa</h5>
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="filterTahunAjaran" class="form-label">Tahun Ajaran</label>
                    <select class="form-select" id="filterTahunAjaran" name="academic_year_id">
                        <option value="">Semua Tahun Ajaran</option>
                        <!-- Loop through academic years -->
                        <!-- @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSemester" class="form-label">Semester</label>
                    <select class="form-select" id="filterSemester" name="semester_id">
                        <option value="">Semua Semester</option>
                        <!-- Loop through semesters -->
                        <!-- @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                        @endforeach -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterKelas" class="form-label">Kelas</label>
                    <select class="form-select" id="filterKelas" name="class_id">
                        <option value="">Semua Kelas</option>
                        <!-- Loop through classes -->
                        <!-- @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status Rapor</label>
                    <select class="form-select" id="filterStatus" name="status">
                        <option value="">Semua Status</option>
                        <option value="1">Sudah Ada Rapor</option>
                        <option value="0">Belum Ada Rapor</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                    <button type="reset" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Kelas Aktif -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <div>
                Menampilkan data siswa
            </div>
        </div>

        <!-- Data Nilai Siswa -->
        <div class="card">
            <div class="card-header bg-primary text-white">Daftar Siswa dan Nilai</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="siswaTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Siswa</th>
                                <th width="15%">NIS/NISN</th>
                                <th width="15%">Kelas</th>
                                <th width="15%">Status Rapor</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through rapor data -->
                            @foreach($rapor as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="student-info">
                                        <!-- Check if the student has a photo -->
                                        @if($item->student->photo_path)
                                            <img src="{{ asset('storage/' . $item->student->photo_path) }}" alt="Foto {{ $item->student->name }}" class="student-photo">
                                        @else
                                            <img src="{{ asset('images/default-avatar.jpg') }}" alt="Foto Default" class="student-photo">
                                        @endif
                                        <div class="student-details">
                                            <p class="student-name">{{ $item->student->name }}</p>
                                            <span class="student-id">{{ $item->student->gender }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: {{ $item->student->nis }}</div>
                                    <div>NISN: {{ $item->student->nisn }}</div>
                                </td>
                                <td>{{ $item->class->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Sudah Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('rapor.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('rapor.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rapor ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                            <!-- If no rapor data, show students without rapor -->
                            <!-- @foreach($students->whereNotIn('id', $rapor->pluck('id_student')) as $index => $student) -->
                            <tr>
                                <td><!-- {{ $rapor->count() + $index + 1 }} --></td>
                                <td>
                                    <div class="student-info">
                                        <!-- @if($student->photo_path) -->
                                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto {{ $student->name }}" class="student-photo">
                                        <!-- @else -->
                                        <img src="{{ asset('images/default-avatar.jpg') }}" alt="Foto Default" class="student-photo">
                                        <!-- @endif -->
                                        <div class="student-details">
                                            <p class="student-name"><!-- {{ $student->name }} --></p>
                                            <span class="student-id"><!-- {{ $student->gender }} --></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: <!-- {{ $student->nis }} --></div>
                                    <div>NISN: <!-- {{ $student->nisn }} --></div>
                                </td>
                                <td><!-- {{ $student->class->name ?? '-' }} --></td>
                                <td>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Belum Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('Rapor.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- @endforeach -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');

        tableRows.forEach(row => {
            const studentName = row.querySelector('.student-name')?.textContent.toLowerCase() || '';
            const studentId = row.querySelector('.student-id')?.textContent.toLowerCase() || '';
            const nisNisn = row.cells[2]?.textContent.toLowerCase() || '';

            if (studentName.includes(searchValue) || studentId.includes(searchValue) || nisNisn.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Fungsi filter
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const tahunAjaran = document.getElementById('filterTahunAjaran').value;
        const semester = document.getElementById('filterSemester').value;
        const kelas = document.getElementById('filterKelas').value;
        const status = document.getElementById('filterStatus').value;

        // Update info kelas aktif
        let infoText = 'Menampilkan data siswa';

        if (kelas) {
            const kelasSelect = document.getElementById('filterKelas');
            const kelasText = kelasSelect.options[kelasSelect.selectedIndex].text;
            infoText += ` kelas <strong>${kelasText}</strong>`;
        } else {
            infoText += ` <strong>semua kelas</strong>`;
        }

        if (tahunAjaran) {
            const tahunSelect = document.getElementById('filterTahunAjaran');
            const tahunText = tahunSelect.options[tahunSelect.selectedIndex].text;
            infoText += ` untuk Tahun Ajaran <strong>${tahunText}</strong>`;
        }

        if (semester) {
            const semesterSelect = document.getElementById('filterSemester');
            const semesterText = semesterSelect.options[semesterSelect.selectedIndex].text;
            infoText += ` Semester <strong>${semesterText}</strong>`;
        }

        document.querySelector('.alert-info div').innerHTML = infoText;

        // In a real implementation, this would submit the form to the server
        // For now, we'll just update the UI
    });

    // Reset filter
    document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
        document.querySelector('.alert-info div').innerHTML = 'Menampilkan data siswa';
    });
});
</script>
</body>
</html>
@endif
