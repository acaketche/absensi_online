<div class="profile-container d-flex justify-content-between align-items-center mb-5 mt-3">
    <h2 class="profile-title fs-4 fw-bold mb-0"></h2>
    <div class="profile-dropdown dropdown">
        <button class="profile-toggle btn p-0 border-0 bg-transparent" type="button" id="profileDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="profile-header">
                <div class="profile-info text-end me-3">
                    <span class="profile-name">{{ Auth::guard('employee')->user()->fullname }}</span>
                    <small class="profile-role">
                        {{ Auth::guard('employee')->user()->role->role_name ?? 'Tidak ada role' }}
                    </small>
                </div>
               @if(Auth::guard('employee')->user()->photo)
    <div class="profile-avatar">
        <img src="{{ asset('storage/' . Auth::guard('employee')->user()->photo) }}"
             alt="Profile Picture" class="w-100 h-100 object-fit-cover">
    </div>
@endif
                <i class="profile-caret fas fa-chevron-down text-muted ms-2"></i>
            </div>
        </button>

        <ul class="profile-menu dropdown-menu dropdown-menu-end mt-3" aria-labelledby="profileDropdownToggle">
            <li>
                <a class="profile-item dropdown-item d-flex align-items-center py-3" href="#" data-bs-toggle="modal" data-bs-target="#profileEditModal">
                    <i class="profile-icon fas fa-user-edit me-3 fs-5"></i>
                    <div>
                        <div class="profile-item-title fw-semibold">Edit Profil</div>
                        <small class="profile-item-desc text-muted">Ubah data pribadi Anda</small>
                    </div>
                </a>
            </li>
            <li>
                <a class="profile-item dropdown-item d-flex align-items-center py-3" href="#" data-bs-toggle="modal" data-bs-target="#profilePasswordModal">
                    <i class="profile-icon fas fa-key me-3 fs-5"></i>
                    <div>
                        <div class="profile-item-title fw-semibold">Ubah Password</div>
                        <small class="profile-item-desc text-muted">Ganti kata sandi akun</small>
                    </div>
                </a>
            </li>
            <li><hr class="profile-divider dropdown-divider my-2"></li>
            <li>
                <form id="profile-logout-form" action="{{ route('logout.employee') }}" method="POST">
                    @csrf
                    <button type="submit" class="profile-item dropdown-item d-flex align-items-center py-3">
                        <i class="profile-icon fas fa-sign-out-alt me-3 fs-5"></i>
                        <div>
                            <div class="profile-item-title fw-semibold">Logout</div>
                            <small class="profile-item-desc text-muted">Keluar dari sistem</small>
                        </div>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- Modal Edit Profil -->
<div class="profile-modal modal fade" id="profileEditModal" tabindex="-1" aria-labelledby="profileEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('employees.profile.update') }}" enctype="multipart/form-data" class="profile-form">
                @csrf
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fs-5" id="profileEditModalLabel">Edit Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                         <div class="profile-image-container position-relative mx-auto">
    @if(Auth::guard('employee')->user()->photo)
        <img id="profileImagePreview"
            src="{{ asset('storage/' . Auth::guard('employee')->user()->photo) }}"
            class="profile-image-preview rounded-circle w-100"
            alt="Profile Photo">
    @endif

    <label for="profile-photo-input"
        class="profile-image-upload-btn btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle p-2">
        <i class="fas fa-camera m-0"></i>
        <input type="file" id="profile-photo-input" name="photo" class="d-none" accept="image/*">
    </label>
