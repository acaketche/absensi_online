<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Buku - E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
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

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      overflow: hidden;
      margin-bottom: 24px;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .card-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      font-weight: 600;
      padding: 16px 20px;
      border: none;
    }

    .search-container {
      position: relative;
      margin-bottom: 24px;
    }

    .search-container input {
      border-radius: 50px;
      padding: 12px 20px 12px 50px;
      border: 1px solid #e0e0e0;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .search-container input:focus {
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
      border-color: var(--primary-color);
    }

    .search-container .search-icon {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 16px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 50px;
      padding: 10px 24px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }

    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
      border-radius: 50px;
      padding: 10px 24px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }

    .book-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 24px;
    }

    .book-card {
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
    }

    .book-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .book-card .card-body {
      padding: 20px;
    }

    .book-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      height: 50px;
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

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }

    .btn-view {
      background-color: rgba(76, 201, 240, 0.1);
      color: var(--success-color);
    }

    .btn-view:hover {
      background-color: var(--success-color);
      color: white;
    }

    .btn-edit {
      background-color: rgba(67, 97, 238, 0.1);
      color: var(--primary-color);
    }

    .btn-edit:hover {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-delete {
      background-color: rgba(247, 37, 133, 0.1);
      color: var(--warning-color);
    }

    .btn-delete:hover {
      background-color: var(--warning-color);
      color: white;
    }

    .view-toggle {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .view-btn {
      background-color: #f0f0f0;
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      color: #777;
      transition: all 0.2s ease;
    }

    .view-btn.active {
      background-color: var(--primary-color);
      color: white;
    }

    .table-view {
      display: none;
    }

    .grid-view {
      display: block;
    }

    .table-container {
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
    }

    .table {
      margin-bottom: 0;
    }

    .table thead th {
      background-color: #f8f9fa;
      font-weight: 600;
      color: #444;
      padding: 15px;
      border-bottom: 2px solid #eee;
    }

    .table tbody td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid #eee;
    }

    .table tbody tr:last-child td {
      border-bottom: none;
    }

    .table tbody tr:hover {
      background-color: rgba(67, 97, 238, 0.03);
    }

    .modal-content {
      border: none;
      border-radius: 16px;
      overflow: hidden;
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
      padding: 20px;
    }

    .modal-body {
      padding: 24px;
    }

    .modal-footer {
      border-top: 1px solid #eee;
      padding: 16px 24px;
    }

    .book-cover-placeholder {
      height: 120px;
      background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #aaa;
      font-size: 24px;
    }

    .info-row {
      margin-bottom: 16px;
    }

    .info-label {
      font-weight: 500;
      color: #666;
      margin-bottom: 5px;
    }

    .info-value {
      color: #333;
      font-size: 16px;
    }

    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #e0e0e0;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
      border-color: var(--primary-color);
    }

    .form-label {
      font-weight: 500;
      color: #555;
      margin-bottom: 8px;
    }

    .alert {
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 24px;
      border: none;
    }

    .alert-success {
      background-color: rgba(76, 201, 240, 0.1);
      color: var(--success-color);
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
    }

    .empty-icon {
      font-size: 48px;
      color: #ccc;
      margin-bottom: 20px;
    }

    .empty-text {
      color: #888;
      font-size: 16px;
      margin-bottom: 20px;
    }

    @media (max-width: 768px) {
      .book-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }

      .header-actions {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
      }

      .btn {
        width: 100%;
      }
    }
  </style>
