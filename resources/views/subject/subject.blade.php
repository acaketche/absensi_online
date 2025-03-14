<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Mata Pelajaran - E-School</title>
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
              <a href="#" class="nav-item text-white d-block mb-2 active">
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
          <a href="#" class="nav-item text-white d-block mb-2">
              <i class="fas fa-graduation-cap me-2"></i>
              <span class="nav-text">Data Nilai</span>
          </a>
      </nav>
  </div>

  <!-- Main Content -->
  <main class="flex-grow-1 p-4">
      <header class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fs-4 fw-bold">Data Mata Pelajaran</h2>
          <div class="d-flex align-items-center">
              <input type="text" placeholder="Cari mata pelajaran" class="form-control me-3" style="width: 200px;" id="searchInput">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                  <i class="fas fa-plus me-1"></i> Tambah Mata Pelajaran
              </button>
          </div>
      </header>

      <!-- Filter Section -->
      <div class="filter-section mb-4">
          <h5 class="mb-3">Filter Data Mata Pelajaran</h5>
          <form id="filterForm" class="row g-3">
              <div class="col-md-6">
                  <label for="filterKategori" class="form-label">Kategori</label>
                  <select class="form-select" id="filterKategori" name="kategori">
                      <option value="">Semua Kategori</option>
                      <option value="wajib">Mata Pelajaran Wajib</option>
                      <option value="peminatan">Mata Pelajaran Peminatan</option>
                      <option value="muatan_lokal">Muatan Lokal</option>
                  </select>
              </div>
              <div class="col-md-6">
                  <label for="filterKelompok" class="form-label">Kelompok</label>
                  <select class="form-select" id="filterKelompok" name="kelompok">
                      <option value="">Semua Kelompok</option>
                      <option value="A">Kelompok A (Umum)</option>
                      <option value="B">Kelompok B (Umum)</option>
                      <option value="C">Kelompok C (Peminatan)</option>
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

      <!-- Data Mata Pelajaran -->
  <!-- Data Mata Pelajaran -->
