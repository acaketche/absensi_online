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
      --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .class-card {
      border-radius: 12px;
      background-color: #fff;
      box-shadow: var(--card-shadow);
      cursor: pointer;
      transition: all 0.2s ease;
      overflow: hidden;
      width: 280px;
      display: flex;
      flex-direction: column;
      border: 1px solid #e9ecef;
    }

    .class-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .class-card-header {
      background: linear-gradient(135deg, #3f37c9, #4266B9);
      color: #fff;
      padding: 16px;
      position: relative;
    }

    .class-icon {
      width: 40px;
      height: 40px;
      background-color: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      font-size: 1rem;
    }

    .class-header-content {
      display: flex;
      align-items: center;
    }

    .class-name {
      font-weight: 600;
      font-size: 1.05rem;
      margin-bottom: 2px;
    }

    .class-meta {
      font-size: 0.75rem;
      opacity: 0.9;
    }

    .class-stats {
      display: flex;
      justify-content: space-around;
      background-color: white;
      padding: 12px 0;
      border-bottom: 1px solid #f1f3f5;
    }

    .stat-item {
      text-align: center;
    }

    .stat-value {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary-color);
    }

    .stat-label {
      font-size: 0.7rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .teacher-info {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      background-color: #f8fafb;
    }

    .teacher-avatar,
    .teacher-initial {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
      font-size: 0.9rem;
      background-color: #4895ef;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: 500;
    }

    .teacher-details {
      flex-grow: 1;
    }

    .teacher-name {
      font-weight: 500;
      font-size: 0.85rem;
      color: #343a40;
    }

    .teacher-role {
      font-size: 0.7rem;
      color: #6c757d;
    }

    .page-header h2 {
      color: var(--secondary-color);
      font-weight: 700;
    }

    .search-box {
      position: relative;
    }

    .search-icon {
      position: absolute;
      left: 16px;
      top: 12px;
      color: var(--primary-color);
    }

    .search-input {
      padding-left: 48px;
      border-radius: 50px;
      border: 1px solid #e0e0e0;
      height: 48px;
    }

    .filter-section {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: var(--card-shadow);
    }

    .empty-state {
      padding: 60px 0;
      text-align: center;
      background-color: white;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
    }

    /* List View */
    #classList.list-view {
      flex-direction: column;
    }

    #classList.list-view .class-card {
      flex-direction: row;
      width: 100%;
      max-width: 100%;
    }

    #classList.list-view .class-card-header {
      width: 200px;
      min-width: 200px;
      border-right: 1px solid #e9ecef;
    }

    #classList.list-view .class-stats {
      flex-direction: column;
      justify-content: center;
      padding: 8px 16px;
      width: 120px;
      min-width: 120px;
    }

    #classList.list-view .teacher-info {
      flex-grow: 1;
      justify-content: space-between;
    }

    .btn-group .btn.active {
      background-color: var(--primary-color);
      color: white;
    }
  </style>
</head>

@if(Auth::guard('employee')->check())
<body class="bg-light">
<div class="d-flex">
  @include('components.sidebar')

  <main class="flex-grow-1 p-4">
    @include('components.profiladmin')

    <header class="d-flex justify-content-between align-items-center mb-4">
      <div class="page-header">
        <h2>Peminjaman Buku - Daftar Kelas</h2>
        <p class="text-muted">
          Pilih kelas untuk melihat daftar siswa dan peminjaman buku<br>
          <small>
            Tahun Ajaran: <strong>{{ $activeAcademicYear->year_name ?? '-' }}</strong> |
            Semester: <strong>{{ $activeSemester->semester_name ?? '-' }}</strong>
          </small>
        </p>
      </div>
    </header>

  <!-- Filter, Pencarian, dan Aksi -->
