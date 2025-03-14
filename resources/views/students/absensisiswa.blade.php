<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding-top: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #4266B9;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #4266B9;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .btn-add {
            background-color: #4266B9;
            color: white;
        }
        .btn-add:hover {
            background-color: #365796;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Absensi Siswa</h1>
        <div class="mb-3 text-end">
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                <i class="fas fa-plus"></i> Tambah Absensi
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Siswa</th>
                        <th>ID Kelas</th>
                        <th>Tanggal Absensi</th>
                        <th>Waktu Absensi</th>
                        <th>Status</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>S001</td>
                        <td>10</td>
                        <td>2025-02-24</td>
                        <td>07:30:00</td>
                        <td>Hadir</td>
                        <td>3.5951956</td>
                        <td>98.6722227</td>
                        <td>
                            <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>S002</td>
                        <td>10</td>
                        <td>2025-02-24</td>
                        <td>07:35:00</td>
                        <td>Terlambat</td>
                        <td>3.5951956</td>
                        <td>98.6722227</td>
                        <td>
                            <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <!-- Tambahkan baris data lainnya di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Absensi -->
    <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttendanceModalLabel">Tambah Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="id_student" class="form-label">ID Siswa</label>
                            <input type="text" class="form-control" id="id_student" required>
                        </div>
                        <div class="mb-3">
                            <label for="class_id" class="form-label">ID Kelas</label>
                            <input type="number" class="form-control" id="class_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="attendance_date" class="form-label">Tanggal Absensi</label>
                            <input type="date" class="form-control" id="attendance_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="attendance_time" class="form-label">Waktu Absensi</label>
                            <input type="time" class="form-control" id="attendance_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <select class="form-select" id="status_id" required>
                                <option value="">Pilih Status</option>
                                <option value="1">Hadir</option>
                                <option value="2">Terlambat</option>
                                <option value="3">Izin</option>
                                <option value="4">Sakit</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="latitude">
                        </div>
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="longitude">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