</div>
                            <small class="profile-image-hint text-muted mt-2 d-block">Ukuran maksimal 2MB (JPG/PNG)</small>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="profile-fullname" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input id="profile-fullname" type="text"
                                           class="form-control @error('fullname') is-invalid @enderror"
                                           name="fullname" value="{{ old('fullname', Auth::guard('employee')->user()->fullname) }}" required>
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input id="profile-email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', Auth::guard('employee')->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-birth-place" class="form-label fw-medium">Tempat Lahir</label>
                                    <input id="profile-birth-place" type="text"
                                           class="form-control @error('birth_place') is-invalid @enderror"
                                           name="birth_place" value="{{ old('birth_place', Auth::guard('employee')->user()->birth_place) }}">
                                    @error('birth_place')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-birth-date" class="form-label fw-medium">Tanggal Lahir</label>
                                    <input id="profile-birth-date" type="date"
                                           class="form-control @error('birth_date') is-invalid @enderror"
                                           name="birth_date" value="{{ old('birth_date', Auth::guard('employee')->user()->birth_date) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-gender" class="form-label fw-medium">Jenis Kelamin</label>
                                    <select id="profile-gender" class="form-select @error('gender') is-invalid @enderror" name="gender">
                                        <option value="L" {{ old('gender', Auth::guard('employee')->user()->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', Auth::guard('employee')->user()->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-phone" class="form-label fw-medium">Nomor Telepon</label>
                                    <input id="profile-phone" type="tel"
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
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="profile-cancel-btn btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="profile-save-btn btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah Password -->
<div class="profile-modal modal fade" id="profilePasswordModal" tabindex="-1" aria-labelledby="profilePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="profile-password-form" method="POST" action="{{ route('employees.profile.update-password') }}" class="profile-form">
                @csrf
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fs-5" id="profilePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="profile-password-field mb-4">
                        <label for="profile-current-password" class="form-label fw-medium">Password Lama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="profile-current-password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            <button type="button" class="profile-password-toggle btn btn-outline-secondary" data-target="profile-current-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-password-field mb-4">
                        <label for="profile-new-password" class="form-label fw-medium">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="profile-new-password"
                                   class="form-control @error('new_password') is-invalid @enderror" required>
                            <button type="button" class="profile-password-toggle btn btn-outline-secondary" data-target="profile-new-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="profile-password-hint text-muted">Minimal 8 karakter, kombinasi huruf dan angka</small>
                    </div>

                    <div class="profile-password-field mb-0">
                        <label for="profile-confirm-password" class="form-label fw-medium">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" id="profile-confirm-password"
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror" required>
                            <button type="button" class="profile-password-toggle btn btn-outline-secondary" data-target="profile-confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer bg-light py-3">
                    <button type="button" class="profile-cancel-btn btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="profile-save-btn btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        customClass: {
            popup: 'rounded-lg'
        }
    });
</script>
@endif
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ $errors->first() }}',
        confirmButtonText: 'OK',
        customClass: {
            popup: 'rounded-lg'
        }
    });
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Profile image preview
    const profilePhotoInput = document.getElementById('profile-photo-input');
    const profileImagePreview = document.getElementById('profileImagePreview');

    if (profilePhotoInput && profileImagePreview) {
        profilePhotoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran file terlalu besar',
                        text: 'Maksimal ukuran file adalah 2MB',
                        customClass: {
                            popup: 'rounded-lg'
                        }
                    });
                    this.value = '';
                    return;
                }

                // Check file format
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format tidak valid',
                        text: 'Format file harus JPG, PNG, atau GIF',
                        customClass: {
                            popup: 'rounded-lg'
                        }
                    });
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.profile-password-toggle').forEach(button => {
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
    $('#profileEditModal').on('hidden.bs.modal', function () {
        $('#profile-photo-input').val('');
        profileImagePreview.src = "{{ Auth::guard('employee')->user()->photo ? asset('storage/'.Auth::guard('employee')->user()->photo) : asset('images/default-avatar.png') }}";
    });

    // Handle form submission for profile update
    $('.profile-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const isProfileForm = form.id === 'profile-password-form' ? false : true;
        const formData = isProfileForm ? new FormData(form) : $(form).serialize();

        $.ajax({
            url: form.action,
            method: form.method,
            data: formData,
            processData: isProfileForm ? false : true,
            contentType: isProfileForm ? false : 'application/x-www-form-urlencoded',
            success: function(response) {
                $(form).closest('.modal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: response.message,
                    confirmButtonColor: '#3085d6',
                    customClass: {
                        popup: 'rounded-lg'
                    }
                }).then(() => {
                    if (isProfileForm) {
                        window.location.reload();
                    } else {
                        form.reset();
                    }
                });
            },
            error: function(xhr) {
                $(form).closest('.modal').modal('hide');
                let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                    customClass: {
                        popup: 'rounded-lg'
                    }
                });
            }
        });
    });
});
</script>

<style>
/* Profile Container */
.profile-container {
    position: relative;
    z-index: 1000;
}

/* Profile Header */
.profile-header {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 50px;
    transition: all 0.3s ease;
    background-color: rgba(0, 0, 0, 0.03);
}

.profile-header:hover {
    background-color: rgba(0, 0, 0, 0.08);
}

.profile-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

.profile-header:hover .profile-avatar {
    border-color: #ced4da;
    transform: scale(1.05);
}

.profile-name {
    display: block;
    font-weight: 600;
    font-size: 1rem;
    color: #212529;
    line-height: 1.2;
}

.profile-role {
    display: block;
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.3;
}

.profile-caret {
    transition: transform 0.3s ease;
}

.profile-dropdown.show .profile-caret {
    transform: rotate(180deg);
}

/* Profile Dropdown Menu */
.profile-menu {
    width: 280px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    border-radius: 12px;
    overflow: hidden;
    padding: 8px 0;
}

.profile-item {
    padding: 12px 16px;
    border-radius: 8px;
    margin: 0 8px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
}

.profile-item:hover {
    background-color: #f8f9fa;
    transform: translateX(4px);
}

.profile-icon {
    width: 24px;
    text-align: center;
    font-size: 1.1rem;
    color: #495057;
}

.profile-item-title {
    font-size: 0.95rem;
}

.profile-item-desc {
    font-size: 0.8rem;
    display: block;
    line-height: 1.3;
}

.profile-divider {
    margin: 4px 0;
    border-color: rgba(0, 0, 0, 0.05);
}

/* Profile Modals */
.profile-modal .modal-content {
    border-radius: 16px;
    overflow: hidden;
}

.profile-modal .modal-header {
    padding: 16px 24px;
}

.profile-modal .modal-title {
    font-weight: 600;
}

/* Profile Image Upload */
.profile-image-container {
    width: 180px;
}

.profile-image-preview {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border: 3px solid #e9ecef;
    transition: all 0.3s;
}

.profile-image-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.profile-image-upload-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-image-hint {
    font-size: 0.8rem;
}

/* Profile Form Elements */
.profile-form .form-label {
    font-weight: 500;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.profile-form .form-control,
.profile-form .form-select {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.profile-form .form-control:focus,
.profile-form .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

/* Profile Password Fields */
.profile-password-field {
    position: relative;
}

.profile-password-toggle {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    padding: 0 12px;
}

.profile-password-hint {
    font-size: 0.8rem;
    display: block;
    margin-top: 6px;
}

/* Profile Buttons */
.profile-cancel-btn,
.profile-save-btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.profile-save-btn {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.profile-save-btn:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.profile-cancel-btn {
    border-color: #dee2e6;
}

.profile-cancel-btn:hover {
    background-color: #f8f9fa;
}
</style>
