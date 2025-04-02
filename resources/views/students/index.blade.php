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

        <!-- Bagian Pencarian dan Tambah Siswa -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0">Data Siswa</h2>
            <div class="d-flex align-items-center ms-auto">
                <input type="text" placeholder="Cari" class="form-control me-2" style="width: 200px;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Tambah Siswa</button>
            </div>
        </header>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Pilih Kelas</div>
            <div class="card-body">
                <form action="{{ route('students.index') }}" method="GET">
                    <div class="row">
                        <!-- Pilih Tahun Ajaran -->
                        <div class="col-md-4">
                            <label>Tahun Ajaran</label>
                            <select id="academicYearSelect" name="academic_year_id" class="form-control">
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($academicYears as $tahun)
                                    <option value="{{ $tahun->id }}"
                                        {{ request('academic_year_id') == $tahun->id ? 'selected' : '' }}>
                                        {{ $tahun->year_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Semester (Akan diubah otomatis dengan AJAX) -->
                        <div class="col-md-4">
                            <label>Semester</label>
                            <select id="semesterSelect" name="semester_id" class="form-control">
                                <option value="">-- Pilih Semester --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}"
                                        {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->semester_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Kelas -->
                        <div class="col-md-4">
                            <label>Kelas</label>
                            <select name="class_id" class="form-control">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->class_id }}"
                                        {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary mt-3">Reset</a>
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
                                <td>{{ $student->birth_date->format('Y-m-d') }}</td>
                                <td>{{ $student->gender }}</td>
                                <td>{{ $student->parent_phonecell }}</td>
                                <td>{{ $student->class ? $student->class->class_name : 'Tidak Ada Kelas' }}
                                </td>
                                <td>
                                    @if ($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" width="50">
                                    @else
                                    <i class="fas fa-user"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- Tombol Detail -->
                                        <a href="{{ route('students.show', $student->id_student) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal"
                                                data-student-id="{{ $student->id_student }}">
                                            <i class="fas fa-edit me-1"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button class="btn btn-sm btn-danger delete-student"
                                                data-student-id="{{ $student->id_student }}">
                                            <i class="fas fa-trash me-1"></i>
                                        </button>
                                    </div>

                                    <form class="delete-student-form" action="{{ route('students.destroy', $student->id_student) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
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
                        <select name="class_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id  }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>

                    <!-- Input hidden untuk Tahun Ajaran & Semester Aktif -->
                    <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                    <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

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
                    <!-- Input Tahun Ajaran & Semester Aktif (Hidden) -->
                    <input type="hidden" id="edit_academic_year_id" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                    <input type="hidden" id="edit_semester_id" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

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
                        <select id="edit_class_id" name="class_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id }}"
                                    {{ old('class_id', $student->class_id ?? '') == $class->class_id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Foto</label>
                        <div class="mb-2">
                            <img id="preview_edit_photo"
                                    src="{{ asset('storage/' . ($student->photo ?? 'default.jpg')) }}"
                                    width="100"
                                    style="{{ isset($student->photo) ? 'display: block;' : 'display: none;' }}">
                            </div>
                        <input type="file" class="form-control" id="edit_photo" name="photo">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
</div>
<script>
     document.addEventListener("DOMContentLoaded", function () {
        let academicYearSelect = document.getElementById("academicYearSelect");
        let semesterSelect = document.getElementById("semesterSelect");

        // Ambil data semester dari server
        let semesters = @json($semesters);

        academicYearSelect.addEventListener("change", function () {
            let selectedYearId = this.value;
            semesterSelect.innerHTML = '<option value="">-- Pilih Semester --</option>'; // Reset dropdown semester

            if (selectedYearId) {
                let filteredSemesters = semesters.filter(sem => sem.academic_year_id == selectedYearId);

                filteredSemesters.forEach(sem => {
                    let option = document.createElement("option");
                    option.value = sem.id;
                    option.textContent = sem.semester_name;
                    semesterSelect.appendChild(option);
                });
            }
        });
    });
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
    var editPhotoInput = document.getElementById("edit_photo");
    var previewPhoto = document.getElementById("preview_edit_photo");

    // Event ketika modal ditampilkan
    editStudentModal.addEventListener('show.bs.modal', function (event) {
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
                // Isi input field dengan data siswa
                document.getElementById('edit_id_student').value = data.id_student;
                document.getElementById('edit_fullname').value = data.fullname;
                document.getElementById('edit_birth_place').value = data.birth_place;
                document.getElementById("edit_birth_date").value = data.birth_date.substring(0, 10);
                document.getElementById('edit_gender').value = data.gender;
                document.getElementById('edit_parent_phonecell').value = data.parent_phonecell;

                let classSelect = document.getElementById('edit_class_id');
                classSelect.querySelectorAll('option').forEach(option => {
                    option.selected = (option.value == data.class_id);
                });

                document.getElementById('editStudentForm').action = `/students/${studentId}`;

                // 🔹 Ganti preview foto dengan data dari server
                if (data.photo) {
                    previewPhoto.src = `/storage/${data.photo}`;
                    previewPhoto.style.display = "block";
                } else {
                    previewPhoto.style.display = "none";
                }
            })
            .catch(error => console.error("Error:", error));
    });

    // Event listener untuk mengganti preview saat pengguna memilih file baru
    editPhotoInput.addEventListener("change", function (event) {
        let file = event.target.files[0];

        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                previewPhoto.src = e.target.result;
                previewPhoto.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            previewPhoto.style.display = "none";
        }
    });
});
        document.querySelectorAll(".delete-student").forEach(button => {
            button.addEventListener("click", function () {
                const studentId = this.getAttribute("data-student-id");
                const form = document.querySelector(`.delete-student-form[action$='${studentId}']`);

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data siswa akan dihapus secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

</body>
</html>
@endif
