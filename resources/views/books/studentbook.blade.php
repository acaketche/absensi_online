<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buku Dipinjam - {{ $student->fullname }} - E-School</title>
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

    .student-avatar {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
    }

    .student-initial {
      width: 80px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 2rem;
      color: white;
      background-color: #6c757d;
      border-radius: 50%;
    }

    .book-cover {
      width: 60px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
    }

    .book-cover-placeholder {
      width: 60px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #e9ecef;
      color: #6c757d;
      border-radius: 5px;
    }

    .badge-status {
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

    .badge-returned {
      background-color: #0d6efd;
      color: white;
    }

    .student-info {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.05);
    }

    .days-remaining {
      font-weight: bold;
    }

    .days-remaining.text-danger {
      animation: blink 1s infinite;
    }

    @keyframes blink {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
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
        <h2 class="fs-4 fw-bold">Buku Dipinjam - {{ $student->fullname }}</h2>
        <a href="{{ route('book-loans.class-students', $student->class_id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </header>

    <!-- Informasi Siswa -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user me-1"></i> Informasi Siswa
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}"
                            alt="Foto {{ $student->fullname }}"
                            class="student-avatar">
                    @else
                        <div class="student-initial">
                            {{ substr($student->fullname, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="col-md-5">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">NIS</div>
                        <div class="col-md-8">{{ $student->id_student }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Nama</div>
                        <div class="col-md-8">{{ $student->fullname }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Kelas</div>
                        <div class="col-md-8">{{ $student->class->class_name ?? 'Tidak Ada Data' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Jenis Kelamin</div>
                        <div class="col-md-8">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="student-info">
                        <h6 class="mb-3">Ringkasan Peminjaman</h6>
                        <div class="row mb-2">
                            <div class="col-7 fw-bold">Total Buku Dipinjam</div>
                            <div class="col-5">{{ $bookLoans->count() }} buku</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-7 fw-bold">Buku Aktif</div>
                            <div class="col-5">{{ $bookLoans->where('status', 'borrowed')->count() }} buku</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-7 fw-bold">Buku Terlambat</div>
                            <div class="col-5 {{ $overdueCount > 0 ? 'text-danger' : '' }}">
                                {{ $overdueCount }} buku
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-7 fw-bold">Buku Dikembalikan</div>
                            <div class="col-5">{{ $bookLoans->where('status', 'returned')->count() }} buku</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Buku Dipinjam -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-book me-1"></i> Daftar Buku Dipinjam
            </div>
            <div class="d-flex">
                <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#addLoanModal">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
                <input type="text" id="searchBook" class="form-control form-control-sm me-2" placeholder="Cari buku...">
                <button class="btn btn-sm btn-light" id="printBookList">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($bookLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bookTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Buku</th>
                                <th width="30%">Judul Buku</th>
                                <th width="15%">Tanggal Pinjam</th>
                                <th width="15%">Tanggal Kembali</th>
                                <th width="10%">Sisa Hari</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookLoans as $index => $loan)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $loan->book->book_code ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($loan->book && $loan->book->cover_image)
                                            <img src="{{ asset('storage/' . $loan->book->cover_image) }}"
                                                alt="Cover {{ $loan->book->title }}"
                                                class="book-cover me-2">
                                        @else
                                            <div class="book-cover-placeholder me-2">
                                                <i class="fas fa-book fa-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $loan->book->title ?? 'Tidak Ada Data' }}</div>
                                            <small class="text-muted">{{ $loan->book->author ?? 'Tidak Ada Data' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $loan->due_date ? $loan->due_date->format('d M Y') : 'N/A' }}</td>
                                <td class="text-center">
                                    @if($loan->status == 'borrowed')
                                        @php
                                            $daysRemaining = now()->diffInDays($loan->due_date, false);
                                        @endphp

                                        @if($daysRemaining < 0)
                                            <span class="days-remaining text-danger">{{ $daysRemaining }} hari</span>
                                        @elseif($daysRemaining <= 2)
                                            <span class="days-remaining text-warning">{{ $daysRemaining }} hari</span>
                                        @else
                                            <span class="days-remaining text-success">{{ $daysRemaining }} hari</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($loan->status == 'borrowed')
                                        @php
                                            $isOverdue = now()->gt($loan->due_date);
                                        @endphp

                                        @if($isOverdue)
                                            <span class="badge rounded-pill badge-status badge-overdue">
                                                <i class="fas fa-exclamation-circle me-1"></i> Terlambat
                                            </span>
                                        @else
                                            <span class="badge rounded-pill badge-status badge-active">
                                                <i class="fas fa-clock me-1"></i> Dipinjam
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge rounded-pill badge-status badge-returned">
                                            <i class="fas fa-check-circle me-1"></i> Dikembalikan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Siswa ini belum meminjam buku.
                </div>
            @endif
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">Total Buku:</span> {{ $bookLoans->count() }} buku
                </div>
                <div>
                    <span class="badge rounded-pill badge-status badge-active me-2">
                        <i class="fas fa-clock me-1"></i> Dipinjam: {{ $bookLoans->where('status', 'borrowed')->count() }}
                    </span>
                    <span class="badge rounded-pill badge-status badge-returned">
                        <i class="fas fa-check-circle me-1"></i> Dikembalikan: {{ $bookLoans->where('status', 'returned')->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="{{ route('book-loans.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addLoanModalLabel">Tambah Peminjaman Buku</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_student" value="{{ $student->id_student }}">

          <div class="mb-3">
            <label for="book_id" class="form-label">Pilih Buku</label>
            <select name="book_id" id="book_id" class="form-select" required>
              <option value="" selected disabled>-- Pilih Buku --</option>
              @foreach($books as $book)
                <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->book_code }})</option>
              @endforeach
            </select>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="loan_date" class="form-label">Tanggal Pinjam</label>
              <input type="date" name="loan_date" id="loan_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
              <label for="due_date" class="form-label">Tanggal Kembali</label>
              <input type="date" name="due_date" id="due_date" class="form-control" required>
            </div>
          </div>

          <div class="mt-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
              <option value="Dipinjam" selected>Dipinjam</option>
              <option value="Dikembalikan">Dikembalikan</option>
            </select>
          </div>

          <div class="row g-3 mt-2">
            <div class="col-md-6">
              <label for="academic_year_id" class="form-label">Tahun Akademik</label>
              <select name="academic_year_id" id="academic_year_id" class="form-select" required>
                <option value="" selected disabled>-- Pilih Tahun Akademik --</option>
                @foreach($academicYears as $year)
                  <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label for="semester_id" class="form-label">Semester</label>
              <select name="semester_id" id="semester_id" class="form-select" required>
                <option value="" selected disabled>-- Pilih Semester --</option>
                @foreach($semesters as $semester)
                  <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
        </div>
      </form>
    </div>
  </div>

  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian buku
    document.getElementById('searchBook').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#bookTable tbody tr');

        tableRows.forEach(row => {
            const bookCode = row.cells[1].textContent.toLowerCase();
            const bookTitle = row.cells[2].textContent.toLowerCase();

            if (bookCode.includes(searchValue) || bookTitle.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Fungsi cetak daftar buku
    document.getElementById('printBookList').addEventListener('click', function() {
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
