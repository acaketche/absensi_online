<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pembayaran SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4267b2;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
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

        .badge-paid {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-unpaid {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .student-row {
            transition: all 0.2s;
        }

        .student-row:hover {
            background-color: rgba(66, 103, 178, 0.05);
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

        .total-paid {
            color: var(--success-color);
        }

        .total-unpaid {
            color: var(--danger-color);
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .btn-pay {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-cancel {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .amount-cell {
            font-weight: 600;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 12px 15px;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .btn-action {
                padding: 6px 10px;
                font-size: 0.75rem;
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

        <!-- Page Header -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fs-4 fw-bold mb-0">Kelola Pembayaran SPP</h2>
            </div>
            <a href="{{ route('payment.listdata') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </header>

        <!-- SPP Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Informasi SPP</span>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Tahun Ajaran: <?= $spp->academicyear->year_name ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted">Kelas</label>
                            <h5><?= $spp->classes->class_name ?></h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted">Semester</label>
                            <h5><?= $spp->semester->semester_name ?></h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nominal SPP</label>
                            <h5>Rp <?= number_format($spp->amount, 0, ',', '.') ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary">
            <div class="row">
                <div class="col-md-4">
                    <div class="summary-item">
                        <span class="summary-label">Total Pembayaran</span>
                        <span class="summary-value">Rp <?= number_format($totalAmount, 0, ',', '.') ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-item">
                        <span class="summary-label">Sudah Bayar</span>
                        <span class="summary-value total-paid"><?= $paidStudents ?> Siswa</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-item">
                        <span class="summary-label">Belum Bayar</span>
                        <span class="summary-value total-unpaid"><?= $unpaidStudents ?> Siswa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Payment Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Daftar Siswa</span>
                    <div class="input-group" style="width: 250px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari siswa..." id="searchInput">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIPD</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $n = 0; @endphp
                            @foreach($students as $student)
                                @php
                                    $n++;
                                    $hasPaid = isset($bayar[$student->id_student]);
                                @endphp
                                <tr class="student-row">
                                    <td><?= $n ?></td>
                                    <td><?= $student->id_student ?></td>
                                    <td><?= $student->fullname ?></td>
                                    <td>
                                        @if($hasPaid)
                                            <span class="badge-paid">
                                                <i class="fas fa-check-circle me-1"></i> Sudah Bayar
                                            </span>
                                        @else
                                            <span class="badge-unpaid">
                                                <i class="fas fa-times-circle me-1"></i> Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="amount-cell">
                                        @if($hasPaid)
                                            Rp <?= number_format($data->amount, 0, ',', '.') ?>
                                        @else
                                            Rp 0
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasPaid)
                                            <button class="btn btn-cancel btn-action text-white"
                                                    onclick="return batal('<?= $student->id_student ?>')">
                                                <i class="fas fa-ban me-1"></i> Batalkan
                                            </button>
                                        @else
                                            <button class="btn btn-pay btn-action text-white"
                                                    onclick="return bayar('<?= $student->id_student ?>')">
                                                <i class="fas fa-money-bill-wave me-1"></i> Bayar
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

        @csrf
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search functionality
    $(document).ready(function() {
    // Search functionality for student list
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

function bayar(studentId) {
    const sppId = $('#spp-id').val();
    const amount = $('#spp-amount').val();
    const academicYearId = $('#academic-year-id').val();
    const semesterId = $('#semester-id').val();

    Swal.fire({
        title: "Konfirmasi Pembayaran",
        html: `<p>Anda akan mencatat pembayaran SPP untuk siswa:</p>
              <p><strong>${studentId}</strong></p>
              <p>Nominal: <strong>Rp ${formatCurrency(amount)}</strong></p>`,
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
                    semester_id: semesterId
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
                text: "Pembayaran telah dicatat",
                icon: "success"
            }).then(() => {
                location.reload();
            });
        }
    });
}

function batal(studentId) {
    const sppId = $('#spp-id').val();
    const academicYearId = $('#academic-year-id').val();
    const semesterId = $('#semester-id').val();

    Swal.fire({
        title: "Konfirmasi Pembatalan",
        html: `<p>Anda akan membatalkan pembayaran SPP untuk siswa:</p>
              <p><strong>${studentId}</strong></p>`,
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
                    semester_id: semesterId
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

// Helper function to format currency
function formatCurrency(amount) {
    return parseInt(amount).toLocaleString('id-ID');
}
</script>
</body>
@endif
</html>
