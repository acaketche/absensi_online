<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4266B9;
      --secondary-color: #3a0ca3;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Inter', sans-serif;
    }

    /* Card Sederhana */
    .class-card {
      border-radius: 8px;
      background-color: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s;
      border: none;
      overflow: hidden;
      width: 240px;
      cursor: pointer;
    }

    .class-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .class-header {
      background-color: var(--primary-color);
      color: white;
      padding: 12px;
    }

    .class-name {
      font-weight: 600;
      font-size: 1rem;
      margin-bottom: 2px;
    }

    .class-meta {
      font-size: 0.7rem;
      opacity: 0.9;
    }

    .class-body {
      padding: 12px;
    }

    .class-stats {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .stat-item {
      text-align: center;
      flex: 1;
    }

    .stat-value {
      font-weight: 600;
      color: var(--primary-color);
    }

    .stat-label {
      font-size: 0.7rem;
      color: #6c757d;
    }

    .teacher-info {
      display: flex;
      align-items: center;
      padding-top: 8px;
      border-top: 1px solid #eee;
    }

    .teacher-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      margin-right: 8px;
      object-fit: cover;
    }

    .teacher-initial {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background-color: #4895ef;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 8px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .teacher-name {
      font-size: 0.8rem;
      font-weight: 500;
    }

    .teacher-role {
      font-size: 0.7rem;
      color: #6c757d;
    }

    /* Responsive Grid */
    .class-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 16px;
    }

    /* Empty State */
    .empty-state {
      padding: 40px;
      text-align: center;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body class="bg-light">
<div class="d-flex">
  @include('components.sidebar')

  <main class="flex-grow-1 p-4">
    @include('components.profiladmin')

    <header class="mb-4">
      <h2 class="text-primary">Peminjaman Buku - Daftar Kelas</h2>
      <p class="text-muted mb-0">
        Pilih kelas untuk melihat daftar siswa dan peminjaman buku
      </p>
      <small class="text-muted">
        Tahun Ajaran: <strong>{{ $activeAcademicYear->year_name ?? '-' }}</strong> |
        Semester: <strong>{{ $activeSemester->semester_name ?? '-' }}</strong>
      </small>
    </header>

    <!-- Filter dan Pencarian -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3 mb-md-0">
            <div class="input-group">
              <span class="input-group-text bg-white">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input type="text" id="searchClass" class="form-control" placeholder="Cari kelas atau wali kelas...">
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex gap-2">
              <select class="form-select" id="filterTingkat">
                <option value="">Semua Tingkat</option>
                <option value="X">Kelas 10</option>
                <option value="XI">Kelas 11</option>
                <option value="XII">Kelas 12</option>
              </select>
              <button id="resetFilter" class="btn btn-outline-secondary">
                <i class="fas fa-redo"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Section: Download & Import Excel -->
<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title mb-3 text-primary">
      <i class="fas fa-file-excel me-2"></i>Manajemen Data Excel
    </h5>

    <div class="row g-3">
      <!-- Download Template -->
      <div class="col-md-6">
        <div class="border p-3 rounded bg-light">
          <div class="d-flex align-items-center mb-2">
            <i class="fas fa-file-download text-success me-2 fs-4"></i>
            <h6 class="mb-0">Download Template</h6>
          </div>
          <p class="small text-muted mb-3">
            Unduh template Excel untuk pengisian data peminjaman buku.
          </p>
          <a href="{{ route('book-loans.export') }}" class="btn btn-success w-100">
            <i class="fas fa-download me-2"></i>Download Template
          </a>
          <div class="mt-2 small text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Template terdiri dari 3 sheet (X, XI, XII) dengan format yang sudah disesuaikan.
          </div>
        </div>
      </div>

      <!-- Import Excel -->
      <div class="col-md-6">
        <div class="border p-3 rounded bg-light">
          <div class="d-flex align-items-center mb-2">
            <i class="fas fa-file-upload text-primary me-2 fs-4"></i>
            <h6 class="mb-0">Import Data</h6>
          </div>
          <p class="small text-muted mb-3">
            Unggah file Excel yang sudah diisi untuk memproses data peminjaman.
          </p>

          <form action="{{ route('book-loans.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
              <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload me-1"></i>Import
              </button>
            </div>
            <div class="small text-muted">
              <i class="fas fa-exclamation-triangle me-1"></i>
              Pastikan file sesuai template dan berformat .xlsx/.xls
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@if (session('import_errors'))
<div class="alert alert-warning alert-dismissible fade show">
  <h5 class="alert-heading">
    <i class="fas fa-exclamation-triangle me-2"></i>Peringatan Import
  </h5>
  <p>Beberapa data tidak dapat diproses:</p>
  <ul class="mb-0">
    @foreach (session('import_errors') as $err)
      <li>{{ $err }}</li>
    @endforeach
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show">
  <h5 class="alert-heading">
    <i class="fas fa-check-circle me-2"></i>Import Berhasil
  </h5>
  {!! session('success') !!}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

    <!-- Daftar Kelas -->
    <div id="classList" class="class-grid">
      @foreach($classes as $class)
        @php
          $loanCount = $classLoans[$class->class_id] ?? 0;
          $studentCount = $class->students->count();
        @endphp

        <div class="class-card" onclick="window.location.href='{{ route('book-loans.class-students', $class->class_id) }}'">
          <div class="class-header">
            <div class="class-name">{{ $class->class_name }}</div>
            <div class="class-meta">{{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}</div>
          </div>

          <div class="class-body">
            <div class="class-stats">
              <div class="stat-item">
                <div class="stat-value">{{ $studentCount }}</div>
                <div class="stat-label">Siswa</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">{{ $loanCount }}</div>
                <div class="stat-label">Pinjaman</div>
              </div>
            </div>

            <div class="teacher-info">
              @if($class->employee && $class->employee->photo)
                <img src="{{ asset('storage/' . $class->employee->photo) }}" class="teacher-avatar" alt="Wali Kelas">
              @else
                <div class="teacher-initial">{{ $class->employee ? substr($class->employee->fullname, 0, 1) : '?' }}</div>
              @endif
              <div>
                <div class="teacher-name">{{ $class->employee->fullname ?? 'Belum ada wali kelas' }}</div>
                <div class="teacher-role">Wali Kelas</div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if($classes->isEmpty())
      <div class="empty-state">
        <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Belum ada kelas tersedia</h5>
        <p class="text-muted small">Silakan tambahkan kelas terlebih dahulu melalui menu administrasi</p>
      </div>
    @endif
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search
    document.getElementById('searchClass').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.class-card').forEach(card => {
            const name = card.querySelector('.class-name').textContent.toLowerCase();
            const teacher = card.querySelector('.teacher-name').textContent.toLowerCase();
            card.style.display = (name.includes(val) || teacher.includes(val)) ? 'block' : 'none';
        });
    });

    // Filter
    document.getElementById('filterTingkat').addEventListener('change', function() {
        const tingkat = this.value;
        document.querySelectorAll('.class-card').forEach(card => {
            const name = card.querySelector('.class-name').textContent;
            card.style.display = (!tingkat || name.includes(tingkat)) ? 'block' : 'none';
        });
    });

    // Reset
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('searchClass').value = '';
        document.getElementById('filterTingkat').value = '';
        document.querySelectorAll('.class-card').forEach(card => card.style.display = 'block');
    });
});
</script>
</body>
</html>
