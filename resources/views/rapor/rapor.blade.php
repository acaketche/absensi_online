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
@include('components.sidebar')
    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold">Data Nilai Siswa</h2>
            <div class="d-flex align-items-center">
                <input type="text" placeholder="Cari nama siswa" class="form-control me-3" style="width: 200px;" id="searchInput">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadRaporModal">
                    <i class="fas fa-plus me-1"></i> Upload Rapor
                </button>
            </div>
        </header>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <h5 class="mb-3">Filter Data Siswa</h5>
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="filterTahunAjaran" class="form-label">Tahun Ajaran</label>
                    <select class="form-select" id="filterTahunAjaran" name="tahun_ajaran_id">
                        <option value="">Semua Tahun Ajaran</option>
                        <option value="1">2022-2023</option>
                        <option value="2" selected>2023-2024</option>
                        <option value="3">2024-2025</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSemester" class="form-label">Semester</label>
                    <select class="form-select" id="filterSemester" name="semester_id">
                        <option value="">Semua Semester</option>
                        <option value="1" selected>Ganjil</option>
                        <option value="2">Genap</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterKelas" class="form-label">Kelas</label>
                    <select class="form-select" id="filterKelas" name="kelas_id">
                        <option value="">Semua Kelas</option>
                        <option value="1">X IPA 1</option>
                        <option value="2">X IPA 2</option>
                        <option value="3">X IPS 1</option>
                        <option value="4" selected>XI IPA 1</option>
                        <option value="5">XI IPA 2</option>
                        <option value="6">XI IPS 1</option>
                        <option value="7">XII IPA 1</option>
                        <option value="8">XII IPA 2</option>
                        <option value="9">XII IPS 1</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status Rapor</label>
                    <select class="form-select" id="filterStatus" name="status">
                        <option value="">Semua Status</option>
                        <option value="1" selected>Sudah Ada Rapor</option>
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
                Menampilkan data siswa kelas <strong>XI IPA 1</strong> untuk Tahun Ajaran <strong>2023-2024</strong> Semester <strong>Ganjil</strong>
            </div>
        </div>

        <!-- Data Nilai Siswa -->
        <div class="card">
            <div class="card-header bg-primary text-white">Daftar Siswa dan Nilai</div>
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
                            <!-- Siswa 1 -->
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="student-info">
                                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Foto Ahmad Rizky" class="student-photo">
                                        <div class="student-details">
                                            <p class="student-name">Ahmad Rizky</p>
                                            <span class="student-id">Laki-laki</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: 2022001</div>
                                    <div>NISN: 0012345678</div>
                                </td>
                                <td>XI IPA 1</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Sudah Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRaporModal" data-siswa-id="1" data-siswa-nama="Ahmad Rizky" data-rapor-file="rapor_ahmad_rizky_xiipa1_ganjil_2023.pdf">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRaporModal" data-siswa-id="1" data-siswa-nama="Ahmad Rizky">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRaporModal" data-siswa-id="1" data-siswa-nama="Ahmad Rizky">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Siswa 2 -->
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="student-info">
                                        <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Foto Anisa Putri" class="student-photo">
                                        <div class="student-details">
                                            <p class="student-name">Anisa Putri</p>
                                            <span class="student-id">Perempuan</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: 2022002</div>
                                    <div>NISN: 0012345679</div>
                                </td>
                                <td>XI IPA 1</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Sudah Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRaporModal" data-siswa-id="2" data-siswa-nama="Anisa Putri" data-rapor-file="rapor_anisa_putri_xiipa1_ganjil_2023.pdf">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRaporModal" data-siswa-id="2" data-siswa-nama="Anisa Putri">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRaporModal" data-siswa-id="2" data-siswa-nama="Anisa Putri">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Siswa 3 -->
                            <tr>
                                <td>3</td>
                                <td>
                                    <div class="student-info">
                                        <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Foto Budi Santoso" class="student-photo">
                                        <div class="student-details">
                                            <p class="student-name">Budi Santoso</p>
                                            <span class="student-id">Laki-laki</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: 2022003</div>
                                    <div>NISN: 0012345680</div>
                                </td>
                                <td>XI IPA 1</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Sudah Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRaporModal" data-siswa-id="3" data-siswa-nama="Budi Santoso" data-rapor-file="rapor_budi_santoso_xiipa1_ganjil_2023.pdf">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRaporModal" data-siswa-id="3" data-siswa-nama="Budi Santoso">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRaporModal" data-siswa-id="3" data-siswa-nama="Budi Santoso">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Siswa 4 -->
                            <tr>
                                <td>4</td>
                                <td>
                                    <div class="student-info">
                                        <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Foto Dewi Anggraini" class="student-photo">
                                        <div class="student-details">
                                            <p class="student-name">Dewi Anggraini</p>
                                            <span class="student-id">Perempuan</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: 2022004</div>
                                    <div>NISN: 0012345681</div>
                                </td>
                                <td>XI IPA 1</td>
                                <td>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Belum Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadRaporModal" data-siswa-id="4" data-siswa-nama="Dewi Anggraini">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Siswa 5 -->
                            <tr>
                                <td>5</td>
                                <td>
                                    <div class="student-info">
                                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Foto Eko Prasetyo" class="student-photo">
                                        <div class="student-details">
                                            <p class="student-name">Eko Prasetyo</p>
                                            <span class="student-id">Laki-laki</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>NIS: 2022005</div>
                                    <div>NISN: 0012345682</div>
                                </td>
                                <td>XI IPA 1</td>
                                <td>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Belum Ada Rapor
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadRaporModal" data-siswa-id="5" data-siswa-nama="Eko Prasetyo">
                                            <i class="fas fa-upload me-1"></i> Upload
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </main>
</div>

