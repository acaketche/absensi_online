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
  <style>
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      margin-bottom: 20px;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      font-weight: bold;
    }

    .class-card {
      cursor: pointer;
    }

    .teacher-info {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      margin-top: 10px;
    }

    .teacher-avatar {
      width: 40px;
      height: 40px;
      object-fit: cover;
    }

    .teacher-initial {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      background-color: #6c757d;
      border-radius: 50%;
    }

    .class-stats {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .stat-item {
      text-align: center;
    }

    .stat-value {
      font-size: 1.2rem;
      font-weight: bold;
      color: #0d6efd;
    }

    .stat-label {
      font-size: 0.8rem;
      color: #6c757d;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold mb-0"></h2>
        <div class="dropdown">
            <div class="admin-profile d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="d-flex flex-column text-end me-2">
                    <span class="admin-name">{{ Auth::guard('employee')->user()->fullname }}</span>
                    <small class="admin-role text-muted">
                        {{ Auth::guard('employee')->user()->role->role_name ?? 'Tidak ada role' }}
                    </small>
                </div>
                <div class="admin-avatar">
                    <img src="{{ Auth::guard('employee')->user()->photo ? asset('storage/' . Auth::guard('employee')->user()->photo) : 'https://via.placeholder.com/150' }}"
                         alt="Admin Profile" class="w-100 h-100 object-fit-cover">
                </div>
                <i class="fas fa-chevron-down ms-2 text-muted"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="fas fa-key"></i> Ubah Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form id="logout-form" action="{{ route('logout.employee') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Judul Halaman -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Peminjaman Buku - Daftar Kelas</h2>
        <div class="d-flex">
            <input type="text" id="searchClass" class="form-control me-2" placeholder="Cari kelas...">
        </div>
    </header>

    <!-- Filter Kelas -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Filter Kelas</h5>
            <form id="filterForm" class="row g-2">
                <div class="col-md-4">
                    <select class="form-select" id="filterTingkat" name="tingkat">
                        <option value="">Pilih Tingkat</option>
                        <option value="X">10</option>
                        <option value="XI">11</option>
                        <option value="XII">12</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <button type="reset" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Kelas -->
<div class="row" id="classList">
    @foreach($classes as $class)
    <div class="col-md-4 mb-3">
        <div class="card h-100 shadow-sm border-0" onclick="window.location.href='{{ route('book-loans.class-students', $class->class_id) }}'" style="cursor: pointer;">
            <div class="card-body">
                <h5 class="mb-2">Kelas: <strong>{{ $class->class_name }}</strong></h5>

                <p class="mb-1">
                    <strong>Wali Kelas:</strong>
                    {{ $class->employee->fullname ?? 'Belum Ditentukan' }}
                </p>

                <p class="mb-0">
                    <strong>Total Siswa:</strong> {{ $class->students_count ?? 0 }}
                </p>
            </div>
            <div class="card-footer text-center text-muted small bg-white">
                Klik untuk melihat siswa
            </div>
        </div>
    </div>
    @endforeach
</div>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian kelas
    document.getElementById('searchClass').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const classItems = document.querySelectorAll('.class-item');

        classItems.forEach(item => {
            const className = item.querySelector('.card-title').textContent.toLowerCase();
            const teacherName = item.querySelector('.fw-bold').textContent.toLowerCase();

            if (className.includes(searchValue) || teacherName.includes(searchValue)) {
                item.style.display = '';
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
            const className = item.querySelector('.card-title').textContent;

            if (tingkat && !className.includes(tingkat)) {
                item.style.display = 'none';
            } else {
                item.style.display = '';
            }
        });
    });

    // Reset filter
    document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
        const classItems = document.querySelectorAll('.class-item');
        classItems.forEach(item => {
            item.style.display = '';
        });
    });
});
</script>
</body>
</html>
@endif
