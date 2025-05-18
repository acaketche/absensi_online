<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rapor - {{ $rapor->student->fullname }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .student-info {
            background-color: #f0f8ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
@if(Auth::guard('employee')->check())
    <body>
        <div class="d-flex">
            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content -->
            <main class="flex-grow-1 p-4">
                <!-- Header dengan Profil Admin -->
                @include('components.profiladmin')


                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Edit Rapor</h4>
                        <a href="{{ route('rapor.students', $rapor->class_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>

    <div class="student-info">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2"><strong>Nama Siswa:</strong> {{ $rapor->student->fullname }}</div>
                <div class="mb-2"><strong>NIS:</strong> {{ $rapor->student->id_student }}</div>
            </div>
            <div class="col-md-6">
                <div class="mb-2"><strong>Kelas:</strong> {{ $rapor->class->class_name }}</div>
                <div class="mb-2"><strong>Status:</strong>
                    <span class="badge {{ $rapor->status_report == 'Sudah Ada' ? 'bg-success' : 'bg-danger' }}">
                        {{ $rapor->status_report }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('rapor.update', $rapor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $rapor->academic_year_id == $year->id ? 'selected' : '' }}>
                                    {{ $year->year_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Semester</label>
                        <select name="semester_id" class="form-select" required>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $rapor->semester_id == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->semester_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

              <div class="mb-3">
                <label class="form-label">Tanggal Rapor</label>
                <input type="date" name="report_date" class="form-control"
                    value="{{ old('report_date', $rapor->report_date ? \Carbon\Carbon::parse($rapor->report_date)->format('Y-m-d') : '') }}"
                    required>
            </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $rapor->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Rapor</label>
                    <select name="status_report" class="form-select" required>
                        <option value="Sudah Ada" {{ $rapor->status_report == 'Sudah Ada' ? 'selected' : '' }}>Sudah Ada</option>
                        <option value="Belum Ada" {{ $rapor->status_report == 'Belum Ada' ? 'selected' : '' }}>Belum Ada</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Rapor (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" class="form-control" name="report_file" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Maks. 5MB)</small>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endif
