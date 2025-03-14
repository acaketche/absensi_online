<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
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

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5f5f5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 10px 35px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
        }

        .search-bar i {
            position: absolute;
            left: 10px;
            color: #666;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
        }

        /* Metric Cards */
        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .metric-card-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .metric-icon {
            width: 45px;
            height: 45px;
            background: rgba(75, 107, 251, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4B6BFB;
        }

        .metric-info h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .metric-info p {
            font-size: 24px;
            font-weight: bold;
        }

        /* Graph Section */
        .graph-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .graph-title {
            margin-bottom: 20px;
        }

        /* Bottom Grid */
        .bottom-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .calendar-section, .activity-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .month-selector {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table th {
            text-align: left;
            color: #666;
            font-weight: normal;
            padding: 10px 0;
        }

        .activity-table td {
            padding: 10px 0;
            border-top: 1px solid #eee;
        }

        @media (max-width: 1024px) {
            .metrics {
                grid-template-columns: repeat(2, 1fr);
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }
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
                    <a href="#" class="nav-item text-white d-block mb-2">
                        <i class="fas fa-book-open me-2"></i>
                        <span class="nav-text">Peminjaman Buku Paket</span>
                    </a>
                </div>
            </nav>
        </div>

       <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold">Data Siswa</h2>
                <div class="d-flex align-items-center">
                    <input type="text" placeholder="Cari" class="form-control me-3" style="width: 200px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Tambah Siswa</button>
                </div>
            </header>

            <!-- Pilih Kelas -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Pilih Kelas</div>
                <div class="card-body">
                    <form action="{{ route('students.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tahun Ajaran</label>
                                <select name="academic_year" class="form-control">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($academicYears as $tahun)
                                        <option value="{{ $tahun->id }}">{{ $tahun->year_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Semester</label>
                                <select name="semester" class="form-control">
                                    <option>Ganjil</option>
                                    <option>Genap</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Kelas</label>
                                <select name="class_id" class="form-control">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                    </form>
                </div>
            </div>

            <!-- Data Siswa -->
            <div class="card">
                <div class="card-header bg-primary text-white">Data Siswa</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>No Orang Tua</th>
                                <th>Kelas</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->id_student }}</td>
                                <td>{{ $student->fullname }}</td>
                                <td>{{ $student->birth_place }}</td>
                                <td>{{ $student->birth_date }}</td>
                                <td>{{ $student->gender }}</td>
                                <td>{{ $student->parent_phonecell }}</td>
                                <td>{{ $student->class->class_name }}</td>
                                <td>
                                    @if ($student->photo)
                                        <img src="{{ asset('storage/photos/' . $student->photo) }}" width="50" height="50">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('students.show', $student->id_student) }}" class="text-info me-2">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-link text-primary p-0 me-2" data-bs-toggle="modal" data-bs-target="#editStudentModal" data-student-id="{{ $student->id_student }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('students.destroy', $student->id_student) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
 <!-- Modal Tambah Siswa -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Tambah Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="id_student" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="id_student" name="id_student" required>
                    </div>
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place" required>
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="parent_phonecell" class="form-label">No Orang Tua</label>
                        <input type="text" class="form-control" id="parent_phonecell" name="parent_phonecell" required>
                    </div>
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Kelas</label>
                        <select name="class_id" class="form-control">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_id_student" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="edit_id_student" name="id_student" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fullname" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="edit_fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="edit_password" name="password" class="form-control" placeholder="Isi jika ingin mengubah password">
                            <button type="button" class="btn btn-outline-secondary" id="toggleEditPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="edit_birth_place" name="birth_place" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="edit_birth_date" name="birth_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gender" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="edit_gender" name="gender" required>
                            <option value="">-- Pilih jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_parent_phonecell" class="form-label">No Orang Tua</label>
                        <input type="text" class="form-control" id="edit_parent_phonecell" name="parent_phonecell" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_class_id" class="form-label">Kelas</label>
                        <select name="class_id" class="form-control" id="edit_class_id">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility for add student modal
        document.getElementById('togglePassword').addEventListener('click', function() {
            let passwordField = document.getElementById('password');
            let icon = this.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });

        // Toggle password visibility for edit student modal
        document.getElementById('toggleEditPassword').addEventListener('click', function() {
            let passwordField = document.getElementById('edit_password');
            let icon = this.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });

        // Fetch and populate student data in edit modal
        var editStudentModal = document.getElementById('editStudentModal');
        editStudentModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var studentId = button.getAttribute('data-student-id');

            if (!studentId) {
                console.error("Student ID tidak ditemukan!");
                return;
            }

            fetch(`/students/${studentId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Gagal mengambil data siswa!");
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('edit_id_student').value = data.id_student;
                    document.getElementById('edit_fullname').value = data.fullname;
                    document.getElementById('edit_birth_place').value = data.birth_place;
                    document.getElementById('edit_birth_date').value = data.birth_date;
                    document.getElementById('edit_gender').value = data.gender;
                    document.getElementById('edit_parent_phonecell').value = data.parent_phonecell;

                    let classSelect = document.getElementById('edit_class_id');
                    classSelect.querySelectorAll('option').forEach(option => {
                        option.selected = (option.value == data.class_id);
                    });

                    document.getElementById('editStudentForm').action = `/students/${studentId}`;
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                });
        });
    });
    </script>

</body>
</html>
