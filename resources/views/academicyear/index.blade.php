<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Tahun Ajaran & Semester</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #4266B9;
            color: white;
            padding: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: #ff6b35;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
        }

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

        .semester-container {
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .toggle-semesters {
            cursor: pointer;
            color: #4266B9;
        }

        .semester-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            margin-left: 0.5rem;
        }

        .status-toggle {
            min-width: 110px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo-text, .nav-text {
                display: none;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">E</div>
            <div class="logo-text">SCHOOL</div>
        </div>
        <nav>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-home me-2"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-users me-2"></i>
                <span class="nav-text">Data User</span>
            </a>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-user-graduate me-2"></i>
                <span class="nav-text">Data Siswa</span>
            </a>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                <span class="nav-text">Data Pegawai</span>
            </a>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-calendar-check me-2"></i>
                <span class="nav-text">Absensi</span>
            </a>
            <div class="ms-3">
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-user-check me-2"></i>
                    <span class="nav-text">Absensi Siswa</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-user-tie me-2"></i>
                    <span class="nav-text">Absensi Pegawai</span>
                </a>
            </div>
            <a href="#" class="nav-item text-white d-block mb-2 active">
                <i class="fas fa-database me-2"></i>
                <span class="nav-text">Master Data</span>
            </a>
            <div class="ms-3">
                <a href="#" class="nav-item text-white d-block mb-2 active">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span class="nav-text">Tahun Ajaran & Semester</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-school me-2"></i>
                    <span class="nav-text">Kelas</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-book me-2"></i>
                    <span class="nav-text">Mata Pelajaran</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-calendar-day me-2"></i>
                    <span class="nav-text">Hari Libur</span>
                </a>
            </div>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-money-bill-wave me-2"></i>
                <span class="nav-text">Data SPP</span>
            </a>
            <a href="#" class="nav-item text-white d-block mb-2">
                <i class="fas fa-book-reader me-2"></i>
                <span class="nav-text">Data Buku Paket</span>
            </a>
            <div class="ms-3">
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-book-open me-2"></i>
                    <span class="nav-text">Peminjaman Buku Paket</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Data Tahun Ajaran & Semester</h2>
            <div class="d-flex align-items-center">
                <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;" id="searchInput">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="addDataDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        + Tambah Data
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="addDataDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addAcademicYearModal">Tambah Tahun Ajaran</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Data Tahun Ajaran & Semester -->
        <div class="card">
            <div class="card-header bg-primary text-white">Daftar Tahun Ajaran & Semester</div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
                <div id="errorMessage" class="alert alert-danger d-none">
                    Terjadi kesalahan saat memuat data. Silahkan coba lagi.
                </div>
                <table class="table table-bordered" id="academicYearTable">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Tahun Ajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($academicYears as $tahun)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span>{{ $tahun->year_name }}</span>
                                    <span class="ms-2 toggle-semesters" data-year-id="{{ $tahun->id }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                    @php
                                        $activeSemester = $tahun->semesters->where('is_active', 1)->first();
                                    @endphp
                                    @if($activeSemester)
                                        <span class="badge bg-success semester-badge">{{ $activeSemester->semester_name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($tahun->start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($tahun->end_date)->format('d/m/Y') }}</td>
                            <td>
                                <button class="btn btn-sm {{ $tahun->is_active ? 'btn-success' : 'btn-danger' }} status-toggle"
                                    data-id="{{ $tahun->id }}"
                                    data-status="{{ $tahun->is_active }}"
                                    data-type="academic-year">
                                <i class="fas {{ $tahun->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                {{ $tahun->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editYearModal{{ $tahun->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('academicyear.destroy', $tahun->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr class="semester-row" id="semesters-{{ $tahun->id }}">
                            <td colspan="6" class="p-0">
                                <div class="semester-container">
                                    <h6 class="mb-3">Semester untuk Tahun Ajaran {{ $tahun->year_name }}</h6>
                                    @if($tahun->semesters->count() > 0)
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tahun->semesters as $semester)
                                            <tr data-year-id="{{ $tahun->id }}">
                                                <td>
                                                    <span class="badge bg-success semester-badge">{{ $semester->semester_name }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($semester->start_date)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($semester->end_date)->format('d/m/Y') }}</td>
                                                <td>
                                                    <button class="btn btn-sm {{ $semester->is_active ? 'btn-success' : 'btn-danger' }} status-toggle"
                                                        data-id="{{ $semester->id }}"
                                                        data-status="{{ $semester->is_active }}"
                                                        data-type="semester"
                                                        data-year-id="{{ $tahun->id }}">
                                                    <i class="fas {{ $semester->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                    {{ $semester->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSemesterModal{{ $semester->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('semesters.destroy', $semester->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus semester ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <div class="text-center py-3">
                                        <p class="text-muted">Belum ada data semester untuk tahun ajaran ini.</p>
                                        <button class="btn btn-sm btn-outline-primary add-semester-btn" data-bs-toggle="modal" data-bs-target="#addSemesterModal" data-academic-year-id="{{ $tahun->id }}">
                                            <i class="fas fa-plus me-1"></i> Tambah Semester
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Tambah Tahun Ajaran -->
<div class="modal fade" id="addAcademicYearModal" tabindex="-1" aria-labelledby="addAcademicYearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAcademicYearModalLabel">Tambah Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('academicyear.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="year_name" class="form-label">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="year_name" name="year_name" placeholder="Contoh: 2023-2024" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-control" id="is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Tahun Ajaran (Dinamis) -->
@foreach($academicYears as $tahun)
<div class="modal fade" id="editYearModal{{ $tahun->id }}" tabindex="-1" aria-labelledby="editYearModalLabel{{ $tahun->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editYearModalLabel{{ $tahun->id }}">Edit Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('academicyear.update', $tahun->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="year_name{{ $tahun->id }}" class="form-label">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="year_name{{ $tahun->id }}" name="year_name" value="{{ $tahun->year_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date{{ $tahun->id }}" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date{{ $tahun->id }}" name="start_date" value="{{ $tahun->start_date }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date{{ $tahun->id }}" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date{{ $tahun->id }}" name="end_date" value="{{ $tahun->end_date }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active{{ $tahun->id }}" class="form-label">Status</label>
                        <select class="form-control" id="is_active{{ $tahun->id }}" name="is_active" required>
                            <option value="1" {{ $tahun->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$tahun->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah Semester -->
<div class="modal fade" id="addSemesterModal" tabindex="-1" aria-labelledby="addSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSemesterModalLabel">Tambah Semester</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('semesters.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                        <select class="form-control" id="academic_year_id" name="academic_year_id" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($academicYears as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester_name" class="form-label">Nama Semester</label>
                        <select class="form-control" id="semester_name" name="semester_name" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-control" id="is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Semester (Dinamis) -->
@foreach($academicYears as $tahun)
    @foreach($tahun->semesters as $semester)
    <div class="modal fade" id="editSemesterModal{{ $semester->id }}" tabindex="-1" aria-labelledby="editSemesterModalLabel{{ $semester->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSemesterModalLabel{{ $semester->id }}">Edit Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('semesters.update', $semester->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="academic_year_id{{ $semester->id }}" class="form-label">Tahun Ajaran</label>
                            <select class="form-control" id="academic_year_id{{ $semester->id }}" name="academic_year_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $semester->academic_year_id == $year->id ? 'selected' : '' }}>{{ $year->year_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester_name{{ $semester->id }}" class="form-label">Nama Semester</label>
                            <select class="form-control" id="semester_name{{ $semester->id }}" name="semester_name" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="Ganjil" {{ $semester->semester_name == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ $semester->semester_name == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date{{ $semester->id }}" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date{{ $semester->id }}" name="start_date" value="{{ $semester->start_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date{{ $semester->id }}" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date{{ $semester->id }}" name="end_date" value="{{ $semester->end_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="is_active{{ $semester->id }}" class="form-label">Status</label>
                            <select class="form-control" id="is_active{{ $semester->id }}" name="is_active" required>
                                <option value="1" {{ $semester->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$semester->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endforeach

<!-- Modal Konfirmasi Perubahan Status -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmStatusModalLabel">Konfirmasi Perubahan Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmStatusMessage">Apakah Anda yakin ingin mengubah status?</p>
                <input type="hidden" id="confirm_id">
                <input type="hidden" id="confirm_type">
                <input type="hidden" id="confirm_status">
                <input type="hidden" id="confirm_year_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatusBtn">Ya, Ubah Status</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sembunyikan semua baris semester saat halaman dimuat
    const semesterRows = document.querySelectorAll('.semester-row');
    semesterRows.forEach(row => {
        row.style.display = 'none';
    });

    // Toggle tampilan semester saat ikon panah diklik
    const toggleButtons = document.querySelectorAll('.toggle-semesters');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const yearId = this.getAttribute('data-year-id');
            const semesterRow = document.getElementById(`semesters-${yearId}`);
            const icon = this.querySelector('i');

            if (semesterRow.style.display === 'none') {
                semesterRow.style.display = 'table-row';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                semesterRow.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const academicYearRows = document.querySelectorAll('#academicYearTable tbody tr:not(.semester-row)');

        academicYearRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const yearId = row.querySelector('.toggle-semesters')?.getAttribute('data-year-id');
            const semesterRow = yearId ? document.getElementById(`semesters-${yearId}`) : null;

            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
                if (semesterRow) {
                    semesterRow.style.display = 'none';
                }
            }
        });
    });

    // Fungsi untuk tombol toggle status
    const statusToggleButtons = document.querySelectorAll('.status-toggle');
    const confirmStatusModal = new bootstrap.Modal(document.getElementById('confirmStatusModal'));

    statusToggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');
            const type = this.getAttribute('data-type');
            const yearId = this.getAttribute('data-year-id') || '';
            const newStatus = currentStatus == '1' ? '0' : '1';
            const typeName = type === 'academic-year' ? 'Tahun Ajaran' : 'Semester';

            // Set nilai untuk konfirmasi
            document.getElementById('confirm_id').value = id;
            document.getElementById('confirm_type').value = type;
            document.getElementById('confirm_status').value = newStatus;
            document.getElementById('confirm_year_id').value = yearId;

            // Set pesan konfirmasi
            const statusText = newStatus == '1' ? 'mengaktifkan' : 'menonaktifkan';
            document.getElementById('confirmStatusMessage').textContent =
                `Apakah Anda yakin ingin ${statusText} ${typeName} ini?`;

            // Tampilkan modal konfirmasi
            confirmStatusModal.show();
        });
    });

    // Fungsi untuk tombol konfirmasi perubahan status
    document.getElementById('confirmStatusBtn').addEventListener('click', function() {
        const id = document.getElementById('confirm_id').value;
        const type = document.getElementById('confirm_type').value;
        const newStatus = document.getElementById('confirm_status').value;
        const yearId = document.getElementById('confirm_year_id').value;

        // Tampilkan loading indicator
        document.getElementById('loadingIndicator').classList.remove('d-none');

        // Kirim permintaan AJAX untuk mengubah status
        fetch(`/${type}/toggle-status/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: newStatus,
                id: yearId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Sembunyikan loading indicator
            document.getElementById('loadingIndicator').classList.add('d-none');

            if (data.success) {
                // Reload halaman untuk memperbarui tampilan
                window.location.reload();
            } else {
                // Tampilkan pesan error
                alert(data.message || 'Gagal mengubah status');
            }
        })
        .catch(error => {
            // Sembunyikan loading indicator
            document.getElementById('loadingIndicator').classList.add('d-none');

            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });

        // Tutup modal konfirmasi
        confirmStatusModal.hide();
    });

    // Fungsi untuk tombol tambah semester dari baris tahun ajaran
    const addSemesterButtons = document.querySelectorAll('.add-semester-btn');
    addSemesterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const yearId = this.getAttribute('data-academic-year-id');
            const selectElement = document.getElementById('academic_year_id');

            // Set nilai tahun ajaran di form tambah semester
            if (selectElement) {
                selectElement.value = yearId;
            }
        });
    });
});
</script>

<!-- Tambahkan script untuk menangani form submit dengan AJAX jika diperlukan -->
</body>
</html>
