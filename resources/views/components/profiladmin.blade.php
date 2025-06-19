<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-4 fw-bold mb-0"></h2>
    <div class="dropdown">
        <button class="btn p-0 border-0 bg-transparent" type="button" id="adminDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="admin-profile">
                <div class="admin-info text-end me-2">
                    <span class="admin-name">{{ Auth::guard('employee')->user()->fullname }}</span>
                    <small class="admin-role">
                        {{ Auth::guard('employee')->user()->role->role_name ?? 'Tidak ada role' }}
                    </small>
                </div>
                <div class="admin-avatar">
                    <img src="{{ Auth::guard('employee')->user()->photo ? asset('storage/' . Auth::guard('employee')->user()->photo) : asset('images/default-avatar.png') }}"
                         alt="Admin Profile" class="w-100 h-100 object-fit-cover">
                </div>
                <i class="fas fa-chevron-down text-muted ms-2"></i>
            </div>
        </button>

        <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="adminDropdownToggle">
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-user-edit me-2"></i>Edit Profil
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key me-2"></i>Ubah Password
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form id="logout-form" action="{{ route('logout.employee') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('employees.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3 position-relative mx-auto" style="width: 150px;">
                                <img id="profileImagePreview"
                                     src="{{ Auth::guard('employee')->user()->photo ? asset('storage/'.Auth::guard('employee')->user()->photo) : asset('images/default-avatar.png') }}"
                                     class="rounded-circle img-thumbnail w-100" style="height: 150px;" alt="Profile Photo">
                                <label for="photo" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" style="cursor: pointer;">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                                </label>
                            </div>
                            <small class="text-muted">Klik ikon kamera untuk mengubah foto</small>
                        </div>
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label">Nama Lengkap</label>
                                    <input id="fullname" type="text"
                                           class="form-control @error('fullname') is-invalid @enderror"
                                           name="fullname" value="{{ old('fullname', Auth::guard('employee')->user()->fullname) }}" >
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', Auth::guard('employee')->user()->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="birth_place" class="form-label">Tempat Lahir</span></label>
                                    <input id="birth_place" type="text"
                                           class="form-control @error('birth_place') is-invalid @enderror"
                                           name="birth_place" value="{{ old('birth_place', Auth::guard('employee')->user()->birth_place) }}">
                                    @error('birth_place')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Tanggal Lahir </span></label>
                                    <input id="birth_date" type="date"
                                           class="form-control @error('birth_date') is-invalid @enderror"
                                           name="birth_date" value="{{ old('birth_date', Auth::guard('employee')->user()->birth_date) }}" >
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender">
                                        <option value="L" {{ old('gender', Auth::guard('employee')->user()->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', Auth::guard('employee')->user()->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Nomor Telepon </label>
                                    <input id="phone_number" type="tel"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           name="phone_number" value="{{ old('phone_number', Auth::guard('employee')->user()->phone_number) }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
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

<!-- Modal Ubah Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="changePasswordForm" method="POST" action="{{ route('employees.profile.update-password') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="new_password"
                                   class="form-control @error('new_password') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 8 karakter, kombinasi huruf dan angka</small>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ $errors->first() }}',
        confirmButtonText: 'OK'
    });
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preview image before upload
    const photoInput = document.getElementById('photo');
    const profileImagePreview = document.getElementById('profileImagePreview');

    if (photoInput && profileImagePreview) {
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    this.value = '';
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file harus JPG, PNG, atau GIF');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Reset form when modal is closed
    $('#editProfileModal').on('hidden.bs.modal', function () {
        $('#photo').val('');
        profileImagePreview.src = "{{ Auth::guard('employee')->user()->photo ? asset('storage/'.Auth::guard('employee')->user()->photo) : asset('images/default-avatar.png') }}";
    });
});
 // Handle form submission for profile update
    $('#editProfileModal form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        $.ajax({
            url: form.action,
            method: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editProfileModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: response.message,
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    // Optional: reload page or update UI
                    window.location.reload();
                });
            },
            error: function(xhr) {
                $('#editProfileModal').modal('hide');
                let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                });
            }
        });
    });

    // Handle form submission for password change
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;

        $.ajax({
            url: form.action,
            method: form.method,
            data: $(form).serialize(),
            success: function(response) {
                $('#changePasswordModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: response.message,
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    form.reset();
                });
            },
            error: function(xhr) {
                $('#changePasswordModal').modal('hide');
                let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                });
            }
        });
    });
</script>

<style>
.admin-profile {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 5px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.admin-profile:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.admin-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #dee2e6;
    transition: border-color 0.3s ease;
}

.admin-profile:hover .admin-avatar {
    border-color: #adb5bd;
}

.admin-name {
    display: block;
    font-weight: 600;
    font-size: 0.875rem;
    color: #212529;
}

.admin-role {
    display: block;
    font-size: 0.75rem;
    color: #6c757d;
}

.img-thumbnail {
    object-fit: cover;
    border: 2px solid #dee2e6;
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-divider {
    margin: 0.3rem 0;
}

#profileImagePreview {
    transition: transform 0.3s ease;
}

#profileImagePreview:hover {
    transform: scale(1.05);
}

.swal2-popup {
    font-family: 'Poppins', sans-serif;
}
</style>
