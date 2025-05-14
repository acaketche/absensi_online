<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-School {{ $class->class_name }} - E-School</title>
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
      margin-bottom: 20px;
    }

    .card-header {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      font-weight: bold;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.05);
      cursor: pointer;
    }

    .student-avatar {
      width: 40px;
      height: 40px;
      object-fit: cover;
    }

    .student-initial {
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

    .badge-book-count {
      font-size: 0.8rem;
      padding: 5px 8px;
    }

    .badge-overdue {
      background-color: #dc3545;
      color: white;
    }

    .badge-active {
      background-color: #198754;
      color: white;
    }

    .badge-none {
      background-color: #6c757d;
      color: white;
    }

    .teacher-info {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
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

    <!-- Judul Halaman dan Tombol Kembali -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Daftar Siswa Kelas {{ $class->class_name }}</h2>
        <a href="{{ route('book-loans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </header>

    <!-- Informasi Kelas -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle me-1"></i> Informasi Kelas
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nama Kelas</div>
                        <div class="col-md-8">{{ $class->class_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tahun Ajaran</div>
                        <div class="col-md-8">{{ $class->academicYear?->year_name ?? 'Tidak Ada Data' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Semester</div>
                        <div class="col-md-8">{{ $class->semester?->semester_name ?? 'Tidak Ada Data' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="teacher-info">
                        <h6 class="mb-3">Wali Kelas</h6>
                        <div class="d-flex align-items-center">
                            @if($class->employee && $class->employee->photo)
                                <img src="{{ asset('storage/' . $class->employee->photo) }}"
                                    alt="Foto {{ $class->employee->fullname }}"
                                    class="rounded-circle me-3"
                                    width="50" height="50">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px;">
                                    {{ $class->employee ? substr($class->employee->fullname, 0, 1) : 'N' }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $class->employee->fullname ?? 'Tidak Ada Data' }}</div>
                                <small class="text-muted">NIP: {{ $class->employee->id_employee ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-users me-1"></i> Daftar Siswa Kelas {{ $class->class_name }}
            </div>
            <div class="d-flex">
                <input type="text" id="searchStudent" class="form-control form-control-sm me-2" placeholder="Cari siswa...">
                <button class="btn btn-sm btn-light" id="printStudentList">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="studentTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NIS</th>
                                <th width="30%">Nama Siswa</th>
                                <th width="15%">Jenis Kelamin</th>
                                <th width="15%">Buku Dipinjam</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $student->id_student }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}"
                                                alt="Foto {{ $student->fullname }}"
                                                class="rounded-circle student-avatar me-2">
                                        @else
                                            <div class="rounded-circle student-initial me-2">
                                                {{ substr($student->fullname, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>{{ $student->fullname }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($student->gender == 'L')
                                        <span class="text-info"><i class="fas fa-male me-1"></i> Laki-laki</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-female me-1"></i> Perempuan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($student->book_loans_count > 0)
                                        @if($student->overdue_loans_count > 0)
                                            <span class="badge rounded-pill badge-book-count badge-overdue">
                                                <i class="fas fa-exclamation-circle me-1"></i> {{ $student->book_loans_count }} Buku ({{ $student->overdue_loans_count }} Terlambat)
                                            </span>
                                        @else
                                            <span class="badge rounded-pill badge-book-count badge-active">
                                                <i class="fas fa-book me-1"></i> {{ $student->book_loans_count }} Buku
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge rounded-pill badge-book-count badge-none">
                                            <i class="fas fa-times-circle me-1"></i> Tidak Ada
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('book-loans.student-books', $student->id_student) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-book me-1"></i> Detail Buku
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Belum ada siswa yang terdaftar di kelas ini.
                </div>
            @endif
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">Total Siswa:</span> {{ $students->count() }} orang
                </div>
                <div>
                    <span class="fw-bold">Total Buku Dipinjam:</span> {{ $totalBookLoans }} buku
                </div>
            </div>
        </div>
    </div>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian siswa
    document.getElementById('searchStudent').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#studentTable tbody tr');

        tableRows.forEach(row => {
            const nis = row.cells[1].textContent.toLowerCase();
            const name = row.cells[2].textContent.toLowerCase();

            if (nis.includes(searchValue) || name.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Fungsi cetak daftar siswa
    document.getElementById('printStudentList').addEventListener('click', function() {
        window.print();
    });
});
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .card-header button, .card-header input {
            display: none;
        }
    }
</style>
</body>
</html>
@endif
