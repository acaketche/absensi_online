<div class="sidebar">
    <div class="logo">
        <div class="logo-icon">E</div>
        <div class="logo-text">SCHOOL</div>
    </div>
    <nav>
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

        <!-- Absensi Dropdown -->
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

        <!-- Master Data Dropdown -->
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

        <a href="{{ route('payment.index')}}" class="nav-item text-white d-block mb-2" data-page="data-spp">
            <i class="fas fa-money-bill-wave me-2"></i>
            <span class="nav-text">Data Pembayaran SPP</span>
        </a>

        <!-- Data Buku Paket Dropdown -->
        <div class="dropdown-container">
            <a href="#" class="nav-item text-white d-block mb-2 sidebar-dropdown-toggle" data-bs-toggle="dropdown-sidebar" data-target="buku-paket-dropdown">
                    <i class="fas fa-book-reader me-2"></i>
                    <span class="nav-text">Data Buku Paket</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <div class="dropdown-menu-sidebar" id="buku-paket-dropdown">
                <a href="#" class="nav-item text-white d-block mb-2 ps-4" data-page="peminjaman-buku">
                    <i class="fas fa-book-open me-2"></i>
                    <span class="nav-text">Peminjaman Buku Paket</span>
                </a>
            </div>
        </div>

        <a href="{{ route('Rapor.index')}}" class="nav-item text-white d-block mb-2" data-page="data-nilai">
            <i class="fas fa-chart-bar me-2"></i>
            <span class="nav-text">Data Nilai Siswa</span>
        </a>
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

    // **Set status dropdown dari localStorage**
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

    // **Set item aktif dari localStorage**
    const activePage = localStorage.getItem('activePage');
    if (activePage) {
        const activeNavItem = document.querySelector(`.nav-item[data-page="${activePage}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');

            // **Buka parent dropdown jika item dalam dropdown**
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
