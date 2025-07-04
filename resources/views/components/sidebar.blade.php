<div class="sidebar">
    <div class="logo">
        <div class="logo-icon">E</div>
        <div class="logo-text">SCHOOL</div>
    </div>
    <nav>
        @php
            $role = Auth::guard('employee')->user()->role->role_name ?? '';
        @endphp
        {{--  Super Admin --}}
        @if($role === 'Super Admin')
            <a href="{{ route('dashboard.admin') }}" class="nav-item text-white d-block mb-2" data-page="dashboard">
                <i class="fas fa-home me-2"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('users.index')}}" class="nav-item text-white d-block mb-2" data-page="data-user">
                <i class="fas fa-users me-2"></i>
                <span class="nav-text">Data User</span>
            </a>
            <a href="{{ route('students.index')}}" class="nav-item text-white d-block mb-2" data-page="data-siswa">
                <i class="fas fa-user-graduate me-2"></i>
                <span class="nav-text">Data Siswa</span>
            </a>
            <a href="{{ route('employees.index')}}" class="nav-item text-white d-block mb-2" data-page="data-pegawai">
                <i class="fas fa-chalkboard-teacher me-2"></i>
                <span class="nav-text">Data Pegawai</span>
            </a>

            {{-- Dropdown Absensi --}}
            <div class="dropdown-container">
                <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="absensi-dropdown">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span class="nav-text">Absensi</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu-sidebar" id="absensi-dropdown">
                    <a href="{{ route('student-attendance.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="absensi-siswa">
                        <i class="fas fa-user-check me-2"></i>
                        <span class="nav-text">Absensi Siswa</span>
                    </a>
                    <a href="{{ route('attendance.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="absensi-pegawai">
                        <i class="fas fa-user-tie me-2"></i>
                        <span class="nav-text">Absensi Pegawai</span>
                    </a>
                </div>
            </div>

            {{-- Dropdown Master Data --}}
            <div class="dropdown-container">
                <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="master-data-dropdown">
                    <i class="fas fa-database me-2"></i>
                    <span class="nav-text">Master Data</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu-sidebar" id="master-data-dropdown">
                    <a href="{{ route('academicyear.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="tahun-ajaran">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span class="nav-text">Tahun Ajaran & Semester</span>
                    </a>
                    <a href="{{ route('classes.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="kelas">
                        <i class="fas fa-school me-2"></i>
                        <span class="nav-text">Kelas</span>
                    </a>
                    <a href="{{ route('holidays.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="hari-libur">
                        <i class="fas fa-calendar-day me-2"></i>
                        <span class="nav-text">Hari Libur</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('payment.listdata')}}" class="nav-item text-white d-block mb-2" data-page="data-spp">
                <i class="fas fa-money-bill-wave me-2"></i>
                <span class="nav-text">Manajemen SPP</span>
            </a>
               {{-- Dropdown Manajemen Buku --}}
<div class="dropdown-container">
    <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="buku-dropdown">
        <i class="fas fa-book me-2"></i>
        <span class="nav-text">Manajemen Buku</span>
        <i class="fas fa-chevron-down dropdown-icon"></i>
    </a>
    <div class="dropdown-menu-sidebar" id="buku-dropdown">
        <a href="{{ route('books.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="buku-paket">
            <i class="fas fa-book-open me-2"></i>
            <span class="nav-text">Buku Paket</span>
        </a>
        <a href="{{ route('book-loans.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="peminjaman-buku">
            <i class="fas fa-exchange-alt me-2"></i>
            <span class="nav-text">Peminjaman</span>
        </a>
    </div>
</div>

            <a href="{{ route('rapor.classes')}}" class="nav-item text-white d-block mb-2" data-page="data-nilai">
                <i class="fas fa-chart-bar me-2"></i>
                <span class="nav-text">Data Nilai Siswa</span>
            </a>
        @endif

        {{--  Admin Tata Usaha --}}
@if($role === 'Admin Tata Usaha')
    <a href="{{ route('dashboard.TU') }}" class="nav-item text-white d-block mb-2" data-page="dashboard">
        <i class="fas fa-home me-2"></i>
        <span class="nav-text">Dashboard</span>
    </a>
    <a href="{{ route('students.index')}}" class="nav-item text-white d-block mb-2" data-page="data-siswa">
        <i class="fas fa-user-graduate me-2"></i>
        <span class="nav-text">Data Siswa</span>
    </a>
    <a href="{{ route('employees.index')}}" class="nav-item text-white d-block mb-2" data-page="data-pegawai">
        <i class="fas fa-chalkboard-teacher me-2"></i>
        <span class="nav-text">Data Pegawai</span>
    </a>

    {{-- Dropdown Master Data --}}
    <div class="dropdown-container">
        <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="master-data-dropdown">
            <i class="fas fa-database me-2"></i>
            <span class="nav-text">Master Data</span>
            <i class="fas fa-chevron-down dropdown-icon"></i>
        </a>
        <div class="dropdown-menu-sidebar" id="master-data-dropdown">
            <a href="{{ route('academicyear.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="tahun-ajaran">
                <i class="fas fa-calendar-alt me-2"></i>
                <span class="nav-text">Tahun Ajaran & Semester</span>
            </a>
            <a href="{{ route('classes.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="kelas">
                <i class="fas fa-school me-2"></i>
                <span class="nav-text">Kelas</span>
            </a>
            <a href="{{ route('holidays.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="hari-libur">
                <i class="fas fa-calendar-day me-2"></i>
                <span class="nav-text">Hari Libur</span>
            </a>
        </div>
    </div>

    <a href="{{ route('payment.listdata')}}" class="nav-item text-white d-block mb-2" data-page="data-spp">
        <i class="fas fa-money-bill-wave me-2"></i>
        <span class="nav-text">Manajemen SPP</span>
    </a>
