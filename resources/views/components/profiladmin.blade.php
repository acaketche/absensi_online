<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-4 fw-bold mb-0"></h2>
    <div class="dropdown">
        <div class="admin-profile" id="adminDropdownToggle">
            <div class="admin-info text-end me-2">
                <span class="admin-name">{{ Auth::guard('employee')->user()->fullname }}</span>
                <small class="admin-role">
                    {{ Auth::guard('employee')->user()->role->role_name ?? 'Tidak ada role' }}
                </small>
            </div>
            <div class="admin-avatar">
                <img src="{{ Auth::guard('employee')->user()->photo ? asset('storage/' . Auth::guard('employee')->user()->photo) : 'https://via.placeholder.com/150' }}"
                     alt="Admin Profile" class="w-100 h-100 object-fit-cover">
            </div>
            <i class="fas fa-chevron-down text-muted ms-2"></i>
        </div>

        <ul class="dropdown-menu dropdown-menu-end mt-2" id="adminDropdownMenu">
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key"></i> Ubah Password
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form id="logout-form" action="{{ route('logout.employee') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Modal Ubah Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @if(isset($token))
        <input type="hidden" name="token" value="{{ $token }}">
        @endif
        <div class="modal-header">
          <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="current_password" class="form-label">Password Lama</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('adminDropdownToggle');
        const menu = document.getElementById('adminDropdownMenu');

        if (toggle && menu) {
            // Toggle dropdown saat klik profil
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.toggle('show');
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function (e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });

            // Tutup dropdown saat klik link di dalam dropdown
            const dropdownLinks = menu.querySelectorAll('.dropdown-item');
            dropdownLinks.forEach(link => {
                link.addEventListener('click', function () {
                    menu.classList.remove('show');
                });
            });
        }
    });
</script>

<style>
    .dropdown-menu.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
        transition: all 0.2s ease-in-out;
    }

    .admin-profile {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .admin-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }

    .admin-name {
        display: block;
        font-weight: 600;
        font-size: 14px;
    }

    .admin-role {
        display: block;
        font-size: 12px;
        color: #6c757d;
    }
</style>
