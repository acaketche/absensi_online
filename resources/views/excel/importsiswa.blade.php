<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
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

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-import"></i> Import Data Siswa
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border p-4 rounded">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-info-circle"></i> Petunjuk Import
                                </h5>
                                <ol class="pl-3">
                                    <li class="mb-2">Download template Excel untuk memastikan format sesuai</li>
                                    <li class="mb-2">Pastikan data mengikuti kolom yang tersedia</li>
                                    <li class="mb-2">File harus berformat .xlsx atau .csv</li>
                                    <li class="mb-2">Maksimal ukuran file 2MB</li>
                                    <li>Kolom dengan tanda bintang (*) wajib diisi</li>
                                </ol>

                                <div class="mt-4">
                                    <a href="{{ route('students.import.template') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-file-download"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border p-4 rounded">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-upload"></i> Upload File Excel
                                </h5>

                                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                    @csrf

                                    <div class="form-group">
                                        <label for="excel_file" class="font-weight-bold">Pilih File Excel*</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('excel_file') is-invalid @enderror"
                                                   id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                                            <label class="custom-file-label" for="excel_file">Pilih file...</label>
                                            @error('excel_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="form-text text-muted">
                                            Format yang didukung: .xlsx, .xls, .csv (Maks. 2MB)
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Opsi Import</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="skip_duplicates" name="skip_duplicates" checked>
                                            <label class="custom-control-label" for="skip_duplicates">Lewati data duplikat (berdasarkan ID Siswa)</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="update_existing" name="update_existing">
                                            <label class="custom-control-label" for="update_existing">Update data yang sudah ada</label>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-upload"></i> Import Data
                                        </button>
                                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary ml-2">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>


                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-table"></i> Preview Data ({{ count($previewData) }} baris)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>ID Siswa</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Tempat Lahir</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>No. HP Orang Tua</th>
                                                    <th>Kelas</th>
                                                    <th>Tahun Ajaran</th>
                                                    <th>Semester</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($previewData as $row)
                                                <tr class="{{ $row['status'] !== 'Valid' ? 'table-warning' : '' }}">
                                                    <td>{{ $row['id_student'] }}</td>
                                                    <td>{{ $row['fullname'] }}</td>
                                                    <td>{{ $row['birth_place'] }}</td>
                                                    <td>{{ $row['birth_date'] }}</td>
                                                    <td>{{ $row['gender'] }}</td>
                                                    <td>{{ $row['parent_phonecell'] }}</td>
                                                    <td>{{ $row['class_id'] }}</td>
                                                    <td>{{ $row['academic_year_id'] }}</td>
                                                    <td>{{ $row['semester_id'] }}</td>
                                                    <td>
                                                        @if($row['status'] === 'Valid')
                                                            <span class="badge badge-success">Valid</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ $row['status'] }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3 text-right">
                                        <form action="{{ route('students.import.confirm') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="filename" value="{{ $filename }}">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Konfirmasi Import
                                            </button>
                                            <a href="{{ route('students.import') }}" class="btn btn-outline-secondary ml-2">
                                                <i class="fas fa-times"></i> Batalkan
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Show filename when file selected
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Form submission handling
    $('#importForm').on('submit', function(e) {
        $('#submitBtn').prop('disabled', true);
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
    });

    // Show error messages if any
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Import',
            html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonText: 'Mengerti'
        });
    @endif

    // Show success message if redirected from successful import
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('students.index') }}";
            }
        });
    @endif
});
</script>
</body>
</html>
