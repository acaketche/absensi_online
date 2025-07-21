<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-School - Data Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4266B9;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
            --border-color: #e3e6f0;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8f9fc;
            color: var(--text-color);
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-bottom: none;
            padding: 1rem 1.35rem;
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }

        .card-footer {
            background-color: white;
            border-top: 1px solid var(--border-color);
            font-size: 0.9rem;
            padding: 1rem 1.35rem;
        }

        .table-responsive-container {
            overflow: auto;
            max-height: 100vh;
            width: 100%;
            border-radius: 0 0 0.35rem 0.35rem;
            position: relative;
        }

       .employee-table {
    width: 120%;
    min-width: 1200px;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid var(--border-color); /* Tambahkan border untuk tabel */
}

.employee-table thead th {
    background-color: var(--secondary-color);
    position: sticky;
    top: 0;
    z-index: 10;
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 700;
    color: var(--text-color);
    border: 1px solid var(--border-color); /* Tambahkan border untuk header */
    white-space: nowrap;
}

.employee-table tbody td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
    border: 1px solid var(--border-color); /* Ubah border-bottom menjadi border semua sisi */
    background-color: white;
}

/* Tambahkan border untuk sel pertama di header dan body */
.employee-table tbody td:first-child,
.employee-table thead th:first-child {
    border-left: 1px solid var(--border-color);
}

/* Tambahkan border untuk sel terakhir di header dan body */
.employee-table tbody td:last-child,
.employee-table thead th:last-child {
    border-right: 1px solid var(--border-color);
}

/* Tambahkan border untuk baris terakhir di body */
.employee-table tbody tr:last-child td {
    border-bottom: 1px solid var(--border-color);
}
        /* Employee photo styling */
        .employee-photo {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e6e1f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .employee-photo i {
            font-size: 16px;
            color: #7950f2;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-table {
            padding: 0.35rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.2rem;
            transition: all 0.2s;
        }

        .btn-table:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Search and header styling */
        .search-container {
            position: relative;
            width: 250px;
        }

        .search-container input {
            padding-left: 2.5rem;
            border-radius: 0.35rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .search-container input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d3e2;
        }

        /* Gender badges */
        .gender-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .gender-male {
            background-color: #e6f7ff;
            color: #1890ff;
        }

        .gender-female {
            background-color: #fff0f6;
            color: #eb2f96;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .search-container {
                width: 200px;
            }

            .employee-table {
                min-width: 1000px;
            }
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 1.25rem;
            }

            .search-container {
                width: 180px;
            }

            .employee-table {
                min-width: 800px;
                font-size: 0.8rem;
            }

            .table-responsive-container {
                max-height: 60vh;
            }
        }

        /* Animation for table rows */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .employee-table tbody tr {
            animation: fadeIn 0.3s ease-out;
            animation-fill-mode: both;
        }

        .employee-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .employee-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .employee-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .employee-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        /* and so on... */
    </style>
</head>
@if(Auth::guard('employee')->check())
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Header with Admin Profile -->
            @include('components.profiladmin')

            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                     <h2 class="fs-3 fw-bold mb-0">Daftar seluruh pegawai dan guru</h2>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Cari pegawai..." class="form-control" id="searchInput">
                    </div>
                    @if(Auth::guard('employee')->user()->role_id == 2)
                    <a href="{{ route('employees.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus mr-2"></i>Tambah Pegawai
                    </a>
                    @endif
                </div>
            </header>

            <!-- Alert for success message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users mr-2"></i>Daftar Pegawai</span>
                    <span class="badge bg-white text-primary">{{ $employees->count() }} Pegawai</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive-container">
                        <table class="employee-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NIP</th>
                                    <th>NAMA LENGKAP</th>
                                    <th>EMAIL</th>
                                    <th>TEMPAT, TGL LAHIR</th>
                                    <th>JENIS KELAMIN</th>
                                    <th>NO. HP</th>
                                    <th>ROLE</th>
                                    <th>POSISI</th>
                                    <th>FOTO</th>
                                    <th>QR CODE</th>
                                    @if(Auth::guard('employee')->user()->role_id == 2)
                                    <th>AKSI</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->id_employee }}</td>
                                    <td>{{ $employee->fullname }}</td>
                                    <td>{{ $employee->email ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $employee->birth_place }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($employee->birth_date)->format('d/m/Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="gender-badge {{ $employee->gender == 'L' ? 'gender-male' : 'gender-female' }}">
                                            {{ $employee->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td>{{ $employee->phone_number }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $employee->role->role_name ?? '--' }}
                                        </span>
                                    </td>
                                    <td>{{ $employee->position->name ?? '--' }}</td>
                                    <td>
                                        <div class="employee-photo">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}" width="40" height="40" style="object-fit: cover; border-radius: 50%;">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($employee->qr_code)
                                        <img src="{{ asset('storage/' . $employee->qr_code) }}" alt="QR Code" width="40">
                                        @else
                                        <span class="badge bg-light text-muted">Tidak Ada</span>
                                        @endif
                                    </td>
                                    @if(Auth::guard('employee')->user()->role_id == 2)
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('employees.edit', $employee->id_employee) }}"
                                               class="btn btn-sm btn-warning btn-table"
                                               data-bs-toggle="tooltip"
                                               title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-table delete-employee"
                                                    data-employee-id="{{ $employee->id_employee }}"
                                                    data-employee-name="{{ $employee->fullname }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Hapus Data">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');

                let visibleCount = 0;

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update counter
                const counterElement = document.querySelector('.card-footer .text-muted small');
                if (counterElement) {
                    counterElement.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari <strong>${tableRows.length}</strong> data`;
                }
            });
        }

        // Delete employee (only for Admin)
        @if(Auth::guard('employee')->user()->role_id == 2)
        const deleteButtons = document.querySelectorAll('.delete-employee');
        if (deleteButtons.length > 0) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const employeeId = this.getAttribute('data-employee-id');
                    const employeeName = this.getAttribute('data-employee-name');

                    // Destroy tooltip before showing Swal
                    const tooltip = bootstrap.Tooltip.getInstance(button);
                    if (tooltip) tooltip.dispose();

                    Swal.fire({
                        title: 'Konfirmasi Penghapusan',
                        html: `Anda akan menghapus data <strong>${employeeName}</strong>. <br>Data yang dihapus tidak dapat dikembalikan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        backdrop: `
                            rgba(78, 115, 223, 0.1)
                            url("/images/trash-animation.gif")
                            center top
                            no-repeat
                        `
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/employees/${employeeId}`;
                            form.style.display = 'none';

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';

                            form.appendChild(csrfToken);
                            form.appendChild(method);
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            // Reinitialize tooltip if deletion is cancelled
                            new bootstrap.Tooltip(button);
                        }
                    });
                });
            });
        }
        @endif

        // Add animation to table rows on load
        const tableRows = document.querySelectorAll('.employee-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    });
    </script>
</body>
</html>
@endif
