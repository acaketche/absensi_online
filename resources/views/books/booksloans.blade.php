<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      margin-bottom: 20px;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      font-weight: bold;
    }

    .class-card {
      cursor: pointer;
    }

    .teacher-info {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      margin-top: 10px;
    }

    .teacher-avatar {
      width: 40px;
      height: 40px;
      object-fit: cover;
    }

    .teacher-initial {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      background-color: #6c757d;
      border-radius: 50%;
    }

    .class-stats {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .stat-item {
      text-align: center;
    }

    .stat-value {
      font-size: 1.2rem;
      font-weight: bold;
      color: #0d6efd;
    }

    .stat-label {
      font-size: 0.8rem;
      color: #6c757d;
    }
  </style>
</head>
@if(Auth::guard('employee')->check())
<body class="bg-light">
<div class="d-flex">
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

    <!-- Judul Halaman -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-4 fw-bold">Peminjaman Buku - Daftar Kelas</h2>
        <div class="d-flex">
            <input type="text" id="searchClass" class="form-control me-2" placeholder="Cari kelas...">
        </div>
    </header>

    <!-- Alert for success message -->
    <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="successAlert">
        <span id="successMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

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
            <form id="filterForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Peminjaman</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">-- Semua Status --</option>
                            <option value="borrowed">Dipinjam</option>
                            <option value="returned">Dikembalikan</option>
                            <option value="overdue">Terlambat</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select class="form-select" id="filterAcademicYear">
                            <option value="">-- Semua Tahun Ajaran --</option>
                            <option value="1">2023/2024</option>
                            <option value="2">2022/2023</option>
                            <option value="3">2021/2022</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" id="filterSemester">
                            <option value="">-- Semua Semester --</option>
                            <option value="1">Ganjil</option>
                            <option value="2">Genap</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="applyFilterBtn" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Tampilkan
                    </button>
                    <button type="reset" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Reset
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
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4">Tanggal Kembali</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Tahun Ajaran</th>
                            <th class="py-3 px-4">Semester</th>
                            <th class="py-3 px-4">Aksi</th>
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
                                    <span class="badge bg-{{ $loan->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                        {{ $loan->status }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">{{ $loan->academicYear->tahun ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $loan->semester->nama ?? '-' }}</td>
                                <td class="py-2 px-4">
                                    <a href="#" class="btn btn-info btn-sm">Detail</a>
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
        <div class="modal-header">
            <h5 class="modal-title" id="addLoanModalLabel">Tambah Peminjaman Buku</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="addLoanForm">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="id_student" class="form-label">Pilih Siswa</label>
                        <select class="form-select" id="id_student" name="id_student" required>
                            <option value="">-- Pilih Siswa --</option>
                            <option value="1">Ahmad Rizky (S001) - Kelas 10 IPA</option>
                            <option value="2">Budi Santoso (S002) - Kelas 10 IPA</option>
                            <option value="3">Citra Dewi (S003) - Kelas 10 IPS</option>
                            <option value="4">Dian Purnama (S004) - Kelas 11 IPA</option>
                            <option value="5">Eko Prasetyo (S005) - Kelas 11 IPS</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="book_id" class="form-label">Pilih Buku</label>
                        <select class="form-select" id="book_id" name="book_id" required>
                            <option value="">-- Pilih Buku --</option>
                            <option value="1">Matematika Kelas 10 Semester 1</option>
                            <option value="2">Bahasa Indonesia Kelas 10</option>
                            <option value="3">Fisika Kelas 11</option>
                            <option value="4">Sejarah Indonesia Kelas 12</option>
                            <option value="5">English for SMA Grade 11</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
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
                            <option value="1">2023/2024</option>
                            <option value="2">2022/2023</option>
                            <option value="3">2021/2022</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select class="form-select" id="semester_id" name="semester_id" required>
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
            <div class="card-footer text-center text-muted small bg-white">
                Klik untuk melihat siswa
            </div>
        </div>
    </div>
    @endforeach
</div>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data for books
    const books = [
        {
            id: 1,
            title: 'Matematika Kelas 10 Semester 1',
            author: 'Dr. Budi Santoso',
            publisher: 'Erlangga',
            year_published: 2022,
            stock: 45,
            created_at: '2023-01-15 08:30:00',
            updated_at: '2023-01-15 08:30:00'
        },
        {
            id: 2,
            title: 'Bahasa Indonesia Kelas 10',
            author: 'Dra. Siti Rahayu',
            publisher: 'Gramedia',
            year_published: 2022,
            stock: 50,
            created_at: '2023-01-15 09:15:00',
            updated_at: '2023-01-15 09:15:00'
        },
        {
            id: 3,
            title: 'Fisika Kelas 11',
            author: 'Prof. Ahmad Wijaya',
            publisher: 'Yudhistira',
            year_published: 2021,
            stock: 30,
            created_at: '2023-01-16 10:20:00',
            updated_at: '2023-01-16 10:20:00'
        },
        {
            id: 4,
            title: 'Sejarah Indonesia Kelas 12',
            author: 'Dr. Hendra Gunawan',
            publisher: 'Erlangga',
            year_published: 2021,
            stock: 25,
            created_at: '2023-01-17 11:45:00',
            updated_at: '2023-01-17 11:45:00'
        },
        {
            id: 5,
            title: 'English for SMA Grade 11',
            author: 'Sarah Johnson',
            publisher: 'Cambridge Press',
            year_published: 2022,
            stock: 10,
            created_at: '2023-01-18 13:10:00',
            updated_at: '2023-01-18 13:10:00'
        }
    ];

    // Sample data for students
    const students = [
        {
            id: 1,
            name: 'Ahmad Rizky',
            nis: 'S001',
            class: '10 IPA'
        },
        {
            id: 2,
            name: 'Budi Santoso',
            nis: 'S002',
            class: '10 IPA'
        },
        {
            id: 3,
            name: 'Citra Dewi',
            nis: 'S003',
            class: '10 IPS'
        },
        {
            id: 4,
            name: 'Dian Purnama',
            nis: 'S004',
            class: '11 IPA'
        },
        {
            id: 5,
            name: 'Eko Prasetyo',
            nis: 'S005',
            class: '11 IPS'
        }
    ];

    // Sample data for academic years
    const academicYears = [
        { id: 1, name: '2023/2024' },
        { id: 2, name: '2022/2023' },
        { id: 3, name: '2021/2022' }
    ];

    // Sample data for semesters
    const semesters = [
        { id: 1, name: 'Ganjil' },
        { id: 2, name: 'Genap' }
    ];

    // Sample data for book loans
    const bookLoans = [
        {
            id: 1,
            id_student: 1,
            book_id: 1,
            loan_date: '2023-08-01',
            due_date: '2023-08-15',
            return_date: null,
            status: 'borrowed',
            created_at: '2023-08-01 10:15:00',
            updated_at: '2023-08-01 10:15:00',
            academic_year_id: 1,
            semester_id: 1
        },
        {
            id: 2,
            id_student: 2,
            book_id: 2,
            loan_date: '2023-07-15',
            due_date: '2023-07-29',
            return_date: '2023-07-28',
            status: 'returned',
            created_at: '2023-07-15 09:30:00',
            updated_at: '2023-07-28 14:20:00',
            academic_year_id: 1,
            semester_id: 1
        },
        {
            id: 3,
            id_student: 3,
            book_id: 3,
            loan_date: '2023-07-10',
            due_date: '2023-07-24',
            return_date: null,
            status: 'overdue',
            created_at: '2023-07-10 11:45:00',
            updated_at: '2023-07-25 08:10:00',
            academic_year_id: 1,
            semester_id: 1
        },
        {
            id: 4,
            id_student: 4,
            book_id: 4,
            loan_date: '2023-08-05',
            due_date: '2023-08-19',
            return_date: null,
            status: 'borrowed',
            created_at: '2023-08-05 13:20:00',
            updated_at: '2023-08-05 13:20:00',
            academic_year_id: 1,
            semester_id: 1
        },
        {
            id: 5,
            id_student: 5,
            book_id: 5,
            loan_date: '2023-07-20',
            due_date: '2023-08-03',
            return_date: '2023-08-05',
            status: 'returned',
            created_at: '2023-07-20 10:30:00',
            updated_at: '2023-08-05 15:45:00',
            academic_year_id: 1,
            semester_id: 1
        }
    ];

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
        const classItems = document.querySelectorAll('.class-item');

        classItems.forEach(item => {
            const className = item.querySelector('.card-title').textContent.toLowerCase();
            const teacherName = item.querySelector('.fw-bold').textContent.toLowerCase();

            if (className.includes(searchValue) || teacherName.includes(searchValue)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Fungsi filter kelas
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const tingkat = document.getElementById('filterTingkat').value;
        const classItems = document.querySelectorAll('.class-item');

        classItems.forEach(item => {
            const className = item.querySelector('.card-title').textContent;

            if (tingkat && !className.includes(tingkat)) {
                item.style.display = 'none';
            } else {
                item.style.display = '';
            }
        });
    });

    // Reset filter
    document.querySelector('#filterForm button[type="reset"]').addEventListener('click', function() {
        const classItems = document.querySelectorAll('.class-item');
        classItems.forEach(item => {
            item.style.display = '';
        });
    });
});
</script>
</body>
</html>
@endif
