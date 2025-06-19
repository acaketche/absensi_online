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
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .app-title {
            color: var(--primary-color);
            font-weight: 700;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
            padding: 15px 20px;
        }

        .class-tab {
            border-bottom: 3px solid transparent;
            padding: 8px 12px;
            cursor: pointer;
            font-weight: 500;
            color: var(--secondary-color);
            transition: all 0.2s;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }

        .class-tab:hover {
            background-color: rgba(66, 103, 178, 0.1);
            color: var(--primary-color);
        }

        .class-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            background-color: rgba(66, 103, 178, 0.1);
        }

        .month-selector {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .month-btn {
            border: 1px solid #dee2e6;
            background: white;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .month-btn:hover {
            background-color: #f1f3f5;
        }

        .month-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .semester-info {
            background-color: #e9f0fb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary-color);
        }

        .payment-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
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

        .btn-action {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

         /* Table Styles */
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom-width: 2px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(66, 103, 178, 0.05);
        }

        .form-control, .form-select {
            border-radius: 6px;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(66, 103, 178, 0.25);
        }

        @media (max-width: 768px) {
            .month-selector {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 15px;
            }

            .month-btn {
                display: inline-block;
                margin: 0 3px;
                padding: 6px 12px;
            }

            .class-tabs-container {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 10px;
            }

            .class-tabs {
                display: inline-flex;
                flex-wrap: nowrap;
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
            <h2 class="fs-4 fw-bold mb-0 text-primary">Manajemen SPP</h2>
            <div class="d-flex align-items-center ms-auto">
                <div class="input-group me-3" style="width: 250px;">
                </div>
                <a href="{{ route('payment.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah SPP
                </a>
            </div>
        </header>

        <!-- Semester Info -->
        <div class="semester-info mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-2"><i class="fas fa-calendar-alt me-2"></i>{{ $activeAcademicYear->year_name }}</h5>
                    <p class="mb-1"><i class="fas fa-book me-2"></i>Semester: {{ $activeSemester->semester_name }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><i class="fas fa-clock me-2"></i>Periode: {{ $semesterRange['start'] }} - {{ $semesterRange['end'] }}</p>
                    <p class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i>
                        Bulan Aktif: {{ $months[$currentMonth]['name'] ?? 'Bulan tidak ditemukan' }} {{ $months[$currentMonth]['year'] ?? '' }}
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
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-2 fw-semibold"><i class="fas fa-calendar me-2"></i>Pilih Bulan:</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($months as $key => $monthData)
                        <button class="month-btn btn-sm {{ $key == $currentMonth ? 'active' : '' }}"
                                data-month="{{ $key }}">
                            {{ $monthData['name'] }} {{ $monthData['year'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
<!-- SPP Summary Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
                   <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-muted">Total SPP Bulan Ini</h5>
                                <h2 class="card-text fw-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
                            </div>
                            <div class=" bg-opacity-10 p-3 rounded">
                                <i class="fas fa-wallet text-primary fs-4"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0 mt-2"><small>Berdasarkan kelas yang dipilih</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100 border-top-0 border-success border-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-muted">Sudah Dibayar</h5>
                                <h2 class="card-text fw-bold">Rp {{ number_format($paidAmount, 0, ',', '.') }}</h2>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentPercentage }}%"
                                 aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mb-0 mt-1"><small>{{ $paymentPercentage }}% dari total</small></p>
                    </div>
                </div>
            </div>
        </div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger border-0 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Terjadi kesalahan saat mengunggah file</h6>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li><small>{{ $error }}</small></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <h5 class="fw-bold mb-3">Download Template Pembayaran</h5>
        <p class="text-muted mb-3">Pilih template berdasarkan tingkatan kelas:</p>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('payments.export.all') }}" class="btn btn-primary px-4 py-2">
                <i class="fas fa-download me-2"></i> Semua Kelas
            </a>
            <div class="btn-group" role="group">
                <a href="{{ route('payments.export.grade', 'X') }}" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-download me-2"></i> Kelas X
                </a>
                <a href="{{ route('payments.export.grade', 'XI') }}" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-download me-2"></i> Kelas XI
                </a>
                <a href="{{ route('payments.export.grade', 'XII') }}" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-download me-2"></i> Kelas XII
                </a>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Upload Pembayaran</h5>
        <form action="{{ route('payments.import', $spp->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label fw-semibold">Pilih File Excel:</label>
                <div class="input-group">
                    <input type="file" name="file" id="file" class="form-control" required>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-upload me-2"></i> Upload
                    </button>
                </div>
                <small class="text-muted">Format file harus sesuai template yang disediakan</small>
            </div>
        </form>
    </div>
</div>
        <!-- SPP Data by Class -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="fas fa-list me-2"></i>Daftar SPP</span>
               <div class="d-flex align-items-center">
    <span class="me-2">Cari Kelas:</span>
    <input type="text" class="form-control form-control-sm" id="classSearch" placeholder="Ketik nama kelas..." style="width: 200px;">
</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light position-sticky top-0" style="z-index: 1;">
            <tr>
                <th width="50">No</th>
                <th>Kelas</th>
                <th>Tahun Akademik</th>
                <th>Semester</th>
                <th>Nominal SPP</th>
                <th width="150" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sppData as $index => $spp)
                <tr class="student-list-item align-middle" data-class="{{ substr($spp->classes->class_name, 0, 2) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 ">
                            {{ $spp->classes->class_name }}
                        </span>
                    </td>
                    <td>{{ $spp->academicYear->year_name }}</td>
                    <td>{{ $spp->semester->semester_name }}</td>
                    <td class="fw-bold">Rp {{ number_format($spp->amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('payment.kelola', ['id' => $spp->id, 'month' => $currentMonth]) }}"
                                class="btn-action btn btn-sm btn-info"
                                title="Kelola Data Pembayaran">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action btn btn-sm btn-warning edit-spp"
                                    data-id="{{ $spp->id }}"
                                    data-amount="{{ $spp->amount }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn btn-sm btn-danger delete-spp"
                                    data-id="{{ $spp->id }}"
                                    title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editModalLabel">Edit Nominal SPP</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nominal SPP</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
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
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                                <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                            </div>
                            <p class="mb-0">Apakah Anda yakin ingin menghapus data SPP ini? Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
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

        // Class filter dropdown
        $('#classFilter').change(function() {
            const classId = $(this).val();
            if(classId === 'all') {
                $('tbody tr').show();
            } else {
                $('tbody tr').hide();
                $(`tr[data-class-id="${classId}"]`).show();
            }
        });
    });
      document.getElementById('classSearch').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('.student-list-item');

        rows.forEach(row => {
            const className = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
            if (className.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</body>
@endif
</html>
