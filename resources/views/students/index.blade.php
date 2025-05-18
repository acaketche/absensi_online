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
         @include('components.profiladmin')
        <!-- Bagian Pencarian dan Tambah Siswa -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0">Data Siswa</h2>
            <div class="d-flex align-items-center ms-auto">
                <input type="text" placeholder="Cari" class="form-control me-2" style="width: 200px;">
                <a href="{{ route('students.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
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
                                <th>QrCode</th>
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
                                <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
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
                                    @if ($student->qr_code)
                                    <img src="{{ asset('storage/' . $student->qr_code) }}" width="50">
                                    @else
                                    <i class="fas fa-user"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- Tombol Detail -->
                                        <a href="{{ route('students.show', $student->id_student) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('students.edit', $student->id_student) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i>
                                        </a>

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

        // Konfirmasi hapus siswa
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
    });
</script>

</body>
</html>
@endif
