<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Salinan Buku - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --primary-light: #eef2ff;
      --secondary-color: #3f37c9;
      --success-color: #4cc9f0;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Poppins', sans-serif;
      color: #333;
    }

    .card {
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      border: none;
      margin-bottom: 24px;
      transition: transform 0.2s, box-shadow 0.2s;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .card-header {
      background-color: white;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      font-weight: 600;
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 8px;
      border: none;
      padding: 0.5rem 1.25rem;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.4);
    }

    .btn-outline-primary {
      border-color: var(--primary-color);
      color: var(--primary-color);
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-light);
    }

    .badge-status {
      border-radius: 50px;
      padding: 0.375rem 0.75rem;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: capitalize;
      letter-spacing: 0.5px;
    }

    .book-cover {
      width: 100%;
      max-width: 160px;
      height: 220px;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s;
    }

    .book-cover:hover {
      transform: scale(1.05) rotate(1deg);
    }

    .book-title {
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 0.75rem;
      line-height: 1.3;
    }

    .book-meta {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }

    .table-container {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      max-height: 500px;
      overflow-y: auto;
    }

    .table {
      margin-bottom: 0;
      width: 100%;
    }

    .table thead {
      background-color: var(--primary-color);
      color: white;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .table th {
      font-weight: 500;
      padding: 1rem 1.25rem;
      white-space: nowrap;
    }

    .table td {
      padding: 1rem 1.25rem;
      vertical-align: middle;
      background-color: white;
      border-bottom: 1px solid #f0f0f0;
    }

    .table tr:last-child td {
      border-bottom: none;
    }

    .table tr:hover td {
      background-color: var(--primary-light);
    }

    .empty-state {
      padding: 3rem 1rem;
      text-align: center;
      color: #6c757d;
      background-color: white;
      border-radius: 12px;
    }

    .empty-state i {
      font-size: 3.5rem;
      color: #e9ecef;
      margin-bottom: 1.5rem;
      opacity: 0.7;
    }

    .empty-state h5 {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .empty-state p {
      max-width: 400px;
      margin: 0 auto 1.5rem;
    }

    .action-buttons .btn {
      padding: 0.375rem 0.75rem;
      border-radius: 6px;
      margin: 0 2px;
    }

    .page-title {
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      color: #6c757d;
      font-size: 0.95rem;
    }

    /* Custom scrollbar for table */
    .table-container::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    .table-container::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    @media (max-width: 768px) {
      .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .book-cover {
        max-width: 120px;
        height: 180px;
      }

      .table th, .table td {
        padding: 0.75rem;
      }

      .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }

      .action-buttons .btn {
        width: 100%;
        margin: 0;
      }
    }

    /* Animation for status badges */
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .badge-status.available {
      animation: pulse 2s infinite;
    }
  </style>
</head>

<body>
@if(Auth::guard('employee')->check())
<div class="d-flex">
  @include('components.sidebar')

  <main class="flex-grow-1 p-4">
    @include('components.profiladmin')

    <div class="container-fluid py-3">
      <!-- Page Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="page-title">Manajemen Salinan Buku</h1>
          <p class="page-subtitle">Kelola salinan buku perpustakaan</p>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Buku
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Book Details Card -->
      <div class="card mb-4">
        <div class="card-body p-4">
          <div class="row align-items-center">
            <div class="col-md-2 text-center mb-3 mb-md-0">
              <img src="{{ asset('storage/' . $book->cover ?? 'images/cover-default.png') }}" alt="Cover Buku" class="book-cover mx-auto">
            </div>
            <div class="col-md-10">
              <div class="d-flex flex-column h-100">
                <h2 class="book-title">{{ $book->title }}</h2>

                <div class="book-meta">
                  <span class="me-3"><i class="fas fa-user me-1 text-primary"></i> {{ $book->author }}</span>
                  <span class="me-3"><i class="fas fa-building me-1 text-primary"></i> {{ $book->publisher }}</span>
                  <span><i class="fas fa-calendar-alt me-1 text-primary"></i> {{ $book->year_published }}</span>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge bg-light text-dark border">
                    <i class="fas fa-copy me-1 text-primary"></i> Total Salinan: {{ $book->copies->count() }}
                  </span>
                  <span class="badge bg-light text-dark border">
                    <i class="fas fa-check-circle me-1 text-success"></i> Tersedia: {{ $book->copies->where('is_available', true)->count() }}
                  </span>
                </div>

                <div class="mt-auto">
                  <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar"
                         style="width: {{ $book->copies->count() > 0 ? ($book->copies->where('is_available', true)->count() / $book->copies->count() * 100) : 0 }}%"
                         aria-valuenow="{{ $book->copies->where('is_available', true)->count() }}"
                         aria-valuemin="0"
                         aria-valuemax="{{ $book->copies->count() }}">
                    </div>
                  </div>
                  <small class="text-muted">
                    Ketersediaan: {{ $book->copies->where('is_available', true)->count() }} dari {{ $book->copies->count() }} salinan
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Book Copies Table -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0"><i class="fas fa-copy me-2"></i>Daftar Kode Salinan</h5>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCopyModal">
            <i class="fas fa-plus me-1"></i> Tambah Salinan
          </button>
        </div>

        <div class="card-body p-0">
          @if($book->copies->count() > 0)
          <div class="table-container">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 80px;">No</th>
                  <th>Kode Buku</th>
                  <th>Tanggal Ditambahkan</th>
                  <th style="width: 150px;">Status</th>
                  <th style="width: 150px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($book->copies as $index => $copy)
                <tr>
                  <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                  <td class="fw-bold text-primary">{{ $copy->copy_code }}</td>
                  <td>{{ $copy->created_at->format('d M Y') }}</td>
                  <td>
                    <span class="badge badge-status {{ $copy->is_available ? 'bg-success available' : 'bg-warning text-dark' }}">
                      <i class="fas {{ $copy->is_available ? 'fa-check-circle' : 'fa-book-reader' }} me-1"></i>
                      {{ $copy->status }}
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons d-flex">
                      <button class="btn btn-sm btn-outline-primary me-2" title="Edit" data-bs-toggle="modal" data-bs-target="#editCopyModal{{ $copy->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <form action="{{ route('book-copies.destroy', $copy->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="confirmDelete({{ $copy->id }})">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h5>Belum ada salinan buku</h5>
            <p>Tambahkan salinan baru untuk memulai manajemen inventaris buku ini</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCopyModal">
              <i class="fas fa-plus me-1"></i> Tambah Salinan Pertama
            </button>
          </div>
          @endif
        </div>
      </div>
    </div>
  </main>
</div>

<!-- Add Copy Modal -->
<div class="modal fade" id="addCopyModal" tabindex="-1" aria-labelledby="addCopyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('books.copies.store', $book->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addCopyModalLabel">Tambah Salinan Buku</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="copyCode" class="form-label">Kode Salinan *</label>
            <input type="text" class="form-control" id="copyCode" name="copy_code" placeholder="Misal: BK-001-2023" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status Awal *</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="is_available" id="availableTrue" value="1" checked>
              <label class="form-check-label" for="availableTrue">
                Tersedia
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="is_available" id="availableFalse" value="0">
              <label class="form-check-label" for="availableFalse">
                Dipinjam
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Salinan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Copy Modals -->
@foreach($book->copies as $copy)
<div class="modal fade" id="editCopyModal{{ $copy->id }}" tabindex="-1" aria-labelledby="editCopyModalLabel{{ $copy->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('book-copies.update', $copy->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editCopyModalLabel{{ $copy->id }}">Edit Salinan Buku</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editCopyCode{{ $copy->id }}" class="form-label">Kode Salinan *</label>
            <input type="text" class="form-control" id="editCopyCode{{ $copy->id }}"
                   name="copy_code" value="{{ $copy->copy_code }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status *</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="is_available"
                     id="editAvailableTrue{{ $copy->id }}" value="1" {{ $copy->is_available ? 'checked' : '' }}>
              <label class="form-check-label" for="editAvailableTrue{{ $copy->id }}">
                Tersedia
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="is_available"
                     id="editAvailableFalse{{ $copy->id }}" value="0" {{ !$copy->is_available ? 'checked' : '' }}>
              <label class="form-check-label" for="editAvailableFalse{{ $copy->id }}">
                Dipinjam
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Add confirmation for delete actions
  function confirmDelete(copyId) {
    Swal.fire({
      title: 'Hapus Salinan?',
      text: "Anda tidak akan dapat mengembalikan data ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#4361ee',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      backdrop: `rgba(67, 97, 238, 0.15)`
    }).then((result) => {
      if (result.isConfirmed) {
        // Find the form and submit it
        document.querySelector(`form[action*="/book-copies/${copyId}"]`).submit();
      }
    });
  }

  // Show success/error messages with SweetAlert
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      confirmButtonColor: '#4361ee',
      timer: 3000,
      timerProgressBar: true,
      toast: true,
      position: 'top-end',
      showConfirmButton: false
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}',
      confirmButtonColor: '#4361ee'
    });
  @endif

  // Initialize tooltips
  document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>
</body>
</html>
