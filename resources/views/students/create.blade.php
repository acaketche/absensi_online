<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
           @include('components.profiladmin')

            <!-- Judul Halaman -->
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold mb-0">Tambah Siswa Baru</h2>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </header>

            <!-- Form Tambah Siswa -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user-plus me-2"></i> Form Tambah Siswa
                </div>
                <div class="card-body">
                    <form id="addStudentForm" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
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
                                            <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="photo" name="photo">
                                    <div class="mt-2">
                                        <img id="preview_photo" src="/placeholder.svg" style="display: none; max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input hidden untuk Tahun Ajaran & Semester Aktif -->
                        <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">
                        <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('students.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
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

            // Preview foto
            document.getElementById('photo').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('preview_photo');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
@endif
