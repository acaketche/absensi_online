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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                @include('components.profiladmin')
        <!-- Search Bar -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0">Kelola SPP</h2>
            <a href="{{ route('payment.listdata') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </header>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Data SPP</div>
            <div class="card-body">
               <table class="table table-hover">
                   <tr>
                       <th>Tahun Ajaran</th>
                       <td><?=$data->year_name?></td>
                   </tr>
                   <tr>
                       <th>Semester</th>
                       <td><?=$data->semester_name?></td>
                   </tr>
                   <tr>
                       <th>Kelas</th>
                       <td><?=$data->class_name?></td>
                   </tr>
                   <tr>
                       <th>Nominal SPP</th>
                       <td><?=$data->amount?></td>
                   </tr>
               </table>
            </div>
        </div>
        <div class="card">
             <div class="card-body">
                 <div class="table-responsive">
               <table class="table table-hover table-borderer">
                   <thead>
                       <tr>
                           <th>No</th>
                           <th>NIS</th>
                           <th>Nama Siswa</th>
                           <th>Pebayaran</th>
                           <th>Nominal</th>
                           <th></th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                       $n = 0;
                       $total = 0;
                       $tb=0;
                       $ts = 0;
                       foreach($siswa as $a=>$b){
                           $n++;

                           ?>
                        <tr>
                            <td><?=$n?></td>
                            <td><?=$b->id_student?></td>
                            <td><?=$b->fullname?></td>
                            <?php

                            if(isset($bayar[$b->id_student])){
                                $ts++;
                                $total += $bayar[$b->id_student]->amount;
                                ?>
                                <td><span class="badge bg-success">Sudah Bayar</span></td>
                                <td><?=(int)$data->amount?></td>
                                <td><button class="btn btn-danger"  onclick="return batal('<?=$b->id_student?>')">Batalkan</button></td>
                                <?php
                            }
                            else {
                                $tb++;
                            ?>
                            <td><span class="badge bg-danger">Belum Bayar</span></td>
                            <td>0</td>
                            <td><button class="btn btn-primary" onclick="return bayar('<?=$b->id_student?>')">Bayar</button></td>
                            <?php
                        } ?>
                        </tr>



                       <?php }?>
                   </tbody>
                   <tfoot>
                            <tr>
                                <th colspan="3">Total <th>
                                <th ><?=(int)$total?> <th>
                            </tr>
                            <tr>
                                <th colspan="3">Total Sudah Bayar <th>
                                <th ><?=(int)$ts?> Siswa <th>
                            </tr>
                            <tr>
                                <th colspan="3">Total Belum Bayar<th>
                                <th ><?=(int)$tb?> Siswa <th>
                            </tr>
                        </tfoot>
               </table>
               <div>
            </div>
        </div>
        @csrf
        <!-- Data Section -->
         </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function bayar(a){
            Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: `Lakukan pembayaran`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, lanjutkan!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
            url: "{{url('/payment/bayar')}}",
            type: "POST",
            data: {id_siswa: a, _token: $("input[name=_token]").val(),
            amount : '<?=(int)$data->amount?>',
            id_spp : '<?=$data->id?>',
            academic_year_id : '<?=$data->academic_year_id?>',
            semester_id: '<?=$data->semester_id?>'
            },
            success: function(response) {
               location.reload();
            }

        });
                    }
                });
        }


        function batal(a){
            Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: `Batalkan Pembayaran`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Batalkan!",
                    cancelButtonText: "Kembali"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
            url: "{{url('/payment/batalbayar')}}",
            type: "POST",
            data: {id_siswa: a, _token: $("input[name=_token]").val(),
            amount : '<?=(int)$data->amount?>',
            id_spp : '<?=$data->id?>',
            academic_year_id : '<?=$data->academic_year_id?>',
            semester_id: '<?=$data->semester_id?>'
            },
            success: function(response) {
               location.reload();
            }

        });
                    }
                });
        }
    </script>
</body>
</html>
@endif
