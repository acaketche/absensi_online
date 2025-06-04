<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buku Dipinjam - {{ $student->fullname }} - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #4266B9;
      --accent-color: #4895ef;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --info-color: #17a2b8;
    }

    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: none;
      margin-bottom: 20px;
    }

    .card-header {
      background-color: var(--secondary-color);
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      font-weight: 600;
    }

    .student-avatar {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 10px;
      border: 3px solid white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .student-initial {
      width: 120px;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 3rem;
      color: white;
      background: linear-gradient(135deg, var(--secondary-color));
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .book-cover {
      width: 60px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
      padding: 5px 10px;
      border-radius: 20px;
      font-weight: 500;
    }

    .badge-overdue {
      background-color: var(--danger-color);
    }

    .badge-active {
      background-color: var(--success-color);
    }

    .badge-returned {
      background-color: var(--info-color);
    }

    .student-info-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }

    .days-remaining {
      font-weight: 600;
    }

    .days-overdue {
      color: var(--danger-color);
      animation: blink 1s infinite;
    }

    .days-warning {
      color: var(--warning-color);
    }

    .days-normal {
      color: var(--success-color);
    }

    @keyframes blink {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
    }

    .summary-card {
      background-color: white;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      height: 100%;
    }

    .summary-value {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary-color);
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

    .action-buttons .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }

    @media (max-width: 768px) {
      .student-avatar, .student-initial {
        width: 80px;
        height: 80px;
        font-size: 2rem;
      }
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
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h4 fw-bold mb-1">Buku Dipinjam</h2>
      </div>
      <a href="{{ route('book-loans.class-students', $student->class_id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>

    <!-- Informasi Siswa -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <i class="fas fa-user-graduate me-2"></i> Informasi Siswa
        </div>
        <div class="badge bg-white text-primary">
          <i class="fas fa-id-card me-1"></i> {{ $student->id_student }}
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 text-center mb-3 mb-md-0">
            @if($student->photo)
              <img src="{{ asset('storage/' . $student->photo) }}" class="student-avatar" alt="Foto {{ $student->fullname }}">
            @else
              <div class="student-initial">
                {{ strtoupper(substr($student->fullname, 0, 1)) }}
              </div>
            @endif
          </div>
          <div class="col-md-5">
            <div class="student-info-card h-100">
              <h5 class="mb-3">{{ $student->fullname }}</h5>
              <div class="mb-2">
                <span class="fw-bold">Kelas:</span> {{ $student->class->class_name ?? '-' }}
              </div>
              <div class="mb-2">
                <span class="fw-bold">Jenis Kelamin:</span> {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
              </div>
              <div>
                <span class="fw-bold">Tahun Ajaran:</span> {{ $activeAcademicYear->year_name ?? '-' }}
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="row h-100">
              <div class="col-md-6 mb-3 mb-md-0">
                <div class="summary-card text-center">
                  <div class="mb-2">
                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                    <div class="summary-value">{{ $bookLoans->where('status', 'Dipinjam')->count() }}</div>
                    <div class="text-muted">Total Pinjaman</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="summary-card text-center">
                  <div class="mb-2">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <div class="summary-value">{{ $bookLoans->where('status', 'Dikembalikan')->count() }}</div>
                    <div class="text-muted">Dikembalikan</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Daftar Buku Dipinjam -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <i class="fas fa-book me-2"></i> Daftar Buku Dipinjam
        </div>
        <div class="d-flex">
             <a href="{{ route('book-loans.print', $student->id_student) }}"
           class="btn btn-primary btn-sm me-2"
           target="_blank">
            <i class="fas fa-print me-1"></i> Cetak
        </a>
          <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchBook" class="form-control form-control-sm search-input" placeholder="Cari buku...">
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        @if($bookLoans->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="bookTable">
              <thead class="table-light">
                <tr>
                  <th width="5%">No</th>
                  <th width="15%">Kode</th>
                  <th width="30%">Judul Buku</th>
                  <th width="15%">Pinjam</th>
                  <th width="15%">Kembali</th>
                  <th width="10%">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bookLoans as $index => $loan)
                <tr>
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td>{{ $loan->book->code ?? 'N/A' }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      @if($loan->book && $loan->book->cover_image)
                        <img src="{{ asset('storage/' . $loan->book->cover_image) }}"
                             class="book-cover me-2"
                             alt="{{ $loan->book->title }}">
                      @else
                        <div class="book-cover-placeholder me-2">
                          <i class="fas fa-book"></i>
                        </div>
                      @endif
                      <div>
                        <div class="fw-bold">{{ $loan->book->title ?? 'N/A' }}</div>
                        <small class="text-muted">{{ $loan->book->author ?? 'N/A' }}</small>
                      </div>
                    </div>
                  </td>
                  <td>{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : 'N/A' }}</td>
                 <td>
    @if($loan->status === 'Dikembalikan' && $loan->return_date)
        {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
    @else
        -
    @endif
</td>
<td class="text-center">
    @if($loan->status === 'Dipinjam')
        <form action="{{ route('book.return', $loan->id) }}" method="POST" class="return-form">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-warning">
                <i class="fas fa-undo me-1"></i> Dikembalikan
            </button>
        </form>
    @else
        <span class="text-success"><i class="fas fa-check-circle"></i> Sudah Dikembalikan</span>
    @endif
</td>

                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada buku yang dipinjam</h5>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addLoanModal">
              <i class="fas fa-plus me-1"></i> Tambah Peminjaman
            </button>
          </div>
        @endif
      </div>

      @if($bookLoans->count() > 0)
        <div class="card-footer bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
              Menampilkan {{ $bookLoans->count() }} data peminjaman
            </div>
            <div>
              <span class="badge badge-status badge-active me-2">
                <i class="fas fa-clock me-1"></i> Dipinjam: {{ $bookLoans->where('status', 'Dipinjam')->count() }}
              </span>
              <span class="badge badge-status badge-returned">
                <i class="fas fa-check-circle me-1"></i> Dikembalikan: {{ $bookLoans->where('status', 'Dikembalikan')->count() }}
              </span>
            </div>
          </div>
        </div>
      @endif
    </div>
  </main>
</div>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('book-loans.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addLoanModalLabel">
            <i class="fas fa-plus-circle me-2"></i> Tambah Peminjaman
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_student" value="{{ $student->id_student }}">
          <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
          <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

          <div class="mb-3">
            <label for="book_id" class="form-label">Buku</label>
            <select class="form-select" id="book_id" name="book_id" required>
              <option value="">-- Pilih Buku --</option>
              @foreach($books as $book)
                <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->code }})</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="loan_date" class="form-label">Tanggal Pinjam</label>
            <input type="date" class="form-control" id="loan_date" name="loan_date"
                   value="{{ date('Y-m-d') }}" required>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="hidden" name="status" value="Dipinjam">
            <p class="form-control-plaintext mb-0">Dipinjam</p>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fungsi pencarian buku
  document.getElementById('searchBook').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#bookTable tbody tr');

    rows.forEach(row => {
      const bookCode = row.cells[1].textContent.toLowerCase();
      const bookTitle = row.cells[2].textContent.toLowerCase();

      if (bookCode.includes(searchValue) || bookTitle.includes(searchValue)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

 // Konfirmasi untuk form return
    document.querySelectorAll('.return-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menandai buku sebagai dikembalikan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Konfirmasi untuk form unreturn (ubah jadi belum dikembalikan)
    document.querySelectorAll('.unreturn-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Ubah status menjadi belum dikembalikan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Alert notifikasi sukses setelah redirect (jika ada flash message)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
  // Auto close alerts after 5 seconds
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      alert.style.display = 'none';
    });
  }, 5000);
});

$(document).ready(function() {
  $('#addLoanModal form').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: $(this).attr('action'),
      method: $(this).attr('method'),
      data: $(this).serialize(),
      success: function(response) {
        $('#addLoanModal').modal('hide');
        location.reload(); // reload halaman
      },
      error: function(xhr) {
        alert('Terjadi kesalahan saat menyimpan.');
      }
    });
  });
});

</script>
</body>
</html>
@endif
