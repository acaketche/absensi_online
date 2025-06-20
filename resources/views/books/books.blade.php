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
      --primary-color: #4361ee;
      --primary-light: #eef2ff;
      --secondary-color: #3f37c9;
      --warning-color: #f72585;
      --light-bg: #f5f7fb;
      --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Poppins', sans-serif;
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
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 24px;
    }

    .book-card {
      border-radius: 16px;
      overflow: hidden;
      height: 100%;
      position: relative;
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
      min-height: 50px;
    }

    .book-author {
      color: #666;
      font-size: 14px;
      margin-bottom: 15px;
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
    }

    .btn-delete:hover {
      background-color: var(--warning-color);
      color: white;
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
    }
  </style>
</head>

@if(Auth::guard('employee')->check())
<body>
<div class="d-flex">
  @include('components.sidebar')

  <main class="flex-grow-1 p-4">
    @include('components.profiladmin')

    <div class="container-fluid py-3">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h1 class="page-title">Manajemen Buku</h1>
          <p class="text-muted">Kelola koleksi buku perpustakaan</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <a href="{{ route('book-loans.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-exchange-alt me-2"></i> Peminjaman
          </a>
          <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Tambah Buku
          </a>
        </div>
      </div>

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
          <i class="fas fa-check-circle me-2"></i>
          <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if($books->count() > 0)
      <div class="book-grid">
        @foreach ($books as $book)
        <div class="card book-card">
          <div class="card-body">
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
                <i class="fas fa-book me-1"></i>  {{ $book->stock }} tersedia
              </span>
              <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </div>
          <a href="{{ route('books.copies.show', $book->id) }}" class="stretched-link"></a>
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
</body>
</html>