@endif
         {{--  Admin Pegawai Piket --}}
        @if($role === 'Admin Pegawai Piket')
            <a href="{{ route('dashboard.piket') }}" class="nav-item text-white d-block mb-2" data-page="dashboard">
                <i class="fas fa-home me-2"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('students.index')}}" class="nav-item text-white d-block mb-2" data-page="data-siswa">
                <i class="fas fa-user-graduate me-2"></i>
                <span class="nav-text">Data Siswa</span>
            </a>
            {{-- Dropdown Absensi --}}
            <div class="dropdown-container">
                <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="absensi-dropdown">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span class="nav-text">Absensi</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu-sidebar" id="absensi-dropdown">
                    <a href="{{ route('student-attendance.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="absensi-siswa">
                        <i class="fas fa-user-check me-2"></i>
                        <span class="nav-text">Absensi Siswa</span>
                    </a>
                    <a href="{{ route('attendance.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="absensi-pegawai">
                        <i class="fas fa-user-tie me-2"></i>
                        <span class="nav-text">Absensi Pegawai</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Admin Perpustakaan --}}
        @if($role === 'Admin Perpustakaan')
            <a href="{{ route('dashboard.perpus') }}" class="nav-item text-white d-block mb-2" data-page="dashboard">
                <i class="fas fa-home me-2"></i>
                <span class="nav-text">Dashboard</span>
            </a>
           {{-- Dropdown Manajemen Buku --}}
<div class="dropdown-container">
    <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="buku-dropdown">
        <i class="fas fa-book me-2"></i>
        <span class="nav-text">Manajemen Buku</span>
        <i class="fas fa-chevron-down dropdown-icon"></i>
    </a>
    <div class="dropdown-menu-sidebar" id="buku-dropdown">
        <a href="{{ route('books.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="buku-paket">
            <i class="fas fa-book-open me-2"></i>
            <span class="nav-text">Buku Paket</span>
        </a>
        <a href="{{ route('book-loans.index')}}" class="nav-item text-white d-block mb-2 ps-4" data-page="peminjaman-buku">
            <i class="fas fa-exchange-alt me-2"></i>
            <span class="nav-text">Peminjaman</span>
        </a>
    </div>
</div>

            <a href="{{ route('book-loans.index')}}" class="nav-item text-white d-block mb-2" data-page="peminjaman-buku">
                <i class="fas fa-exchange-alt me-2"></i>
                <span class="nav-text">Peminjaman Buku</span>
            </a>
        @endif
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdown toggles
    const dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown-sidebar"]');

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('data-target');
            const targetDropdown = document.getElementById(targetId);

            if (targetDropdown) {
                targetDropdown.classList.toggle('show');
                this.classList.toggle('show');

                // Simpan status dropdown di localStorage
                if (targetDropdown.classList.contains('show')) {
                    localStorage.setItem(`dropdown-${targetId}`, 'open');
                } else {
                    localStorage.removeItem(`dropdown-${targetId}`);
                }
            }
        });
    });

    // Handle active state for nav items
    const navItems = document.querySelectorAll('.nav-item');

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // Hapus semua item aktif
            navItems.forEach(nav => nav.classList.remove('active'));

            // Tambahkan class active ke item yang diklik
            this.classList.add('active');

            // Simpan halaman aktif di localStorage
            const page = this.getAttribute('data-page');
            if (page) {
                localStorage.setItem('activePage', page);
            }
        });
    });

    // Set status dropdown dari localStorage
    dropdownToggles.forEach(toggle => {
        const targetId = toggle.getAttribute('data-target');
        if (localStorage.getItem(`dropdown-${targetId}`) === 'open') {
            const targetDropdown = document.getElementById(targetId);
            if (targetDropdown) {
                targetDropdown.classList.add('show');
                toggle.classList.add('show');
            }
        }
    });

    // Set item aktif dari localStorage
    const activePage = localStorage.getItem('activePage');
    if (activePage) {
        const activeNavItem = document.querySelector(`.nav-item[data-page="${activePage}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');

            // Buka parent dropdown jika item dalam dropdown
            const parentDropdown = activeNavItem.closest('.dropdown-menu-sidebar');
            if (parentDropdown) {
                parentDropdown.classList.add('show');
                const parentToggle = parentDropdown.previousElementSibling;
                if (parentToggle) {
                    parentToggle.classList.add('show');
                }
            }
        }
    }
});
</script>
