<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #4266B9;
            color: white;
            padding: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: #ff6b35;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Admin Profile Styles */
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .admin-profile:hover {
            background-color: #f0f0f0;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #E6E1F9;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-info {
            display: flex;
            flex-direction: column;
        }

        .admin-name {
            font-weight: 600;
            font-size: 14px;
            margin: 0;
        }

        .admin-role {
            font-size: 12px;
            color: #666;
            margin: 0;
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

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #eee;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-dipinjam {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .status-dikembalikan {
            background-color: #D1FAE5;
            color: #059669;
        }

        .status-terlambat {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        .pdf-icon {
            color: #DC2626;
            font-size: 18px;
        }

        .book-cover {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                padding: 20px;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">E</div>
                <div class="logo-text">SCHOOL</div>
            </div>
            <nav>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-home me-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-users me-2"></i>
                    <span class="nav-text">Data User</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-user-graduate me-2"></i>
                    <span class="nav-text">Data Siswa</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <span class="nav-text">Data Pegawai</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span class="nav-text">Absensi</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-user-check me-2"></i>
                        <span class="nav-text">Absensi Siswa</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-user-tie me-2"></i>
                        <span class="nav-text">Absensi Pegawai</span>
                    </a>
                </div>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-database me-2"></i>
                    <span class="nav-text">Master Data</span>
                </a>
                <div class="ms-3">
                    <a href="" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span class="nav-text">Tahun Ajaran</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-school me-2"></i>
                        <span class="nav-text">Kelas</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-book me-2"></i>
                        <span class="nav-text">Mata Pelajaran</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-calendar-day me-2"></i>
                        <span class="nav-text">Hari Libur</span>
                    </a>
                </div>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    <span class="nav-text">Data SPP</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-book-reader me-2"></i>
                    <span class="nav-text">Data Buku Paket</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item active text-white d-block mb-2">
                        <i class="fas fa-book-open me-2"></i>
                        <span class="nav-text">Peminjaman Buku Paket</span>
                    </a>
                </div>
            </nav>
        </div>

       <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Admin Profile Header -->
            <div class="d-flex justify-content-end mb-4">
                <div class="dropdown">
                    <div class="admin-profile d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex flex-column text-end me-2">
                            <span class="admin-name">Nama Admin</span>
                            <small class="admin-role text-muted">Admin</small>
                        </div>
                        <div class="admin-avatar">
                            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/password-field-W2aCv47MBKPq1Z3HGzL9qVgMlruksc.tsx" alt="Admin Profile" class="w-100 h-100 object-fit-cover">
                        </div>
                        <i class="fas fa-chevron-down ms-2 text-muted"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="fas fa-user-edit"></i> Edit Profil</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="fas fa-key"></i> Ubah Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>

            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold">Peminjaman Buku Paket</h2>
                <div class="d-flex align-items-center">
                    <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBorrowingModal">+ Tambah Peminjaman</button>
                </div>
            </header>

            <!-- Filter Peminjaman -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Filter Peminjaman</div>
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Kelas</label>
                                <select name="class" class="form-control">
                                    <option value="">-- Semua Kelas --</option>
                                    <option value="1A">Kelas 1A</option>
                                    <option value="1B">Kelas 1B</option>
                                    <option value="2A">Kelas 2A</option>
                                    <option value="2B">Kelas 2B</option>
                                    <option value="3A">Kelas 3A</option>
                                    <option value="3B">Kelas 3B</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="dikembalikan">Dikembalikan</option>
                                    <option value="terlambat">Terlambat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                            <button type="button" class="btn btn-success ms-2" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Peminjaman Buku -->
            <div class="card">
                <div class="card-header bg-primary text-white">Data Peminjaman Buku</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>S001</td>
                                <td>Ahmad Rizky</td>
                                <td>Kelas 1A</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg?height=80&width=60" alt="Sampul Buku" class="book-cover me-2">
                                        <div>
                                            <p class="mb-0 fw-bold">Matematika Dasar</p>
                                            <small class="text-muted">MTK-01</small>
                                        </div>
                                    </div>
                                </td>
                                <td>15-03-2025</td>
                                <td>22-03-2025</td>
                                <td><span class="status-badge status-dipinjam">Dipinjam</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Peminjaman - Ahmad Rizky">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBorrowingModal" data-id="1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnBookModal" data-id="1" data-name="Ahmad Rizky" data-book="Matematika Dasar">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBorrowingModal" data-id="1" data-name="Ahmad Rizky">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>S002</td>
                                <td>Budi Santoso</td>
                                <td>Kelas 1A</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg?height=80&width=60" alt="Sampul Buku" class="book-cover me-2">
                                        <div>
                                            <p class="mb-0 fw-bold">Bahasa Indonesia</p>
                                            <small class="text-muted">BIN-01</small>
                                        </div>
                                    </div>
                                </td>
                                <td>10-03-2025</td>
                                <td>17-03-2025</td>
                                <td><span class="status-badge status-terlambat">Terlambat</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Peminjaman - Budi Santoso">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBorrowingModal" data-id="2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnBookModal" data-id="2" data-name="Budi Santoso" data-book="Bahasa Indonesia">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBorrowingModal" data-id="2" data-name="Budi Santoso">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>S003</td>
                                <td>Citra Dewi</td>
                                <td>Kelas 1B</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg?height=80&width=60" alt="Sampul Buku" class="book-cover me-2">
                                        <div>
                                            <p class="mb-0 fw-bold">Ilmu Pengetahuan Alam</p>
                                            <small class="text-muted">IPA-01</small>
                                        </div>
                                    </div>
                                </td>
                                <td>05-03-2025</td>
                                <td>12-03-2025</td>
                                <td><span class="status-badge status-dikembalikan">Dikembalikan</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Peminjaman - Citra Dewi">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBorrowingModal" data-id="3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#returnDetailModal" data-id="3" data-name="Citra Dewi" data-book="Ilmu Pengetahuan Alam">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBorrowingModal" data-id="3" data-name="Citra Dewi">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>S004</td>
                                <td>Dian Purnama</td>
                                <td>Kelas 1B</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg?height=80&width=60" alt="Sampul Buku" class="book-cover me-2">
                                        <div>
                                            <p class="mb-0 fw-bold">Ilmu Pengetahuan Sosial</p>
                                            <small class="text-muted">IPS-01</small>
                                        </div>
                                    </div>
                                </td>
                                <td>08-03-2025</td>
                                <td>15-03-2025</td>
                                <td><span class="status-badge status-dipinjam">Dipinjam</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Peminjaman - Dian Purnama">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBorrowingModal" data-id="4">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnBookModal" data-id="4" data-name="Dian Purnama" data-book="Ilmu Pengetahuan Sosial">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBorrowingModal" data-id="4" data-name="Dian Purnama">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>S005</td>
                                <td>Eko Prasetyo</td>
                                <td>Kelas 2A</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg?height=80&width=60" alt="Sampul Buku" class="book-cover me-2">
                                        <div>
                                            <p class="mb-0 fw-bold">English for Beginners</p>
                                            <small class="text-muted">BIG-01</small>
                                        </div>
                                    </div>
                                </td>
                                <td>01-03-2025</td>
                                <td>08-03-2025</td>
                                <td><span class="status-badge status-dikembalikan">Dikembalikan</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Peminjaman - Eko Prasetyo">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBorrowingModal" data-id="5">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#returnDetailModal" data-id="5" data-name="Eko Prasetyo" data-book="English for Beginners">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBorrowingModal" data-id="5" data-name="Eko Prasetyo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Peminjaman -->
    <div class="modal fade" id="addBorrowingModal" tabindex="-1" aria-labelledby="addBorrowingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBorrowingModalLabel">Tambah Peminjaman Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBorrowingForm" action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Siswa</label>
                                    <select class="form-control" id="student_id" name="student_id" required>
                                        <option value="">-- Pilih Siswa --</option>
                                        <option value="S001">S001 - Ahmad Rizky (Kelas 1A)</option>
                                        <option value="S002">S002 - Budi Santoso (Kelas 1A)</option>
                                        <option value="S003">S003 - Citra Dewi (Kelas 1B)</option>
                                        <option value="S004">S004 - Dian Purnama (Kelas 1B)</option>
                                        <option value="S005">S005 - Eko Prasetyo (Kelas 2A)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="book_id" class="form-label">Buku</label>
                                    <select class="form-control" id="book_id" name="book_id" required>
                                        <option value="">-- Pilih Buku --</option>
                                        <option value="MTK-01">MTK-01 - Matematika Dasar (Kelas 1)</option>
                                        <option value="BIN-01">BIN-01 - Bahasa Indonesia (Kelas 1)</option>
                                        <option value="IPA-01">IPA-01 - Ilmu Pengetahuan Alam (Kelas 2)</option>
                                        <option value="IPS-01">IPS-01 - Ilmu Pengetahuan Sosial (Kelas 2)</option>
                                        <option value="BIG-01">BIG-01 - English for Beginners (Kelas 3)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="borrow_date" class="form-label">Tanggal Pinjam</label>
                                    <input type="date" class="form-control" id="borrow_date" name="borrow_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="return_date" class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" id="return_date" name="return_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="dipinjam">Dipinjam</option>
                                        <option value="dikembalikan">Dikembalikan</option>
                                        <option value="terlambat">Terlambat</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="proof_document" class="form-label">Bukti Peminjaman (PDF)</label>
                                    <input type="file" class="form-control" id="proof_document" name="proof_document" accept="application/pdf" require
```I'll create all the requested pages with PDF functionality for CRUD operations. Let's start with each page:

## 1. Data SPP (School Fee Data)

```html project="School System" file="data-spp.html" type="html"
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #4266B9;
            color: white;
            padding: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: #ff6b35;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Admin Profile Styles */
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .admin-profile:hover {
            background-color: #f0f0f0;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #E6E1F9;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-info {
            display: flex;
            flex-direction: column;
        }

        .admin-name {
            font-weight: 600;
            font-size: 14px;
            margin: 0;
        }

        .admin-role {
            font-size: 12px;
            color: #666;
            margin: 0;
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

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #eee;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-lunas {
            background-color: #D1FAE5;
            color: #059669;
        }

        .status-belum {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        .status-sebagian {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .pdf-icon {
            color: #DC2626;
            font-size: 18px;
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
                padding: 20px;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">E</div>
                <div class="logo-text">SCHOOL</div>
            </div>
            <nav>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-home me-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-users me-2"></i>
                    <span class="nav-text">Data User</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-user-graduate me-2"></i>
                    <span class="nav-text">Data Siswa</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <span class="nav-text">Data Pegawai</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span class="nav-text">Absensi</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-user-check me-2"></i>
                        <span class="nav-text">Absensi Siswa</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-user-tie me-2"></i>
                        <span class="nav-text">Absensi Pegawai</span>
                    </a>
                </div>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-database me-2"></i>
                    <span class="nav-text">Master Data</span>
                </a>
                <div class="ms-3">
                    <a href="" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span class="nav-text">Tahun Ajaran</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-school me-2"></i>
                        <span class="nav-text">Kelas</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-book me-2"></i>
                        <span class="nav-text">Mata Pelajaran</span>
                    </a>
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-calendar-day me-2"></i>
                        <span class="nav-text">Hari Libur</span>
                    </a>
                </div>
                <a href="#" class="nav-item active text-white d-block mb-2">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    <span class="nav-text">Data SPP</span>
                </a>
                <a href="#" class="nav-item text-white d-block mb-2">
                    <i class="fas fa-book-reader me-2"></i>
                    <span class="nav-text">Data Buku Paket</span>
                </a>
                <div class="ms-3">
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-book-open me-2"></i>
                        <span class="nav-text">Peminjaman Buku Paket</span>
                    </a>
                </div>
            </nav>
        </div>

       <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Admin Profile Header -->
            <div class="d-flex justify-content-end mb-4">
                <div class="dropdown">
                    <div class="admin-profile d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex flex-column text-end me-2">
                            <span class="admin-name">Nama Admin</span>
                            <small class="admin-role text-muted">Admin</small>
                        </div>
                        <div class="admin-avatar">
                            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/password-field-W2aCv47MBKPq1Z3HGzL9qVgMlruksc.tsx" alt="Admin Profile" class="w-100 h-100 object-fit-cover">
                        </div>
                        <i class="fas fa-chevron-down ms-2 text-muted"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="fas fa-user-edit"></i> Edit Profil</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="fas fa-key"></i> Ubah Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>

            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold">Data SPP</h2>
                <div class="d-flex align-items-center">
                    <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSppModal">+ Tambah Data SPP</button>
                </div>
            </header>

            <!-- Pilih Tahun Ajaran dan Kelas -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Filter Data SPP</div>
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tahun Ajaran</label>
                                <select name="academic_year" class="form-control">
                                    <option value="">-- Pilih Tahun --</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2022-2023">2022-2023</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Kelas</label>
                                <select name="class_id" class="form-control">
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="1">Kelas 1A</option>
                                    <option value="2">Kelas 1B</option>
                                    <option value="3">Kelas 2A</option>
                                    <option value="4">Kelas 2B</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Status Pembayaran</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="belum">Belum Lunas</option>
                                    <option value="sebagian">Sebagian</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                        <button type="button" class="btn btn-success mt-3 ms-2" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </button>
                    </form>
                </div>
            </div>

            <!-- Data SPP -->
            <div class="card">
                <div class="card-header bg-primary text-white">Data SPP</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th>Nominal SPP</th>
                                <th>Status</th>
                                <th>Bukti Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>S001</td>
                                <td>Ahmad Rizky</td>
                                <td>Kelas 1A</td>
                                <td>2024-2025</td>
                                <td>Rp 500.000</td>
                                <td><span class="status-badge status-lunas">Lunas</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Pembayaran - Ahmad Rizky">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSppModal" data-id="1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="1" data-name="Ahmad Rizky">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSppModal" data-id="1" data-name="Ahmad Rizky">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>S002</td>
                                <td>Budi Santoso</td>
                                <td>Kelas 1A</td>
                                <td>2024-2025</td>
                                <td>Rp 500.000</td>
                                <td><span class="status-badge status-belum">Belum Lunas</span></td>
                                <td>
                                    <span class="text-muted">-</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSppModal" data-id="2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="2" data-name="Budi Santoso">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSppModal" data-id="2" data-name="Budi Santoso">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>S003</td>
                                <td>Citra Dewi</td>
                                <td>Kelas 1B</td>
                                <td>2024-2025</td>
                                <td>Rp 500.000</td>
                                <td><span class="status-badge status-sebagian">Sebagian</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Pembayaran - Citra Dewi">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSppModal" data-id="3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="3" data-name="Citra Dewi">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSppModal" data-id="3" data-name="Citra Dewi">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>S004</td>
                                <td>Dian Purnama</td>
                                <td>Kelas 1B</td>
                                <td>2024-2025</td>
                                <td>Rp 500.000</td>
                                <td><span class="status-badge status-lunas">Lunas</span></td>
                                <td>
                                    <a href="#" class="pdf-icon" data-bs-toggle="modal" data-bs-target="#viewPdfModal" data-pdf-title="Bukti Pembayaran - Dian Purnama">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSppModal" data-id="4">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="4" data-name="Dian Purnama">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSppModal" data-id="4" data-name="Dian Purnama">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>S005</td>
                                <td>Eko Prasetyo</td>
                                <td>Kelas 2A</td>
                                <td>2024-2025</td>
                                <td>Rp 550.000</td>
                                <td><span class="status-badge status-belum">Belum Lunas</span></td>
                                <td>
                                    <span class="text-muted">-</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSppModal" data-id="5">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="5" data-name="Eko Prasetyo">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSppModal" data-id="5" data-name="Eko Prasetyo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Data SPP -->
    <div class="modal fade" id="addSppModal" tabindex="-1" aria-labelledby="addSppModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSppModalLabel">Tambah Data SPP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSppForm" action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Siswa</label>
                            <select class="form-control" id="student_id" name="student_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                <option value="S001">S001 - Ahmad Rizky</option>
                                <option value="S002">S002 - Budi Santoso</option>
                                <option value="S003">S003 - Citra Dewi</option>
                                <option value="S004">S004 - Dian Purnama</option>
                                <option value="S005">S005 - Eko Prasetyo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="academic_year" class="form-label">Tahun Ajaran</label>
                            <select class="form-control" id="academic_year" name="academic_year" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2022-2023">2022-2023</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Nominal SPP</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="belum">Belum Lunas</option>
                                <option value="sebagian">Sebagian</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_proof" class="form-label">Bukti Pembayaran (PDF)</label>
                            <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="application/pdf">
                            <small class="text-muted">Upload file PDF bukti pembayaran (opsional)</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data SPP -->
    <div class="modal fade" id="editSppModal" tabindex="-1" aria-labelledby="editSppModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSppModalLabel">Edit Data SPP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSppForm" action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="edit_spp_id" name="id">
                        <div class="mb-3">
                            <label for="edit_student_id" class="form-label">Siswa</label>
                            <select class="form-control" id="edit_student_id" name="student_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                <option value="S001">S001 - Ahmad Rizky</option>
                                <option value="S002">S002 - Budi Santoso</option>
                                <option value="S003">S003 - Citra Dewi</option>
                                <option value="S004">S004 - Dian Purnama</option>
                                <option value="S005">S005 - Eko Prasetyo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_academic_year" class="form-label">Tahun Ajaran</label>
                            <select class="form-control" id="edit_academic_year" name="academic_year" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2022-2023">2022-2023</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Nominal SPP</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="edit_amount" name="amount" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="belum">Belum Lunas</option>
                                <option value="sebagian">Sebagian</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payment_proof" class="form-label">Bukti Pembayaran (PDF)</label>
                            <input type="file" class="form-control" id="edit_payment_proof" name="payment_proof" accept="application/pdf">
                            <small class="text-muted">Upload file PDF bukti pembayaran baru (opsional)</small>
                            <div id="current_proof_container" class="mt-2">
                                <small class="text-muted">Bukti pembayaran saat ini:</small>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-file-pdf pdf-icon me-2"></i>
                                    <span id="current_proof_name">bukti_pembayaran.pdf</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran SPP -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Pembayaran SPP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="payment_spp_id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Siswa</label>
                            <input type="text" class="form-control" id="payment_student_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label">Jumlah Pembayaran</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="payment_amount" name="payment_amount" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_proof_file" class="form-label">Bukti Pembayaran (PDF)</label>
                            <input type="file" class="form-control" id="payment_proof_file" name="payment_proof_file" accept="application/pdf" required>
                            <small class="text-muted">Upload file PDF bukti pembayaran</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Data SPP -->
    <div class="modal fade" id="deleteSppModal" tabindex="-1" aria-labelledby="deleteSppModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSppModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data SPP ini?</p>
                    <p class="fw-bold" id="delete_spp_name">Nama Siswa</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteSppForm" action="" method="POST">
                        <input type="hidden" id="delete_spp_id" name="id">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat PDF -->
    <div class="modal fade" id="viewPdfModal" tabindex="-1" aria-labelledby="viewPdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPdfModalLabel">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe id="pdfViewer" src="about:blank" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="downloadPdf">
                        <i class="fas fa-download me-2"></i>Download
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil Admin -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profil Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <div class="rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; background-color: #E6E1F9; overflow: hidden;">
                                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/password-field-W2aCv47MBKPq1Z3HGzL9qVgMlruksc.tsx" alt="Admin Profile" class="w-100 h-100 object-fit-cover" id="profilePreview">
                                </div>
                                <label for="profilePhoto" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" id="profilePhoto" name="photo" class="d-none" onchange="previewImage(this)">
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="admin_name" name="name" value="Nama Admin" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="admin_email" name="email" value="admin@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="admin_phone" name="phone" value="081234567890">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password minimal 8 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordField = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });

        // Preview profile image before upload
        window.previewImage = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        };

        // Handle edit SPP modal
        var editSppModal = document.getElementById('editSppModal');
        editSppModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var sppId = button.getAttribute('data-id');

            // In a real application, you would fetch the SPP data from the server
            // For this example, we'll use dummy data based on the SPP ID
            let sppData = {
                id: sppId,
                student_id: 'S00' + sppId,
                academic_year: '2024-2025',
                amount: sppId <= 2 ? 500000 : 550000,
                status: sppId % 2 === 1 ? 'lunas' : 'belum'
            };

            document.getElementById('edit_spp_id').value = sppData.id;
            document.getElementById('edit_student_id').value = sppData.student_id;
            document.getElementById('edit_academic_year').value = sppData.academic_year;
            document.getElementById('edit_amount').value = sppData.amount;
            document.getElementById('edit_status').value = sppData.status;

            // Show/hide current proof container based on status
            if (sppData.status === 'lunas' || sppData.status === 'sebagian') {
                document.getElementById('current_proof_container').style.display = 'block';
                document.getElementById('current_proof_name').textContent = 'bukti_pembayaran_' + sppData.student_id + '.pdf';
            } else {
                document.getElementById('current_proof_container').style.display = 'none';
            }

            document.getElementById('editSppForm').action = `/spp/${sppId}`;
        });

        // Handle payment modal
        var paymentModal = document.getElementById('paymentModal');
        paymentModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var sppId = button.getAttribute('data-id');
            var studentName = button.getAttribute('data-name');

            document.getElementById('payment_spp_id').value = sppId;
            document.getElementById('payment_student_name').value = studentName;
            document.getElementById('payment_date').valueAsDate = new Date();

            document.getElementById('paymentForm').action = `/spp/payment/${sppId}`;
        });

        // Handle delete SPP modal
        var deleteSppModal = document.getElementById('deleteSppModal');
        deleteSppModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var sppId = button.getAttribute('data-id');
            var studentName = button.getAttribute('data-name');

            document.getElementById('delete_spp_id').value = sppId;
            document.getElementById('delete_spp_name').textContent = studentName;

            document.getElementById('deleteSppForm').action = `/spp/${sppId}`;
        });

        // Handle PDF viewer modal
        var viewPdfModal = document.getElementById('viewPdfModal');
        viewPdfModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var pdfTitle = button.getAttribute('data-pdf-title');

            // In a real application, you would set the actual PDF URL
            // For this example, we'll use a sample PDF
            var pdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

            document.getElementById('viewPdfModalLabel').textContent = pdfTitle;
            document.getElementById('pdfViewer').src = pdfUrl;

            // Set download button URL
            document.getElementById('downloadPdf').onclick = function() {
                window.open(pdfUrl, '_blank');
            };
        });

        // Export to PDF function
        window.exportToPDF = function() {
            alert('Mengekspor data SPP ke PDF...');
            // In a real application, this would trigger a server-side PDF generation
        };
    });
</script>

</body>
</html>
