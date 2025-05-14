<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Siswa {{ $class->name }} | E-School</title>
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
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            padding: 16px 20px;
            border: none;
        }

        .search-container {
            position: relative;
            margin-bottom: 24px;
        }

        .search-container input {
            border-radius: 50px;
            padding: 12px 20px 12px 50px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary-color);
        }

        .search-container .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, #20bf55, #01baef);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #01baef, #20bf55);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(32, 191, 85, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #f72585);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #f72585, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(247, 37, 133, 0.2);
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
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
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

        .status-badge {
            padding: 8px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .status-badge i {
            margin-right: 5px;
        }

        .status-available {
            background-color: rgba(32, 191, 85, 0.1);
            color: #20bf55;
        }

        .status-unavailable {
            background-color: rgba(247, 37, 133, 0.1);
            color: #f72585;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead th {
            background-color: #f8f9fa;
            border: none;
            padding: 15px;
            font-weight: 600;
            color: #555;
        }

        .table tbody tr {
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table tbody td {
            border: none;
            padding: 15px;
            vertical-align: middle;
        }

        .table tbody td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .table tbody td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .student-info-table {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .student-details-table {
            display: flex;
            flex-direction: column;
        }

        .student-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 3px;
            color: #333;
        }

        .student-id-table {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .student-id-item {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #666;
        }

        .student-id-item i {
            width: 20px;
            color: var(--primary-color);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: translateX(-5px);
            color: var(--secondary-color);
        }

        .back-button i {
            margin-right: 8px;
        }

        .info-alert {
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }

        .info-alert i {
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-text {
            color: #888;
            font-size: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .class-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .class-icon {
                margin-bottom: 15px;
            }

            .student-info-table {
                flex-direction: column;
                align-items: flex-start;
            }

            .student-photo {
                margin-bottom: 10px;
            }

            .table-responsive {
                border-radius: 12px;
                overflow: hidden;
            }
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

        <!-- Back Button -->
        <a href="{{ route('rapor.classes') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelas
        </a>

        <!-- Class Info -->
        <div class="class-info">
            <div class="class-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="class-details">
                <h3 class="class-name">{{ $class->name }}</h3>
                <div class="class-meta">
                    <div class="class-meta-item">
                        <i class="fas fa-user-tie"></i> {{ $class->teacher->name ?? 'Belum ada wali kelas' }}
                    </div>
                    <div class="class-meta-item">
                        <i class="fas fa-calendar-alt"></i> {{ $class->academicYear->name ?? 'Tahun Ajaran' }}
                    </div>
                    <div class="class-meta-item">
                        <i class="fas fa-users"></i> {{ $students->count() }} Siswa
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="fs-4 fw-bold mb-1">Data Siswa {{ $class->name }}</h2>
                <p class="text-muted mb-0">Kelola nilai rapor siswa</p>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Cari nama siswa atau NIS/NISN..." class="form-control" id="searchInput">
                </div>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="info-alert mb-4">
            <i class="fas fa-info-circle"></i>
            <div>Menampilkan data siswa kelas {{ $class->name }}</div>
        </div>

        <!-- Data Siswa -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users me-2"></i> Daftar Siswa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="siswaTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Siswa</th>
                                <th width="15%">NIS/NISN</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="15%">Status Rapor</th>
                                <th width="15%">Tanggal Rapor</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="student-info-table">
                                        @if($student->photo_path)
                                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto {{ $student->name }}" class="student-photo">
                                        @else
                                            <img src="{{ asset('images/default-avatar.jpg') }}" alt="Foto Default" class="student-photo">
                                        @endif
                                        <div class="student-details-table">
                                            <p class="student-name">{{ $student->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-id-table">
                                        <div class="student-id-item">
                                            <i class="fas fa-id-card"></i> NIS: {{ $student->nis }}
                                        </div>
                                        <div class="student-id-item">
                                            <i class="fas fa-id-badge"></i> NISN: {{ $student->nisn }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->gender }}</td>
                                <td>
                                    @if($student->rapor && count($student->rapor) > 0)
                                        <span class="status-badge status-available">
                                            <i class="fas fa-check-circle"></i> Sudah Ada Rapor
                                        </span>
                                    @else
                                        <span class="status-badge status-unavailable">
                                            <i class="fas fa-times-circle"></i> Belum Ada Rapor
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->rapor && count($student->rapor) > 0)
                                        {{ \Carbon\Carbon::parse($student->rapor[0]->report_date)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($student->rapor && count($student->rapor) > 0)
                                            <a href="{{ route('rapor.edit', $student->rapor[0]->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('rapor.destroy', $student->rapor[0]->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rapor ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('rapor.create', ['student_id' => $student->id_student, 'class_id' => $class->class_id]) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-upload"></i> Upload Rapor
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <h5>Belum Ada Siswa</h5>
                                        <p class="empty-text">Belum ada data siswa di kelas ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#siswaTable tbody tr');
        let visibleRows = 0;

        tableRows.forEach(row => {
            const studentName = row.querySelector('.student-name')?.textContent.toLowerCase() || '';
            const studentId = row.querySelector('.student-id-table')?.textContent.toLowerCase() || '';
            const gender = row.cells[3]?.textContent.toLowerCase() || '';

            if (studentName.includes(searchValue) || studentId.includes(searchValue) || gender.includes(searchValue)) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
@endif
