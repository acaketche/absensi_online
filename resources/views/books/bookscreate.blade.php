<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        /* Layout */
        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background: #f5f5f5;
        }

        /* Card & Header Styles */
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

        /* Button Styles */
        .btn-primary {
            background-color: #4266B9 !important;
            border-color: #4266B9 !important;
        }

        .btn-primary:hover {
            background-color: #365796 !important;
            border-color: #365796 !important;
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4266B9;
            box-shadow: 0 0 0 0.25rem rgba(66, 102, 185, 0.25);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .form-container {
                padding: 0 15px;
            }
        }

        /* Validation Error Styles */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
        }

        /* File Input Customization */
        .form-control[type="file"] {
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Admin Profile Header -->
            @include('components.profiladmin')

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 fw-bold mb-0">
                    <i class="fas fa-book me-2"></i>Tambah Buku Baru
                </h1>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Validation Errors Alert -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Add Book Form -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus-circle me-2"></i> Form Tambah Buku
                </div>
                <div class="card-body">
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <!-- Book Title -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Book Code -->
                            <div class="col-md-6">
                                <label for="code" class="form-label">Kode Buku <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                       id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Class Level -->
                            <div class="col-md-6">
                                <label for="class_level" class="form-label">Tingkatan Kelas <span class="text-danger">*</span></label>
                                <select name="class_level" id="class_level"
                                        class="form-select @error('class_level') is-invalid @enderror" required>
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
                                        <option value="{{ $level }}" {{ old('class_level') == $level ? 'selected' : '' }}>
                                            {{ $level }} (kelas {{ $angka }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Author -->
                            <div class="col-md-6">
                                <label for="author" class="form-label">Penulis <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror"
                                       id="author" name="author" value="{{ old('author') }}" required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Publisher -->
                            <div class="col-md-6">
                                <label for="publisher" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher" name="publisher" value="{{ old('publisher') }}" required>
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Year Published -->
                            <div class="col-md-6">
                                <label for="year_published" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('year_published') is-invalid @enderror"
                                       id="year_published" name="year_published"
                                       min="1900" max="{{ date('Y') + 2 }}"
                                       value="{{ old('year_published', date('Y')) }}" required>
                                @error('year_published')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                       id="stock" name="stock" min="1" value="{{ old('stock', 1) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cover Image -->
                            <div class="col-md-12">
                                <label for="cover" class="form-label">Cover Buku</label>
                                <input type="file" class="form-control @error('cover') is-invalid @enderror"
                                       id="cover" name="cover" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB)</small>
                                @error('cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Buku
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus on first input field
        document.querySelector('form').elements[0].focus();

        // Year validation
        const yearInput = document.getElementById('year_published');
        yearInput.addEventListener('change', function() {
            const currentYear = new Date().getFullYear();
            if (this.value < 1900) {
                this.value = 1900;
            } else if (this.value > currentYear + 2) {
                this.value = currentYear + 2;
            }
        });

        // Stock validation
        const stockInput = document.getElementById('stock');
        stockInput.addEventListener('change', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });

        // Cover image preview (optional)
        const coverInput = document.getElementById('cover');
        if (coverInput) {
            coverInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    this.value = '';
                }
            });
        }
    });
    </script>
</body>
</html>
