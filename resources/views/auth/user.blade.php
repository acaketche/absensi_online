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
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>

      /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5f5f5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 10px 35px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
        }

        .search-bar i {
            position: absolute;
            left: 10px;
            color: #666;
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
        <!-- Admin Profile Header -->
       @include('components.profiladmin')

            <!-- Data User -->
            <div class="card">
                <div class="card-header bg-primary text-white">Data User</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NIP</th>
                                <th>Email</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->id_employee }}</td>
                                <td>{{ $employee->email ?? '-' }}</td>
                                <td>{{ $employee->fullname }}</td>
                                <td>{{ $employee->role->role_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editEmployeeModal = document.getElementById('editEmployeeModal');
            editEmployeeModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var employeeId = button.getAttribute('data-employee-id');

                document.getElementById('editEmployeeForm').action = `/employees/${employeeId}`;
            });
        });
        </script>
</body>
</html>
@endif
