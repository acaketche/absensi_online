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
@if(Auth::guard('employee')->check())
<body class="bg-light">
<div class="d-flex">
  <!-- Sidebar -->
  @include('components.sidebar')

  <!-- Main Content -->
  <main class="flex-grow-1 p-4">
    <!-- Header dengan Profil Admin -->
    @include('components.profiladmin')
      <header class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fs-4 fw-bold">Data Kelas</h2>
          <div class="d-flex align-items-center">
              <input type="text" placeholder="Cari kelas" class="form-control me-3" style="width: 200px;" id="searchInput">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                  <i class="fas fa-plus me-1"></i> Tambah Kelas
              </button>
          </div>
      </header>

    <!-- Filter Kelas -->
<div class="filter-section mb-4">
    <h5>Filter Kelas</h5>
    <form id="filterForm" class="row g-2">
        <div class="col-md-6">
            <select class="form-select" id="filterTingkat" name="tingkat">
                <option value="">Pilih Tingkat</option>
                <option value="X">10</option>
                <option value="XI">11</option>
                <option value="XII">12</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button type="reset" class="btn btn-secondary btn-sm ms-2">
                <i class="fas fa-sync-alt"></i> Reset
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
                               <img src="{{ $class->employee?->photo ? asset('storage/' . $class->employee->photo) : 'https://via.placeholder.com/150' }}"
                                alt="Foto {{ $class->employee?->fullname ?? 'Tidak Ada Data' }}"
                                class="teacher-photo rounded-circle me-3"
                                width="50" height="60">
                                <div class="teacher-details">
                                    <p class="teacher-name mb-0">
                                        {{ $class->employee?->fullname ?? 'Tidak Ada Data' }}
                                        {{ $class->employee?->qualification ? ', ' . $class->employee?->qualification : '' }}
                                    </p>
                                    <small class="text-muted">NIP: {{ $class->employee?->id_employee ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('classes.show', $class->class_id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                </a>

                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editClassModal"
                                        data-class-id="{{ $class->class_id }}">
                                    <i class="fas fa-edit me-1"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-sm btn-danger delete-class"
                                        data-class-id="{{ $class->class_id }}"
                                        data-class-name="{{ $class->class_name }}">
                                    <i class="fas fa-trash me-1"></i>
                                </button>
                            </div>

                            <form class="delete-class-form" action="{{ route('classes.destroy', $class->class_id) }}" method="POST" style="display: none;">
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
                  </div>
                  <div class="mb-3">
                    <label for="id_employee" class="form-label">Wali Kelas</label>
                    <select class="form-select" id="id_employee" name="id_employee" required>
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach ($waliKelas as $wali)
                            <option value="{{ $wali->id_employee}}">
                                {{ $wali->fullname }} (NIP: {{ $wali->id_employee }})
                            </option>
                        @endforeach
                    </select>
                </div>
                 <!-- Input hidden untuk Tahun Ajaran & Semester Aktif -->
                 <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                 <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">
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

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editClassModalLabel">Edit Kelas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="editClassForm" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                      <label for="edit_class_name" class="form-label">Nama Kelas</label>
                      <input type="text" class="form-control" id="edit_class_name" name="class_name" required>
                      <div class="form-text">Contoh: X IPA 1, XI IPS 2, XII IPA 3, dll.</div>
                  </div>
                 <div class="mb-3">
                        <label for="edit_id_employee" class="form-label">Wali Kelas</label>
                        <select class="form-select" id="edit_id_employee" name="id_employee" required>
                            <option value="">-- Pilih Wali Kelas --</option>
                           @foreach ($classes as $class)
                            @foreach ($waliKelas as $wali)
                                <option value="{{ $wali->id_employee }}" {{ $class->employee && $class->employee->id_employee == $wali->id_employee ? 'selected' : '' }}>
                                    {{ $wali->fullname }} (NIP: {{ $wali->id_employee }})
                                </option>
                            @endforeach
                        @endforeach
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fungsi pencarian
  document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchValue = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('#classTable tbody tr');

      tableRows.forEach(row => {
          const className = row.cells[1].textContent.toLowerCase();
          const teacherName = row.querySelector('.teacher-name')?.textContent.toLowerCase() || '';
          const teacherNIP = row.querySelector('.text-muted')?.textContent.toLowerCase() || '';

          if (className.includes(searchValue) || teacherName.includes(searchValue) || teacherNIP.includes(searchValue)) {
              row.style.display = '';
          } else {
              row.style.display = 'none';
          }
      });
  });

  // Fungsi filter
  document.addEventListener('DOMContentLoaded', function () {
    // Filter saat submit form
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const tingkat = document.getElementById('filterTingkat').value;

        const tableRows = document.querySelectorAll('#classTable tbody tr');

        tableRows.forEach(row => {
            let showRow = true;

            // Filter berdasarkan tingkat
            if (tingkat && !row.cells[1].textContent.startsWith(tingkat)) {
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
});

  // Fungsi untuk modal edit kelas
  const editClassModal = document.getElementById('editClassModal');
  if (editClassModal) {
      editClassModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const classId = button.getAttribute('data-class-id');

          if (!classId) {
              console.error('ID kelas tidak ditemukan');
              return;
          }

          // Update action URL form
          document.getElementById('editClassForm').action = `/classes/${classId}`;

        fetch(`/classes/json/${classId}`)
              .then(response => {
                  if (!response.ok) {
                      throw new Error('Network response was not ok');
                  }
                  return response.json();
              })
              .then(data => {
                  // Isi form dengan data yang diterima
                  document.getElementById('edit_class_name').value = data.class_name;

                  // Pilih wali kelas yang sesuai
                  const selectElement = document.getElementById('edit_id_employee');
                  const options = selectElement.options;

                  for (let i = 0; i < options.length; i++) {
                      if (options[i].value === data.employee_nip) {
                          selectElement.selectedIndex = i;
                          break;
                      }
                  }
              })
              .catch(error => {
                  console.error('Error fetching class data for edit:', error);
                  alert('Gagal mengambil data kelas untuk diedit.');
              });
      });
  }

  // Fungsi untuk modal hapus kelas
  document.querySelectorAll(".delete-class").forEach(button => {
      button.addEventListener("click", function () {
          const classId = this.getAttribute("data-class-id");
          const className = this.getAttribute("data-class-name");
          const form = document.querySelector(`.delete-class-form[action$='${classId}']`);

          Swal.fire({
              title: "Apakah Anda yakin?",
              text: `Kelas "${className}" akan dihapus secara permanen!`,
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
</script>
</body>
</html>
@endif
