<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Kelas - E-School</title>
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

      .teacher-info {
          display: flex;
          align-items: center;
          gap: 15px;
      }

      .teacher-photo {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          object-fit: cover;
      }

      .teacher-details {
          display: flex;
          flex-direction: column;
      }

      .teacher-name {
          font-weight: bold;
          margin-bottom: 0;
      }

      .teacher-id {
          font-size: 0.8rem;
          color: #6c757d;
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
              <a href="#" class="nav-item text-white d-block mb-2 active">
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
          <a href="#" class="nav-item text-white d-block mb-2">
              <i class="fas fa-graduation-cap me-2"></i>
              <span class="nav-text">Data Nilai</span>
          </a>
      </nav>
  </div>

  <!-- Main Content -->
  <main class="flex-grow-1 p-4">
      <header class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fs-4 fw-bold">Data Kelas</h2>
          <div class="d-flex align-items-center">
              <input type="text" placeholder="Cari kelas" class="form-control me-3" style="width: 200px;" id="searchInput">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                  <i class="fas fa-plus me-1"></i> Tambah Kelas
              </button>
          </div>
      </header>

      <!-- Filter Section -->
      <div class="filter-section mb-4">
          <h5 class="mb-3">Filter Data Kelas</h5>
          <form id="filterForm" class="row g-3">
              <div class="col-md-6">
                  <label for="filterTingkat" class="form-label">Tingkat</label>
                  <select class="form-select" id="filterTingkat" name="tingkat">
                      <option value="">Semua Tingkat</option>
                      <option value="X">X (Sepuluh)</option>
                      <option value="XI">XI (Sebelas)</option>
                      <option value="XII">XII (Dua Belas)</option>
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

     <!-- Data Kelas -->
<div class="card">
    <div class="card-header bg-primary text-white">Daftar Kelas</div>
    <div class="card-body">
        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="text-center d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="alert alert-danger d-none">
            Terjadi kesalahan saat memuat data. Silahkan coba lagi.
        </div>

        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="classTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Kelas</th>
                        <th width="35%">Wali Kelas</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $class->class_name }}</td>
                        <td>
                            <div class="teacher-info d-flex align-items-center">
                                <img src="{{ $class->teacher->photo_url ?? 'https://randomuser.me/api/portraits/men/1.jpg' }}"
                                     alt="Foto {{ $class->employee->fullname }}"
                                     class="teacher-photo rounded-circle me-3"
                                     width="40" height="40">
                                <div class="teacher-details">
                                    <p class="teacher-name mb-0">{{ $class->employee->fullname  }}, {{ $class->employee->qualification }}</p>
                                    <small class="text-muted">NIP: {{ $class->employee->id_employee }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Tombol Lihat -->
                                <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewClassModal"
                                        data-class-id="{{ $class->id_class }}"
                                        data-class-name="{{ $class->class_name }}">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </button>

                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editClassModal"
                                        data-class-id="{{ $class->id_class }}">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteClassModal"
                                        data-class-id="{{ $class->id_class }}"
                                        data-class-name="{{ $class->class_name }}">
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

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="addClassModalLabel">Tambah Kelas Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="addClassForm" action="{{ route('classes.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                      <label for="class_name" class="form-label">Nama Kelas</label>
                      <input type="text" class="form-control" id="class_name" name="class_name" required>
                      <div class="form-text">Contoh: X IPA 1, XI IPS 2, XII IPA 3, dll.</div>
                  </div>
                  <div class="mb-3">
                      <label for="id_employee" class="form-label">Wali Kelas</label>
                      <select class="form-select" id="id_employee" name="id_employee" required>
                          <option value="">-- Pilih Wali Kelas --</option>
                          <option value="1">Ahmad Fauzi, S.Pd (NIP: 198501152010011001)</option>
                          <option value="2">Siti Nurhaliza, M.Pd (NIP: 198603202011012002)</option>
                          <option value="3">Budi Santoso, S.Pd (NIP: 198709102012011003)</option>
                          <option value="4">Dewi Anggraini, S.Pd (NIP: 198805152013012004)</option>
                          <option value="5">Eko Prasetyo, M.Pd (NIP: 198910202014011005)</option>
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

<!-- Modal Lihat Kelas -->
<div class="modal fade" id="viewClassModal" tabindex="-1" aria-labelledby="viewClassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="viewClassModalLabel">Detail Kelas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <h5 class="border-bottom pb-2">Informasi Kelas</h5>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">ID Kelas</div>
                      <div class="col-8" id="view_class_id">1</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Nama Kelas</div>
                      <div class="col-8" id="view_class_name">X IPA 1</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Wali Kelas</div>
                      <div class="col-8" id="view_employee_name">Ahmad Fauzi, S.Pd</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">NIP</div>
                      <div class="col-8" id="view_employee_nip">198501152010011001</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Dibuat Pada</div>
                      <div class="col-8" id="view_created_at">2023-07-15 08:30:00</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Diperbarui Pada</div>
                      <div class="col-8" id="view_updated_at">2023-07-15 08:30:00</div>
                  </div>
              </div>
              <div class="mb-3">
                  <h5 class="border-bottom pb-2">Jumlah Siswa</h5>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Total Siswa</div>
                      <div class="col-8" id="view_student_count">32 siswa</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Laki-laki</div>
                      <div class="col-8" id="view_male_count">15 siswa</div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-4 fw-bold">Perempuan</div>
                      <div class="col-8" id="view_female_count">17 siswa</div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <a href="#" class="btn btn-primary" id="view_student_list_btn">
                  <i class="fas fa-users me-1"></i> Lihat Daftar Siswa
              </a>
          </div>
      </div>
  </div>
</div>

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editClassModalLabel">Edit Kelas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="editClassForm" action="{{ route('classes.update', 1) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input type="hidden" id="edit_class_id" name="class_id" value="1">
                  <div class="mb-3">
                      <label for="edit_class_name" class="form-label">Nama Kelas</label>
                      <input type="text" class="form-control" id="edit_class_name" name="class_name" value="X IPA 1" required>
                      <div class="form-text">Contoh: X IPA 1, XI IPS 2, XII IPA 3, dll.</div>
                  </div>
                  <div class="mb-3">
                      <label for="edit_id_employee" class="form-label">Wali Kelas</label>
                      <select class="form-select" id="edit_id_employee" name="id_employee" required>
                          <option value="">-- Pilih Wali Kelas --</option>
                          <option value="1" selected>Ahmad Fauzi, S.Pd (NIP: 198501152010011001)</option>
                          <option value="2">Siti Nurhaliza, M.Pd (NIP: 198603202011012002)</option>
                          <option value="3">Budi Santoso, S.Pd (NIP: 198709102012011003)</option>
                          <option value="4">Dewi Anggraini, S.Pd (NIP: 198805152013012004)</option>
                          <option value="5">Eko Prasetyo, M.Pd (NIP: 198910202014011005)</option>
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

<!-- Modal Hapus Kelas -->
<div class="modal fade" id="deleteClassModal" tabindex="-1" aria-labelledby="deleteClassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="deleteClassModalLabel">Konfirmasi Hapus Kelas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p>Apakah Anda yakin ingin menghapus kelas <strong id="delete_class_name">X IPA 1</strong>?</p>
              <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini akan menghapus data kelas secara permanen. Pastikan tidak ada siswa yang terdaftar di kelas ini sebelum menghapus.</p>
          </div>
          <div class="modal-footer">
              <form id="deleteClassForm" action="{{ route('classes.destroy', 1) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" id="delete_class_id" name="class_id" value="1">
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
      const tableRows = document.querySelectorAll('#classTable tbody tr');

      tableRows.forEach(row => {
          const className = row.cells[2].textContent.toLowerCase();
          const teacherName = row.querySelector('.teacher-name').textContent.toLowerCase();
          const teacherId = row.querySelector('.teacher-id').textContent.toLowerCase();

          if (className.includes(searchValue) || teacherName.includes(searchValue) || teacherId.includes(searchValue)) {
              row.style.display = '';
          } else {
              row.style.display = 'none';
          }
      });
  });

  // Fungsi filter
  document.getElementById('filterForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const waliKelas = document.getElementById('filterWaliKelas').value;
      const tingkat = document.getElementById('filterTingkat').value;

      const tableRows = document.querySelectorAll('#classTable tbody tr');

      tableRows.forEach(row => {
          let showRow = true;

          // Filter berdasarkan wali kelas
          if (waliKelas && !row.querySelector('.teacher-info').getAttribute('data-employee-id')?.includes(waliKelas)) {
              showRow = false;
          }

          // Filter berdasarkan tingkat
          if (tingkat && !row.cells[2].textContent.startsWith(tingkat)) {
              showRow = false;
          }

          row.style.display = showRow ? '' : 'none';
      });
  });

  // Reset filter
  document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
      const tableRows = document.querySelectorAll('#classTable tbody tr');
      tableRows.forEach(row => {
          row.style.display = '';
      });
  });

  // Fungsi untuk modal lihat kelas
  const viewClassModal = document.getElementById('viewClassModal');
  if (viewClassModal) {
      viewClassModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const classId = button.getAttribute('data-class-id');
          const className = button.getAttribute('data-class-name');

          document.getElementById('view_class_id').textContent = classId;
          document.getElementById('view_class_name').textContent = className;

          // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data kelas dari server
          // dan mengisi detail kelas dengan data tersebut

          // Untuk demo, kita gunakan data statis
          if (classId === '1') {
              document.getElementById('view_employee_name').textContent = 'Ahmad Fauzi, S.Pd';
              document.getElementById('view_employee_nip').textContent = '198501152010011001';
              document.getElementById('view_created_at').textContent = '2023-07-15 08:30:00';
              document.getElementById('view_updated_at').textContent = '2023-07-15 08:30:00';
              document.getElementById('view_student_count').textContent = '32 siswa';
              document.getElementById('view_male_count').textContent = '15 siswa';
              document.getElementById('view_female_count').textContent = '17 siswa';
          } else if (classId === '2') {
              document.getElementById('view_employee_name').textContent = 'Siti Nurhaliza, M.Pd';
              document.getElementById('view_employee_nip').textContent = '198603202011012002';
              document.getElementById('view_created_at').textContent = '2023-07-15 09:15:00';
              document.getElementById('view_updated_at').textContent = '2023-07-15 09:15:00';
              document.getElementById('view_student_count').textContent = '30 siswa';
              document.getElementById('view_male_count').textContent = '14 siswa';
              document.getElementById('view_female_count').textContent = '16 siswa';
          }

          // Set URL untuk tombol lihat daftar siswa
          document.getElementById('view_student_list_btn').href = `/students?class_id=${classId}`;
      });
  }

  // Fungsi untuk modal edit kelas
  const editClassModal = document.getElementById('editClassModal');
  if (editClassModal) {
      editClassModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const classId = button.getAttribute('data-class-id');

          document.getElementById('edit_class_id').value = classId;

          // Dalam implementasi nyata, di sini akan ada kode untuk mengambil data kelas dari server
          // dan mengisi form dengan data tersebut

          // Untuk demo, kita gunakan data statis
          if (classId === '1') {
              document.getElementById('edit_class_name').value = 'X IPA 1';
              document.getElementById('edit_id_employee').value = '1';
          } else if (classId === '2') {
              document.getElementById('edit_class_name').value = 'X IPA 2';
              document.getElementById('edit_id_employee').value = '2';
          } else if (classId === '3') {
              document.getElementById('edit_class_name').value = 'X IPS 1';
              document.getElementById('edit_id_employee').value = '3';
          } else if (classId === '4') {
              document.getElementById('edit_class_name').value = 'XI IPA 1';
              document.getElementById('edit_id_employee').value = '4';
          } else if (classId === '5') {
              document.getElementById('edit_class_name').value = 'XI IPA 2';
              document.getElementById('edit_id_employee').value = '5';
          }

          // Update action URL form
          document.getElementById('editClassForm').action = `{{ route('classes.update', '') }}/${classId}`;
      });
  }

  // Fungsi untuk modal hapus kelas
  const deleteClassModal = document.getElementById('deleteClassModal');
  if (deleteClassModal) {
      deleteClassModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const classId = button.getAttribute('data-class-id');
          const className = button.getAttribute('data-class-name');

          document.getElementById('delete_class_id').value = classId;
          document.getElementById('delete_class_name').textContent = className;

          // Update action URL form
          document.getElementById('deleteClassForm').action = `{{ route('classes.destroy', '') }}/${classId}`;
      });
  }
});
</script>
</body>
</html>
