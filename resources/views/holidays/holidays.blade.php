<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Hari Libur - E-School</title>
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

      .filter-section {
          background-color: #f8f9fa;
          border-radius: 0.25rem;
          padding: 1rem;
          margin-bottom: 1rem;
      }

      .holiday-badge {
          font-size: 0.8rem;
          padding: 0.3rem 0.5rem;
      }

      .holiday-national {
          background-color: #dc3545;
      }

      .holiday-school {
          background-color: #ffc107;
      }

      .holiday-religious {
          background-color: #28a745;
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
              <a href="#" class="nav-item text-white d-block mb-2">
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
              <a href="#" class="nav-item text-white d-block mb-2 active">
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
          <a href="#" class="nav-item text-white d-block mb-2">
              <i class="fas fa-graduation-cap me-2"></i>
              <span class="nav-text">Data Nilai</span>
          </a>
      </nav>
  </div>

  <!-- Main Content -->
  <main class="flex-grow-1 p-4">
      <header class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fs-4 fw-bold">Data Hari Libur</h2>
          <div class="d-flex align-items-center">
              <input type="text" placeholder="Cari hari libur" class="form-control me-3" style="width: 200px;" id="searchInput">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                  <i class="fas fa-plus me-1"></i> Tambah Hari Libur
              </button>
          </div>
      </header>

      <!-- Filter Section -->
      <div class="filter-section mb-4">
          <h5 class="mb-3">Filter Data Hari Libur</h5>
          <form id="filterForm" class="row g-3">
              <div class="col-md-4">
                  <label for="filterTahunAjaran" class="form-label">Tahun Ajaran</label>
                  <select class="form-select" id="filterTahunAjaran" name="academic_year_id">
                      <option value="">Semua Tahun Ajaran</option>
                      <option value="1">2022-2023</option>
                      <option value="2" selected>2023-2024</option>
                      <option value="3">2024-2025</option>
                  </select>
              </div>
              <div class="col-md-4">
                  <label for="filterJenis" class="form-label">Jenis Libur</label>
                  <select class="form-select" id="filterJenis" name="jenis">
                      <option value="">Semua Jenis</option>
                      <option value="nasional">Libur Nasional</option>
                      <option value="sekolah">Libur Sekolah</option>
                      <option value="keagamaan">Libur Keagamaan</option>
                  </select>
              </div>
              <div class="col-md-4">
                  <label for="filterBulan" class="form-label">Bulan</label>
                  <select class="form-select" id="filterBulan" name="bulan">
                      <option value="">Semua Bulan</option>
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
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

      <!-- Data Hari Libur -->
      <div class="card">
          <div class="card-header bg-primary text-white">Daftar Hari Libur</div>
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
                  <table class="table table-bordered table-hover" id="holidayTable">
                      <thead class="table-light">
                          <tr>
                              <th width="5%">No</th>
                              <th width="10%">ID</th>
                              <th width="15%">Tanggal</th>
                              <th width="30%">Deskripsi</th>
                              <th width="15%">Jenis</th>
                              <th width="10%">Tahun Ajaran</th>
                              <th width="15%">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                          <!-- Hari Libur 1 -->
                          <tr>
                              <td>1</td>
                              <td>1</td>
                              <td>17 Agustus 2023</td>
                              <td>Hari Kemerdekaan Republik Indonesia</td>
                              <td><span class="badge holiday-badge holiday-national">Libur Nasional</span></td>
                              <td>2023-2024</td>
                              <td>
                                  <div class="d-flex gap-1">
                                      <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewHolidayModal" data-holiday-id="1" data-holiday-desc="Hari Kemerdekaan Republik Indonesia">
                                          <i class="fas fa-eye me-1"></i> Lihat
                                      </button>
                                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-holiday-id="1">
                                          <i class="fas fa-edit me-1"></i> Edit
                                      </button>
                                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal" data-holiday-id="1" data-holiday-desc="Hari Kemerdekaan Republik Indonesia">
                                          <i class="fas fa-trash me-1"></i>
                                      </button>
                                  </div>
                              </td>
                          </tr>

                          <!-- Hari Libur 2 -->
                          <tr>
                              <td>2</td>
                              <td>2</td>
                              <td>25 Desember 2023</td>
                              <td>Hari Natal</td>
                              <td><span class="badge holiday-badge holiday-religious">Libur Keagamaan</span></td>
                              <td>2023-2024</td>
                              <td>
                                  <div class="d-flex gap-1">
                                      <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewHolidayModal" data-holiday-id="2" data-holiday-desc="Hari Natal">
                                          <i class="fas fa-eye me-1"></i> Lihat
                                      </button>
                                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-holiday-id="2">
                                          <i class="fas fa-edit me-1"></i> Edit
                                      </button>
                                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal" data-holiday-id="2" data-holiday-desc="Hari Natal">
                                          <i class="fas fa-trash me-1"></i>
                                      </button>
                                  </div>
                              </td>
                          </tr>

                          <!-- Hari Libur 3 -->
                          <tr>
                              <td>3</td>
                              <td>3</td>
                              <td>1 Januari 2024</td>
                              <td>Tahun Baru 2024</td>
                              <td><span class="badge holiday-badge holiday-national">Libur Nasional</span></td>
                              <td>2023-2024</td>
                              <td>
                                  <div class="d-flex gap-1">
                                      <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewHolidayModal" data-holiday-id="3" data-holiday-desc="Tahun Baru 2024">
                                          <i class="fas fa-eye me-1"></i> Lihat
                                      </button>
                                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-holiday-id="3">
                                          <i class="fas fa-edit me-1"></i> Edit
                                      </button>
                                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal" data-holiday-id="3" data-holiday-desc="Tahun Baru 2024">
                                          <i class="fas fa-trash me-1"></i>
                                      </button>
                                  </div>
                              </td>
                          </tr>

                          <!-- Hari Libur 4 -->
                          <tr>
                              <td>4</td>
                              <td>4</td>
                              <td>22 Maret 2024</td>
                              <td>Hari Raya Nyepi</td>
                              <td><span class="badge holiday-badge holiday-religious">Libur Keagamaan</span></td>
                              <td>2023-2024</td>
                              <td>
                                  <div class="d-flex gap-1">
                                      <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewHolidayModal" data-holiday-id="4" data-holiday-desc="Hari Raya Nyepi">
                                          <i class="fas fa-eye me-1"></i> Lihat
                                      </button>
                                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-holiday-id="4">
                                          <i class="fas fa-edit me-1"></i> Edit
                                      </button>
                                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal" data-holiday-id="4" data-holiday-desc="Hari Raya Nyepi">
                                          <i class="fas fa-trash me-1"></i>
                                      </button>
                                  </div>
                              </td>
                          </tr>

                          <!-- Hari Libur 5 -->
                          <tr>
                              <td>5</td>
                              <td>5</td>
                              <td>15 Juni 2024</td>
                              <td>Libur Akhir Semester Genap</td>
                              <td><span class="badge holiday-badge holiday-school">Libur Sekolah</span></td>
                              <td>2023-2024</td>
                              <td>
                                  <div class="d-flex gap-1">
                                      <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewHolidayModal" data-holiday-id="5" data-holiday-desc="Libur Akhir Semester Genap">
                                          <i class="fas fa-eye me-1"></i> Lihat
                                      </button>
                                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-holiday-id="5">
                                          <i class="fas fa-edit me-1"></i> Edit
                                      </button>
                                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal" data-holiday-id="5" data-holiday-desc="Libur Akhir Semester Genap">
                                          <i class="fas fa-trash me-1"></i>
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

<!-- Modal Tambah Hari Libur -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="addHolidayModalLabel">Tambah Hari Libur Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="addHolidayForm" action="{{ route('holidays.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                      <label for="holiday_date" class="form-label">Tanggal Libur</label>
                      <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                  </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">Deskripsi</label>
                      <input type="text" class="form-control" id="description" name="description" required>
                  </div>
                  <div class="mb-3">
                      <label for="jenis" class="form-label">Jenis Libur</label>
                      <select class="form-select" id="jenis" name="jenis" required>
                          <option value="">-- Pilih Jenis Libur --</option>
                          <option value="nasional">Libur Nasional</option>
                          <option value="sekolah">Libur Sekolah</option>
                          <option value="keagamaan">Libur Keagamaan</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                      <select class="form-select" id="academic_year_id" name="academic_year_id" required>
                          <option value="">-- Pilih Tahun Ajaran --</option>
                          <option value="1">2022-2023</option>
                          <option value="2" selected>2023-2024</option>
                          <option value="3">2024-2025</option>
                      </select>
                  </div>
                  <div class="d-grid">
                      <button type="submit" class="btn btn-primary">
                          <i class="fas fa-save me-1"></i> Simpan
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

<!-- Modal Lihat Hari Libur -->
<div class="modal fade" id="viewHolidayModal" tabindex="-1" aria-labelledby="viewHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="viewHolidayModalLabel">Detail Hari Libur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <h5 class="border-bottom pb-2">Informasi Hari Libur</h5>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">ID</div>
                      <div class="col-8" id="view_holiday_id">1</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Tanggal</div>
                      <div class="col-8" id="view_holiday_date">17 Agustus 2023</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Deskripsi</div>
                      <div class="col-8" id="view_description">Hari Kemerdekaan Republik Indonesia</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Jenis</div>
                      <div class="col-8" id="view_jenis">Libur Nasional</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Tahun Ajaran</div>
                      <div class="col-8" id="view_academic_year">2023-2024</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Dibuat Pada</div>
                      <div class="col-8" id="view_created_at">2023-07-01 10:00:00</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Diperbarui Pada</div>
                      <div class="col-8" id="view_updated_at">2023-07-01 10:00:00</div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
      </div>
  </div>
</div>

<!-- Modal Edit Hari Libur -->
<div class="modal fade" id="editHolidayModal" tabindex="-1" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editHolidayModalLabel">Edit Hari Libur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="editHolidayForm" action="{{ route('holidays.update', 1) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input type="hidden" id="edit_holiday_id" name="id" value="1">
                  <div class="mb-3">
                      <label for="edit_holiday_date" class="form-label">Tanggal Libur</label>
                      <input type="date" class="form-control" id="edit_holiday_date" name="holiday_date" value="2023-08-17" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_description" class="form-label">Deskripsi</label>
                      <input type="text" class="form-control" id="edit_description" name="description" value="Hari Kemerdekaan Republik Indonesia" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_jenis" class="form-label">Jenis Libur</label>
                      <select class="form-select" id="edit_jenis" name="jenis" required>
                          <option value="">-- Pilih Jenis Libur --</option>
                          <option value="nasional" selected>Libur Nasional</option>
                          <option value="sekolah">Libur Sekolah</option>
                          <option value="keagamaan">Libur Keagamaan</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="edit_academic_year_id" class="form-label">Tahun Ajaran</label>
                      <select class="form-select" id="edit_academic_year_id" name="academic_year_id" required>
                          <option value="">-- Pilih Tahun Ajaran --</option>
                          <option value="1">2022-2023</option>
                          <option value="2" selected>2023-2024</option>
                          <option value="3">2024-2025</option>
                      </select>
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

<!-- Modal Hapus Hari Libur -->
<div class="modal fade" id="deleteHolidayModal" tabindex="-1" aria-labelledby="deleteHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="deleteHolidayModalLabel">Konfirmasi Hapus Hari Libur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p>Apakah Anda yakin ingin menghapus hari libur <strong id="delete_holiday_desc">Hari Kemerdekaan Republik Indonesia</strong>?</p>
              <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini akan menghapus data hari libur secara permanen.</p>
          </div>
          <div class="modal-footer">
              <form id="deleteHolidayForm" action="{{ route('holidays.destroy', 1) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" id="delete_holiday_id" name="id" value="1">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-danger">
                      <i class="fas fa-trash me-1"></i> Hapus
                  </button>
              </form>
          </div>
      </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fungsi pencarian
  document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchValue = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('#holidayTable tbody tr');

      tableRows.forEach(row => {
          const holidayDate = row.cells[2].textContent.toLowerCase();
          const description = row.cells[3].textContent.toLowerCase();
          const jenis = row.cells[4].textContent.toLowerCase();

          if (holidayDate.includes(searchValue) || description.includes(searchValue) || jenis.includes(searchValue)) {
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
      const jenis = document.getElementById('filterJenis').value;
      const bulan = document.getElementById('filterBulan').value;

      const tableRows = document.querySelectorAll('#holidayTable tbody tr');

      tableRows.forEach(row => {
          let showRow = true;

          // Filter berdasarkan tahun ajaran
          if (tahunAjaran && !row.cells[5].textContent.includes(tahunAjaran === '1' ? '2022-2023' : tahunAjaran === '2' ? '2023-2024' : '2024-2025')) {
              showRow = false;
          }

          // Filter berdasarkan jenis
          if (jenis && !row.cells[4].textContent.toLowerCase().includes(jenis)) {
              showRow = false;
          }

          // Filter berdasarkan bulan
          if (bulan) {
              const monthNames = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
              const monthName = monthNames[parseInt(bulan) - 1];
              if (!row.cells[2].textContent.toLowerCase().includes(monthName)) {
                  showRow = false;
              }
          }

          row.style.display = showRow ? '' : 'none';
      });
  });

  // Reset filter
  document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
      const tableRows = document.querySelectorAll('#holidayTable tbody tr');
      tableRows.forEach(row => {
          row.style.display = '';
      });
  });

  // Fungsi untuk modal lihat hari libur
  const viewHolidayModal = document.getElementById('viewHolidayModal');
  if (viewHolidayModal) {
      viewHolidayModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const holidayId = button.getAttribute('data-holiday-id');
          const holidayDesc = button.getAttribute('data-holiday-desc');

          document.getElementById('view_holiday_id').textContent = holidayId;
          document.getElementById('view_description').textContent = holidayDesc;

          // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data hari libur dari server
          // dan mengisi detail hari libur dengan data tersebut

          // Untuk demo, kita gunakan data statis
          if (holidayId === '1') {
              document.getElementById('view_holiday_date').textContent = '17 Agustus 2023';
              document.getElementById('view_jenis').textContent = 'Libur Nasional';
              document.getElementById('view_academic_year').textContent = '2023-2024';
              document.getElementById('view_created_at').textContent = '2023-07-01 10:00:00';
              document.getElementById('view_updated_at').textContent = '2023-07-01 10:00:00';
          } else if (holidayId === '2') {
              document.getElementById('view_holiday_date').textContent = '25 Desember 2023';
              document.getElementById('view_jenis').textContent = 'Libur Keagamaan';
              document.getElementById('view_academic_year').textContent = '2023-2024';
              document.getElementById('view_created_at').textContent = '2023-07-01 10:15:00';
              document.getElementById('view_updated_at').textContent = '2023-07-01 10:15:00';
          }
      });
  }

  // Fungsi untuk modal edit hari libur
  const editHolidayModal = document.getElementById('editHolidayModal');
  if (editHolidayModal) {
      editHolidayModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const holidayId = button.getAttribute('data-holiday-id');

          document.getElementById('edit_holiday_id').value = holidayId;

          // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data hari libur dari server
          // dan mengisi form dengan data tersebut

          // Untuk demo, kita gunakan data statis
          if (holidayId === '1') {
              document.getElementById('edit_holiday_date').value = '2023-08-17';
              document.getElementById('edit_description').value = 'Hari Kemerdekaan Republik Indonesia';
              document.getElementById('edit_jenis').value = 'nasional';
              document.getElementById('edit_academic_year_id').value = '2';
          } else if (holidayId === '2') {
              document.getElementById('edit_holiday_date').value = '2023-12-25';
              document.getElementById('edit_description').value = 'Hari Natal';
              document.getElementById('edit_jenis').value = 'keagamaan';
              document.getElementById('edit_academic_year_id').value = '2';
          }

          // Update action URL form
          document.getElementById('editHolidayForm').action = `{{ route('holidays.update', '') }}/${holidayId}`;
      });
  }

  // Fungsi untuk modal hapus hari libur
  const deleteHolidayModal = document.getElementById('deleteHolidayModal');
  if (deleteHolidayModal) {
      deleteHolidayModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const holidayId = button.getAttribute('data-holiday-id');
          const holidayDesc = button.getAttribute('data-holiday-desc');

          document.getElementById('delete_holiday_id').value = holidayId;
          document.getElementById('delete_holiday_desc').textContent = holidayDesc;

          // Update action URL form
          document.getElementById('deleteHolidayForm').action = `{{ route('holidays.destroy', '') }}/${holidayId}`;
      });
  }
});
</script>
</body>
</html>
