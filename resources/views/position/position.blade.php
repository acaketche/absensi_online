<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jabatan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }
        .table {
            margin-bottom: 0;
            width: 100%;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-top: none;
            border-bottom: 2px solid #e9ecef;
            padding: 12px 16px;
        }
        .table td {
            padding: 12px 16px;
            vertical-align: middle;
            border-top: 1px solid #e9ecef;
            white-space: nowrap;
        }
        .table tr:hover {
            background-color: #f8f9fa;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 4px;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .action-buttons .btn {
            margin-left: 4px;
        }
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .alert {
            border-radius: 8px;
        }
        .table-container {
            padding: 0;
            border-radius: 8px;
            overflow-x: auto;
        }
        .empty-state {
            padding: 40px 0;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #dee2e6;
        }
       /* Scroll vertikal dan horizontal */
.table-scroll-vertical {
    max-height: 800px; /* Atur sesuai kebutuhan tinggi tampilan */
    overflow-y: auto;
    overflow-x: auto;
}

/* Opsional: Tambahkan scrollbar yang lebih halus */
.table-scroll-vertical::-webkit-scrollbar {
    width: 8px;
}
.table-scroll-vertical::-webkit-scrollbar-thumb {
    background-color: #dee2e6;
    border-radius: 4px;
}

    </style>
</head>
<body>
    @if(Auth::guard('employee')->check())
    <div class="d-flex">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <main class="flex-grow-1 p-4">
            @include('components.profiladmin')

          <div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h4 class="mb-0">Daftar Jabatan</h4>
            <div class="ms-auto d-flex align-items-center gap-2" style="flex: 1; max-width: 600px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari Jabatan..." style="flex: 1;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPositionModal">
                    <i class="fas fa-plus me-2"></i>Tambah Jabatan
                </button>
            </div>
        </div>
    </div>
</div>

                <div class="row justify-content-center">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-briefcase me-2"></i>Daftar Jabatan
                        </div>
                        <div class="card-body p-0">
                            @if($positions->isEmpty())
                                <div class="empty-state p-4 text-center">
                                    <i class="fas fa-briefcase fa-3x mb-3 text-muted"></i>
                                    <h5 class="mb-2">Tidak Ada Data Jabatan</h5>
                                    <p class="text-muted mb-4">Silahkan tambahkan jabatan baru menggunakan tombol di atas</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPositionModal">
                                        <i class="fas fa-plus me-1"></i> Tambah Jabatan
                                    </button>
                                </div>
                            @else
                               <div class="table-responsive-container">
    <div class="table-container table-scroll-vertical">
        <table class="table table-hover table-bordered mb-0" id="positionTable">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="70%">Nama Jabatan</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($positions as $position)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $position->name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPositionModal"
                                    data-id="{{ $position->id }}"
                                    data-name="{{ $position->name }}"
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('positions.destroy', $position->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-position"
                                        data-position-id="{{ $position->id }}"
                                        data-position-name="{{ $position->name }}"
                                        title="Hapus">
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Position Modal -->
            <div class="modal fade" id="createPositionModal" tabindex="-1" aria-labelledby="createPositionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('positions.store') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-light">
                                <h5 class="modal-title" id="createPositionModalLabel">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Jabatan Baru
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Jabatan</label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama jabatan">
                                </div>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Position Modal -->
            <div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="editPositionForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-light">
                                <h5 class="modal-title" id="editPositionModalLabel">
                                    <i class="fas fa-edit me-2"></i>Edit Jabatan
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Nama Jabatan</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required placeholder="Masukkan nama jabatan">
                                </div>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletePositionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus jabatan <strong id="positionNameToDelete"></strong>?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deletePositionForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Handle edit modal
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editPositionModal');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = editModal.querySelector('#editPositionForm');
                const nameInput = editModal.querySelector('#edit_name');

                nameInput.value = name;
                form.action = `/positions/${id}`;
            });

            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-position');
            const deleteModal = new bootstrap.Modal(document.getElementById('deletePositionModal'));
            const positionNameSpan = document.getElementById('positionNameToDelete');
            const deleteForm = document.getElementById('deletePositionForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const positionId = this.getAttribute('data-position-id');
                    const positionName = this.getAttribute('data-position-name');

                    positionNameSpan.textContent = positionName;
                    deleteForm.action = `/positions/${positionId}`;

                    deleteModal.show();
                });
            });

            // Initialize horizontal scroll indicators
            function updateScrollIndicators() {
                const containers = document.querySelectorAll('.table-responsive-container');
                containers.forEach(container => {
                    const table = container.querySelector('.table-container');
                    if (table.scrollWidth > container.clientWidth) {
                        container.classList.add('scrollable');
                    } else {
                        container.classList.remove('scrollable');
                    }
                });
            }

            // Run on load and resize
            window.addEventListener('load', updateScrollIndicators);
            window.addEventListener('resize', updateScrollIndicators);
        });
            const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#positionTable tbody tr');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const jabatan = row.querySelectorAll('td')[1].textContent.toLowerCase();
                row.style.display = jabatan.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
@endif
