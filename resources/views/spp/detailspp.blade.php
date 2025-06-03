<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran SPP Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .card {
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
        .month-card {
            border-left: 4px solid #4267b2;
            transition: all 0.3s ease;
        }
        .month-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .month-paid {
            border-left: 4px solid #28a745;
        }
        .btn-bayar {
            background-color: #4267b2;
            color: white;
        }
        .btn-bayar:hover {
            background-color: #365899;
        }
        .btn-batal {
            background-color: #dc3545;
            color: white;
        }
        .btn-batal:hover {
            background-color: #c82333;
        }
        .student-info {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .badge-paid {
            background-color: #28a745;
        }
        .badge-unpaid {
            background-color: #dc3545;
        }
        .total-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Header dengan Profil Admin -->
        @include('components.profiladmin')

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('payment.listdata') }}">Kelola SPP</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pembayaran</li>
            </ol>
        </nav>

        <!-- Student Information -->
        <div class="student-info">
            <div class="row">
                <div class="col-md-6">
                    <h4>Detail Siswa</h4>
                    <table class="table table-sm">
                        <tr>
                            <th width="30%">NIS</th>
                            <td><?= $siswa->id_student ?></td>
                        </tr>
                        <tr>
                            <th>Nama Siswa</th>
                            <td><?= $siswa->fullname ?></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td><?= $data->class_name ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Informasi SPP</h4>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Tahun Ajaran</th>
                            <td><?= $data->year_name ?></td>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <td><?= $data->semester_name ?></td>
                        </tr>
                        <tr>
                            <th>Nominal per Bulan</th>
                            <td>Rp <?= number_format($data->amount, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Status Pembayaran</span>
                    <span class="badge bg-<?= $totalPaid == 12 ? 'success' : 'warning' ?>">
                        <?= $totalPaid ?>/12 Bulan
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $months = [
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];

                    foreach ($months as $month) {
                        $isPaid = in_array($month, $paidMonths);
                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="card month-card <?= $isPaid ? 'month-paid' : '' ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $month ?></h5>
                                <p class="card-text">
                                    <span class="badge <?= $isPaid ? 'badge-paid' : 'badge-unpaid' ?>">
                                        <?= $isPaid ? 'Lunas' : 'Belum Bayar' ?>
                                    </span>
                                </p>
                                <p class="card-text">
                                    Rp <?= number_format($data->amount, 0, ',', '.') ?>
                                </p>
                                <?php if ($isPaid) { ?>
                                    <button class="btn btn-batal btn-sm w-100"
                                        onclick="batalPembayaran('<?= $month ?>')">
                                        Batalkan
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-bayar btn-sm w-100"
                                        onclick="bayarSpp('<?= $month ?>')">
                                        Bayar
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="total-card">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex justify-content-between">
                        <span>Total Bulan:</span>
                        <strong>12 Bulan</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-between">
                        <span>Sudah Dibayar:</span>
                        <strong><?= $totalPaid ?> Bulan</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-between">
                        <span>Belum Dibayar:</span>
                        <strong><?= 12 - $totalPaid ?> Bulan</strong>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <span>Total Pembayaran:</span>
                        <strong>Rp <?= number_format($totalPaid * $data->amount, 0, ',', '.') ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('payment.listdata') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function bayarSpp(month) {
        Swal.fire({
            title: "Konfirmasi Pembayaran",
            text: `Anda akan melakukan pembayaran SPP untuk bulan ${month}`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#4267b2",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Bayar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/payment/bayar-bulanan') }}",
                    type: "POST",
                    data: {
                        id_siswa: '<?= $siswa->id_student ?>',
                        month: month,
                        amount: '<?= $data->amount ?>',
                        id_spp: '<?= $data->id ?>',
                        academic_year_id: '<?= $data->academic_year_id ?>',
                        semester_id: '<?= $data->semester_id ?>',
                        _token: $("input[name=_token]").val()
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: response.message,
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat memproses pembayaran",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }

    function batalPembayaran(month) {
        Swal.fire({
            title: "Konfirmasi Pembatalan",
            text: `Anda akan membatalkan pembayaran SPP untuk bulan ${month}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Batalkan",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/payment/batal-bulanan') }}",
                    type: "POST",
                    data: {
                        id_siswa: '<?= $siswa->id_student ?>',
                        month: month,
                        id_spp: '<?= $data->id ?>',
                        _token: $("input[name=_token]").val()
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: response.message,
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat memproses pembatalan",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }
</script>
</body>
</html>
