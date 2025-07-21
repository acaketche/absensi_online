<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Piket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4266B9;
            --secondary-color: #365796;
            --light-bg: #F5F7FB;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        .table-responsive {
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }

        .table th {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .modal-content {
            border-radius: 12px;
        }

        .badge-date {
            background-color: #e9f0fd;
            color: var(--primary-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    @if(Auth::guard('employee')->check())
    <div class="d-flex">
        @include('components.sidebar')

        <main class="flex-grow-1 p-4">
            @include('components.profiladmin')

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Jadwal Piket</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
                        <i class="fas fa-plus me-2"></i>Tambah Jadwal
                    </button>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $index => $schedule)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge-date">
                                                {{ \Carbon\Carbon::parse($schedule->picket_date)->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td>{{ $schedule->employee->fullname }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editScheduleModal"
                                                        data-id="{{ $schedule->id }}"
                                                        data-employee_id="{{ $schedule->employee_id }}"
                                                        data-picket_date="{{ $schedule->picket_date }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('picket.destroy', $schedule->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Schedule Modal -->
            <div class="modal fade" id="createScheduleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('picket.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Jadwal Piket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                             <div class="mb-3">
                                <label class="form-label">Pegawai</label>
                                <select name="employee_id" class="form-select" required>
                                    <option value="">Pilih Pegawai</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id_employee }}">
                                            {{ $employee->fullname }}
                                            ({{ $employee->position->name ?? '-' }})
                                            @if($employee->kelasAsuh)
                                            {{ $employee->kelasAsuh->class_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Piket</label>
                                    <input type="date" name="picket_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Schedule Modal -->
            <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="editScheduleForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Jadwal Piket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Pegawai</label>
                                    <select name="employee_id" id="edit_employee_id" class="form-select" required>
                                         @foreach($employees as $employee)
                                        <option value="{{ $employee->id_employee }}">
                                            {{ $employee->fullname }}
                                            ({{ $employee->position->name ?? '-' }})
                                            @if($employee->kelasAsuh)
                                            {{ $employee->kelasAsuh->class_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Piket</label>
                                    <input type="date" name="picket_date" id="edit_picket_date" class="form-control" required>
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

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const editModal = document.getElementById('editScheduleModal');

                    editModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id');
                        const employeeId = button.getAttribute('data-employee_id');
                        const picketDate = button.getAttribute('data-picket_date');

                        const form = editModal.querySelector('#editScheduleForm');
                        const employeeSelect = editModal.querySelector('#edit_employee_id');
                        const dateInput = editModal.querySelector('#edit_picket_date');

                        form.action = `/picket/${id}`;
                        employeeSelect.value = employeeId;
                        dateInput.value = picketDate;
                    });
                });
            </script>
        </main>
    </div>
    @endif
</body>
</html>
