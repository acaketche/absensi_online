<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peminjaman Buku</title>
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

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-borrowed {
        background-color: #FEF3C7;
        color: #D97706;
    }

    .status-returned {
        background-color: #D1FAE5;
        color: #059669;
    }

    .status-overdue {
        background-color: #FEE2E2;
        color: #DC2626;
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
        <h2 class="fs-4 fw-bold">Data Peminjaman Buku</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('books.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-book me-2"></i> Data Buku
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoanModal">
                <i class="fas fa-plus-circle me-2"></i> Tambah Peminjaman
            </button>
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
        <input type="text" placeholder="Cari nama siswa atau judul buku..." class="form-control" id="searchInput">
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i> Filter Data
        </div>
        <div class="card-body p-4">
            <form id="filterForm" method="GET" action="{{ route('book-loans.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Peminjaman</label>
                        <select class="form-select" id="filterStatus" name="status">
                            <option value="">-- Semua Status --</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select class="form-select" id="filterAcademicYear" name="academic_year_id">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('academic_year_id') == $class->id ? 'selected' : '' }}>{{ $class->name ?? $class->tahun }}</option>
                        @endforeach
                        </select>
                        </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" id="filterSemester" name="semester_id">
                            <option value="">-- Semua Semester --</option>
                            <option value="1" {{ request('semester_id') == 1 ? 'selected' : '' }}>Ganjil</option>
                            <option value="2" {{ request('semester_id') == 2 ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Tampilkan
                    </button>
                    <button type="reset" id="resetFilterBtn" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-2"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-2"></i> Data Peminjaman Buku
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="loanTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-2 px-4">Nama Siswa</th>
                            <th class="py-2 px-4">Judul Buku</th>
                            <th class="py-2 px-4">Tanggal Pinjam</th>
                            <th class="py-2 px-4">Tanggal Kembali</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4">Tahun Ajaran</th>
                            <th class="py-2 px-4">Semester</th>
                            <th class="py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $index => $loan)
                            <tr>
                                <td class="py-2 px-4">{{ $index + 1 }}</td>
                                <td class="py-2 px-4">{{ $loan->student->fullname ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $loan->book->title ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $loan->loan_date }}</td>
                                <td class="py-2 px-4">{{ $loan->return_date ?? '-' }}</td>
                                <td class="py-2 px-4">
                                    <span class="badge bg-{{ $loan->status === 'Dipinjam' ? 'warning' : ($loan->status === 'Dikembalikan' ? 'success' : 'danger') }}">
                                        {{ $loan->status }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">{{ $loan->academicYear->tahun ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $loan->semester->nama ?? '-' }}</td>
                                <td class="py-2 px-4">
                                    <a href="{{ route('book-loans.show', $loan->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('book-loans.edit', $loan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('book-loans.destroy', $loan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</div>

<!-- Add Loan Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ route('book-loans.store') }}" method="POST" id="addLoanForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addLoanModalLabel">Tambah Peminjaman Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="id_student" class="form-label">Pilih Siswa</label>
                        <select class="form-select" id="id_student" name="id_student" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id_student }}">{{ $student->fullname }} ({{ $student->id_student }}) - Kelas {{ $student->classes->name ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="book_id" class="form-label">Pilih Buku</label>
                        <select class="form-select" id="book_id" name="book_id" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                            @endforeach
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="loan_date" class="form-label">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" id="loan_date" name="loan_date" required>
                    </div>
                    <div class="col-md-6">
                        <label for="due_date" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                        <select class="form-select" id="academic_year_id" name="academic_year_id" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select class="form-select" id="semester_id" name="semester_id" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="1" {{ request('semester_id') == 1 ? 'selected' : '' }}>Ganjil</option>
                            <option value="2" {{ request('semester_id') == 2 ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="saveLoanBtn">Simpan Peminjaman</button>
        </div>
    </div>
</div>
</div>

<!-- Edit Loan Modal -->
<div class="modal fade" id="editLoanModal" tabindex="-1" aria-labelledby="editLoanModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editLoanModalLabel">Edit Peminjaman Buku</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="editLoanForm">
                <input type="hidden" id="edit_loan_id">

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="edit_id_student" class="form-label">Siswa</label>
                        <input type="text" class="form-control" id="edit_student_name" readonly>
                        <input type="hidden" id="edit_id_student">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_book_id" class="form-label">Buku</label>
                        <input type="text" class="form-control" id="edit_book_title" readonly>
                        <input type="hidden" id="edit_book_id">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="edit_loan_date" class="form-label">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" id="edit_loan_date" required>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_due_date" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="edit_due_date" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="edit_return_date" class="form-label">Tanggal Dikembalikan</label>
                        <input type="date" class="form-control" id="edit_return_date">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" required>
                            <option value="borrowed">Dipinjam</option>
                            <option value="returned">Dikembalikan</option>
                            <option value="overdue">Terlambat</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="edit_academic_year_id" class="form-label">Tahun Ajaran</label>
                        <select class="form-select" id="edit_academic_year_id" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <option value="1">2023/2024</option>
                            <option value="2">2022/2023</option>
                            <option value="3">2021/2022</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_semester_id" class="form-label">Semester</label>
                        <select class="form-select" id="edit_semester_id" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="1">Ganjil</option>
                            <option value="2">Genap</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="updateLoanBtn">Simpan Perubahan</button>
        </div>
    </div>
</div>
</div>

<!-- Detail Loan Modal -->
<div class="modal fade" id="detailLoanModal" tabindex="-1" aria-labelledby="detailLoanModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="detailLoanModalLabel">Detail Peminjaman Buku</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Student Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-graduate me-2"></i> Informasi Siswa
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Nama Siswa</div>
                                <div class="info-value" id="detail_student_name">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kelas</div>
                                <div class="info-value" id="detail_student_class">: -</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-book me-2"></i> Informasi Buku
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Judul Buku</div>
                                <div class="info-value" id="detail_book_title">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Penulis</div>
                                <div class="info-value" id="detail_book_author">: -</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Penerbit</div>
                                <div class="info-value" id="detail_book_publisher">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tahun Terbit</div>
                                <div class="info-value" id="detail_book_year">: -</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-exchange-alt me-2"></i> Informasi Peminjaman
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Tanggal Pinjam</div>
                                <div class="info-value" id="detail_loan_date">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tanggal Kembali</div>
                                <div class="info-value" id="detail_due_date">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tanggal Dikembalikan</div>
                                <div class="info-value" id="detail_return_date">: -</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Status</div>
                                <div class="info-value" id="detail_status">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tahun Ajaran</div>
                                <div class="info-value" id="detail_academic_year">: -</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Semester</div>
                                <div class="info-value" id="detail_semester">: -</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="info-label">Tanggal Dibuat:</div>
                        <div class="p-3 bg-light rounded mt-2" id="detail_created_at">-</div>
                    </div>
                    <div class="mt-3">
                        <div class="info-label">Terakhir Diupdate:</div>
                        <div class="p-3 bg-light rounded mt-2" id="detail_updated_at">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning text-white me-2" id="editFromDetailBtn">
                <i class="fas fa-edit me-2"></i> Edit
            </button>
            <button type="button" class="btn btn-success me-2" id="returnBookBtn">
                <i class="fas fa-check-circle me-2"></i> Kembalikan Buku
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Set today's date as default for loan dates
    const today = new Date();
    const formattedDate = today.toISOString().substr(0, 10);
    document.getElementById('loan_date').value = formattedDate;

    // Set default return date (today + 14 days)
    const returnDate = new Date(today);
    returnDate.setDate(returnDate.getDate() + 14);
    const formattedReturnDate = returnDate.toISOString().substr(0, 10);
    document.getElementById('due_date').value = formattedReturnDate;

    // Populate loan table
    function populateLoanTable(data) {
        const tableBody = document.getElementById('loanTableBody');
        tableBody.innerHTML = '';

        if (data.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="9" class="text-center py-4">Tidak ada data peminjaman</td>`;
            tableBody.appendChild(row);
            return;
        }

        data.forEach((loan, index) => {
            const student = students.find(s => s.id === loan.id_student);
            const book = books.find(b => b.id === loan.book_id);
            const academicYear = academicYears.find(a => a.id === loan.academic_year_id);
            const semester = semesters.find(s => s.id === loan.semester_id);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="py-3 px-4">${index + 1}</td>
                <td class="py-3 px-4">${student ? student.name : '-'}</td>
                <td class="py-3 px-4">${book ? book.title : '-'}</td>
                <td class="py-3 px-4">${formatDate(loan.loan_date)}</td>
                <td class="py-3 px-4">${formatDate(loan.due_date)}</td>
                <td class="py-3 px-4">
                    <span class="status-badge status-${loan.status}">
                        ${loan.status === 'borrowed' ? 'Dipinjam' : loan.status === 'returned' ? 'Dikembalikan' : 'Terlambat'}
                    </span>
                </td>
                <td class="py-3 px-4">${academicYear ? academicYear.name : '-'}</td>
                <td class="py-3 px-4">${semester ? semester.name : '-'}</td>
                <td class="py-3 px-4">
                    <button class="btn btn-sm btn-info text-white me-1 view-btn" data-id="${loan.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning text-white me-1 edit-btn" data-id="${loan.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    ${loan.status !== 'returned' ?
                    `<button class="btn btn-sm btn-success me-1 return-btn" data-id="${loan.id}">
                        <i class="fas fa-check"></i>
                    </button>` : ''}
                </td>
            `;
            tableBody.appendChild(row);
        });

        // Add event listeners to buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const loanId = this.getAttribute('data-id');
                showLoanDetails(loanId);
            });
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const loanId = this.getAttribute('data-id');
                showEditLoanModal(loanId);
            });
        });

        document.querySelectorAll('.return-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const loanId = this.getAttribute('data-id');
                showReturnBookModal(loanId);
            });
        });
    }

    // Format date from YYYY-MM-DD to DD/MM/YYYY
    function formatDate(dateString) {
        if (!dateString) return '-';
        const parts = dateString.split('-');
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }

    // Filter loans
    function filterLoans() {
        const status = document.getElementById('filterStatus').value;
        const academicYearId = document.getElementById('filterAcademicYear').value;
        const semesterId = document.getElementById('filterSemester').value;

        let filteredLoans = [...bookLoans];

        if (status) {
            filteredLoans = filteredLoans.filter(loan =>
                loan.status === status
            );
        }

        if (academicYearId) {
            filteredLoans = filteredLoans.filter(loan =>
                loan.academic_year_id == academicYearId
            );
        }

        if (semesterId) {
            filteredLoans = filteredLoans.filter(loan =>
                loan.semester_id == semesterId
            );
        }

        populateLoanTable(filteredLoans);
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        let filteredLoans = bookLoans.filter(loan => {
            const student = students.find(s => s.id === loan.id_student);
            const book = books.find(b => b.id === loan.book_id);

            return (student && student.name.toLowerCase().includes(searchValue)) ||
                   (book && book.title.toLowerCase().includes(searchValue));
        });

        populateLoanTable(filteredLoans);
    });

    // Apply filter button
    document.getElementById('applyFilterBtn').addEventListener('click', filterLoans);

    // Reset filter button
    document.getElementById('resetFilterBtn').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        populateLoanTable(bookLoans);
    });

    // Show loan details
    function showLoanDetails(loanId) {
        const loan = bookLoans.find(l => l.id == loanId);
        if (!loan) return;

        const student = students.find(s => s.id === loan.id_student);
        const book = books.find(b => b.id === loan.book_id);
        const academicYear = academicYears.find(a => a.id === loan.academic_year_id);
        const semester = semesters.find(s => s.id === loan.semester_id);

        // Populate student information
        document.getElementById('detail_student_name').textContent = ': ' + (student ? student.name : '-');
        document.getElementById('detail_student_class').textContent = ': ' + (student ? student.class : '-');

        // Populate book information
        document.getElementById('detail_book_title').textContent = ': ' + (book ? book.title : '-');
        document.getElementById('detail_book_author').textContent = ': ' + (book ? book.author : '-');
        document.getElementById('detail_book_publisher').textContent = ': ' + (book ? book.publisher : '-');
        document.getElementById('detail_book_year').textContent = ': ' + (book ? book.year_published : '-');

        // Populate loan information
        document.getElementById('detail_loan_date').textContent = ': ' + formatDate(loan.loan_date);
        document.getElementById('detail_due_date').textContent = ': ' + formatDate(loan.due_date);
        document.getElementById('detail_return_date').textContent = ': ' + (loan.return_date ? formatDate(loan.return_date) : '-');

        const statusText = loan.status === 'borrowed' ? 'Dipinjam' : loan.status === 'returned' ? 'Dikembalikan' : 'Terlambat';
        const statusElement = document.getElementById('detail_status');
        statusElement.innerHTML = ': <span class="status-badge status-' + loan.status + '">' + statusText + '</span>';

        document.getElementById('detail_academic_year').textContent = ': ' + (academicYear ? academicYear.name : '-');
        document.getElementById('detail_semester').textContent = ': ' + (semester ? semester.name : '-');
        document.getElementById('detail_created_at').textContent = formatDate(loan.created_at.split(' ')[0]) + ' ' + loan.created_at.split(' ')[1];
        document.getElementById('detail_updated_at').textContent = formatDate(loan.updated_at.split(' ')[0]) + ' ' + loan.updated_at.split(' ')[1];

        // Set up edit button in detail modal
        document.getElementById('editFromDetailBtn').setAttribute('data-id', loanId);
        document.getElementById('editFromDetailBtn').addEventListener('click', function() {
            const loanId = this.getAttribute('data-id');
            // Close detail modal
            const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailLoanModal'));
            detailModal.hide();
            // Open edit modal
            showEditLoanModal(loanId);
        });

        // Set up return book button
        const returnBookBtn = document.getElementById('returnBookBtn');
        if (loan.status === 'returned') {
            returnBookBtn.style.display = 'none';
        } else {
            returnBookBtn.style.display = 'inline-block';
            returnBookBtn.setAttribute('data-id', loanId);
            returnBookBtn.addEventListener('click', function() {
                const loanId = this.getAttribute('data-id');
                // Close detail modal
                const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailLoanModal'));
                detailModal.hide();
                // Open return book modal
                showReturnBookModal(loanId);
            });
        }

        // Show the modal
        const detailModal = new bootstrap.Modal(document.getElementById('detailLoanModal'));
        detailModal.show();
    }

    // Show edit loan modal
    function showEditLoanModal(loanId) {
        const loan = bookLoans.find(l => l.id == loanId);
        if (!loan) return;

        const student = students.find(s => s.id === loan.id_student);
        const book = books.find(b => b.id === loan.book_id);

        // Populate form fields
        document.getElementById('edit_loan_id').value = loan.id;
        document.getElementById('edit_id_student').value = loan.id_student;
        document.getElementById('edit_student_name').value = student ? student.name : '-';
        document.getElementById('edit_book_id').value = loan.book_id;
        document.getElementById('edit_book_title').value = book ? book.title : '-';
        document.getElementById('edit_loan_date').value = loan.loan_date;
        document.getElementById('edit_due_date').value = loan.due_date;
        document.getElementById('edit_return_date').value = loan.return_date || '';
        document.getElementById('edit_status').value = loan.status;
        document.getElementById('edit_academic_year_id').value = loan.academic_year_id;
        document.getElementById('edit_semester_id').value = loan.semester_id;

        // Show the modal
        const editModal = new bootstrap.Modal(document.getElementById('editLoanModal'));
        editModal.show();
    }

    // Show return book modal
    function showReturnBookModal(loanId) {
        const loan = bookLoans.find(l => l.id == loanId);
        if (!loan) return;

        // Pre-fill the edit form with return information
        showEditLoanModal(loanId);

        // Set status to returned
        document.getElementById('edit_status').value = 'returned';

        // Set return date to today
        document.getElementById('edit_return_date').value = formattedDate;
    }

    // Save new loan
    document.getElementById('saveLoanBtn').addEventListener('click', function() {
        // Get form values
        const idStudent = document.getElementById('id_student').value;
        const bookId = document.getElementById('book_id').value;
        const loanDate = document.getElementById('loan_date').value;
        const dueDate = document.getElementById('due_date').value;
        const academicYearId = document.getElementById('academic_year_id').value;
        const semesterId = document.getElementById('semester_id').value;

        // Validate form
        if (!idStudent || !bookId || !loanDate || !dueDate || !academicYearId || !semesterId) {
            alert('Mohon lengkapi semua field yang diperlukan');
            return;
        }

        // In a real application, you would send this data to the server
        // For this example, we'll just add it to our local data
        const newId = bookLoans.length > 0 ? Math.max(...bookLoans.map(l => l.id)) + 1 : 1;
        const now = new Date().toISOString().replace('T', ' ').substring(0, 19);

        const newLoan = {
            id: newId,
            id_student: parseInt(idStudent),
            book_id: parseInt(bookId),
            loan_date: loanDate,
            due_date: dueDate,
            return_date: null,
            status: 'borrowed',
            created_at: now,
            updated_at: now,
            academic_year_id: parseInt(academicYearId),
            semester_id: parseInt(semesterId)
        };

        bookLoans.push(newLoan);

        // Close modal and refresh table
        const modal = bootstrap.Modal.getInstance(document.getElementById('addLoanModal'));
        modal.hide();

        // Reset form
        document.getElementById('addLoanForm').reset();
        document.getElementById('loan_date').value = formattedDate;
        document.getElementById('due_date').value = formattedReturnDate;

        // Show success message
        document.getElementById('successMessage').textContent = 'Peminjaman berhasil ditambahkan';
        document.getElementById('successAlert').classList.remove('d-none');

        // Refresh table
        populateLoanTable(bookLoans);
    });

    // Update loan
    document.getElementById('updateLoanBtn').addEventListener('click', function() {
        // Get form values
        const loanId = document.getElementById('edit_loan_id').value;
        const loanDate = document.getElementById('edit_loan_date').value;
        const dueDate = document.getElementById('edit_due_date').value;
        const returnDate = document.getElementById('edit_return_date').value;
        const status = document.getElementById('edit_status').value;
        const academicYearId = document.getElementById('edit_academic_year_id').value;
        const semesterId = document.getElementById('edit_semester_id').value;

        // Validate form
        if (!loanDate || !dueDate || !status || !academicYearId || !semesterId) {
            alert('Mohon lengkapi semua field yang diperlukan');
            return;
        }

        // If status is returned, require return date
        if (status === 'returned' && !returnDate) {
            alert('Mohon lengkapi tanggal pengembalian');
            return;
        }

        // Find the loan to update
        const loanIndex = bookLoans.findIndex(l => l.id == loanId);
        if (loanIndex === -1) return;

        const loan = bookLoans[loanIndex];

        // Update loan
        const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
        bookLoans[loanIndex] = {
            ...loan,
            loan_date: loanDate,
            due_date: dueDate,
            return_date: status === 'returned' ? returnDate : null,
            status: status,
            updated_at: now,
            academic_year_id: parseInt(academicYearId),
            semester_id: parseInt(semesterId)
        };

        // Close modal and refresh table
        const modal = bootstrap.Modal.getInstance(document.getElementById('editLoanModal'));
        modal.hide();

        // Show success message
        document.getElementById('successMessage').textContent = 'Peminjaman berhasil diperbarui';
        document.getElementById('successAlert').classList.remove('d-none');

        // Refresh table
        populateLoanTable(bookLoans);
    });

    // Initialize the table
    populateLoanTable(bookLoans);
});
</script>
</body>
</html>
