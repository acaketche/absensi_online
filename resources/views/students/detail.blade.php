<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        .profile-img {
            max-width: 250px;
            max-height: 250px;
            object-fit: cover;
        }
        .qr-img {
            max-width: 200px;
            max-height: 200px;
        }
        .info-card {
            border-left: 4px solid #0d6efd;
            border-radius: 8px;
        }
        .history-item {
            border-left: 3px solid #0d6efd;
            padding-left: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .history-item:hover {
            background-color: #f8f9fa;
        }
        .card-header {
            border-radius: 8px 8px 0 0 !important;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
            display: inline-block;
        }
    </style>
</head>
@if(Auth::guard('employee')->check())
<body>
<div class="d-flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Header dengan Profil Admin -->
        @include('components.profiladmin')

        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Detail Siswa</h3>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                    <hr class="mt-2">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-graduate me-2"></i>Profil Siswa
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Kolom Foto dan QR Code -->
                                <div class="col-md-4 text-center">
                                    <div class="mb-4">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}"
                                                 alt="Foto Siswa"
                                                 class="img-thumbnail profile-img mb-2">
                                        @else
                                            <div class="bg-light p-4 text-center mb-3 rounded d-flex flex-column justify-content-center" style="height: 250px;">
                                                <i class="fas fa-user fa-5x text-secondary mb-3"></i>
                                                <p class="text-muted">Foto tidak tersedia</p>
                                            </div>
                                        @endif
                                        <h5 class="mt-2">{{ $student->fullname }}</h5>
                                        <p class="text-muted">NIS: {{ $student->id_student }}</p>
                                    </div>

                                    @if($student->qr_code)
                                        <div class="border-top pt-3">
                                            <img src="{{ asset('storage/' . $student->qr_code) }}"
                                                 alt="QR Code"
                                                 class="img-thumbnail qr-img mb-2">
                                            <p class="text-muted">QR Code Siswa</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Kolom Informasi Siswa -->
                                <div class="col-md-8">
                                    <div class="card info-card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">
                                                <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Pribadi
                                            </h5>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-2">
                                                        <span class="detail-label">Tempat Lahir:</span>
                                                        {{ $student->birth_place ?? '-' }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <span class="detail-label">Tanggal Lahir:</span>
                                                        {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d F Y') : '-' }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <span class="detail-label">Usia:</span>
                                                        {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->age . ' tahun' : '-' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="mb-2">
                                                        <span class="detail-label">Jenis Kelamin:</span>
                                                        {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <span class="detail-label">No. HP Orang Tua:</span>
                                                        {{ $student->parent_phonecell ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Riwayat Kelas -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light py-3">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-history me-2 text-primary"></i>Riwayat Kelas
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if($semesters->count() > 0)
                                                <div class="timeline">
                                                    @foreach($semesters as $semester)
                                                        <div class="history-item position-relative">
                                                            <h6 class="mb-1">
                                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                                Tahun Ajaran {{ $semester->academicYear->academic_year_name }}
                                                            </h6>
                                                            <p class="mb-1">
                                                                <i class="fas fa-bookmark me-2 text-primary"></i>
                                                                Semester {{ $semester->semester->semester_name }} -
                                                                Kelas {{ $semester->class->class_name }}
                                                            </p>
                                                            <small class="text-muted">
                                                                <i class="far fa-clock me-1"></i>
                                                                Diinput pada: {{ \Carbon\Carbon::parse($semester->created_at)->format('d F Y H:i') }}
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-warning mb-0">
                                                    <i class="fas fa-exclamation-circle me-2"></i>
                                                    Tidak ada riwayat kelas untuk siswa ini.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-end">
                            <a href="{{ route('students.edit', $student->id_student) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i> Edit Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
@endif
</html>
