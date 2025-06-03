<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4267b2;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .app-title {
            color: var(--primary-color);
            font-weight: 700;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }

        .class-tab {
            border-bottom: 3px solid transparent;
            padding-bottom: 5px;
            cursor: pointer;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .class-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        .month-selector {
            background-color: white;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .month-btn {
            border: none;
            background: none;
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px;
            font-weight: 500;
        }

        .month-btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        .semester-info {
            background-color: #e9f0fb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .payment-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-paid {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-unpaid {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .status-partial {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .student-list-item {
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .student-list-item:hover {
            background-color: rgba(66, 103, 178, 0.05);
            border-left-color: var(--primary-color);
        }

        .amount-due {
            font-weight: 600;
            color: var(--danger-color);
        }

        .amount-paid {
            font-weight: 600;
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .month-selector {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 10px;
            }

            .month-btn {
                display: inline-block;
                margin: 0 3px;
            }
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
        @include('components.profiladmin')

        <!-- Search and Add Payment -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0">Manajemen SPP</h2>
            <div class="d-flex align-items-center ms-auto">
                <input type="text" placeholder="Cari siswa..." class="form-control me-2" style="width: 200px;" id="searchInput">
                <a href="{{ route('payment.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah SPP
                </a>
            </div>
        </header>

        <!-- Semester Info -->
        <div class="semester-info mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold">{{ $activeAcademicYear->year_name }}</h5>
                    <p class="mb-1">Semester: {{ $activeSemester->semester_name }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1">Periode: {{ $semesterRange['start'] }} - {{ $semesterRange['end'] }}</p>
                  <p class="mb-0">
    Bulan Aktif:
    {{ $months[$currentMonth]['name'] ?? 'Bulan tidak ditemukan' }}
    {{ $months[$currentMonth]['year'] ?? '' }}
</p>

                </div>
            </div>
        </div>

        <!-- Class Tabs -->
        <div class="d-flex mb-4 border-bottom">
            <div class="class-tab active me-4" data-class="all">Semua Kelas</div>
            @foreach(\App\Models\Classes::select(DB::raw('SUBSTRING(class_name, 1, 2) as grade'))->distinct()->get() as $grade)
                <div class="class-tab me-4" data-class="{{ $grade->grade }}">Kelas {{ $grade->grade }}</div>
            @endforeach
        </div>

        <!-- Month Selector -->
        <div class="month-selector mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bulan:</h5>
                <div>
                    @foreach($months as $key => $monthData)
                        <button class="month-btn {{ $key == $currentMonth ? 'active' : '' }}"
                                data-month="{{ $key }}">
                            {{ $monthData['name'] }} {{ $monthData['year'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SPP Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total SPP Bulan Ini</h5>
                        <h2 class="card-text">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
                        <p class="text-muted mb-0">Berdasarkan kelas yang dipilih</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sudah Dibayar</h5>
                        <h2 class="card-text">Rp {{ number_format($paidAmount, 0, ',', '.') }}</h2>
                        <p class="text-muted mb-0">{{ $paymentPercentage }}% dari total</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Belum Dibayar</h5>
                        <h2 class="card-text">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</h2>
                        <p class="text-muted mb-0">{{ $unpaidCount }} siswa belum lunas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPP Data by Class -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Daftar SPP</span>
                <div>
                    <select class="form-select form-select-sm" style="width: 150px;" id="classFilter">
                        <option value="all">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Tahun Akademik</th>
                                <th>Semester</th>
                                <th>Nominal SPP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sppData as $index => $spp)
                                <tr class="student-list-item" data-class="{{ substr($spp->classes->class_name, 0, 2) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $spp->classes->class_name }}</td>
                                    <td>{{ $spp->academicYear->year_name }}</td>
                                    <td>{{ $spp->semester->semester_name }}</td>
                                    <td>Rp {{ number_format($spp->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('payment.kelola', $spp->id) }}" class="btn btn-sm btn-info" title="Kelola">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-warning edit-spp"
                                                    data-id="{{ $spp->id }}"
                                                    data-amount="{{ $spp->amount }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-spp"
                                                    data-id="{{ $spp->id }}"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Nominal SPP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nominal SPP</label>
                                <input type="number" name="amount" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data SPP ini?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Class tab filtering
        $('.class-tab').click(function() {
            $('.class-tab').removeClass('active');
            $(this).addClass('active');

            const classGroup = $(this).data('class');
            if(classGroup === 'all') {
                $('tr[data-class]').show();
            } else {
                $('tr[data-class]').hide();
                $(`tr[data-class="${classGroup}"]`).show();
            }
        });

       // Month filtering
        $('.month-btn').click(function() {
            const month = $(this).data('month');
            const url = new URL(window.location.href);
            url.searchParams.set('month', month);
            window.location.href = url.toString();
        });

        // Search functionality
        $('#searchInput').keyup(function() {
            const searchText = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                const className = $(this).find('td:eq(1)').text().toLowerCase();
                if(className.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Edit SPP modal
        $('.edit-spp').click(function() {
            const sppId = $(this).data('id');
            const amount = $(this).data('amount');

            $('#editForm').attr('action', '/payment/update/' + sppId);
            $('#editForm input[name="amount"]').val(amount);
        });

        // Delete SPP
        $('.delete-spp').click(function() {
            const sppId = $(this).data('id');
            $('#deleteForm').attr('action', '/payment/destroy/' + sppId);
            $('#deleteModal').modal('show');
        });
    });
</script>
</body>
@endif
</html>
