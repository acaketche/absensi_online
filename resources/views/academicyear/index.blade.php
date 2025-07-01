<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Tahun Ajaran & Semester</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        .add-semester-container {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
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
@if(Auth::guard('employee')->check())
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
   @include('components.sidebar')

   <!-- Main Content -->
<main class="flex-grow-1 p-4">
    @include('components.profiladmin')
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Data Tahun Ajaran & Semester</h2>
        <div class="d-flex align-items-center">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addAcademicYearModal">
                <i class="fas fa-plus me-1"></i> Tambah Tahun Ajaran
            </button>
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
                    @php
                        $hasGanjil = $tahun->semesters->contains('semester_name', 'Ganjil');
                        $hasGenap = $tahun->semesters->contains('semester_name', 'Genap');
                    @endphp
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
                            <span class="badge bg-success semester-badge" id="active-semester-badge-{{ $tahun->id }}"
                                  style="{{ $activeSemester ? '' : 'display: none;' }}">
                                {{ $activeSemester ? $activeSemester->semester_name : '' }}
                            </span>
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
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editYearModal{{ $tahun->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $tahun->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>

                        <!-- Modal Konfirmasi Hapus -->
                        <div class="modal fade" id="confirmDeleteModal{{ $tahun->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $tahun->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $tahun->id }}"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Penghapusan</h5>
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                        <p><strong>Dengan menghapus tahun ini, semua data terkait termasuk ujian dan nilai akan terhapus.</strong></p>
                                        <p>Apakah Anda yakin ingin melanjutkan?</p>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('academicyear.destroy', $tahun->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        <tr data-year-id="{{ $tahun->id }}" id="semester-row-{{ $semester->id }}">
                                            <td>
                                                <span class="badge bg-{{ $semester->is_active ? 'success' : 'secondary' }} semester-badge semester-name-{{ $semester->id }}">{{ $semester->semester_name }}</span>
                                            </td>
                                            <td>
                                                {{
                                                    $loop->iteration % 2 != 0
                                                        ? \Carbon\Carbon::parse($tahun->start_date)->format('d/m/Y')
                                                        : \Carbon\Carbon::parse($semester->start_date)->format('d/m/Y')
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    $loop->iteration % 2 == 0
                                                        ? \Carbon\Carbon::parse($tahun->end_date)->format('d/m/Y')
                                                        : \Carbon\Carbon::parse($semester->end_date)->format('d/m/Y')
                                                }}
                                            </td>
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
                                @endif

                                <!-- Tombol Tambah Semester (Hanya tampil jika belum ada Ganjil atau Genap) -->
                                <div class="add-semester-container text-center">
                                    @if(!$hasGanjil || !$hasGenap)
                                    <button class="btn btn-sm btn-outline-primary add-semester-btn" data-bs-toggle="modal" data-bs-target="#addSemesterModal" data-academic-year-id="{{ $tahun->id }}">
                                        <i class="fas fa-plus me-1"></i> Tambah Semester
                                    </button>
                                    @endif
                                </div>
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
            <form action="{{ route('academicyear.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="year_name">Tahun Ajaran</label>
                        <input type="text" name="year_name" class="form-control" placeholder="Contoh: 2023-2024" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active">Status</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0" selected>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Tahun Ajaran -->
@foreach($academicYears as $tahun)
<div class="modal fade" id="editYearModal{{ $tahun->id }}" tabindex="-1" aria-labelledby="editYearModalLabel{{ $tahun->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('academicyear.update', $tahun->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="year_name{{ $tahun->id }}" class="form-label">Tahun Ajaran</label>
                        <input type="text" name="year_name" id="year_name{{ $tahun->id }}" class="form-control" value="{{ $tahun->year_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date{{ $tahun->id }}" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date{{ $tahun->id }}" class="form-control" value="{{ $tahun->start_date }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date{{ $tahun->id }}" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date{{ $tahun->id }}" class="form-control" value="{{ $tahun->end_date }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active{{ $tahun->id }}" class="form-label">Status</label>
                        <select name="is_active" id="is_active{{ $tahun->id }}" class="form-select">
                            <option value="1" {{ $tahun->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$tahun->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Tambah Semester -->
<div class="modal fade" id="addSemesterModal" tabindex="-1" aria-labelledby="addSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('semesters.store') }}" method="POST" id="addSemesterForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSemesterModalLabel">Tambah Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="academic_year_id" id="academic_year_id">

                    <div class="mb-3">
                        <label for="semester_name" class="form-label">Semester</label>
                        <select name="semester_name" id="semester_name" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0" selected>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Semester -->
@foreach($academicYears as $tahun)
    @foreach($tahun->semesters as $semester)
    <div class="modal fade" id="editSemesterModal{{ $semester->id }}" tabindex="-1" aria-labelledby="editSemesterModalLabel{{ $semester->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('semesters.update', $semester->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Semester</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <input type="hidden" name="academic_year_id" value="{{ $semester->academic_year_id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="semester_name{{ $semester->id }}" class="form-label">Nama Semester</label>
                            <select name="semester_name" id="semester_name{{ $semester->id }}" class="form-select" required>
                                <option value="Ganjil" {{ $semester->semester_name == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ $semester->semester_name == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date{{ $semester->id }}" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date{{ $semester->id }}" class="form-control" value="{{ $semester->start_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date{{ $semester->id }}" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date{{ $semester->id }}" class="form-control" value="{{ $semester->end_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="is_active{{ $semester->id }}" class="form-label">Status</label>
                            <select name="is_active" id="is_active{{ $semester->id }}" class="form-select">
                                <option value="1" {{ $semester->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $semester->is_active == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi semua modal Bootstrap
    const modalElements = document.querySelectorAll('.modal');
    modalElements.forEach(modalEl => {
        new bootstrap.Modal(modalEl);
    });

    // 1. SEMBUNYIKAN SEMUA BARIS SEMESTER SAAT PERTAMA KALI DIMUAT
    document.querySelectorAll('.semester-row').forEach(row => {
        row.style.display = 'none';
    });

    // 2. TOGGLE TAMPILAN BARIS SEMESTER
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('.toggle-semesters');
        if (toggleBtn) {
            const yearId = toggleBtn.dataset.yearId;
            const semesterRow = document.getElementById(`semesters-${yearId}`);
            const icon = toggleBtn.querySelector('i');

            if (semesterRow.style.display === 'none') {
                semesterRow.style.display = 'table-row';
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                semesterRow.style.display = 'none';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        }
    });

    // 3. FUNGSI UNTUK MENGUBAH TAMPILAN TOMBOL STATUS DAN BADGE
    function updateStatusButton(button, isActive) {
        const id = button.dataset.id;
        const type = button.dataset.type;
        const yearId = button.dataset.yearId || null;

        // Update tombol status
        button.dataset.status = isActive ? '1' : '0';
        button.className = `btn btn-sm ${isActive ? 'btn-success' : 'btn-danger'} status-toggle`;
        button.innerHTML = `<i class="fas ${isActive ? 'fa-check-circle' : 'fa-times-circle'} me-1"></i> ${isActive ? 'Aktif' : 'Tidak Aktif'}`;

        // Jika ini adalah semester
        if (type === 'semester') {
            // Update badge semester
            const badge = document.querySelector(`.semester-name-${id}`);
            if (badge) {
                badge.className = `badge bg-${isActive ? 'success' : 'secondary'} semester-badge semester-name-${id}`;
            }

            // Update badge aktif di tahun ajaran
            const activeBadge = document.querySelector(`#active-semester-badge-${yearId}`);
            if (activeBadge) {
                // Cari semester aktif di tahun ini
                const activeSemesterButton = document.querySelector(`.status-toggle[data-type="semester"][data-year-id="${yearId}"][data-status="1"]`);

                if (activeSemesterButton) {
                    // Jika ada semester aktif, update badge
                    const activeSemesterName = activeSemesterButton.closest('tr').querySelector('.semester-badge').textContent;
                    activeBadge.textContent = activeSemesterName;
                    activeBadge.style.display = '';
                } else {
                    // Jika tidak ada semester aktif, sembunyikan badge
                    activeBadge.style.display = 'none';
                }
            }
        }
    }

    // 4. EVENT UNTUK TOGGLE STATUS TAHUN AJARAN / SEMESTER
document.addEventListener('click', async function (e) {
    const statusBtn = e.target.closest('.status-toggle');
    if (!statusBtn) return;

    const id = statusBtn.dataset.id;
    const type = statusBtn.dataset.type;
    const currentStatus = statusBtn.dataset.status === '1';
    const newStatus = !currentStatus;
    const yearId = statusBtn.dataset.yearId || null;

    // Jika status sudah sesuai, tidak perlu melakukan apa-apa
    if ((currentStatus && newStatus) || (!currentStatus && !newStatus)) {
        return;
    }

    const originalContent = statusBtn.innerHTML;
    statusBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';
    statusBtn.disabled = true;

    try {
        const endpoint = type === 'academic-year'
            ? `/academic-year/toggle-status/${id}`
            : `/semester/toggle-status/${id}`;

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: newStatus ? 1 : 0,
                year_id: yearId
            })
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        if (!data.success) throw new Error(data.message || 'Gagal memperbarui status');

        // Update tombol yang diklik
        updateStatusButton(statusBtn, newStatus);

        // === Jika tipe adalah Tahun Ajaran ===
        if (type === 'academic-year') {
            // Nonaktifkan semua tahun ajaran lainnya jika tahun ini diaktifkan
            if (newStatus) {
                document.querySelectorAll(`.status-toggle[data-type="academic-year"]`).forEach(btn => {
                    if (btn !== statusBtn && btn.dataset.status === '1') {
                        // Nonaktifkan tahun ajaran lain
                        updateStatusButton(btn, false);

                        // Nonaktifkan semua semester dari tahun ajaran yang dinonaktifkan
                        const deactivatedYearId = btn.dataset.id;
                        document.querySelectorAll(`.status-toggle[data-type="semester"][data-year-id="${deactivatedYearId}"]`).forEach(semesterBtn => {
                            if (semesterBtn.dataset.status === '1') {
                                updateStatusButton(semesterBtn, false);

                                // Kirim request untuk menonaktifkan semester
                                fetch(`/semester/toggle-status/${semesterBtn.dataset.id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        status: 0,
                                        year_id: deactivatedYearId
                                    })
                                }).catch(error => {
                                    console.error('Failed to deactivate semester:', error);
                                });
                            }
                        });
                    }
                });
            }

            // Nonaktifkan semua semester dalam tahun ini jika tahun dinonaktifkan
            if (!newStatus) {
                document.querySelectorAll(`.status-toggle[data-type="semester"][data-year-id="${id}"]`).forEach(btn => {
                    if (btn.dataset.status === '1') {
                        // Update UI immediately
                        updateStatusButton(btn, false);

                        // Send request to deactivate
                        fetch(`/semester/toggle-status/${btn.dataset.id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                status: 0,
                                year_id: id
                            })
                        }).catch(error => {
                            console.error('Failed to deactivate semester:', error);
                        });
                    }
                });
            }
        }

        // === Jika tipe adalah Semester ===
        if (type === 'semester') {
            // Nonaktifkan semua semester lain dalam tahun yang sama
            if (newStatus && yearId) {
                document.querySelectorAll(`.status-toggle[data-type="semester"][data-year-id="${yearId}"]`).forEach(btn => {
                    if (btn !== statusBtn && btn.dataset.status === '1') {
                        updateStatusButton(btn, false);
                    }
                });

                // Aktifkan tahun ajaran terkait jika belum aktif
                const ayBtn = document.querySelector(`.status-toggle[data-id="${yearId}"][data-type="academic-year"]`);
                if (ayBtn && ayBtn.dataset.status === '0') {
                    updateStatusButton(ayBtn, true);
                }

                // Nonaktifkan semua semester dari tahun ajaran lain yang aktif
                document.querySelectorAll(`.status-toggle[data-type="semester"]`).forEach(btn => {
                    if (btn.dataset.yearId !== yearId && btn.dataset.status === '1') {
                        updateStatusButton(btn, false);

                        // Kirim request untuk menonaktifkan semester dari tahun lain
                        fetch(`/semester/toggle-status/${btn.dataset.id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                status: 0,
                                year_id: btn.dataset.yearId
                            })
                        }).catch(error => {
                            console.error('Failed to deactivate semester:', error);
                        });
                    }
                });
            }
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + (error.message || 'Gagal memperbarui status'));
        statusBtn.innerHTML = originalContent;
        updateStatusButton(statusBtn, currentStatus); // Kembalikan ke status semula jika error
    } finally {
        statusBtn.disabled = false;
    }
});
    // 5. HANDLE TOMBOL TAMBAH SEMESTER (Menggunakan event delegation)
    document.addEventListener('click', function(e) {
        const addSemesterBtn = e.target.closest('.add-semester-btn');
        if (addSemesterBtn) {
            const yearId = addSemesterBtn.dataset.academicYearId;
            const form = document.getElementById('addSemesterForm');
            const semesterSelect = form.querySelector('#semester_name');

            // Reset form dan set academic_year_id
            form.reset();
            document.querySelector('#addSemesterForm input[name="academic_year_id"]').value = yearId;

            // Get existing semesters for this academic year
            const existingSemesters = [];
            document.querySelectorAll(`#semesters-${yearId} .semester-badge`).forEach(badge => {
                existingSemesters.push(badge.textContent.trim());
            });

            // Update semester options
            semesterSelect.innerHTML = '<option value="" selected disabled>-- Pilih Semester --</option>';
            if (!existingSemesters.includes('Ganjil')) {
                semesterSelect.innerHTML += '<option value="Ganjil">Ganjil</option>';
            }
            if (!existingSemesters.includes('Genap')) {
                semesterSelect.innerHTML += '<option value="Genap">Genap</option>';
            }

            // Set status default ke Tidak Aktif
            document.querySelector('#addSemesterForm select[name="is_active"]').value = '0';

            // Set tanggal default berdasarkan tahun ajaran
            const yearRow = addSemesterBtn.closest('tr').previousElementSibling;
            if (yearRow) {
                const startDateText = yearRow.querySelector('td:nth-child(3)').textContent;
                const endDateText = yearRow.querySelector('td:nth-child(4)').textContent;

                if (startDateText) {
                    const [d, m, y] = startDateText.split('/');
                    document.querySelector('#addSemesterForm input[name="start_date"]').value = `${y}-${m}-${d}`;
                }

                if (endDateText) {
                    const [d, m, y] = endDateText.split('/');
                    document.querySelector('#addSemesterForm input[name="end_date"]').value = `${y}-${m}-${d}`;
                }
            }
        }
    });

    // Reset form saat modal tambah semester ditutup
    document.getElementById('addSemesterModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('addSemesterForm').reset();
    });
});
</script>

</body>
</html>
@endif
