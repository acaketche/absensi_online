<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Siswa {{ $class->class_name }} | E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', sans-serif;
        }

       .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .card-header {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      font-weight: bold;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.05);
      cursor: pointer;
    }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-weight: 500;
        }

        .btn-success {
            background: var(--success-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
        }

        .class-info {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .search-container {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 10px;
            color: #6c757d;
        }

        .search-input {
            padding-left: 35px;
        }

        /* Modal Styles */
        .modal-header {
            background: var(--primary-color);
            color: white;
        }

        .file-upload {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
        }
              .class-info {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .class-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .class-details {
            flex: 1;
        }

        .class-name {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .class-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }

        .class-meta-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .class-meta-item i {
            margin-right: 5px;
            color: var(--primary-color);
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

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Cari nama siswa atau NIS/NISN..." class="form-control search-input" id="searchInput">
                    </div>
                </div>


            <a href="{{ route('rapor.classes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

            <!-- Class Info -->
        <div class="class-info">
            <div class="class-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="class-details">
                <h3 class="class-name">{{ $class->class_name }}</h3>
                <div class="class-meta">
                    <div class="class-meta-item">
                        <i class="fas fa-user-tie"></i> {{ $class->employee->fullname ?? 'Belum ada wali kelas' }}
                    </div>
                    <div class="class-meta-item">
                        <i class="fas fa-calendar-alt"></i> {{ $class->academicYear->year_name ?? 'Tahun Ajaran' }}
                    </div>
                    <div class="class-meta-item">
                        <i class="fas fa-users"></i> {{ $students->count() }} Siswa
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-2"></i> Daftar Siswa
                </div>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="siswaTable">
                             <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="25%">Nama Siswa</th>
                                    <th width="15%">NIS</th>
                                    <th width="10%">Jenis Kelamin</th>
                                    <th width="15%">Status Rapor</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}"
                                                    alt="Foto {{ $student->fullname }}"
                                                    class="student-avatar me-2">
                                            @else
                                                <div class="student-avatar bg-secondary text-white d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($student->fullname, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>{{ $student->fullname }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $student->id_student }}</td>
                                    <td>
                                        @if($student->gender == 'L')
                                            <span class="text-primary"><i class="fas fa-male me-1"></i> Laki-laki</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-female me-1"></i> Perempuan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $rapor = $student->rapor ? $student->rapor->first() : null;
                                        @endphp

                                        @if($rapor)
                                            <span class="badge bg-success ms-2">Sudah Ada</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Ada</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($student->rapor && $student->rapor->first() && $student->rapor->first()->report_date)
                                            {{ \Carbon\Carbon::parse($student->rapor->first()->report_date)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @php
                                $rapor = $student->rapor ? $student->rapor->first() : null;
                            @endphp

                            <td class="text-center">
                                <div class="action-buttons">
                                    @if($rapor)
                                        <a href="{{ route('rapor.edit', $rapor->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <form action="{{ route('rapor.destroy', $rapor->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus rapor ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
            <button type="button" class="btn btn-sm btn-success upload-rapor-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#uploadRaporModal"
                    data-student-id="{{ $student->id_student }}"
                    data-student-name="{{ $student->fullname }}"
                    data-student-nis="{{ $student->id_student }}"
                    data-class-id="{{ $class->class_id }}">
                <i class="fas fa-upload"></i>
            </button>
        @endif
    </div>
</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i> Belum ada siswa yang terdaftar.
                    </div>
                @endif
            </div>

            <div class="card-footer bg-light d-flex justify-content-between">
                <div><strong>Total Siswa:</strong> {{ $students->count() }} orang</div>
            </div>
        </div>
    </main>
</div>

<!-- Upload Rapor Modal -->
<div class="modal fade" id="uploadRaporModal" tabindex="-1" aria-labelledby="uploadRaporModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadRaporModalLabel">
                    <i class="fas fa-upload me-2"></i> Upload Rapor Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadRaporForm" action="{{ route('rapor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Hidden Fields -->
                    <input type="hidden" name="id_student" id="formStudentId">
                    <input type="hidden" name="class_id" id="formClassId">
                    <input type="hidden" name="status_report" value="Sudah Ada">

                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="modalStudentName" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" class="form-control" id="modalStudentNis" readonly>
                    </div>

                    <!-- Report Date -->
                    <div class="mb-3">
                        <label for="report_date" class="form-label">Tanggal Rapor</label>
                        <input type="date" class="form-control" name="report_date" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi atau catatan tambahan..."></textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="form-label">File Rapor</label>
                        <div class="file-upload" id="fileUpload">
                            <input type="file" name="report_file" id="reportFile" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="text-center">
                                <i class="fas fa-file-upload fa-3x text-primary mb-2"></i>
                                <div class="file-upload-text">Klik atau seret file ke sini</div>
                                <div class="text-muted small">Format: PDF, JPG, JPEG, PNG (Maks. 5MB)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
   document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');

        tableRows.forEach(row => {
            const studentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const studentId = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            if (studentName.includes(searchValue) || studentId.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    // Upload Rapor Modal
    const uploadRaporBtns = document.querySelectorAll('.upload-rapor-btn');
    uploadRaporBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('formStudentId').value = this.getAttribute('data-student-id');
            document.getElementById('formClassId').value = this.getAttribute('data-class-id');
            document.getElementById('modalStudentName').value = this.getAttribute('data-student-name');
            document.getElementById('modalStudentNis').value = this.getAttribute('data-student-nis');

            // Set default date to today
            document.querySelector('input[name="report_date"]').valueAsDate = new Date();
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
</body>
</html>
@endif
