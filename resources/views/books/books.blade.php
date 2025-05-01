<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Buku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
<style>
    /* Main Content Styles */
    .main-content {
        flex: 1;
        padding: 20px;
        background: #f5f5f5;
    }
    .btn-primary, .bg-primary {
        background-color: #4266B9 !important;
        border-color: #4266B9 !important;
    }

    .btn-primary:hover {
        background-color: #365796 !important;
        border-color: #365796 !important;
    }

    .text-primary {
        color: #4266B9 !important;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: none;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #4266B9;
        color: white;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 10px 10px 0 0 !important;
    }

    .action-btn {
        background-color: #4266B9;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .action-btn:hover {
        background-color: #365796;
        color: white;
        text-decoration: none;
    }

    .info-row {
        display: flex;
        margin-bottom: 10px;
    }

    .info-label {
        width: 150px;
        font-weight: 500;
    }

    .info-value {
        flex: 1;
    }

    .modal-header {
        background-color: #4266B9;
        color: white;
    }

    .modal-header .btn-close {
        color: white;
        filter: brightness(0) invert(1);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 70px;
            padding: 20px 10px;
        }

        .logo-text, .nav-text {
            display: none;
        }

        .main-content {
            padding: 15px;
        }
    }
</style>
</head>
<body>
<div class="container">
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

    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Data Buku</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('book-loans.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-exchange-alt me-2"></i> Peminjaman Buku
            </a>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Tambah Buku
            </a>
        </div>
    </header>

    <!-- Alert for success message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Search Bar -->
    <div class="search-container mb-4">
        <i class="fas fa-search search-icon"></i>
        <input type="text" placeholder="Cari judul buku atau penulis..." class="form-control" id="searchInput">
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-2"></i> Data Buku
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Penulis</th>
                            <th class="py-3 px-4">Penerbit</th>
                            <th class="py-3 px-4">Tahun Terbit</th>
                            <th class="py-3 px-4">Stok</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $index => $book)
                            <tr>
                                <td class="py-2 px-4">{{ $index + 1 }}</td>
                                <td class="py-2 px-4">{{ $book->code }}</td>
                                <td class="py-2 px-4">{{ $book->title }}</td>
                                <td class="py-2 px-4">{{ $book->author }}</td>
                                <td class="py-2 px-4">{{ $book->publisher }}</td>
                                <td class="py-2 px-4">{{ $book->year_published }}</td>
                                <td class="py-2 px-4">{{ $book->stock }}</td>
                                <td class="py-2 px-4">
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-info btn-sm text-white show-book-btn"
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
                                        <button type="button" class="btn btn-warning btn-sm text-white edit-book-btn"
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
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data buku</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</div>

<!-- Show Book Modal -->
<div class="modal fade" id="showBookModal" tabindex="-1" aria-labelledby="showBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showBookModalLabel">Detail Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Book Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-book me-2"></i> Informasi Buku
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label">Judul Buku</div>
                                    <div class="info-value" id="detail_title">: -</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Penulis</div>
                                    <div class="info-value" id="detail_author">: -</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Penerbit</div>
                                    <div class="info-value" id="detail_publisher">: -</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label">Tahun Terbit</div>
                                    <div class="info-value" id="detail_year_published">: -</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Jumlah Stok</div>
                                    <div class="info-value" id="detail_stock">: -</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Tanggal Dibuat</div>
                                    <div class="info-value" id="detail_created_at">: -</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Terakhir Diupdate</div>
                                    <div class="info-value" id="detail_updated_at">: -</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning text-white me-2" id="editFromDetailBtn">
                    <i class="fas fa-edit me-2"></i> Edit
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="edit_author" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="edit_author" name="author" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_publisher" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="edit_publisher" name="publisher" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="edit_year_published" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="edit_year_published" name="year_published" min="1900" max="2099" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_stock" class="form-label">Jumlah Stok</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const author = row.cells[2].textContent.toLowerCase();

            if (title.includes(searchValue) || author.includes(searchValue)) {
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
            const createdDate = formatDate(created);
            const updatedDate = formatDate(updated);

            // Populate book information
            document.getElementById('detail_title').textContent = ': ' + title;
            document.getElementById('detail_author').textContent = ': ' + author;
            document.getElementById('detail_publisher').textContent = ': ' + publisher;
            document.getElementById('detail_year_published').textContent = ': ' + year;
            document.getElementById('detail_stock').textContent = ': ' + stock;
            document.getElementById('detail_created_at').textContent = ': ' + createdDate;
            document.getElementById('detail_updated_at').textContent = ': ' + updatedDate;

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
            month: '2-digit',
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
