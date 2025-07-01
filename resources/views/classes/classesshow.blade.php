<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Kelas - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
      background-color: #6c757d;
      border-radius: 50%;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      border: none;
    }

    .card-header {
      border-top-left-radius: 10px !important;
      border-top-right-radius: 10px !important;
      font-weight: bold;
      background-color: #4266B9;
      color: white;
    }

    .teacher-info {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
    }

    .badge-level {
      font-size: 0.8rem;
      padding: 0.35rem 0.65rem;
    }

    .bg-level-x { background-color: #6f42c1; }
    .bg-level-xi { background-color: #fd7e14; }
    .bg-level-xii { background-color: #20c997; }

    .empty-state {
      height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #6c757d;
    }

    /* Scrollable table styles */
    .table-responsive-container {
      max-height: 500px;
      overflow-y: auto;
      position: relative;
    }

    .table-responsive {
      overflow-x: auto;
      width: 100%;
    }

    .table-responsive thead th {
      position: sticky;
      top: 0;
      background-color: #f8f9fa;
      z-index: 10;
    }

    /* Custom print button style */
    .btn-print {
      color: #495057;
      border-color: #495057;
      background-color: white;
    }
    .btn-print:hover {
      background-color: #495057;
      color: white;
    }

    /* Scrollbar styling */
    .table-responsive-container::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    .table-responsive-container::-webkit-scrollbar-thumb {
      background-color: rgba(0,0,0,0.2);
      border-radius: 4px;
    }
    .table-responsive-container::-webkit-scrollbar-track {
      background-color: rgba(0,0,0,0.05);
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
        <div>
            <h2 class="fs-4 fw-bold mb-0">Detail Kelas</h2>
        </div>
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </header>

 <!-- Informasi Kelas -->
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-info-circle me-1"></i> Informasi Kelas
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Nama Kelas</div>
            <div class="col-md-4">{{ $class->class_name }}</div>

        @if ($activeYear && $activeSemester)
        <div class="row">
            <div class="col-md-12">
                <small class="text-muted">
                    Tahun Ajaran Aktif: <strong>{{ $activeYear->year_name }}</strong> |
                    Semester Aktif: <strong>{{ $activeSemester->semester_name }}</strong>
                </small>
            </div>
        </div>
        @endif
    </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="fw-bold mb-3"><i class="fas fa-user-tie me-2"></i>Wali Kelas</h5>
                    <div class="teacher-info d-flex align-items-center">
                        <img src="{{ $class->employee?->photo ? asset('storage/' . $class->employee->photo) : asset('images/default-profile.png') }}"
                             alt="Foto {{ $class->employee?->fullname ?? 'Tidak Ada Data' }}"
                             class="rounded-circle me-3"
                             width="80" height="80">
                        <div>
                            <div class="fw-bold fs-5">{{ $class->employee?->fullname ?? 'Belum Ada Wali Kelas' }}</div>
                            <div class="text-muted mb-1">
                                <i class="fas fa-id-card me-1"></i>NIP: {{ $class->employee?->id_employee ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-users me-1"></i> Daftar Siswa
                <span class="badge bg-primary ms-2">{{ $students->count() }} Siswa</span>
            </div>
            <div class="d-flex">
                <div class="input-group input-group-sm me-2" style="width: 200px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchStudent" class="form-control" placeholder="Cari siswa...">
                </div>
                <button class="btn btn-sm btn-print" id="printStudentList">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($students->count() > 0)
                <div class="table-responsive-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="studentTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">NIPD</th>
                                    <th width="35%">Nama Siswa</th>
                                    <th width="15%">Jenis Kelamin</th>
                                    <th width="20%">Tanggal Lahir</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student->id_student }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}"
                                                    alt="Foto {{ $student->fullname }}"
                                                    class="rounded-circle student-avatar me-3">
                                            @else
                                                <div class="student-initial me-3">
                                                    {{ substr($student->fullname, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $student->fullname }}</div>
                                                <small class="text-muted">{{ $student->religion ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
    @if($student->gender == 'L')
        <span class="badge bg-primary">
            <i class="fas fa-male me-1"></i> Laki-laki
        </span>
    @elseif($student->gender == 'P')
        <span class="badge bg-danger">
            <i class="fas fa-female me-1"></i> Perempuan
        </span>
    @else
        <span class="badge bg-secondary">
            <i class="fas fa-question-circle me-1"></i> Tidak Diketahui
        </span>
    @endif
</td>

                                    <td>{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger remove-student"
                                            data-student-id="{{ $student->id }}"
                                            data-student-name="{{ $student->fullname }}">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-user-graduate fa-3x mb-3"></i>
                    <h5>Belum Ada Siswa di Kelas Ini</h5>
                    <p class="text-muted">Tambahkan siswa melalui menu manajemen siswa</p>
                </div>
            @endif
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">Total:</span> {{ $students->count() }} siswa
                </div>
                <div>
                    <span class="fw-bold">Laki-laki:</span> {{ $students->where('gender', 'L')->count() }} |
                    <span class="fw-bold">Perempuan:</span> {{ $students->where('gender', 'P')->count() }}
                </div>
            </div>
        </div>
    </div>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian siswa
    const searchStudent = document.getElementById('searchStudent');
    if (searchStudent) {
        searchStudent.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#studentTable tbody tr');

            tableRows.forEach(row => {
                const nis = row.cells[1].textContent.toLowerCase();
                const name = row.cells[2].textContent.toLowerCase();
                row.style.display = (nis.includes(searchValue) || name.includes(searchValue)) ? '' : 'none';
            });
        });
    }

    // Fungsi cetak daftar siswa
    const printBtn = document.getElementById('printStudentList');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            const printContents = document.querySelector('.card').outerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
                <style>
                    @page { size: portrait; margin: 1cm; }
                    body { font-family: Arial, sans-serif; }
                    .card { border: none; box-shadow: none; }
                    .card-header button, .card-header input { display: none; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; }
                    th { background-color: #f2f2f2; }
                    .student-avatar { width: 30px; height: 30px; }
                </style>
                <h2 style="text-align: center; margin-bottom: 20px;">Daftar Siswa Kelas ${document.querySelector('h2').textContent}</h2>
                ${printContents}
                <div style="text-align: right; margin-top: 20px; font-style: italic;">
                    Dicetak pada ${new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                </div>
            `;

            window.print();
            document.body.innerHTML = originalContents;
        });
    }

    // Fungsi hapus siswa dari kelas
    document.querySelectorAll('.remove-student').forEach(btn => {
        btn.addEventListener('click', function() {
            const studentId = this.getAttribute('data-student-id');
            const studentName = this.getAttribute('data-student-name');

            Swal.fire({
                title: `Hapus Siswa dari Kelas?`,
                html: `Anda akan menghapus <b>${studentName}</b> dari kelas ini.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/classes/{{ $class->class_id }}/remove-student/${studentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Berhasil!',
                                `Siswa ${studentName} telah dihapus dari kelas.`,
                                'success'
                            ).then(() => window.location.reload());
                        } else {
                            Swal.fire(
                                'Gagal!',
                                data.message || 'Terjadi kesalahan saat menghapus siswa.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghubungi server.',
                            'error'
                        );
                    });
                }
            });
        });
    });
});
</script>
</body>
@endif
</html>
