<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .app-title {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-info {
            text-align: right;
        }

        .admin-name {
            font-weight: bold;
            margin-bottom: 0;
        }

        .admin-role {
            color: #6c757d;
            font-size: 14px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #c8d6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 10px 15px 10px 40px;
            border-radius: 30px;
            border: 1px solid #e0e0e0;
            width: 100%;
            max-width: 300px;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background-color: #4267b2;
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .btn-primary {
            background-color: #4267b2;
            border-color: #4267b2;
        }

        .btn-primary:hover {
            background-color: #365899;
            border-color: #365899;
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

        .table {
            margin-top: 15px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .action-btn {
            background-color: #4267b2;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .action-btn:hover {
            background-color: #365899;
        }

        .settings-icon {
            color: #6c757d;
            cursor: pointer;
        }

        .settings-icon:hover {
            color: #4267b2;
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

        <!-- Search Bar -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0">Tambah SPP</h2>
            <div class="d-flex align-items-center ms-auto">
                <input type="text" placeholder="Cari" class="form-control me-2" style="width: 200px;">
                <a href="{{ url('/payment/create')}}" class="btn btn-primary" >+ Tambah Pembayaran</a>
            </div>
        </header>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Pilih Kelas</div>
            <div class="card-body">
                <form method="POST" action="{{url('/payment/create')}}">
                    <div class="row">
                        <!-- Pilih Tahun Ajaran -->
                        @csrf
                        <div class="col-md-6 p-2">
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
                        <div class="col-md-6 p-2">
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
                        <div class="col-md-6 p-2">
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

                        <div class="col-md-6 p-2">
                            <label>Nominal SPP</label>
                            <input type="number" class="form-control" name="nominal">
                        </div>
                    </div>
                    <button type="submit" id="simpan" name="simpan" class="btn btn-primary mt-3">Simpan</button>
                    <a href="{{ route('payment.listdata') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>

        <!-- Data Section -->
         </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle filter form submission
            const filterButton = document.querySelector('#tampilkan');
            filterButton.addEventListener('click', function() {
                // In a real application, this would submit the form or fetch filtered data
                // For this example, we'll just show an alert
                alert('Data berhasil difilter');
            });

            // Handle search functionality
            const searchInput = document.querySelector('.search-container input');
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');

                tableRows.forEach(row => {
                    const studentName = row.cells[1].textContent.toLowerCase();
                    const studentNIS = row.cells[2].textContent.toLowerCase();

                    if (studentName.includes(searchValue) || studentNIS.includes(searchValue)) {
                        row.style.display = '';
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
