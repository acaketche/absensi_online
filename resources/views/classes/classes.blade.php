<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-School - Data Kelas</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    .table-container {
      max-height: 600px;
      overflow-y: auto;
      border: 1px solid #dee2e6;
      border-radius: 0.25rem;
    }

    .table-container thead th {
      position: sticky;
      top: 0;
      background-color: #f8f9fa;
      z-index: 10;
    }

    .teacher-photo {
      width: 50px;
      height: 50px;
      object-fit: cover;
    }

    .class-badge {
      font-size: 0.8rem;
      padding: 0.25rem 0.5rem;
    }

    .badge-X { background-color: #6f42c1; }
    .badge-XI { background-color: #fd7e14; }
    .badge-XII { background-color: #20c997; }

    .empty-state {
      height: 300px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #6c757d;
    }
  </style>
</head>
<body class="bg-light">
@if(Auth::guard('employee')->check())
<div class="d-flex">
  @include('components.sidebar')

  <main class="flex-grow-1 p-4">
    @include('components.profiladmin')

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fs-4 fw-bold mb-0">
        <i class="fas fa-chalkboard-teacher me-2"></i>Data Kelas
      </h2>
      <div class="d-flex align-items-center">
            <input type="text" placeholder="Cari kelas" class="form-control me-3" style="width: 200px;" id="searchInput">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                  <i class="fas fa-plus me-1"></i> Tambah Kelas
              </button>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-white border-bottom">
        <i class="fas fa-filter me-2"></i>Filter Kelas
      </div>
      <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('classes.index') }}" class="row g-3">
          <div class="col-md-3">
            <label for="class_level" class="form-label">Tingkatan Kelas</label>
            <select name="class_level" id="class_level" class="form-select">
              <option value="">Semua Tingkatan</option>
              <option value="X" {{ request('class_level') == 'X' ? 'selected' : '' }}>X (Kelas 10)</option>
              <option value="XI" {{ request('class_level') == 'XI' ? 'selected' : '' }}>XI (Kelas 11)</option>
              <option value="XII" {{ request('class_level') == 'XII' ? 'selected' : '' }}>XII (Kelas 12)</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
            <select name="academic_year_id" id="academic_year_id" class="form-select">
              <option value="">Semua Tahun Ajaran</option>
              @foreach ($academicYears as $tahun)
                <option value="{{ $tahun->id }}"
                  {{ request('academic_year_id') == $tahun->id ? 'selected' : '' }}
                  {{ $activeAcademicYear && $tahun->id == $activeAcademicYear->id ? 'selected' : '' }}>
                  {{ $tahun->year_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-auto d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-filter me-1"></i> Terapkan
            </button>
            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
              <i class="fas fa-sync-alt me-1"></i> Reset
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Kelas -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-primary text-white">
        <i class="fas fa-list me-2"></i>Daftar Kelas
      </div>
      <div class="card-body p-0">
        @if($classes->isEmpty())
          <div class="empty-state">
            <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
            <h5 class="mb-2">Tidak Ada Data Kelas</h5>
            <p class="text-muted mb-4">Silahkan tambahkan kelas baru menggunakan tombol di atas</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
              <i class="fas fa-plus me-1"></i> Tambah Kelas
            </button>
          </div>
        @else
          <div class="table-container">
            <table class="table table-hover mb-0" id="classTable">
              <thead class="table-light">
                <tr>
                  <th width="5%">No</th>
                  <th width="25%">Nama Kelas</th>
                  <th width="40%">Wali Kelas</th>
                  <th width="15%">Tahun Ajaran</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($classes as $class)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    {{ $class->class_name }}
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="{{ $class->employee?->photo ? asset('storage/' . $class->employee->photo) : asset('images/default-profile.png') }}"
                        alt="Foto {{ $class->employee?->fullname ?? 'Tidak Ada Data' }}"
                        class="teacher-photo rounded-circle me-3">
                      <div>
                        <p class="mb-0 fw-medium">
                          {{ $class->employee?->fullname ?? 'Belum Ada Wali Kelas' }}
                        </p>
                        <small class="text-muted">
                          <i class="fas fa-id-card me-1"></i>{{ $class->employee?->id_employee ?? '-' }}
                        </small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark">
                      {{ $class->academicYear->year_name ?? '-' }}
                    </span>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="{{ route('classes.show', $class->class_id) }}"
                        class="btn btn-sm btn-info" title="Detail">
                        <i class="fas fa-eye"></i>
                      </a>

                        <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editClassModal"
                                        data-class-id="{{ $class->class_id }}">
                                    <i class="fas fa-edit me-1"></i>
                                </button>

                      <button class="btn btn-sm btn-danger delete-class"
                        data-class-id="{{ $class->class_id }}"
                        data-class-name="{{ $class->class_name }}"
                        title="Hapus">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
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
                     <input type="text" id="edit_class_name" name="class_name" class="form-control" />
                      <div class="form-text">Contoh: X IPA 1, XI IPS 2, XII IPA 3, dll.</div>
                  </div>
                 <div class="mb-3">
                        <label for="edit_id_employee" class="form-label">Wali Kelas</label>
                        <select class="form-select" id="edit_id_employee" name="id_employee" required>
                            <option value="">-- Pilih Wali Kelas --</option>
                           @foreach ($waliKelas->unique('id_employee') as $wali)
                        <option value="{{ $wali->id_employee }}">
                            {{ $wali->fullname }} (NIP: {{ $wali->id_employee }})
                        </option>
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

    // Ambil data kelas via fetch
    fetch(`/classes/json/${classId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(response => {
        const data = response.data;

        console.log('Data yang diambil:', data); // Debug

        // Isi input nama kelas
        const classInput = document.getElementById('edit_class_name');
        if (classInput) {
          classInput.value = data.class_name;
        }

        // Isi wali kelas
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
@endif
</html>
