<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Buku - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4266B9;
      --primary-light: #eef2ff;
      --secondary-color: #3f37c9;
      --warning-color: #f72585;
      --light-bg: #f5f7fb;
      --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      transition: transform 0.3s ease;
      overflow: hidden;
      margin-bottom: 24px;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      font-weight: 600;
      padding: 16px 20px;
      border: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 50px;
      padding: 10px 24px;
      font-weight: 500;
    }

    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
      border-radius: 50px;
      padding: 10px 24px;
      font-weight: 500;
    }

    .book-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 18px;
    }

    .book-card {
      border-radius: 16px;
      overflow: hidden;
      position: relative;
      display: flex;
      flex-direction: column;
    }

    .book-cover {
      height: 150px;
      background-color: #f0f2f5;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }

    .book-cover img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .book-card:hover .book-cover img {
      transform: scale(1.05);
    }

    .book-cover .default-cover {
      font-size: 48px;
      color: #adb5bd;
    }

    .book-content {
      padding: 10px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .book-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 8px;
      color: #2b2d42;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .book-author {
      color: #666;
      font-size: 14px;
      margin-bottom: 10px;
    }

    .book-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-size: 13px;
      color: #777;
      flex-wrap: wrap;
      gap: 8px;
    }

    .book-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 15px;
      border-top: 1px solid #eee;
      margin-top: auto;
    }

    .stock-badge {
      background-color: rgba(67, 97, 238, 0.1);
      color: var(--primary-color);
      padding: 5px 12px;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 500;
    }

    .btn-delete {
      background-color: rgba(247, 37, 133, 0.1);
      color: var(--warning-color);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 2;
    }

    .btn-delete:hover {
      background-color: var(--warning-color);
      color: white;
    }

    .btn-edit {
      background-color: rgba(67, 97, 238, 0.1);
      color: var(--primary-color);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 2;
    }

    .btn-edit:hover {
      background-color: var(--primary-color);
      color: white;
    }

    .book-actions {
      display: flex;
      gap: 8px;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background-color: white;
      border-radius: 16px;
    }

    .empty-icon {
      font-size: 64px;
      color: #e0e0e0;
      margin-bottom: 20px;
    }

    .page-title {
      font-size: 24px;
      font-weight: 700;
      color: #2b2d42;
      margin-bottom: 5px;
    }

    @media (max-width: 768px) {
      .book-grid {
        grid-template-columns: 1fr;
      }

      .header-actions {
        flex-direction: column;
        gap: 12px;
      }
       /* Add these new styles for the modal */
    .modal-content {
      border-radius: 16px;
      border: none;
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 16px 16px 0 0 !important;
      padding: 16px 24px;
      border-bottom: none;
    }

    .modal-title {
      font-weight: 600;
      font-size: 1.25rem;
    }

    .modal-body {
      padding: 24px;
    }

    .modal-footer {
      padding: 16px 24px;
      border-top: 1px solid #eee;
      background-color: #f9fafc;
      border-radius: 0 0 16px 16px;
    }

    .form-label {
      font-weight: 500;
      color: #4a5568;
      margin-bottom: 8px;
    }

    .form-control, .form-select {
      border-radius: 8px;
      padding: 10px 15px;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .img-thumbnail {
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      padding: 4px;
      background-color: white;
    }

    .btn-secondary {
      background-color: #edf2f7;
      color: #4a5568;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
    }

    .btn-secondary:hover {
      background-color: #e2e8f0;
    }
    }
  </style>
</head>

@if(Auth::guard('employee')->check())
<body>
<div class="d-flex">
  @include('components.sidebar')

  <main class="flex-grow-1 p-4">
    @include('components.profiladmin')

   <div class="container-fluid py-4">
      <!-- Header -->
<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
  <div>
    <h1 class="h3 mb-1">📚 Manajemen Buku</h1>
    <p class="text-muted mb-0">
      Kelola koleksi buku perpustakaan dengan mudah dan cepat<br>
      <small>
        Tahun Ajaran: <strong>{{ $activeAcademicYear->year_name ?? '-' }}</strong> |
        Semester: <strong>{{ $activeSemester->semester_name ?? '-' }}</strong>
      </small>
    </p>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('books.create') }}" class="btn btn-primary">
      <i class="fas fa-plus-circle me-2"></i> Tambah Buku
    </a>
  </div>