<div class="card">
    <div class="card-header bg-primary text-white">Daftar Mata Pelajaran</div>
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
            <table class="table table-bordered table-hover" id="subjectTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Nama Mata Pelajaran</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $key => $subject)
                        <tr>
                            <td>{{ $subject->subject_id }}</td>
                            <td>{{ $subject->subject_name }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewSubjectModal" data-subject-id="{{ $subject->subject_id }}" data-subject-name="{{ $subject->subject_name }}">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </button>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSubjectModal" data-subject-id="{{ $subject->subject_id }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSubjectModal" data-subject-id="{{ $subject->subject_id }}" data-subject-name="{{ $subject->subject_name }}">
                                        <i class="fas fa-trash me-1"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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

<!-- Modal Tambah Mata Pelajaran -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="addSubjectModalLabel">Tambah Mata Pelajaran Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="addSubjectForm" action="{{ route('subjects.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                      <label for="subject_name" class="form-label">Nama Mata Pelajaran</label>
                      <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                  </div>
                  <div class="mb-3">
                      <label for="kategori" class="form-label">Kategori</label>
                      <select class="form-select" id="kategori" name="kategori" required>
                          <option value="">-- Pilih Kategori --</option>
                          <option value="wajib">Mata Pelajaran Wajib</option>
                          <option value="peminatan">Mata Pelajaran Peminatan</option>
                          <option value="muatan_lokal">Muatan Lokal</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="kelompok" class="form-label">Kelompok</label>
                      <select class="form-select" id="kelompok" name="kelompok" required>
                          <option value="">-- Pilih Kelompok --</option>
                          <option value="A">Kelompok A (Umum)</option>
                          <option value="B">Kelompok B (Umum)</option>
                          <option value="C">Kelompok C (Peminatan)</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="kode" class="form-label">Kode Mata Pelajaran (Opsional)</label>
                      <input type="text" class="form-control" id="kode" name="kode">
                      <div class="form-text">Contoh: MTK, BIG, FIS, dll.</div>
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

<!-- Modal Lihat Mata Pelajaran -->
<div class="modal fade" id="viewSubjectModal" tabindex="-1" aria-labelledby="viewSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="viewSubjectModalLabel">Detail Mata Pelajaran</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <h5 class="border-bottom pb-2">Informasi Mata Pelajaran</h5>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">ID</div>
                      <div class="col-8" id="view_subject_id">1</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Nama</div>
                      <div class="col-8" id="view_subject_name">Pendidikan Agama dan Budi Pekerti</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Kategori</div>
                      <div class="col-8" id="view_kategori">Mata Pelajaran Wajib</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Kelompok</div>
                      <div class="col-8" id="view_kelompok">Kelompok A (Umum)</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Kode</div>
                      <div class="col-8" id="view_kode">PAI</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Dibuat Pada</div>
                      <div class="col-8" id="view_created_at">2023-07-10 08:00:00</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Diperbarui Pada</div>
                      <div class="col-8" id="view_updated_at">2023-07-10 08:00:00</div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
      </div>
  </div>
</div>

<!-- Modal Edit Mata Pelajaran -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editSubjectModalLabel">Edit Mata Pelajaran</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="editSubjectForm" action="{{ route('subjects.update', 1) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input type="hidden" id="edit_subject_id" name="subject_id" value="1">
                  <div class="mb-3">
                      <label for="edit_subject_name" class="form-label">Nama Mata Pelajaran</label>
                      <input type="text" class="form-control" id="edit_subject_name" name="subject_name" value="Pendidikan Agama dan Budi Pekerti" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_kategori" class="form-label">Kategori</label>
                      <select class="form-select" id="edit_kategori" name="kategori" required>
                          <option value="">-- Pilih Kategori --</option>
                          <option value="wajib" selected>Mata Pelajaran Wajib</option>
                          <option value="peminatan">Mata Pelajaran Peminatan</option>
                          <option value="muatan_lokal">Muatan Lokal</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="edit_kelompok" class="form-label">Kelompok</label>
                      <select class="form-select" id="edit_kelompok" name="kelompok" required>
                          <option value="">-- Pilih Kelompok --</option>
                          <option value="A" selected>Kelompok A (Umum)</option>
                          <option value="B">Kelompok B (Umum)</option>
                          <option value="C">Kelompok C (Peminatan)</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="edit_kode" class="form-label">Kode Mata Pelajaran (Opsional)</label>
                      <input type="text" class="form-control" id="edit_kode" name="kode" value="PAI">
                      <div class="form-text">Contoh: MTK, BIG, FIS, dll.</div>
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

<!-- Modal Hapus Mata Pelajaran -->
<div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="deleteSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="deleteSubjectModalLabel">Konfirmasi Hapus Mata Pelajaran</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p>Apakah Anda yakin ingin menghapus mata pelajaran <strong id="delete_subject_name">Pendidikan Agama dan Budi Pekerti</strong>?</p>
              <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini akan menghapus data mata pelajaran secara permanen. Pastikan mata pelajaran ini tidak digunakan dalam jadwal pelajaran atau data nilai sebelum menghapus.</p>
          </div>
          <div class="modal-footer">
              <form id="deleteSubjectForm" action="{{ route('subjects.destroy', 1) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" id="delete_subject_id" name="subject_id" value="1">
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
      const tableRows = document.querySelectorAll('#subjectTable tbody tr');

      tableRows.forEach(row => {
          const subjectName = row.cells[2].textContent.toLowerCase();
          const kategori = row.cells[3].textContent.toLowerCase();

          if (subjectName.includes(searchValue) || kategori.includes(searchValue)) {
              row.style.display = '';
          } else {
              row.style.display = 'none';
          }
      });
  });

  // Fungsi filter
  document.getElementById('filterForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const kategori = document.getElementById('filterKategori').value;
      const kelompok = document.getElementById('filterKelompok').value;

      const tableRows = document.querySelectorAll('#subjectTable tbody tr');

      tableRows.forEach(row => {
          let showRow = true;

          // Filter berdasarkan kategori
          if (kategori && !row.cells[3].textContent.toLowerCase().includes(kategori)) {
              showRow = false;
          }

          // Filter berdasarkan kelompok (dalam implementasi nyata, ini akan lebih kompleks)
          if (kelompok && !row.getAttribute('data-kelompok')?.includes(kelompok)) {
              showRow = false;
          }

          row.style.display = showRow ? '' : 'none';
      });
  });

  // Reset filter
  document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
      const tableRows = document.querySelectorAll('#subjectTable tbody tr');
      tableRows.forEach(row => {
          row.style.display = '';
      });
  });

  // Fungsi untuk modal lihat mata pelajaran
  const viewSubjectModal = document.getElementById('viewSubjectModal');
  if (viewSubjectModal) {
      viewSubjectModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const subjectId = button.getAttribute('data-subject-id');
          const subjectName = button.getAttribute('data-subject-name');

          document.getElementById('view_subject_id').textContent = subjectId;
          document.getElementById('view_subject_name').textContent = subjectName;

          // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data mata pelajaran dari server
          // dan mengisi detail mata pelajaran dengan data tersebut

          // Untuk demo, kita gunakan data statis
          if (subjectId === '1') {
              document.getElementById('view_kategori').textContent = 'Mata Pelajaran Wajib';
              document.getElementById('view_kelompok').textContent = 'Kelompok A (Umum)';
              document.getElementById('view_kode').textContent = 'PAI';
              document.getElementById('view_created_at').textContent = '2023-07-10 08:00:00';
              document.getElementById('view_updated_at').textContent = '2023-07-10 08:00:00';
          } else if (subjectId === '2') {
              document.getElementById('view_kategori').textContent = 'Mata Pelajaran Wajib';
              document.getElementById('view_kelompok').textContent = 'Kelompok A (Umum)';
              document.getElementById('view_kode').textContent = 'PPKn';
              document.getElementById('view_created_at').textContent = '2023-07-10 08:15:00';
              document.getElementById('view_updated_at').textContent = '2023-07-10 08:15:00';
          }
      });
  }

  // Fungsi untuk modal edit mata pelajaran
  const editSubjectModal = document.getElementById('editSubjectModal');
  if (editSubjectModal) {
      editSubjectModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const subjectId = button.getAttribute('data-subject-id');

          document.getElementById('edit_subject_id').value = subjectId;

          // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data mata pelajaran dari server
          // dan mengisi form dengan data tersebut

          // Untuk demo, kita gunakan data statis
          if (subjectId === '1') {
              document.getElementById('edit_subject_name').value = 'Pendidikan Agama dan Budi Pekerti';
              document.getElementById('edit_kategori').value = 'wajib';
              document.getElementById('edit_kelompok').value = 'A';
              document.getElementById('edit_kode').value = 'PAI';
          } else if (subjectId === '2') {
              document.getElementById('edit_subject_name').value = 'Pendidikan Pancasila dan Kewarganegaraan';
              document.getElementById('edit_kategori').value = 'wajib';
              document.getElementById('edit_kelompok').value = 'A';
              document.getElementById('edit_kode').value = 'PPKn';
          } else if (subjectId === '6') {
              document.getElementById('edit_subject_name').value = 'Fisika';
              document.getElementById('edit_kategori').value = 'peminatan';
              document.getElementById('edit_kelompok').value = 'C';
              document.getElementById('edit_kode').value = 'FIS';
          }

          // Update action URL form
          document.getElementById('editSubjectForm').action = `{{ route('subjects.update', '') }}/${subjectId}`;
      });
  }

  // Fungsi untuk modal hapus mata pelajaran
  const deleteSubjectModal = document.getElementById('deleteSubjectModal');
  if (deleteSubjectModal) {
      deleteSubjectModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const subjectId = button.getAttribute('data-subject-id');
          const subjectName = button.getAttribute('data-subject-name');

          document.getElementById('delete_subject_id').value = subjectId;
          document.getElementById('delete_subject_name').textContent = subjectName;

          // Update action URL form
          document.getElementById('deleteSubjectForm').action = `{{ route('subjects.destroy', '') }}/${subjectId}`;
      });
  }
});
</script>
</body>
</html>