<div class="filter-section mb-4">
  <div class="row g-3">

    <!-- Kolom Pencarian -->
    <div class="col-md-6 col-lg-4">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
          <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text" id="searchClass" class="form-control border-start-0" placeholder="Cari kelas atau wali kelas...">
      </div>
    </div>

    <!-- Kolom Filter dan Toggle -->
    <div class="col-md-6 col-lg-8">
      <form id="filterForm" class="d-flex flex-wrap gap-2 justify-content-lg-end align-items-center">
        <!-- Filter Tingkat -->
        <select class="form-select w-auto" id="filterTingkat" name="tingkat">
          <option value="">Semua Tingkat</option>
          <option value="X">Kelas 10</option>
          <option value="XI">Kelas 11</option>
          <option value="XII">Kelas 12</option>
        </select>

        <!-- Tombol Filter -->
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-filter me-1"></i> Filter
        </button>

        <!-- Reset Filter -->
        <button type="button" id="resetFilter" class="btn btn-outline-secondary">
          <i class="fas fa-redo me-1"></i> Reset
        </button>

        <!-- Toggle Tampilan -->
        <div class="btn-group" role="group" aria-label="Tampilan">
          <button id="gridViewBtn" type="button" class="btn btn-outline-primary active" title="Grid">
            <i class="fas fa-th-large"></i>
          </button>
          <button id="listViewBtn" type="button" class="btn btn-outline-primary" title="List">
            <i class="fas fa-list"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Section: Download & Import Excel -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body py-3">
    <div class="row align-items-center g-3">

      <!-- Download Template -->
      <div class="col-md-4">
        <a href="{{ route('book-loans.export') }}" class="btn btn-outline-success w-100">
          <i class="fas fa-download me-1"></i> Download Template Excel
        </a>
        <small class="text-muted d-block mt-2">
          Unduh format <strong>template pengisian data peminjaman</strong> yang sudah disiapkan. Template ini terdiri dari <strong>3 sheet terpisah</strong> untuk masing-masing tingkat: Kelas <code>X</code>, <code>XI</code>, dan <code>XII</code>.
        </small>
      </div>

      <!-- Separator (garis horizontal mobile) -->
      <div class="col-12 d-md-none">
        <hr class="my-2">
      </div>

      <!-- Import Excel Form -->
      <div class="col-md-8">
        <form action="{{ route('book-loans.import') }}" method="POST" enctype="multipart/form-data" class="row g-2">
          @csrf

          <!-- Input File -->
          <div class="col-sm-8">
            <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls" required>
          </div>

          <!-- Tombol Import -->
          <div class="col-sm-4 text-end">
            <button type="submit" class="btn btn-primary w-100">
              <i class="fas fa-upload me-1"></i> Import Excel
            </button>
          </div>
        </form>

        <small class="text-muted d-block mt-2">
          Pastikan file sesuai dengan template yang diunduh, dan memiliki ekstensi <code>.xlsx</code> atau <code>.xls</code>.
          Data akan diproses secara otomatis sesuai dengan tingkat dan kelas masing-masing.
        </small>
      </div>

    </div>
  </div>
</div>

@if (session('import_errors'))
    <div class="alert alert-warning">
        <strong>Beberapa data tidak diproses:</strong>
        <ul class="mb-0">
            @foreach (session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {!! session('success') !!}
    </div>
@endif

    <!-- Daftar Kelas -->
    <div id="classList" class="d-flex flex-wrap gap-3">
      @foreach($classes as $class)
        @php
          $loanCount = $classLoans[$class->class_id] ?? 0;
          $studentCount = $class->students->count();
        @endphp

        <div class="class-card class-item" onclick="window.location.href='{{ route('book-loans.class-students', $class->class_id) }}'">
          <div class="class-card-header">
            <div class="class-header-content">
              <div class="class-icon">
                <i class="fas fa-chalkboard-teacher"></i>
              </div>
              <div>
                <div class="class-name">{{ $class->class_name }}</div>
                <div class="class-meta">{{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}</div>
              </div>
            </div>
          </div>

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
            <div class="teacher-details">
              <div class="teacher-name">{{ $class->employee->fullname ?? 'Belum ada wali kelas' }}</div>
              <div class="teacher-role">Wali Kelas</div>
            </div>
            <i class="fas fa-chevron-right text-muted ms-2" style="font-size: 0.8rem;"></i>
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
        document.querySelectorAll('.class-item').forEach(card => {
            const name = card.querySelector('.class-name').textContent.toLowerCase();
            const teacher = card.querySelector('.teacher-name').textContent.toLowerCase();
            card.style.display = (name.includes(val) || teacher.includes(val)) ? 'flex' : 'none';
        });
    });

    // Filter
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const tingkat = document.getElementById('filterTingkat').value;
        document.querySelectorAll('.class-item').forEach(card => {
            const name = card.querySelector('.class-name').textContent;
            card.style.display = (!tingkat || name.includes(tingkat)) ? 'flex' : 'none';
        });
    });

    // Reset
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('searchClass').value = '';
        document.getElementById('filterTingkat').value = '';
        document.querySelectorAll('.class-item').forEach(card => card.style.display = 'flex');
    });

    // Toggle View
    document.getElementById('gridViewBtn').addEventListener('click', function () {
      document.getElementById('classList').classList.remove('list-view');
      this.classList.add('active');
      document.getElementById('listViewBtn').classList.remove('active');
    });

    document.getElementById('listViewBtn').addEventListener('click', function () {
      document.getElementById('classList').classList.add('list-view');
      this.classList.add('active');
      document.getElementById('gridViewBtn').classList.remove('active');
    });
});
</script>
</body>
@endif
</html>
