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
          color: white;
      }

      .holiday-school {
          background-color: #ffc107;
          color: black;
      }

      .holiday-religious {
          background-color: #28a745;
          color: white;
      }

      .tab-content {
          padding-top: 20px;
      }

      .nav-tabs .nav-link.active {
          color: #4266B9;
          font-weight: bold;
      }

      .nav-tabs .nav-link {
          color: #495057;
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
    <!-- Header dengan Profil Admin -->
    @include('components.profiladmin')
      <header class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fs-4 fw-bold">Kalender Hari Libur</h2>
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
    <form id="filterForm" method="GET" action="{{ route('holidays.index') }}" class="row g-3">
        <div class="col-md-4">
            <label for="filterTahunAjaran" class="form-label">Tahun Ajaran</label>
            <select name="academic_year" id="filterTahunAjaran" class="form-control">
                <option value="">-- Pilih Tahun --</option>
                @foreach ($academicYears as $tahun)
                    <option value="{{ $tahun->id }}"
                        {{ request('academic_year') == $tahun->id ? 'selected' : '' }}>
                        {{ $tahun->year_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="filterBulan" class="form-label">Bulan</label>
            <select class="form-select" id="filterBulan" name="bulan">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}"
                        {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Terapkan Filter
            </button>
            <a href="{{ route('holidays.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-sync-alt me-1"></i> Reset
            </a>
        </div>
    </form>
</div>

      <!-- Table View -->
      <div class="card">
        <div class="card-header bg-primary text-white">Daftar Hari Libur</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="holidayTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($holidays as $index => $holiday)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}</td>
                                <td>{{ $holiday->description }}</td>
                                <td>{{ $holiday->academicYear->year_name ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewHolidayModal"
                                                data-id="{{ $holiday->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHolidayModal"
                                                data-id="{{ $holiday->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteHolidayModal"
                                                data-id="{{ $holiday->id }}"
                                                data-description="{{ $holiday->description }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                      <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                      <select class="form-select" id="academic_year_id" name="academic_year_id" required>
                          <option value="">-- Pilih Tahun Ajaran --</option>
                          @foreach ($academicYears as $tahun)
                              <option value="{{ $tahun->id }}" {{ $tahun->is_active ? 'selected' : '' }}>
                                  {{ $tahun->year_name }}
                              </option>
                          @endforeach
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
              <div id="viewHolidayLoading" class="text-center">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2">Memuat data...</p>
              </div>
              <div id="viewHolidayContent" style="display: none;">
                  <div class="mb-3">
                      <h5 class="border-bottom pb-2">Informasi Hari Libur</h5>
                      <div class="row mb-2">
                          <div class="col-4 fw-bold">Tanggal</div>
                          <div class="col-8" id="view_holiday_date">-</div>
                      </div>
                      <div class="row mb-2">
                          <div class="col-4 fw-bold">Deskripsi</div>
                          <div class="col-8" id="view_description">-</div>
                      </div>
                      <div class="row mb-2">
                          <div class="col-4 fw-bold">Jenis</div>
                          <div class="col-8" id="view_jenis">-</div>
                      </div>
                      <div class="row mb-2">
                          <div class="col-4 fw-bold">Tahun Ajaran</div>
                          <div class="col-8" id="view_academic_year">-</div>
                      </div>
                  </div>
              </div>
              <div id="viewHolidayError" class="alert alert-danger" style="display: none;">
                  Terjadi kesalahan saat memuat data. Silahkan coba lagi.
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
              <div id="editHolidayLoading" class="text-center">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2">Memuat data...</p>
              </div>
              <div id="editHolidayError" class="alert alert-danger" style="display: none;">
                  Terjadi kesalahan saat memuat data. Silahkan coba lagi.
              </div>
              <form id="editHolidayForm" method="POST" style="display: none;">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                      <label for="edit_holiday_date" class="form-label">Tanggal Libur</label>
                      <input type="date" class="form-control" id="edit_holiday_date" name="holiday_date" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_description" class="form-label">Deskripsi</label>
                      <input type="text" class="form-control" id="edit_description" name="description" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_academic_year_id" class="form-label">Tahun Ajaran</label>
                      <select class="form-select" id="edit_academic_year_id" name="academic_year_id" required>
                          <option value="">-- Pilih Tahun Ajaran --</option>
                          @foreach ($academicYears as $tahun)
                              <option value="{{ $tahun->id }}">{{ $tahun->year_name }}</option>
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

<!-- Modal Hapus Hari Libur -->
<div class="modal fade" id="deleteHolidayModal" tabindex="-1" aria-labelledby="deleteHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="deleteHolidayModalLabel">Konfirmasi Hapus Hari Libur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p>Apakah Anda yakin ingin menghapus hari libur <strong id="delete_holiday_desc">-</strong>?</p>
              <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini akan menghapus data hari libur secara permanen.</p>
          </div>
          <div class="modal-footer">
              <form id="deleteHolidayForm" method="POST">
                  @csrf
                  @method('DELETE')
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
          const holidayDate = row.cells[1].textContent.toLowerCase();
          const description = row.cells[2].textContent.toLowerCase();
          const academicYear = row.cells[3].textContent.toLowerCase();

          if (holidayDate.includes(searchValue) || description.includes(searchValue) || academicYear.includes(searchValue)) {
              row.style.display = '';
          } else {
              row.style.display = 'none';
          }
      });
  });

  // Fungsi untuk modal lihat hari libur
  const viewHolidayModal = document.getElementById('viewHolidayModal');
    if (viewHolidayModal) {
        viewHolidayModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const holidayId = button.getAttribute('data-id');
            if (!holidayId) {
                console.error('ID hari libur tidak ditemukan');
                return;
            }

            document.getElementById('viewHolidayLoading').style.display = 'block';
            document.getElementById('viewHolidayContent').style.display = 'none';
            document.getElementById('viewHolidayError').style.display = 'none';

            fetch(`/holidays/${holidayId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('viewHolidayLoading').style.display = 'none';
                    document.getElementById('viewHolidayContent').style.display = 'block';

                    document.getElementById('view_holiday_date').textContent = new Date(data.holiday_date).toLocaleDateString('id-ID', {
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                    });
                    document.getElementById('view_description').textContent = data.description;
                    document.getElementById('view_jenis').textContent = data.jenis === 'nasional' ? 'Libur Nasional' :
                        data.jenis === 'sekolah' ? 'Libur Sekolah' :
                            data.jenis === 'keagamaan' ? 'Libur Keagamaan' : data.jenis;
                    document.getElementById('view_academic_year').textContent = data.academic_year.year_name;
                })
                .catch(error => {
                    console.error('Error fetching holiday data:', error);
                    document.getElementById('viewHolidayLoading').style.display = 'none';
                    document.getElementById('viewHolidayError').style.display = 'block';
                });
        });
    }

    // Modal Edit Hari Libur
    const editHolidayModal = document.getElementById('editHolidayModal');
    if (editHolidayModal) {
        editHolidayModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const holidayId = button.getAttribute('data-id');
            if (!holidayId) {
                console.error('ID hari libur tidak ditemukan');
                return;
            }

            document.getElementById('editHolidayLoading').style.display = 'block';
            document.getElementById('editHolidayForm').style.display = 'none';
            document.getElementById('editHolidayError').style.display = 'none';

            document.getElementById('editHolidayForm').action = `/holidays/${holidayId}`;

            fetch(`/holidays/${holidayId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editHolidayLoading').style.display = 'none';
                    document.getElementById('editHolidayForm').style.display = 'block';

                    document.getElementById('edit_holiday_date').value = data.holiday_date;
                    document.getElementById('edit_description').value = data.description;
                    document.getElementById('edit_academic_year_id').value = data.academic_year_id;
                })
                .catch(error => {
                    console.error('Error fetching holiday data for edit:', error);
                    document.getElementById('editHolidayLoading').style.display = 'none';
                    document.getElementById('editHolidayError').style.display = 'block';
                });
        });
    }

  // Fungsi untuk modal hapus hari libur
  const deleteHolidayModal = document.getElementById('deleteHolidayModal');
  if (deleteHolidayModal) {
      deleteHolidayModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const holidayId = button.getAttribute('data-id');
          const holidayDesc = button.getAttribute('data-description');

          if (!holidayId) {
              console.error('ID hari libur tidak ditemukan');
              return;
          }

          document.getElementById('delete_holiday_desc').textContent = holidayDesc;

          // Update action URL form
          document.getElementById('deleteHolidayForm').action = `/holidays/${holidayId}`;
      });
  }
});
</script>
</body>
</html>
@endif