<!-- Modal Upload Rapor -->
<div class="modal fade" id="uploadRaporModal" tabindex="-1" aria-labelledby="uploadRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadRaporModalLabel">Upload Rapor Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadRaporForm" action="{{ route('Rapor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="tahun_ajaran_id" name="tahun_ajaran_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <option value="1">2022-2023</option>
                                <option value="2" selected>2023-2024</option>
                                <option value="3">2024-2025</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="semester_id" class="form-label">Semester</label>
                            <select class="form-select" id="semester_id" name="semester_id" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="1" selected>Ganjil</option>
                                <option value="2">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select class="form-select" id="kelas_id" name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                <option value="1">X IPA 1</option>
                                <option value="2">X IPA 2</option>
                                <option value="3">X IPS 1</option>
                                <option value="4" selected>XI IPA 1</option>
                                <option value="5">XI IPA 2</option>
                                <option value="6">XI IPS 1</option>
                                <option value="7">XII IPA 1</option>
                                <option value="8">XII IPA 2</option>
                                <option value="9">XII IPS 1</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="siswa_id" class="form-label">Siswa</label>
                            <select class="form-select" id="siswa_id" name="siswa_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                <option value="1">Ahmad Rizky (2022001)</option>
                                <option value="2">Anisa Putri (2022002)</option>
                                <option value="3">Budi Santoso (2022003)</option>
                                <option value="4" selected>Dewi Anggraini (2022004)</option>
                                <option value="5">Eko Prasetyo (2022005)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_rapor" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" id="tanggal_rapor" name="tanggal_rapor" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file_rapor" class="form-label">File Rapor (PDF)</label>
                        <input type="file" class="form-control" id="file_rapor" name="file_rapor" accept=".pdf" required>
                        <div class="form-text">Upload file dalam format PDF. Maksimal ukuran file 5MB.</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="konfirmasi" required>
                            <label class="form-check-label" for="konfirmasi">
                                Saya menyatakan bahwa data yang diupload sudah benar dan sesuai
                            </label>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Data Rapor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Rapor -->
<div class="modal fade" id="editRaporModal" tabindex="-1" aria-labelledby="editRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRaporModalLabel">Edit Rapor Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRaporForm" action="{{ route('Rapor.update', 1) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_rapor_id" name="id" value="1">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="edit_tahun_ajaran_id" name="tahun_ajaran_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <option value="1">2022-2023</option>
                                <option value="2" selected>2023-2024</option>
                                <option value="3">2024-2025</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_semester_id" class="form-label">Semester</label>
                            <select class="form-select" id="edit_semester_id" name="semester_id" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="1" selected>Ganjil</option>
                                <option value="2">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_kelas_id" class="form-label">Kelas</label>
                            <select class="form-select" id="edit_kelas_id" name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                <option value="1">X IPA 1</option>
                                <option value="2">X IPA 2</option>
                                <option value="3">X IPS 1</option>
                                <option value="4" selected>XI IPA 1</option>
                                <option value="5">XI IPA 2</option>
                                <option value="6">XI IPS 1</option>
                                <option value="7">XII IPA 1</option>
                                <option value="8">XII IPA 2</option>
                                <option value="9">XII IPS 1</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_siswa_id" class="form-label">Siswa</label>
                            <input type="text" class="form-control" id="edit_siswa_nama" value="Ahmad Rizky (2022001)" readonly>
                            <input type="hidden" id="edit_siswa_id" name="siswa_id" value="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal_rapor" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" id="edit_tanggal_rapor" name="tanggal_rapor" value="2023-12-20" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3">Rapor Semester Ganjil 2023-2024</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Rapor Saat Ini</label>
                        <div class="d-flex align-items-center">
                            <span class="me-2"><i class="fas fa-file-pdf text-danger"></i> rapor_ahmad_rizky_xiipa1_ganjil_2023.pdf</span>
                            <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRaporModal" data-siswa-id="1" data-siswa-nama="Ahmad Rizky" data-rapor-file="rapor_ahmad_rizky_xiipa1_ganjil_2023.pdf">
                                <i class="fas fa-eye me-1"></i> Lihat
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_file_rapor" class="form-label">Ganti File Rapor (PDF)</label>
                        <input type="file" class="form-control" id="edit_file_rapor" name="file_rapor" accept=".pdf">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti file. Upload file dalam format PDF. Maksimal ukuran file 5MB.</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Rapor -->
<div class="modal fade" id="deleteRaporModal" tabindex="-1" aria-labelledby="deleteRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRaporModalLabel">Konfirmasi Hapus Rapor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus rapor siswa <strong id="delete_siswa_nama">Ahmad Rizky</strong>?</p>
                <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan dan file PDF rapor akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteRaporForm" action="{{ route('Rapor.destroy', 1) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_rapor_id" name="id" value="1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Rapor PDF -->
<div class="modal fade" id="viewRaporModal" tabindex="-1" aria-labelledby="viewRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRaporModalLabel">Rapor Siswa: Ahmad Rizky</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <a href="#" id="downloadRaporBtn" class="btn btn-success" download>
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                    <button class="btn btn-primary ms-2" id="printRaporBtn">
                        <i class="fas fa-print me-1"></i> Cetak Rapor
                    </button>
                </div>
                <div class="ratio ratio-16x9">
                    <iframe id="raporViewer" class="pdf-preview" src="/placeholder.svg" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');

        tableRows.forEach(row => {
            const studentName = row.querySelector('.student-name').textContent.toLowerCase();
            const studentId = row.querySelector('.student-id').textContent.toLowerCase();
            const nisNisn = row.cells[2].textContent.toLowerCase();

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
            const kelasMap = {
                '1': 'X IPA 1', '2': 'X IPA 2', '3': 'X IPS 1',
                '4': 'XI IPA 1', '5': 'XI IPA 2', '6': 'XI IPS 1',
                '7': 'XII IPA 1', '8': 'XII IPA 2', '9': 'XII IPS 1'
            };
            infoText += ` kelas <strong>${kelasMap[kelas]}</strong>`;
        } else {
            infoText += ` <strong>semua kelas</strong>`;
        }

        if (tahunAjaran) {
            const tahunMap = {
                '1': '2022-2023', '2': '2023-2024', '3': '2024-2025'
            };
            infoText += ` untuk Tahun Ajaran <strong>${tahunMap[tahunAjaran]}</strong>`;
        }

        if (semester) {
            infoText += ` Semester <strong>${semester === '1' ? 'Ganjil' : 'Genap'}</strong>`;
        }

        document.querySelector('.alert-info div').innerHTML = infoText;

        // Dalam implementasi nyata, ini akan memanggil API untuk mendapatkan data yang difilter
        // Untuk demo, kita hanya simulasikan filter pada data yang sudah ada
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');

        tableRows.forEach(row => {
            const kelasCell = row.cells[3].textContent;
            const statusCell = row.cells[4].textContent;

            let showRow = true;

            if (kelas && !kelasCell.includes(kelas === '4' ? 'XI IPA 1' : '')) {
                showRow = false;
            }

            if (status) {
                const hasRapor = statusCell.includes('Sudah Ada Rapor');
                if ((status === '1' && !hasRapor) || (status === '0' && hasRapor)) {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
        });
    });

    // Reset filter
    document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');
        tableRows.forEach(row => {
            row.style.display = '';
        });

        document.querySelector('.alert-info div').innerHTML = 'Menampilkan data siswa kelas <strong>XI IPA 1</strong> untuk Tahun Ajaran <strong>2023-2024</strong> Semester <strong>Ganjil</strong>';
    });

    // Fungsi untuk modal lihat rapor
    const viewRaporModal = document.getElementById('viewRaporModal');
    if (viewRaporModal) {
        viewRaporModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const siswaId = button.getAttribute('data-siswa-id');
            const siswaNama = button.getAttribute('data-siswa-nama');
            const raporFile = button.getAttribute('data-rapor-file');

            document.getElementById('viewRaporModalLabel').textContent = `Rapor Siswa: ${siswaNama}`;

            // Dalam implementasi nyata, ini akan menjadi URL ke file PDF yang disimpan
            const pdfUrl = `/storage/rapor/${raporFile}`;

            // Untuk demo, kita gunakan PDF sample
            const samplePdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

            document.getElementById('raporViewer').src = samplePdfUrl;
            document.getElementById('downloadRaporBtn').href = samplePdfUrl;
            document.getElementById('downloadRaporBtn').setAttribute('download', raporFile);
        });
    }

    // Fungsi untuk tombol cetak rapor
    document.getElementById('printRaporBtn')?.addEventListener('click', function() {
        const iframe = document.getElementById('raporViewer');
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    });

    // Fungsi untuk modal edit rapor
    const editRaporModal = document.getElementById('editRaporModal');
    if (editRaporModal) {
        editRaporModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const siswaId = button.getAttribute('data-siswa-id');
            const siswaNama = button.getAttribute('data-siswa-nama');

            document.getElementById('edit_siswa_id').value = siswaId;
            document.getElementById('edit_siswa_nama').value = `${siswaNama} (${2022000 + parseInt(siswaId)})`;
            document.getElementById('edit_rapor_id').value = siswaId;

            // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data rapor dari server
            // dan mengisi form dengan data tersebut
        });
    }

    // Fungsi untuk modal hapus rapor
    const deleteRaporModal = document.getElementById('deleteRaporModal');
    if (deleteRaporModal) {
        deleteRaporModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const siswaId = button.getAttribute('data-siswa-id');
            const siswaNama = button.getAttribute('data-siswa-nama');

            document.getElementById('delete_rapor_id').value = siswaId;
            document.getElementById('delete_siswa_nama').textContent = siswaNama;
        });
    }

    // Fungsi untuk modal upload rapor
    const uploadRaporModal = document.getElementById('uploadRaporModal');
    if (uploadRaporModal) {
        uploadRaporModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (button.hasAttribute('data-siswa-id')) {
                const siswaId = button.getAttribute('data-siswa-id');
                const siswaNama = button.getAttribute('data-siswa-nama');

                document.getElementById('siswa_id').value = siswaId;
                document.getElementById('uploadRaporModalLabel').textContent = `Upload Rapor: ${siswaNama}`;
            } else {
                document.getElementById('uploadRaporModalLabel').textContent = 'Upload Rapor Siswa';
                document.getElementById('siswa_id').value = '';
            }
        });
    }

    // Validasi form upload rapor
    const uploadRaporForm = document.getElementById('uploadRaporForm');
    if (uploadRaporForm) {
        uploadRaporForm.addEventListener('submit', function(event) {
            const fileInput = document.getElementById('file_rapor');
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size;

                if (fileSize > maxSize) {
                    event.preventDefault();
                    alert('Ukuran file terlalu besar. Maksimal ukuran file adalah 5MB.');
                    return false;
                }

                const fileType = fileInput.files[0].type;
                if (fileType !== 'application/pdf') {
                    event.preventDefault();
                    alert('Format file tidak valid. Harap upload file PDF.');
                    return false;
                }
            }
        });
    }

    // Validasi form edit rapor
    const editRaporForm = document.getElementById('editRaporForm');
    if (editRaporForm) {
        editRaporForm.addEventListener('submit', function(event) {
            const fileInput = document.getElementById('edit_file_rapor');
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size;

                if (fileSize > maxSize) {
                    event.preventDefault();
                    alert('Ukuran file terlalu besar. Maksimal ukuran file adalah 5MB.');
                    return false;
                }

                const fileType = fileInput.files[0].type;
                if (fileType !== 'application/pdf') {
                    event.preventDefault();
                    alert('Format file tidak valid. Harap upload file PDF.');
                    return false;
                }
            }
        });
    }
});
</script>
</body>
</html>

