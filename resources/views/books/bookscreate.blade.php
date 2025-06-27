<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Buku</title>
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

    /* Form styling */
    .form-container {
        margin: 0 auto;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 5px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #ddd;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4266B9;
        box-shadow: 0 0 0 0.25rem rgba(66, 102, 185, 0.25);
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

        .form-container {
            padding: 0 15px;
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
    @include('components.profiladmin')

    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Tambah Buku</h2>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </header>

    <!-- Alert for validation errors -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Add Book Form -->
    <div class="card form-container">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Form Tambah Buku
        </div>
        <div class="card-body p-4">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Kode Buku</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $book->code ?? '') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
    <label for="class_level" class="form-label">Tingkatan Kelas</label>
    <select name="class_level" id="class_level" class="form-select" required>
        <option value="">-- Pilih Tingkatan --</option>
        @foreach ($classLevels as $level)
            @php
                $angka = match($level) {
                    'X' => 10,
                    'XI' => 11,
                    'XII' => 12,
                    default => ''
                };
            @endphp
            <option value="{{ $level }}">{{ $level }} (kelas {{ $angka }})</option>
        @endforeach
    </select>
</div>
                    <div class="col-md-6 mb-3">
                        <label for="author" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author" value="{{ old('author') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="publisher" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="publisher" name="publisher" value="{{ old('publisher') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="year_published" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" id="year_published" name="year_published" value="{{ old('year_published', date('Y')) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Jumlah Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{ old('stock') }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="cover" class="form-label">Cover Buku (Gambar)</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB)</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('books.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set current year as default for year_published
    const currentYear = new Date().getFullYear();
    const yearInput = document.getElementById('year_published');
    if (!yearInput.value) {
        yearInput.value = currentYear;
    }

    // Validate year input
    yearInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1900) {
            this.value = 1900;
        } else if (value > 2099) {
            this.value = 2099;
        }
    });

    // Validate stock input
    const stockInput = document.getElementById('stock');
    stockInput.addEventListener('input', function() {
        if (parseInt(this.value) < 0) {
            this.value = 0;
        }
    });
});
</script>
</body>
</html>