</div>

      <!-- Filter Section -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <form action="{{ route('books.index') }}" method="GET">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label for="class_level" class="form-label">Tingkatan Kelas</label>
                <select name="class_level" id="class_level" class="form-select">
                  <option value="">Semua Tingkatan</option>
                  @foreach($classes as $class)
                    @php
                      $angka = match($class->class_level) {
                          'X' => 10,
                          'XI' => 11,
                          'XII' => 12,
                          default => ''
                      };
                    @endphp
                    <option value="{{ $class->class_level }}" {{ request('class_level') == $class->class_level ? 'selected' : '' }}>
                      {{ $class->class_level }} (kelas {{ $angka }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
  <label for="search" class="form-label">Pencarian</label>
  <div class="input-group">
    <input type="text" class="form-control" id="searchBooks"
           placeholder="Cari judul, pengarang, penerbit atau kode...">
    <button class="btn btn-primary" type="button" id="resetSearch">
      <i class="fas fa-sync-alt"></i>
    </button>
  </div>
</div>
              <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2 flex-grow-1">
                  <i class="fas fa-filter me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                  <i class="fas fa-sync-alt"></i>
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Import Section -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <h5 class="mb-0">
            <i class="fas fa-file-import me-2"></i> Import Data Buku dan Salinan
          </h5>
          <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#importCollapse">
            <i class="fas fa-chevron-down"></i>
          </button>
        </div>
        <div class="collapse show" id="importCollapse">
          <div class="card-body">
            <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="file" class="form-label">Pilih File Excel</label>
                <input type="file" class="form-control" id="file" name="file" required>
                <div class="form-text">
                  File Excel harus memiliki 2 sheet:
                  <ul class="mb-0">
                    <li><strong>Books</strong> — Data buku utama</li>
                    <li><strong>Copies</strong> — Data salinan buku</li>
                  </ul>
                </div>
              </div>
              <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('books.download-template') }}" class="btn btn-info">
                  <i class="fas fa-download"></i> Download Template
                </a>
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-upload"></i> Import Sekarang
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Alert Success -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($books->count() > 0)
        <div class="book-grid">
          @foreach ($books as $book)
          <div class="card book-card">
            <div class="book-cover">
              @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}">
              @else
                <div class="default-cover">
                  <i class="fas fa-book"></i>
                </div>
              @endif
            </div>
            <div class="book-content">
              <h5 class="book-title">{{ $book->title }}</h5>
              <p class="book-author">
                <i class="fas fa-user-edit me-1"></i> {{ $book->author }}
              </p>
              <div class="book-meta">
                <span><i class="fas fa-building me-1"></i> {{ $book->publisher }}</span>
                <span><i class="fas fa-calendar-alt me-1"></i> {{ $book->year_published }}</span>
              </div>
              <div class="book-footer">
                <span class="stock-badge">
                  <i class="fas fa-book me-1"></i> {{ $book->stock }} tersedia
                </span>
                <div class="book-actions">
                  <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editBookModal{{ $book->id }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
            <a href="{{ route('books.copies.show', $book->id) }}" class="stretched-link"></a>
          </div>

          <!-- Edit Book Modal -->
          <div class="modal fade" id="editBookModal{{ $book->id }}" tabindex="-1" aria-labelledby="editBookModalLabel{{ $book->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="editBookModalLabel{{ $book->id }}">
                    <i class="fas fa-edit me-2"></i> Edit Buku
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="modal-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="code" class="form-label">Kode Buku</label>
                          <input type="text" class="form-control" id="code" name="code" value="{{ $book->code }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="title" class="form-label">Judul Buku</label>
                          <input type="text" class="form-control" id="title" name="title" value="{{ $book->title }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="author" class="form-label">Pengarang</label>
                          <input type="text" class="form-control" id="author" name="author" value="{{ $book->author }}" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="publisher" class="form-label">Penerbit</label>
                          <input type="text" class="form-control" id="publisher" name="publisher" value="{{ $book->publisher }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="year_published" class="form-label">Tahun Terbit</label>
                          <input type="number" class="form-control" id="year_published" name="year_published" value="{{ $book->year_published }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="class_id" class="form-label">Tingkatan Kelas</label>
                          <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">-- Pilih Tingkatan --</option>
                            @foreach($classes as $class)
                              @php
                                $angka = match($class->class_level) {
                                    'X' => 10,
                                    'XI' => 11,
                                    'XII' => 12,
                                    default => ''
                                };
                              @endphp
                              <option value="{{ $class->class_id }}" {{ $book->class_id == $class->class_id ? 'selected' : '' }}>
                                {{ $class->class_level }} (kelas {{ $angka }})
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="mb-3">
                          <label for="cover" class="form-label">Cover Buku</label>
                          <div class="border rounded p-3 bg-light">
                            <div class="row align-items-center">
                              <div class="col-md-8">
                                <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                                <div class="form-text mt-1">Format: JPG, PNG, JPEG (Maksimal 2MB)</div>
                              </div>
                              @if($book->cover)
                              <div class="col-md-4 text-center">
                                <p class="small text-muted mb-2">Cover saat ini:</p>
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Current Cover" class="img-thumbnail" style="max-height: 150px;">
                                <div class="form-check mt-2">
                                  <input class="form-check-input" type="checkbox" id="remove_cover_{{ $book->id }}" name="remove_cover">
                                  <label class="form-check-label small" for="remove_cover_{{ $book->id }}">
                                    Hapus cover saat ini
                                  </label>
                                </div>
                              </div>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      @else
        <div class="card">
          <div class="card-body empty-state">
            <div class="empty-icon">
              <i class="fas fa-book-open"></i>
            </div>
            <h5>Belum Ada Buku</h5>
            <p class="text-muted">Belum ada data buku yang tersedia. Silakan tambahkan buku baru.</p>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
              <i class="fas fa-plus-circle me-2"></i> Tambah Buku Baru
            </a>
          </div>
        </div>
      @endif
    </div>
  </main>
</div>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchBooks');
  const resetBtn = document.getElementById('resetSearch');

  // Live search functionality
  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const bookCards = document.querySelectorAll('.book-card');

    if (searchTerm === '') {
      // Show all if search is empty
      bookCards.forEach(card => card.style.display = 'block');
      return;
    }

    bookCards.forEach(card => {
      // Get all searchable text from card
      const cardText = [
        card.querySelector('.book-title')?.textContent || '',
        card.querySelector('.book-author')?.textContent || '',
        card.querySelector('.book-meta span:nth-child(1)')?.textContent || '', // publisher
        card.querySelector('.book-meta span:nth-child(2)')?.textContent || '', // year
        card.getAttribute('data-code') || '' // if you add data-code attribute
      ].join(' ').toLowerCase();

      // Show/hide based on match
      card.style.display = cardText.includes(searchTerm) ? 'block' : 'none';
    });
  });

  // Reset search
  resetBtn.addEventListener('click', function() {
    searchInput.value = '';
    document.querySelectorAll('.book-card').forEach(card => {
      card.style.display = 'block';
    });
  });
});
</script>
</body>
</html>