</head>
@if(Auth::guard('employee')->check())
<body>
<div class="d-flex">
  <!-- Sidebar -->
  @include('components.sidebar')

  <!-- Main Content -->
  <main class="flex-grow-1 p-4">
    <!-- Header dengan Profil Admin -->
    @include('components.profiladmin')

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 header-actions">
      <div>
        <h2 class="fs-4 fw-bold mb-1">Manajemen Buku</h2>
        <p class="text-muted mb-0">Kelola koleksi buku perpustakaan</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('book-loans.index') }}" class="btn btn-outline-primary">
          <i class="fas fa-exchange-alt me-2"></i> Peminjaman
        </a>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
          <i class="fas fa-plus-circle me-2"></i> Tambah Buku
        </a>
      </div>
    </div>

    <!-- Alert for success message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <div class="d-flex align-items-center">
        <i class="fas fa-check-circle me-2"></i>
        <div>{{ session('success') }}</div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Search and View Toggle -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <div class="search-container flex-grow-1 me-3">
        <i class="fas fa-search search-icon"></i>
        <input type="text" placeholder="Cari judul buku, penulis, atau penerbit..." class="form-control" id="searchInput">
      </div>
      <div class="view-toggle">
        <button class="view-btn active" id="gridViewBtn">
          <i class="fas fa-th-large"></i>
        </button>
        <button class="view-btn" id="tableViewBtn">
          <i class="fas fa-list"></i>
        </button>
      </div>
    </div>

    <!-- Grid View -->
    <div class="grid-view" id="gridView">
      <div class="book-grid">
        @forelse ($books as $book)
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
                <i class="fas fa-book me-1"></i> {{ $book->stock }} tersedia
              </span>
              <div class="action-buttons">
                <button type="button" class="btn btn-icon btn-view show-book-btn"
                        data-id="{{ $book->id }}"
                        data-title="{{ $book->title }}"
                        data-author="{{ $book->author }}"
                        data-publisher="{{ $book->publisher }}"
                        data-year="{{ $book->year_published }}"
                        data-stock="{{ $book->stock }}"
                        data-created="{{ $book->created_at }}"
                        data-updated="{{ $book->updated_at }}">
                  <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-icon btn-edit edit-book-btn"
                        data-id="{{ $book->id }}"
                        data-title="{{ $book->title }}"
                        data-author="{{ $book->author }}"
                        data-publisher="{{ $book->publisher }}"
                        data-year="{{ $book->year_published }}"
                        data-stock="{{ $book->stock }}">
                  <i class="fas fa-edit"></i>
                </button>
                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-icon btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12">
          <div class="card">
            <div class="card-body empty-state">
              <div class="empty-icon">
                <i class="fas fa-books"></i>
              </div>
              <h5>Belum Ada Buku</h5>
              <p class="empty-text">Belum ada data buku yang tersedia. Silakan tambahkan buku baru.</p>
              <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Tambah Buku Baru
              </a>
            </div>
          </div>
        </div>
        @endforelse
      </div>
    </div>

    <!-- Table View -->
    <div class="table-view" id="tableView">
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Judul Buku</th>
              <th>Penulis</th>
              <th>Penerbit</th>
              <th>Tahun</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($books as $index => $book)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $book->code }}</td>
              <td>{{ $book->title }}</td>
              <td>{{ $book->author }}</td>
              <td>{{ $book->publisher }}</td>
              <td>{{ $book->year_published }}</td>
              <td>
                <span class="stock-badge">{{ $book->stock }}</span>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-icon btn-view show-book-btn"
                          data-id="{{ $book->id }}"
                          data-title="{{ $book->title }}"
                          data-author="{{ $book->author }}"
                          data-publisher="{{ $book->publisher }}"
                          data-year="{{ $book->year_published }}"
                          data-stock="{{ $book->stock }}"
                          data-created="{{ $book->created_at }}"
                          data-updated="{{ $book->updated_at }}">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-edit edit-book-btn"
                          data-id="{{ $book->id }}"
                          data-title="{{ $book->title }}"
                          data-author="{{ $book->author }}"
                          data-publisher="{{ $book->publisher }}"
                          data-year="{{ $book->year_published }}"
                          data-stock="{{ $book->stock }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-4">Tidak ada data buku</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- Show Book Modal -->
