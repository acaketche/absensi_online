<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            background-color: #f8f9fc;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.1);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .table-container {
            overflow-y: auto;
            max-height: 700px;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--secondary-color);
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 1rem;
            border-bottom: 2px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            font-weight: 700;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
        }

        .table thead th:last-child,
        .table tbody td:last-child {
            border-right: none;
        }

        .role-summary {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .role-card {
            flex: 1;
            min-width: 200px;
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .role-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .role-icon.super-admin {
            background-color: rgba(24, 144, 255, 0.1);
            color: #1890ff;
        }

        .role-icon.wali-kelas {
            background-color: rgba(250, 140, 22, 0.1);
            color: #fa8c16;
        }

        .role-icon.tata-usaha {
            background-color: rgba(82, 196, 26, 0.1);
            color: #52c41a;
        }

        .role-icon.piket {
            background-color: rgba(114, 46, 209, 0.1);
            color: #722ed1;
        }

        .role-icon.perpustakaan {
            background-color: rgba(235, 47, 150, 0.1);
            color: #eb2f96;
        }

        .role-info h5 {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            color: var(--text-color);
            font-weight: 600;
        }

        .role-info p {
            margin-bottom: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .badge-role {
            padding: 0.35rem 0.65rem;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-super-admin {
            background-color: #e6f7ff;
            color: #1890ff;
        }

        .badge-wali-kelas {
            background-color: #fff7e6;
            color: #fa8c16;
        }

        .badge-tata-usaha {
            background-color: #f6ffed;
            color: #52c41a;
        }

        .badge-piket {
            background-color: #f9f0ff;
            color: #722ed1;
        }

        .badge-perpustakaan {
            background-color: #fff0f6;
            color: #eb2f96;
        }

        @media (max-width: 768px) {
            .role-summary {
                flex-direction: column;
            }

            .role-card {
                min-width: 100%;
            }
        }
    </style>
</head>
<body class="bg-light">
    @if(Auth::guard('employee')->check())
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <!-- Profil Admin -->
            @include('components.profiladmin')

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data User</h5>
                </div>
                <div class="card-body">
                    <!-- Role Summary Cards -->
                    <div class="role-summary">
                        <div class="role-card">
                            <div class="role-icon super-admin"><i class="fas fa-user-shield"></i></div>
                            <div class="role-info">
                                <h5>Super Admin</h5>
                                <p>{{ $roleCounts['Super Admin'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="role-card">
                            <div class="role-icon wali-kelas"><i class="fas fa-chalkboard-teacher"></i></div>
                            <div class="role-info">
                                <h5>Wali Kelas</h5>
                                <p>{{ $roleCounts['Wali Kelas'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="role-card">
                            <div class="role-icon tata-usaha"><i class="fas fa-user-tie"></i></div>
                            <div class="role-info">
                                <h5>Admin Tata Usaha</h5>
                                <p>{{ $roleCounts['Admin Tata Usaha'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="role-card">
                            <div class="role-icon piket"><i class="fas fa-clipboard-list"></i></div>
                            <div class="role-info">
                                <h5>Admin Piket</h5>
                                <p>{{ $roleCounts['Admin Pegawai Piket'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="role-card">
                            <div class="role-icon perpustakaan"><i class="fas fa-book"></i></div>
                            <div class="role-info">
                                <h5>Admin Perpustakaan</h5>
                                <p>{{ $roleCounts['Admin Perpustakaan'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel User -->
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>NIP</th>
                                <th>NAMA LENGKAP</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>JABATAN</th> {{-- Tambahan --}}
                            </tr>
                        </thead>
                           <tbody>
    @foreach($users as $index => $user)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $user->id_employee }}</td>
        <td>{{ $user->fullname }}</td>
        <td>{{ $user->email ?? '-' }}</td>
        <td>
            @php
                $roleName = $user->role->role_name;
                $badgeClass = 'badge-role ';
                $icon = 'user';

                if($roleName == 'Super Admin') {
                    $badgeClass .= 'badge-super-admin';
                    $icon = 'user-shield';
                } elseif($roleName == 'Wali Kelas') {
                    $badgeClass .= 'badge-wali-kelas';
                    $icon = 'chalkboard-teacher';
                } elseif($roleName == 'Admin Tata Usaha') {
                    $badgeClass .= 'badge-tata-usaha';
                    $icon = 'user-tie';
                } elseif($roleName == 'Admin Pegawai Piket') {
                    $badgeClass .= 'badge-piket';
                    $icon = 'clipboard-list';
                } else {
                    $badgeClass .= 'badge-perpustakaan';
                    $icon = 'book';
                }
            @endphp
            <span class="{{ $badgeClass }}">
                <i class="fas fa-{{ $icon }} me-1"></i>
                {{ $roleName }}
            </span>
        </td>
        <td>{{ $user->position->name ?? '-' }}</td> {{-- Tambahan --}}
    </tr>
    @endforeach
</tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="text-muted small">
                        Total <strong>{{ $users->count() }}</strong> data user
                    </div>
                </div>
            </div>
        </main>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
