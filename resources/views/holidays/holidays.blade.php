<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kalender Hari Libur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    .main-content { flex: 1; padding: 30px; background: #f5f5f5; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .table-responsive { overflow-y: auto; max-height: 500px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
    .table { table-layout: auto; width: 100%; margin-bottom: 0; }
    .table th { background-color: #4266B9; color: white; position: sticky; top: 0; }
    .table th, .table td { padding: 12px 15px; vertical-align: middle; }
    .table td { border-bottom: 1px solid #e0e0e0; }
    .table tr:last-child td { border-bottom: none; }
    .table tr:hover { background-color: rgba(66, 102, 185, 0.05); }
    .filter-section { background-color: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    .btn-primary, .bg-primary { background-color: #4266B9 !important; border-color: #4266B9 !important; }
    .btn-primary:hover { background-color: #365796 !important; border-color: #365796 !important; }
    .btn-success { background-color: #28a745 !important; border-color: #28a745 !important; }
    .btn-success:hover { background-color: #218838 !important; border-color: #218838 !important; }
    .badge-api { background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
    .holiday-date { font-weight: 500; color: #4266B9; }
    .action-buttons .btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
    .no-data { padding: 30px; text-align: center; color: #6c757d; }
    .no-data i { font-size: 2rem; margin-bottom: 10px; color: #dee2e6; }
    .active-year { font-weight: bold; color: #28a745; }
    @media (max-width: 768px) {
      .sidebar { width: 70px; padding: 20px 10px; }
      .logo-text, .nav-text { display: none; }
      .main-content { padding: 20px; }
      .header { flex-direction: column; align-items: flex-start; gap: 15px; }
      .header .d-flex { width: 100%; }
      .filter-section .col-md-4 { margin-bottom: 15px; }
    }
  </style>
</head>
<body class="bg-light">
  @if(Auth::guard('employee')->check())
  <div class="d-flex">
    @include('components.sidebar')
    <main class="main-content">
      @include('components.profiladmin')
      <div class="header">
        <h2 class="fs-4 fw-bold">Kalender Hari Libur</h2>
        <div class="d-flex align-items-center gap-2">
          <form method="GET" action="{{ route('holidays.index') }}" class="d-flex">
            <input type="text" class="form-control me-2" name="search" placeholder="Cari hari libur...">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
          </form>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
            <i class="fas fa-plus me-1"></i> Tambah
          </button>
        </div>
      </div>

      <div class="filter-section">
        <form class="row g-3" method="GET" action="{{ route('holidays.index') }}">
          <div class="col-md-4">
            <label class="form-label">Tahun Ajaran</label>
            <select class="form-select" name="academic_year">
              <option value="">Semua Tahun</option>
              @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ request('academic_year') == $year->id ? 'selected' : '' }} class="{{ $year->is_active ? 'active-year' : '' }}">
                  {{ $year->year_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Bulan</label>
            <select class="form-select" name="bulan">
              <option value="">Semua Bulan</option>
              @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
              @endfor
            </select>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary me-2" type="submit"><i class="fas fa-filter me-1"></i> Filter</button>
            <a href="{{ route('holidays.index') }}" class="btn btn-outline-secondary"><i class="fas fa-sync-alt me-1"></i> Reset</a>
          </div>
        </form>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="table-responsive bg-white">
        <table class="table">
          <thead>
            <tr>
              <th width="50px">No</th>
              <th>Tanggal</th>
              <th>Deskripsi</th>
              <th width="120px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($allHolidays as $index => $holiday)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td class="holiday-date">{{ \Carbon\Carbon::parse($holiday['holiday_date'])->translatedFormat('d F Y') }}</td>
                <td>{{ $holiday['holiday_name'] }}</td>
                <td>
                  @if($holiday['source'] === 'local')
                    <div class="action-buttons d-flex gap-2">
                     <button class="btn btn-sm btn-warning edit-holiday"
                            data-id="{{ $holiday['id'] }}"
                            data-date="{{ $holiday['holiday_date'] }}"
                            data-name="{{ $holiday['holiday_name'] }}"
                            title="Edit">
                    <i class="fas fa-edit"></i>
                    </button>
                      <form method="POST" action="{{ route('holidays.destroy', $holiday['id']) }}" onsubmit="return confirm('Hapus hari libur ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  @else
                    <span class="badge-api">API</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5">
                  <div class="no-data">
                    <i class="far fa-calendar-alt"></i>
                    <p class="mb-0">Tidak ada data hari libur</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modal Tambah Hari Libur -->
  <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addHolidayModalLabel">Tambah Hari Libur</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('holidays.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="holiday_date" class="form-label">Tanggal</label>
              <input type="date" name="holiday_date" id="holiday_date" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi</label>
              <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
              <select name="academic_year_id" id="academic_year_id" class="form-select" required>
                <option value="">-- Pilih Tahun Ajaran --</option>
                @foreach($academicYears as $year)
                  <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                    {{ $year->year_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Hari Libur -->
<div class="modal fade" id="editHolidayModal" tabindex="-1" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
   <form method="POST" id="editHolidayForm" action="{{ route('holidays.update', ':id') }}">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit-id">
      <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYearId }}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editHolidayModalLabel">Edit Hari Libur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit-holiday-date" class="form-label">Tanggal Libur</label>
            <input type="date" name="holiday_date" class="form-control" id="edit-holiday-date" required>
          </div>
          <div class="mb-3">
            <label for="edit-holiday-name" class="form-label">Nama Libur</label>
            <input type="text" name="holiday_name" class="form-control" id="edit-holiday-name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>
  @endif
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.edit-holiday').forEach(button => {
      button.addEventListener('click', function () {
        const id = this.dataset.id;
        const date = this.dataset.date;
        const name = this.dataset.name;

        // Set form input values
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-holiday-date').value = date;
        document.getElementById('edit-holiday-name').value = name;

        // Ganti action form dengan ID yang benar
        const form = document.getElementById('editHolidayForm');
        const actionTemplate = "{{ route('holidays.update', ':id') }}";
        form.action = actionTemplate.replace(':id', id);

        // Tampilkan modal
        const editModal = new bootstrap.Modal(document.getElementById('editHolidayModal'));
        editModal.show();
      });
    });
  });
</script>
</body>
</html>
