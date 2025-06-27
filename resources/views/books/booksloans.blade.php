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
      --secondary-color: #3a0ca3;
      --accent-color: #4895ef;
      --success-color: #4cc9f0;
      --warning-color: #f72585;
      --danger-color: #e63946;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

   #classList {
  justify-content: flex-start;
}

.class-card {
  border-radius: 10px;
  background-color: #fff;
  box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  cursor: pointer;
  transition: transform 0.2s;
}

.class-card:hover {
  transform: translateY(-4px);
}

.class-card-header {
  background: linear-gradient(135deg, #3f37c9, #4361ee);
  color: #fff;
  border-radius: 10px 10px 0 0;
}

.class-icon {
  width: 36px;
  height: 36px;
  background-color: rgba(255,255,255,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.class-name {
  font-weight: bold;
  font-size: 1rem;
}

.class-stats {
  display: flex;
  justify-content: space-around;
  padding: 10px 0;
  background: #f8f9fa;
}

.stat-value {
  font-weight: 600;
  color: #3f37c9;
  font-size: 0.95rem;
}

.stat-label {
  font-size: 0.7rem;
  text-transform: uppercase;
  color: #6c757d;
}

.teacher-info {
  display: flex;
  align-items: center;
  background-color: #f1f3f5;
  border-radius: 0 0 10px 10px;
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
}

    .class-stats {
      display: flex;
      justify-content: space-around;
      padding: 16px 0;
      background-color: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-item {
      text-align: center;
      padding: 8px;
      flex: 1;
    }

    .stat-value {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 0.8rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 500;
    }

    /* Search and Filter */
    .search-box {
      position: relative;
    }

    .search-icon {
      position: absolute;
      left: 16px;
      top: 12px;
      color: var(--primary-color);
      z-index: 10;
    }

    .search-input {
      padding-left: 48px;
      border-radius: 50px;
      border: 1px solid #e0e0e0;
      height: 48px;
      font-size: 0.95rem;
      transition: all 0.3s;
    }

    .search-input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .filter-section {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: var(--card-shadow);
    }

    .form-select {
      height: 48px;
      border-radius: 50px;
      border: 1px solid #e0e0e0;
      padding-left: 16px;
      font-size: 0.95rem;
    }

    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 50px;
      height: 48px;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 0 24px;
      box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
      transition: all 0.3s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(67, 97, 238, 0.25);
    }

    .btn-outline-primary {
      border-radius: 50px;
      height: 48px;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 0 24px;
      transition: all 0.3s;
    }

    /* Page Header */
    .page-header h2 {
      color: var(--secondary-color);
      font-weight: 700;
      position: relative;
      display: inline-block;
    }

    .page-header h2::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), transparent);
      border-radius: 2px;
    }

    /* Empty State */
    .empty-state {
      padding: 60px 0;
      text-align: center;
      background-color: white;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
    }

    .empty-state i {
      font-size: 4rem;
      color: var(--accent-color);
      margin-bottom: 20px;
      opacity: 0.7;
    }

    .empty-state h4 {
      color: var(--secondary-color);
      font-weight: 600;
      margin-bottom: 8px;
    }

    .empty-state p {
      color: #6c757d;
      max-width: 500px;
      margin: 0 auto;
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
    <header class="d-flex justify-content-between align-items-center">
      <div class="page-header">
        <h2 class="fs-3 fw-bold mb-2">Peminjaman Buku - Daftar Kelas</h2>
        <p class="text-muted">Pilih kelas untuk melihat daftar siswa dan peminjaman buku</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('books.index') }}" class="btn btn-outline-primary d-flex align-items-center">
          <i class="fas fa-exchange-alt me-2"></i> Buku
        </a>
      </div>
    </header>
<!-- Filter dan Pencarian -->
<div class="filter-section mb-4">
  <div class="row g-3 align-items-center">
    <!-- Pencarian -->
    <div class="col-lg-4 col-md-6">
      <div class="search-box position-relative">
        <i class="fas fa-search search-icon position-absolute top-50 start-0 translate-middle-y ms-3"></i>
         <input type="text" id="searchClass" class="form-control search-input ps-5" placeholder="Cari kelas atau wali kelas...">
      </div>
    </div>

    <!-- Filter dan Reset -->
    <div class="col-lg-8 col-md-6">
      <form id="filterForm" class="d-flex flex-wrap justify-content-lg-end gap-2">
        <select class="form-select w-auto" id="filterTingkat" name="tingkat">
          <option value="">Semua Tingkat Kelas</option>
          <option value="X">Kelas 10</option>
          <option value="XI">Kelas 11</option>
          <option value="XII">Kelas 12</option>
        </select>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-filter me-1"></i> Filter
        </button>
        <button type="button" id="resetFilter" class="btn btn-outline-secondary">
          <i class="fas fa-redo me-1"></i> Reset
        </button>
      </form>
    </div>
  </div>
</div>

   <!-- Daftar Kelas -->
<div id="classList" class="d-flex flex-wrap gap-3">
  @foreach($classes as $class)
    <div class="class-card  class-item flex-shrink-0" style="width: 300px;" onclick="window.location.href='{{ route('book-loans.class-students', $class->class_id) }}'">
      <div class="class-card-header d-flex align-items-center p-2">
        <div class="class-icon me-2">
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
        <div class="stat-value">{{ $classLoans[$class->class_id] ?? 0 }}</div>
          <div class="stat-label">Pinjaman</div>
        </div>
      </div>

      <div class="teacher-info p-2">
        @if($class->employee && $class->employee->photo)
          <img src="{{ asset('storage/' . $class->employee->photo) }}" class="teacher-avatar" alt="Wali Kelas">
        @else
          <div class="teacher-initial">{{ $class->employee ? substr($class->employee->fullname, 0, 1) : '?' }}</div>
        @endif
        <div>
          <div class="fw-semibold small">{{ $class->employee->fullname ?? 'Belum ada wali kelas' }}</div>
          <div class="text-muted small">Wali Kelas</div>
        </div>
      </div>
    </div>
  @endforeach
</div>

@if($classes->isEmpty())
  <div class="empty-state text-center py-5">
    <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Belum ada kelas tersedia</h5>
    <p class="text-muted small">Silakan tambahkan kelas terlebih dahulu melalui menu administrasi</p>
  </div>
@endif


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian kelas
    document.getElementById('searchClass').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const classItems = document.querySelectorAll('.class-item');

        classItems.forEach(item => {
            const className = item.querySelector('.class-name').textContent.toLowerCase();
            const teacherName = item.querySelector('.fw-semibold')?.textContent.toLowerCase() || '';

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

   document.getElementById('resetFilter').addEventListener('click', function () {
    // Reset nilai input dan select
    document.getElementById('searchClass').value = '';
    document.getElementById('filterTingkat').value = '';

    // Tampilkan semua class card
    const classItems = document.querySelectorAll('.class-item');
    classItems.forEach(item => {
        item.style.display = 'block';
    });
});

</script>
</body>
</html>
@endif
