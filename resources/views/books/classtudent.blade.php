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

   .badge-book-count.badge-active {
    background-color: #28a745; /* Hijau */
    color: #fff;
}

.badge-book-count.badge-none {
    background-color: #dc3545; /* Merah */
    color: #fff;
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
    @include('components.profiladmin')
    <!-- Judul Halaman dan Tombol Kembali -->
<!-- Header -->
<!-- Header -->
<header class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <div>
      <h2 class="fs-4 fw-bold mb-1">Daftar Siswa Kelas {{ $class->class_name }}</h2>
      <p class="text-muted mb-0">
        Tahun Ajaran: <strong>{{ $activeAcademicYear->year_name ?? '-' }}</strong> |
        Semester: <strong>{{ $activeSemester->semester_name ?? '-' }}</strong>
      </p>
    </div>

    <!-- Tombol Kembali -->
     <a href="{{ route('book-loans.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Kembali
  </a>
  </div>
</header>

<!-- Daftar Siswa -->
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <!-- Judul -->
      <div class="fw-semibold">
        <i class="fas fa-users me-2"></i> Daftar Siswa Kelas {{ $class->class_name }}
      </div>

      <!-- Tombol Aksi -->
      <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Input Pencarian -->
        <div class="input-group input-group-sm" style="width: 180px;">
          <span class="input-group-text bg-white">
            <i class="fas fa-search text-dark"></i>
          </span>
          <input type="text" id="searchStudent" class="form-control" placeholder="Cari siswa...">
        </div>

        <!-- Tombol Cetak -->
        <button class="btn btn-sm btn-light" id="printStudentList">
          <i class="fas fa-print me-1"></i> Cetak
        </button>

        <!-- Tombol Import -->
        <form action="{{ route('book-loans.import') }}" method="POST" enctype="multipart/form-data" class="btn btn-sm btn-success">
          @csrf
            <i class="fas fa-file-import me-1"></i> Import
            <input type="file" name="import_file" accept=".xlsx, .xls" onchange="this.form.submit()" hidden>
          </label>
        </form>

        <!-- Tombol Export -->
        <a href="{{ route('export.bookloan.class', ['classId' => $class->class_id]) }}" class="btn btn-sm btn-success">
          <i class="fas fa-file-excel me-1"></i> Export
        </a>
      </div>
    </div>
  </div>

       <div class="card-body">
    @if($students->count() > 0)
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-hover" id="studentTable">
                <thead class="table-light">
                    <tr>
                        <th width="3%">No</th>
                        <th width="12%">NIS</th>
                        <th width="25%">Nama Siswa</th>
                        <th width="12%">Jenis Kelamin</th>
                        <th width="12%">Buku Dipinjam</th>
                        <th width="15%">Buku Dikembalikan</th>
                        <th width="10%">Aksi</th>
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
                                            <span class="text-primary"><i class="fas fa-male me-1"></i> Laki-laki</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-female me-1"></i> Perempuan</span>
                                        @endif
                                    </td>
                       <td class="text-center">
    @if($student->borrowed_books_count > 0)
        <span class="badge rounded-pill badge-book-count badge-active">
            <i class="fas fa-book me-1"></i> {{ $student->borrowed_books_count }} Buku
        </span>
    @else
        <span class="badge rounded-pill badge-book-count badge-none">
            <i class="fas fa-times-circle me-1"></i> Tidak Ada
        </span>
    @endif
</td>
<td class="text-center">
    @if($student->returned_books_count > 0)
        <span class="badge rounded-pill badge-book-count badge-active">
            <i class="fas fa-undo me-1"></i> {{ $student->returned_books_count }} Buku
        </span>
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
<div class="card-footer bg-light ">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold">Total Siswa:</span> {{ $students->count() }} orang
        </div>
        <div>
            <span class="fw-bold">Total Buku Dipinjam:</span> {{ $totalBookLoans }} buku
        </div>
    </div>
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

   document.getElementById('printStudentList').addEventListener('click', function() {
    // Dapatkan tabel asli
    const table = document.getElementById('studentTable');

    // Clone tabel agar tidak mengubah tampilan halaman utama
    const cloneTable = table.cloneNode(true);

    // Hapus kolom 'Aksi' dari cloneTable
    // Pertama, cari index kolom 'Aksi' di thead
    const ths = cloneTable.querySelectorAll('thead th');
    let aksiIndex = -1;
    ths.forEach((th, index) => {
        if (th.textContent.trim().toLowerCase() === 'aksi') {
            aksiIndex = index;
        }
    });

    if (aksiIndex !== -1) {
        // Hapus header kolom aksi
        cloneTable.querySelector('thead tr').children[aksiIndex].remove();

        // Hapus sel kolom aksi di setiap baris tbody
        cloneTable.querySelectorAll('tbody tr').forEach(row => {
            if (row.children[aksiIndex]) {
                row.children[aksiIndex].remove();
            }
        });
    }
// Perkecil foto di cloneTable
cloneTable.querySelectorAll('img').forEach(img => {
    img.style.width = '30px';
    img.style.height = '40px';
});

// Buat jendela baru untuk cetak
const printWindow = window.open('', '', 'height=600,width=800');

printWindow.document.write('<html><head><title>Daftar Siswa</title>');
printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
// Bisa juga letakkan style di sini sebagai alternatif:
// printWindow.document.write('<style> img { width: 50px; height: auto; } </style>');
printWindow.document.write('</head><body>');

printWindow.document.write('<h3>Daftar Siswa</h3>');
printWindow.document.write(cloneTable.outerHTML);

printWindow.document.write('</body></html>');

printWindow.document.close();
printWindow.focus();

printWindow.print();
printWindow.close();
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
