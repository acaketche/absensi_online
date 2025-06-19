<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail SPP - {{ $spp->classes->class_name ?? 'Kelas' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4267b2;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 15px 20px;
        }

        .payment-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .payment-status.status-paid {
            color: green;
            font-weight: bold;
        }

        .payment-status.status-unpaid {
            color: red;
            font-weight: bold;
        }

        .payment-summary {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .summary-value {
            font-weight: 600;
        }

        .student-row:hover {
            background-color: rgba(66, 103, 178, 0.05);
        }

        .action-btns .btn {
            padding: 5px 10px;
            font-size: 0.875rem;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
        }

        .filter-section {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .month-selector {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .month-btn {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .month-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .month-btn.active {
            background-color: var(--primary-color);
            color: white;
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

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h2 class="mb-1 text-primary">Detail SPP - {{ $spp->classes->class_name ?? 'Kelas' }}</h2>
            </div>
            <div>
                <a href="{{ route('payment.listdata') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <h5 class="mb-3">Pilih Bulan Pembayaran</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach($months as $key => $monthData)
                    <form method="GET" action="{{ request()->url() }}">
                        <input type="hidden" name="month" value="{{ $key }}">
                        <button type="submit"
                                class="btn month-btn {{ $key == $currentMonth ? 'btn-primary' : 'btn-outline-primary' }} shadow-sm">
                            {{ $monthData['name'] }} {{ $monthData['year'] }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="btn-icon btn-success rounded-circle me-3 p-3">
                    <i class="fas fa-download fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-1">Download Template</h5>
                    <p class="text-muted small mb-2">Unduh template untuk kelas {{ $spp->classes->class_name }}</p>
                    <a href="{{ route('payment.template', $spp->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="btn-icon btn-primary rounded-circle me-3 p-3">
                    <i class="fas fa-file-import fa-lg"></i>
                </div>
                <div>
                    <h5 class="card-title mb-1">Import Pembayaran</h5>
                    <p class="text-muted small mb-2">Upload file Excel yang sudah diisi</p>
                    <form action="{{ route('payments.import', $spp->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="form-control form-control-sm" id="customFile" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload me-1"></i> Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
     <!-- SPP Info and Payment Summary -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white py-3 px-4 rounded-top">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="fas fa-money-check-alt me-2"></i>Informasi SPP</h6>
            <span class="badge bg-light text-primary fw-semibold">
                {{ $spp->academicYear->year_name ?? '-' }} - Semester {{ $spp->semester->semester_name ?? '-' }}
            </span>
        </div>
    </div>

    <div class="card-body px-4 py-4">
        <!-- Nominal SPP -->
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="mb-0 fs-6">
                    <strong><i class="fas fa-cash-register me-1 text-primary"></i>Nominal SPP:</strong>
                    Rp {{ number_format($spp->amount, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <hr>

        <!-- Payment Summary -->
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="bg-light rounded p-3 shadow-sm border-start border-4 border-success">
                    <div class="fw-semibold text-secondary"><i class="fas fa-wallet me-1"></i>Total Pembayaran</div>
                    <div class="fs-5 text-success fw-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="bg-light rounded p-3 shadow-sm border-start border-4 border-primary">
                    <div class="fw-semibold text-secondary"><i class="fas fa-user-check me-1"></i>Sudah Bayar</div>
                    <div class="fs-5 text-primary fw-bold">{{ $paidStudents }} Siswa</div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="bg-light rounded p-3 shadow-sm border-start border-4 border-danger">
                    <div class="fw-semibold text-secondary"><i class="fas fa-user-times me-1"></i>Belum Bayar</div>
                    <div class="fs-5 text-danger fw-bold">{{ $unpaidStudents }} Siswa</div>
                </div>
            </div>
        </div>
    </div>
</div>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            @if(session('imported_spp'))
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Student List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Daftar Siswa</span>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Cari siswa..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light position-sticky top-0" style="z-index: 1;">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status Pembayaran</th>
                                <th>Nominal</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            @php
                                $payment = $payments[$student->id_student] ?? null;
                                $status = $payment ? 'Lunas' : 'Belum Lunas';
                                $statusClass = $payment ? 'status-paid' : 'status-unpaid';
                            @endphp
                            <tr class="student-row">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->id_student }}</td>
                                <td>{{ $student->fullname }}</td>
                                <td>
                                    <span class="payment-status {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment)
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($spp->amount, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    @if($payment)
                                        {{ $payment->created_at->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="action-btns">
                                    @if($payment)
                                        <button class="btn btn-sm btn-danger" onclick="batalBayar('{{ $student->id_student }}')">
                                            <i class="fas fa-times"></i> Batalkan
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success" onclick="bayarSPP('{{ $student->id_student }}')">
                                            <i class="fas fa-check"></i> Bayar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hidden form fields -->
        <input type="hidden" id="spp-id" value="{{ $spp->id }}">
        <input type="hidden" id="spp-amount" value="{{ $spp->amount }}">
        <input type="hidden" id="academic-year-id" value="{{ $spp->academic_year_id }}">
        <input type="hidden" id="semester-id" value="{{ $spp->semester_id }}">
        <input type="hidden" id="current-month" value="{{ $currentMonth }}">
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Search functionality
        $('#searchInput').keyup(function() {
            const searchText = $(this).val().toLowerCase();
            $('.student-row').each(function() {
                const studentName = $(this).find('td:eq(2)').text().toLowerCase();
                const studentId = $(this).find('td:eq(1)').text().toLowerCase();
                if(studentName.includes(searchText) || studentId.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    function bayarSPP(studentId) {
        const sppId = $('#spp-id').val();
        const amount = $('#spp-amount').val();
        const academicYearId = $('#academic-year-id').val();
        const semesterId = $('#semester-id').val();
        const month = $('#current-month').val();

        Swal.fire({
            title: "Konfirmasi Pembayaran",
            html: `<p>Anda akan mencatat pembayaran SPP untuk siswa:</p>
                  <p><strong>${studentId}</strong></p>
                  <p>Nominal: <strong>Rp ${parseInt(amount).toLocaleString('id-ID')}</strong></p>
                  <p>Bulan: <strong>${getMonthName(month)}</strong></p>`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Konfirmasi Pembayaran",
            cancelButtonText: "Batal",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('payment.bayar') }}",
                    type: "POST",
                    data: {
                        id_siswa: studentId,
                        _token: "{{ csrf_token() }}",
                        amount: amount,
                        id_spp: sppId,
                        academic_year_id: academicYearId,
                        semester_id: semesterId,
                        month: month
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }
                    return response;
                }).catch(error => {
                    let errorMsg = error.responseJSON?.message || error.statusText;
                    if (error.responseJSON?.details) {
                        errorMsg += `<br><small>Detail: ${JSON.stringify(error.responseJSON.details)}</small>`;
                    }
                    Swal.showValidationMessage(`Request failed: ${errorMsg}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Pembayaran telah dicatat",
                    icon: "success"
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function batalBayar(studentId) {
        const sppId = $('#spp-id').val();
        const academicYearId = $('#academic-year-id').val();
        const semesterId = $('#semester-id').val();
        const month = $('#current-month').val();

        Swal.fire({
            title: "Konfirmasi Pembatalan",
            html: `<p>Anda akan membatalkan pembayaran SPP untuk siswa:</p>
                  <p><strong>${studentId}</strong></p>
                  <p>Bulan: <strong>${getMonthName(month)}</strong></p>`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Batalkan!",
            cancelButtonText: "Kembali",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('payment.batalbayar') }}",
                    type: "POST",
                    data: {
                        id_siswa: studentId,
                        _token: "{{ csrf_token() }}",
                        id_spp: sppId,
                        academic_year_id: academicYearId,
                        semester_id: semesterId,
                        month: month
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error.responseJSON?.message || error.statusText}`
                    );
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Pembayaran telah dibatalkan",
                    icon: "success"
                }).then(() => {
                    location.reload();
                });
            }
        });
    }

    function getMonthName(monthNumber) {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return months[monthNumber - 1] || 'Bulan tidak diketahui';
    }
</script>
</body>
@endif
</html>