<div class="modal fade" id="showBookModal" tabindex="-1" aria-labelledby="showBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showBookModalLabel">Detail Buku</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <div class="book-cover-placeholder mx-auto">
            <i class="fas fa-book"></i>
          </div>
        </div>

        <div class="info-row">
          <div class="info-label">Judul Buku</div>
          <div class="info-value fw-bold" id="detail_title">-</div>
        </div>

        <div class="info-row">
          <div class="info-label">Penulis</div>
          <div class="info-value" id="detail_author">-</div>
        </div>

        <div class="info-row">
          <div class="info-label">Penerbit</div>
          <div class="info-value" id="detail_publisher">-</div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="info-row">
              <div class="info-label">Tahun Terbit</div>
              <div class="info-value" id="detail_year_published">-</div>
            </div>
          </div>
          <div class="col-6">
            <div class="info-row">
              <div class="info-label">Jumlah Stok</div>
              <div class="info-value" id="detail_stock">-</div>
            </div>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="editFromDetailBtn">
          <i class="fas fa-edit me-2"></i> Edit
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookModalLabel">Edit Buku</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editBookForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_title" class="form-label">Judul Buku</label>
            <input type="text" class="form-control" id="edit_title" name="title" required>
          </div>

          <div class="mb-3">
            <label for="edit_author" class="form-label">Penulis</label>
            <input type="text" class="form-control" id="edit_author" name="author" required>
          </div>

          <div class="mb-3">
            <label for="edit_publisher" class="form-label">Penerbit</label>
            <input type="text" class="form-control" id="edit_publisher" name="publisher" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="edit_year_published" class="form-label">Tahun Terbit</label>
              <input type="number" class="form-control" id="edit_year_published" name="year_published" min="1900" max="2099" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="edit_stock" class="form-label">Jumlah Stok</label>
              <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  // View toggle functionality
  const gridViewBtn = document.getElementById('gridViewBtn');
  const tableViewBtn = document.getElementById('tableViewBtn');
  const gridView = document.getElementById('gridView');
  const tableView = document.getElementById('tableView');

  gridViewBtn.addEventListener('click', function() {
    gridView.style.display = 'block';
    tableView.style.display = 'none';
    gridViewBtn.classList.add('active');
    tableViewBtn.classList.remove('active');
    localStorage.setItem('bookViewPreference', 'grid');
  });

  tableViewBtn.addEventListener('click', function() {
    gridView.style.display = 'none';
    tableView.style.display = 'block';
    tableViewBtn.classList.add('active');
    gridViewBtn.classList.remove('active');
    localStorage.setItem('bookViewPreference', 'table');
  });

  // Load user preference if exists
  const viewPreference = localStorage.getItem('bookViewPreference');
  if (viewPreference === 'table') {
    tableViewBtn.click();
  }

  // Search functionality
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();

    // Search in grid view
    const bookCards = document.querySelectorAll('.book-card');
    bookCards.forEach(card => {
      const title = card.querySelector('.book-title').textContent.toLowerCase();
      const author = card.querySelector('.book-author').textContent.toLowerCase();
      const publisher = card.querySelector('.book-meta').textContent.toLowerCase();

      if (title.includes(searchValue) || author.includes(searchValue) || publisher.includes(searchValue)) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });

    // Search in table view
    const tableRows = document.querySelectorAll('#tableView tbody tr');
    tableRows.forEach(row => {
      if (!row.cells || row.cells.length < 3) return;

      const title = row.cells[2].textContent.toLowerCase();
      const author = row.cells[3].textContent.toLowerCase();
      const publisher = row.cells[4].textContent.toLowerCase();

      if (title.includes(searchValue) || author.includes(searchValue) || publisher.includes(searchValue)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

  // Auto-dismiss alerts after 5 seconds
  setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);

  // Show Book Modal
  const showBookModal = new bootstrap.Modal(document.getElementById('showBookModal'));

  // Add event listeners to all show buttons
  document.querySelectorAll('.show-book-btn').forEach(button => {
    button.addEventListener('click', function() {
      const bookId = this.getAttribute('data-id');
      const title = this.getAttribute('data-title');
      const author = this.getAttribute('data-author');
      const publisher = this.getAttribute('data-publisher');
      const year = this.getAttribute('data-year');
      const stock = this.getAttribute('data-stock');
      const created = this.getAttribute('data-created');
      const updated = this.getAttribute('data-updated');

      // Format dates


      // Populate book information
      document.getElementById('detail_title').textContent = title;
      document.getElementById('detail_author').textContent = author;
      document.getElementById('detail_publisher').textContent = publisher;
      document.getElementById('detail_year_published').textContent = year;
      document.getElementById('detail_stock').textContent = stock;


      // Set up edit button in detail modal
      const editFromDetailBtn = document.getElementById('editFromDetailBtn');
      editFromDetailBtn.setAttribute('data-id', bookId);
      editFromDetailBtn.addEventListener('click', function() {
        // Close detail modal
        showBookModal.hide();

        // Open edit modal with the book data
        const editBookModal = new bootstrap.Modal(document.getElementById('editBookModal'));
        const editBookForm = document.getElementById('editBookForm');

        // Set form action
        editBookForm.action = `/books/${bookId}`;

        // Populate form fields
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_author').value = author;
        document.getElementById('edit_publisher').value = publisher;
        document.getElementById('edit_year_published').value = year;
        document.getElementById('edit_stock').value = stock;

        // Show edit modal
        editBookModal.show();
      });

      // Show the modal
      showBookModal.show();
    });
  });

  // Format date function
  function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }

  // Edit Book Modal
  const editBookModal = new bootstrap.Modal(document.getElementById('editBookModal'));
  const editBookForm = document.getElementById('editBookForm');

  // Add event listeners to all edit buttons
  document.querySelectorAll('.edit-book-btn').forEach(button => {
    button.addEventListener('click', function() {
      const bookId = this.getAttribute('data-id');
      const title = this.getAttribute('data-title');
      const author = this.getAttribute('data-author');
      const publisher = this.getAttribute('data-publisher');
      const year = this.getAttribute('data-year');
      const stock = this.getAttribute('data-stock');

      // Set form action
      editBookForm.action = `/books/${bookId}`;

      // Populate form fields
      document.getElementById('edit_title').value = title;
      document.getElementById('edit_author').value = author;
      document.getElementById('edit_publisher').value = publisher;
      document.getElementById('edit_year_published').value = year;
      document.getElementById('edit_stock').value = stock;

      // Show modal
      editBookModal.show();
    });
  });

  // Validate year input
  const yearInput = document.getElementById('edit_year_published');
  yearInput.addEventListener('input', function() {
    const value = parseInt(this.value);
    if (value < 1900) {
      this.value = 1900;
    } else if (value > 2099) {
      this.value = 2099;
    }
  });

  // Validate stock input
  const stockInput = document.getElementById('edit_stock');
  stockInput.addEventListener('input', function() {
    if (parseInt(this.value) < 0) {
      this.value = 0;
    }
  });
});
</script>
</body>
</html>
@endif
