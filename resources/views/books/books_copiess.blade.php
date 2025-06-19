<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Salinan Buku - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/salinan.css') }}">
  <style>
  :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --success-color: #4cc9f0;
      --warning-color: #f72585;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    body {
      background-color: #f5f7fb;
      font-family: 'Poppins', sans-serif;
    }

  a {
    text-decoration: none;
  }

  /* Sidebar */
  .sidebar {
    width: 250px;
    min-height: 100vh;
    background: linear-gradient(180deg, #2c3e50, #34495e);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    color: #fff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
  }

  .sidebar a {
    color: #ecf0f1;
    padding: 12px 20px;
    display: block;
    transition: background 0.3s ease;
  }

  .sidebar a:hover {
    background: rgba(255, 255, 255, 0.1);
  }

  .sidebar .active {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    color: #fff;
  }

  /* Main Content */
  .main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 100vh;
  }

  .main-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Cards */
  .card {
    border-radius: 15px;
    border: none;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
    background: rgba(255, 255, 255, 0.95);
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
  }

  .card-header {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    color: white;
    border: none;
    padding: 20px;
  }

  /* Buttons */
  .btn-primary {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    border: none;
    border-radius: 50px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
  }

  .btn-outline-secondary {
    border: 2px solid #6c757d;
    border-radius: 50px;
    padding: 10px 25px;
    transition: all 0.3s;
  }

  .btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
  }

  .btn-outline-primary {
    border: 2px solid #3f37c9;
    border-radius: 50px;
    padding: 10px 25px;
    color: #3f37c9;
  }

  .btn-outline-primary:hover {
    background: #3f37c9;
    color: #fff;
  }

  /* Badge Styles */
  .badge-status {
    border-radius: 50px;
    padding: 6px 15px;
    font-size: 12px;
    font-weight: 500;
  }

  .bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
  }

  .bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
  }

  /* Tables */
  .table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }

  .table thead th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: none;
    color: #495057;
    font-weight: 600;
  }

  .table tbody td {
    border: none;
    vertical-align: middle;
    padding: 12px;
  }

  .table tbody tr:hover {
    background: rgba(67, 97, 238, 0.05);
    transform: scale(1.01);
  }

  /* Book Cover */
  .book-cover {
    width: 150px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s;
  }

  .book-cover:hover {
    transform: scale(1.05);
  }

  .text-gradient {
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* Alerts */
  .alert {
    border-radius: 12px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 20px;
  }

  .alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
  }

  /* Responsive */
  @media (max-width: 992px) {
    .sidebar {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    .sidebar.show {
      transform: translateX(0);
    }
    .main-content {
      margin-left: 0;
    }
    .mobile-toggle {
      display: block !important;
    }
  }

  /* Mobile Toggle Button */
  .mobile-toggle {
    display: none;
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1100;
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }

  /* Scrollbar */
  ::-webkit-scrollbar {
    width: 8px;
  }
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    border-radius: 10px;
  }

  /* Ripple Effect */
  .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
  }

  @keyframes ripple-animation {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }
</style>

</head>

<body>
  @if(Auth::guard('employee')->check())
    <button class="mobile-toggle" onclick="toggleSidebar()">
      <i class="fas fa-bars"></i>
    </button>

    <div class="d-flex">
      @include('components.sidebar')
      <main class="main-content flex-grow-1">
        @include('components.profiladmin')

        <div class="main-container">
          @if(session('success'))
            <div class="alert alert-success">
              <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
          @endif

          <div class="alert alert-success d-none" id="successAlert">
            <i class="fas fa-check-circle me-2"></i> Operasi berhasil dilakukan!
          </div>

          <div class="card">
            <div class="card-header text-center">
              <h2 class="fw-bold mb-1 text-white">
                <i class="fas fa-books me-2"></i> Manajemen Salinan Buku
              </h2>
              <p class="mb-0 opacity-75">Kelola salinan buku perpustakaan dengan mudah</p>
            </div>
          </div>

          <div class="card">
            <div class="card-body text-center">
              <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/cover-default.png') }}" alt="Cover Buku" class="book-cover mb-3">
              <h5 class="fw-semibold text-gradient">{{ $book->title ?? 'Judul Buku' }}</h5>
              <div class="text-muted mb-3">
                <i class="fas fa-user me-1"></i> {{ $book->author ?? 'Penulis' }} |
                <i class="fas fa-building me-1"></i> {{ $book->publisher ?? 'Penerbit' }} |
                <i class="fas fa-calendar me-1"></i> {{ $book->year_published ?? 'Tahun' }}
              </div>
              <div class="d-flex justify-content-center gap-2">
                <span class="badge bg-primary">
                  <i class="fas fa-book me-1"></i>
                  Total: <span id="totalCopies">{{ $book->copies->count() ?? 0 }}</span> Salinan
                </span>
                <span class="badge bg-success">
                  <i class="fas fa-check me-1"></i>
                  Tersedia: <span id="availableCopies">{{ $book->copies->where('status', 'Tersedia')->count() ?? 0 }}</span>
                </span>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-semibold mb-0">
                  <i class="fas fa-list me-2"></i> Daftar Kode Salinan
                </h5>
                <button class="btn btn-primary btn-sm" onclick="addNewCopy()">
                  <i class="fas fa-plus me-1"></i> Tambah Salinan
                </button>
              </div>

              @if($book->copies && $book->copies->count() > 0)
                <div class="table-responsive">
                  <table class="table align-middle mb-0" id="copiesTable">
                    <thead>
                      <tr class="text-center">
                        <th style="width: 60px;">No</th>
                        <th>Kode Buku</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="copiesTableBody">
                      @foreach($book->copies as $index => $copy)
                        <tr>
                          <td class="text-center fw-bold">{{ $index + 1 }}</td>
                          <td class="fw-semibold">{{ $copy->copy_code }}</td>
                          <td class="text-center">
                            <span class="badge badge-status {{ $copy->status == 'Tersedia' ? 'bg-success' : 'bg-secondary' }}">
                              <i class="fas fa-{{ $copy->status == 'Tersedia' ? 'check' : 'times' }} me-1"></i>
                              {{ $copy->status ?? 'Tidak Diketahui' }}
                            </span>
                          </td>
                          <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="editCopy({{ $copy->id }})">
                              <i class="fas fa-edit"></i>
                            </button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="text-center py-5">
                  <i class="fas fa-book text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                  <p class="text-muted mt-3">Belum ada kode salinan untuk buku ini.</p>
                  <button class="btn btn-primary" onclick="addNewCopy()">
                    <i class="fas fa-plus me-1"></i> Tambah Salinan Pertama
                  </button>
                </div>
              @endif

              <div class="mt-4 text-center">
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i> Klik tombol edit untuk mengubah status atau kode salinan
                </small>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Buku
            </a>
            <div class="d-flex gap-2">
              <button class="btn btn-primary" onclick="exportData()">
                <i class="fas fa-download me-1"></i> Export
              </button>
              <button class="btn btn-outline-primary" onclick="printData()">
                <i class="fas fa-print me-1"></i> Print
              </button>
            </div>
          </div>

        </div>
      </main>
    </div>
  @else
    <div class="container mt-5">
      <div class="alert alert-danger text-center">
        <h4>Akses Ditolak</h4>
        <p>Anda harus login sebagai employee untuk mengakses halaman ini.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
      </div>
    </div>
  @endif

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/salinan.js') }}"></script>
</body>

</html>
