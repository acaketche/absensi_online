<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --success-color: #4cc9f0;
      --warning-color: #f72585;
      --danger-color: #e63946;
    }

    .class-card {
      border-radius: 12px;
      border: none;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      overflow: hidden;
      margin-bottom: 20px;
      cursor: pointer;
    }

    .class-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .class-card-header {
      background: linear-gradient(135deg, var(--secondary-color));
      color: white;
      padding: 15px;
      position: relative;
    }

    .class-card-header::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 100%;
      height: 20px;
      background: white;
      clip-path: polygon(0 0, 100% 0, 100% 10px, 0 100%);
    }

    .class-name {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .class-icon {
      width: 50px;
      height: 50px;
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }

    .teacher-info {
      display: flex;
      align-items: center;
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 0 0 10px 10px;
    }

    .teacher-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }

    .teacher-initial {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--accent-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin-right: 10px;
    }

    .class-stats {
      display: flex;
      justify-content: space-around;
      padding: 10px 0;
      background-color: white;
    }

    .stat-item {
      text-align: center;
      padding: 5px;
    }

    .stat-value {
      font-size: 1.1rem;
      font-weight: bold;
      color: var(--primary-color);
    }

    .stat-label {
      font-size: 0.75rem;
      color: #6c757d;
      text-transform: uppercase;
    }

    .search-box {
      position: relative;
    }

    .search-icon {
      position: absolute;
      left: 12px;
      top: 10px;
      color: #6c757d;
    }

    .search-input {
      padding-left: 40px;
    }

    .filter-section {
      background-color: white;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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

    <!-- Judul Halaman -->
    <header class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fs-4 fw-bold mb-1">Peminjaman Buku - Daftar Kelas</h2>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
          <i class="fas fa-exchange-alt me-2"></i> Buku
        </a>
      </div>
    </header>

    <!-- Filter dan Pencarian -->
    <div class="filter-section">
      <div class="row align-items-center">
        <div class="col-md-6 mb-2 mb-md-0">
          <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchClass" class="form-control search-input" placeholder="Cari kelas atau wali kelas...">
          </div>
        </div>
        <div class="col-md-6">
          <form id="filterForm" class="row g-2">
            <div class="col-md-8">
              <select class="form-select" id="filterTingkat" name="tingkat">
                <option value="">Semua Tingkat Kelas</option>
                <option value="X">Kelas 10</option>
                <option value="XI">Kelas 11</option>
                <option value="XII">Kelas 12</option>
              </select>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter me-1"></i> Filter
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Daftar Kelas -->
    <div class="row" id="classList">
      @foreach($classes as $class)
      <div class="col-md-4 mb-4 class-item">
        <div class="class-card h-100" onclick="window.location.href='{{ route('book-loans.class-students', $class->class_id) }}'">
          <div class="class-card-header d-flex align-items-center">
            <div class="class-icon">
              <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div>
              <div class="class-name">{{ $class->class_name }}</div>
              <div class="small opacity-75">{{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}</div>
            </div>
          </div>

          <div class="class-stats">
            <div class="stat-item">
             <div class="stat-value">{{ $class->students->count() }}</div>
              <div class="stat-label">Siswa</div>
            </div>
            <div class="stat-item">
  <div class="stat-value">{{ $classLoans[$class->id] ?? 0 }}</div>
  <div class="stat-label">Pinjaman</div>
</div>

          </div>

          <div class="teacher-info">
            @if($class->employee && $class->employee->photo)
              <img src="{{ asset('storage/' . $class->employee->photo) }}" class="teacher-avatar" alt="Wali Kelas">
            @else
              <div class="teacher-initial">
                {{ $class->employee ? substr($class->employee->fullname, 0, 1) : '?' }}
              </div>
            @endif
            <div>
              <div class="fw-bold">{{ $class->employee->fullname ?? 'Belum ada wali kelas' }}</div>
              <div class="small text-muted">Wali Kelas</div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    @if($classes->isEmpty())
      <div class="text-center py-5">
        <i class="fas fa-chalkboard fa-4x text-muted mb-4"></i>
        <h4 class="text-muted">Belum ada kelas tersedia</h4>
        <p class="text-muted">Silakan tambahkan kelas terlebih dahulu</p>
      </div>
    @endif
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian kelas
    document.getElementById('searchClass').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const classItems = document.querySelectorAll('.class-item');

        classItems.forEach(item => {
            const className = item.querySelector('.class-name').textContent.toLowerCase();
            const teacherName = item.querySelector('.fw-bold').textContent.toLowerCase();

            if (className.includes(searchValue) || teacherName.includes(searchValue)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Fungsi filter kelas
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const tingkat = document.getElementById('filterTingkat').value;
        const classItems = document.querySelectorAll('.class-item');

        classItems.forEach(item => {
            const className = item.querySelector('.class-name').textContent;

            if (tingkat && !className.includes(tingkat)) {
                item.style.display = 'none';
            } else {
                item.style.display = 'block';
            }
        });
    });

    // Reset filter saat select diubah
    document.getElementById('filterTingkat').addEventListener('change', function() {
        if (this.value === '') {
            const classItems = document.querySelectorAll('.class-item');
            classItems.forEach(item => {
                item.style.display = 'block';
            });
        }
    });
});
</script>
</body>
</html>
@endif
