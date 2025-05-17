<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Kelas - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    .table-hover tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.05);
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
    }

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
    @include('components.profiladmin')

    <!-- Judul Halaman dan Tombol Kembali -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Detail Kelas {{ $class->class_name }}</h2>
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </header>

    <!-- Informasi Kelas -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle me-1"></i> Informasi Kelas
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Nama Kelas</div>
                <div class="col-md-8">{{ $class->class_name }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-4 fw-bold">Wali Kelas</div>
                <div class="col-md-8">
                    <div class="teacher-info d-flex align-items-center">
                        <img src="{{ $class->employee?->photo ? asset('storage/' . $class->employee->photo) : 'https://via.placeholder.com/150' }}"
                             alt="Foto {{ $class->employee?->fullname ?? 'Tidak Ada Data' }}"
                             class="rounded-circle me-3"
                             width="100" height="120">
                        <div>
                            <div class="fw-bold">{{ $class->employee?->fullname ?? 'Tidak Ada Data' }}</div>
                            <small class="text-muted">NIP: {{ $class->employee?->id_employee ?? '-' }}</small>
                        </div>
                    </div>
                </div>
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
                                <th width="40%">Nama Siswa</th>
                                <th width="20%">Jenis Kelamin</th>
                                <th width="20%">Tanggal Lahir</th>
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
                                            <div class="rounded-circle student-initial bg-secondary me-2">
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
                                <td>{{ $student->birth_date->format('d F Y') }}</td>
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
                    <span class="fw-bold">Laki-laki:</span> {{ $students->where('gender', 'L')->count() }} orang |
                    <span class="fw-bold">Perempuan:</span> {{ $students->where('gender', 'P')->count() }} orang
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
