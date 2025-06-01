<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah SPP - Manajemen Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        .form-section {
            background-color: white;
            border-radius: 10px;
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e6ed;
            margin-bottom: 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(66, 103, 178, 0.2);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #365899;
            border-color: #365899;
        }

        .btn-secondary {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .header-title {
            font-weight: 600;
            color: var(--primary-color);
        }

        .currency-input {
            position: relative;
        }

        .currency-input span {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 500;
            color: var(--secondary-color);
        }

        .currency-input input {
            padding-left: 40px;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 15px;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                margin-bottom: 10px;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="header-title mb-1">Tambah SPP</h2>
            </div>
            <div>
                <a href="{{ route('payment.listdata') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-plus-circle me-2"></i>
                    <span>Form Tambah SPP</span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payment.create') }}" class="form-section">
                    @csrf

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                                        <!-- Tahun Ajaran (Hidden) -->
                        <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id ?? '' }}">

                        <!-- Semester (Hidden) -->
                        <input type="hidden" name="semester_id" value="{{ $activeSemester->id ?? '' }}">

                    <!-- Kelas -->
                    <div class="mb-3">
                        <label for="classSelect" class="form-label">Kelas</label>
                        <select id="classSelect" name="class_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id }}">
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nominal SPP -->
                    <div class="mb-3">
                        <label for="nominalInput" class="form-label">Nominal SPP</label>
                        <div class="currency-input">
                            <span>Rp</span>
                            <input type="number" id="nominalInput" name="nominal"
                                   class="form-control" placeholder="Masukkan nominal"
                                   min="1000" required>
                        </div>


                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan SPP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Format nominal input
        $('#nominalInput').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(value);
        });

        // Form validation
        $('form').submit(function(e) {
            let isValid = true;

            // Check each required field
            $(this).find('select[required], input[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Check minimum nominal
            if ($('#nominalInput').val() && parseInt($('#nominalInput').val()) < 1000) {
                $('#nominalInput').addClass('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap lengkapi semua field yang wajib diisi dengan benar',
                    confirmButtonColor: '#4267b2'
                });
            }
        });

        // Remove invalid class when user starts typing/selecting
        $('select, input').on('change keyup', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
</body>
@endif
</html>
