<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Import Siswa - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-main {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
        .template-card {
            transition: transform 0.3s;
            border-left: 4px solid #28a745;
        }
        .template-card:hover {
            transform: translateY(-5px);
        }
        .attention-card {
            border-left: 4px solid #ffc107;
        }
        .example-table th {
            white-space: nowrap;
        }
        .btn-download {
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="card card-main mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-file-excel me-2"></i>Template Import Data Siswa
                    </h5>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="alert alert-info border-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-2">Petunjuk Penggunaan Template</h5>
                                    <ol class="mb-0 ps-3">
                                        <li class="mb-2">Download template dengan mengklik tombol <strong>Download Template</strong></li>
                                        <li class="mb-2">Isi data sesuai dengan kolom yang tersedia</li>
                                        <li class="mb-2">Jangan mengubah nama kolom atau struktur template</li>
                                        <li class="mb-2">Format tanggal harus: <span class="badge bg-secondary">YYYY-MM-DD</span> (contoh: <code>2005-08-20</code>)</li>
                                        <li>Jenis kelamin: <span class="badge bg-primary">L</span> (Laki-laki) atau <span class="badge bg-primary">P</span> (Perempuan)</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-table me-2 text-primary"></i>Contoh Data Template (Data Terbaru dari Database)
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 example-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="15%">NIS</th>
                                                <th width="20%">Nama Lengkap</th>
                                                <th width="15%">Password</th>
                                                <th width="15%">Tempat Lahir</th>
                                                <th width="15%">Tanggal Lahir</th>
                                                <th width="10%">Gender</th>
                                                <th width="15%">No. Orang Tua</th>
                                                <th width="15%">Kelas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @php
                                        use App\Models\Student;

                                        $recentStudents = Student::with('studentSemesters.class')
                                            ->orderBy('created_at', 'desc')
                                            ->take(2)
                                            ->get();
                                    @endphp

                                            @forelse($recentStudents as $student)
                                            <tr>
                                                <td>{{ $student->id_student }}</td>
                                                <td>{{ $student->fullname }}</td>
                                                <td>password123</td>
                                                <td>{{ $student->birth_place }}</td>
                                                <td>{{ $student->birth_date->format('Y-m-d') }}</td>
                                                <td><span class="badge bg-primary">{{ $student->gender }}</span></td>
                                                <td>{{ $student->parent_phonecell }}</td>
                                                <td>{{ $student->class->class_name ?? 'N/A' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Belum ada data siswa</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Download Card -->
                        <div class="card border-0 shadow-sm template-card mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-download me-2 text-success"></i>Download Template
                                </h6>
                            </div>
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-file-excel text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="mb-2">Template Import Siswa</h5>
                                <p class="text-muted mb-4">
                                    File Excel siap pakai dengan format yang sudah disesuaikan
                                </p>
                                <a href="{{ route('students.template.empty') }}" class="btn btn-success btn-download w-100">
                                    <i class="fas fa-download me-2"></i> Download Template
                                </a>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>Ukuran file: ~15KB (.xlsx)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Attention Card -->
                        <div class="card border-0 shadow-sm attention-card">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Perhatian Penting
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3 d-flex">
                                        <div class="me-3 text-primary">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <strong>NIPD harus unik</strong>
                                            <p class="text-muted small mb-0">Tidak boleh sama dengan data yang sudah ada</p>
                                        </div>
                                    </li>
                                    <li class="mb-3 d-flex">
                                        <div class="me-3 text-primary">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <strong>Nama kelas valid</strong>
                                            <p class="text-muted small mb-0">Harus sesuai dengan yang terdaftar di sistem</p>
                                        </div>
                                    </li>
                                    <li class="d-flex">
                                        <div class="me-3 text-primary">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <strong>Password aman</strong>
                                            <p class="text-muted small mb-0">Akan dienkripsi secara otomatis</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tambahkan animasi saat hover tombol download
        document.querySelector('.btn-download').addEventListener('mouseover', function() {
            this.querySelector('i').classList.add('fa-bounce');
        });
        document.querySelector('.btn-download').addEventListener('mouseout', function() {
            this.querySelector('i').classList.remove('fa-bounce');
        });
    </script>
</body>
</html>
