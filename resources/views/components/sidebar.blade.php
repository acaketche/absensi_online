<div class="sidebar">
    <!-- Logo Section -->
    <div class="logo">
        <div class="logo-icon">
            <img src="{{ asset('img/sma5.png') }}" alt="Logo Sekolah">
        </div>
        <div class="logo-text">E-SCHOOL</div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        @php
            use App\Models\Classes;
            use Illuminate\Support\Facades\Auth;
            $employee = Auth::guard('employee')->user();
            $class = Classes::where('id_employee', $employee->id_employee)->first();
            $role = Auth::guard('employee')->user()->role->role_name ?? '';
        @endphp

        {{-- Super Admin --}}
        @if($role === 'Super Admin')
            <!-- Group Title -->
            <div class="nav-group-title">Main Menu</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard.admin') }}" class="nav-item" data-page="dashboard" data-tooltip="Dashboard">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- Data User -->
            <a href="{{ route('users.index') }}" class="nav-item" data-page="data-user" data-tooltip="Data User">
                <div class="nav-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span class="nav-text">Data User</span>
            </a>

            <!-- Data Siswa -->
            <a href="{{ route('students.index') }}" class="nav-item" data-page="data-siswa" data-tooltip="Data Siswa">
                <div class="nav-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="nav-text">Data Siswa</span>
            </a>

            <!-- Data Pegawai -->
            <a href="{{ route('employees.index') }}" class="nav-item" data-page="data-pegawai" data-tooltip="Data Pegawai dan Guru">
                <div class="nav-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <span class="nav-text">Data Pegawai dan Guru</span>
            </a>

            <!-- Dropdown Absensi -->
            <div class="dropdown-container">
                <button class="sidebar-dropdown-toggle" data-target="absensi-dropdown" data-tooltip="Absensi">
                    <div class="dropdown-main-content">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="nav-text">Absensi</span>
                    </div>
                    <div class="dropdown-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="dropdown-menu-sidebar" id="absensi-dropdown">
                    <a href="{{ route('student-attendance.index') }}" class="submenu-item" data-page="absensi-siswa">
                        <div class="submenu-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span>Absensi Siswa</span>
                    </a>
                    <a href="{{ route('attendance.index') }}" class="submenu-item" data-page="absensi-pegawai">
                        <div class="submenu-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span>Absensi Pegawai dan Guru</span>
                    </a>
                </div>
            </div>

            <!-- Manajemen SPP -->
            <a href="{{ route('payment.listdata') }}" class="nav-item" data-page="data-spp" data-tooltip="Manajemen SPP">
                <div class="nav-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <span class="nav-text">Manajemen SPP</span>
            </a>

            <!-- Dropdown Manajemen Buku -->
            <div class="dropdown-container">
                <button class="sidebar-dropdown-toggle" data-target="buku-dropdown" data-tooltip="Manajemen Buku">
                    <div class="dropdown-main-content">
                        <div class="nav-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="nav-text">Manajemen Buku</span>
                    </div>
                    <div class="dropdown-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="dropdown-menu-sidebar" id="buku-dropdown">
                    <a href="{{ route('books.index') }}" class="submenu-item" data-page="buku-paket">
                        <div class="submenu-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span>Buku Paket</span>
                    </a>
                    <a href="{{ route('book-loans.index') }}" class="submenu-item" data-page="peminjaman-buku">
                        <div class="submenu-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <span>Peminjaman</span>
                    </a>
                </div>
            </div>

            <!-- Data Rapor -->
            <a href="{{ route('rapor.classes') }}" class="nav-item" data-page="data-nilai" data-tooltip="Data Rapor Siswa">
                <div class="nav-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span class="nav-text">Data Rapor Siswa</span>
            </a>

            <!-- Separator -->
            <div class="nav-separator"></div>

            <!-- Group Title -->
            <div class="nav-group-title">Master Data</div>

            <!-- Dropdown Master Data -->
            <div class="dropdown-container">
                <button class="sidebar-dropdown-toggle" data-target="master-data-dropdown" data-tooltip="Master Data">
                    <div class="dropdown-main-content">
                        <div class="nav-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <span class="nav-text">Master Data</span>
                    </div>
                    <div class="dropdown-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="dropdown-menu-sidebar" id="master-data-dropdown">
                    <a href="{{ route('positions.index') }}" class="submenu-item" data-page="data-jabatan">
                        <div class="submenu-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <span>Data Jabatan</span>
                    </a>
                    <a href="{{ route('picket.index') }}" class="submenu-item" data-page="piket">
                        <div class="submenu-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span>Jadwal Piket</span>
                    </a>
                    <a href="{{ route('classes.index') }}" class="submenu-item" data-page="kelas">
                        <div class="submenu-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <span>Kelas</span>
                    </a>
                    <a href="{{ route('holidays.index') }}" class="submenu-item" data-page="hari-libur">
                        <div class="submenu-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <span>Hari Libur</span>
                    </a>
                    <a href="{{ route('academicyear.index') }}" class="submenu-item" data-page="tahun-ajaran">
                        <div class="submenu-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span>Tahun Ajaran & Semester</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Admin Tata Usaha --}}
        @if($role === 'Admin Tata Usaha')
            <!-- Group Title -->
            <div class="nav-group-title">Main Menu</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard.TU') }}" class="nav-item" data-page="dashboard" data-tooltip="Dashboard">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- Data Siswa -->
            <a href="{{ route('students.index') }}" class="nav-item" data-page="data-siswa" data-tooltip="Data Siswa">
                <div class="nav-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="nav-text">Data Siswa</span>
            </a>

            <!-- Data Pegawai -->
            <a href="{{ route('employees.index') }}" class="nav-item" data-page="data-pegawai" data-tooltip="Data Pegawai dan Guru">
                <div class="nav-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <span class="nav-text">Data Pegawai dan Guru</span>
            </a>

            <!-- Dropdown Absensi -->
            <div class="dropdown-container">
                <button class="sidebar-dropdown-toggle" data-target="absensi-dropdown-tu" data-tooltip="Absensi">
                    <div class="dropdown-main-content">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="nav-text">Absensi</span>
                    </div>
                    <div class="dropdown-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="dropdown-menu-sidebar" id="absensi-dropdown-tu">
                    {{-- <a href="{{ route('student-attendance.index') }}" class="submenu-item" data-page="absensi-siswa">
                        <div class="submenu-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span>Absensi Siswa</span>
                    </a> --}}
                    <a href="{{ route('attendance.index') }}" class="submenu-item" data-page="absensi-pegawai">
                        <div class="submenu-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span>Absensi Pegawai dan Guru</span>
                    </a>
                </div>
            </div>

            <!-- Manajemen SPP -->
            <a href="{{ route('payment.listdata') }}" class="nav-item" data-page="data-spp" data-tooltip="Manajemen SPP">
                <div class="nav-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <span class="nav-text">Manajemen SPP</span>
            </a>

            <!-- Separator -->
            <div class="nav-separator"></div>

            <!-- Group Title -->
            <div class="nav-group-title">Master Data</div>

            <!-- Dropdown Master Data -->
            <div class="dropdown-container">
                <button class="sidebar-dropdown-toggle" data-target="master-data-dropdown-tu" data-tooltip="Master Data">
                    <div class="dropdown-main-content">
                        <div class="nav-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <span class="nav-text">Master Data</span>
                    </div>
                    <div class="dropdown-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="dropdown-menu-sidebar" id="master-data-dropdown-tu">
                    <a href="{{ route('positions.index') }}" class="submenu-item" data-page="data-jabatan">
                        <div class="submenu-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <span>Data Jabatan</span>
                    </a>
                    <a href="{{ route('picket.index') }}" class="submenu-item" data-page="piket">
                        <div class="submenu-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span>Jadwal Piket</span>
                    </a>
                    <a href="{{ route('classes.index') }}" class="submenu-item" data-page="kelas">
                        <div class="submenu-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <span>Kelas</span>
                    </a>
                    <a href="{{ route('holidays.index') }}" class="submenu-item" data-page="hari-libur">
                        <div class="submenu-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <span>Hari Libur</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Admin Pegawai Piket --}}
        @if($role === 'Admin Pegawai Piket')
            <!-- Group Title -->
            <div class="nav-group-title">Main Menu</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard.piket') }}" class="nav-item" data-page="dashboard" data-tooltip="Dashboard">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- Data Siswa -->
            <a href="{{ route('students.index') }}" class="nav-item" data-page="data-siswa" data-tooltip="Data Siswa">
                <div class="nav-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="nav-text">Data Siswa</span>
            </a>

            <!-- Absensi Siswa -->
            <a href="{{ route('student-attendance.index') }}" class="nav-item" data-page="absensi-siswa" data-tooltip="Absensi Siswa">
                <div class="nav-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <span class="nav-text">Absensi Siswa</span>
            </a>
        @endif

        {{-- Admin Perpustakaan --}}
        @if($role === 'Admin Perpustakaan')
            <!-- Group Title -->
            <div class="nav-group-title">Main Menu</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard.perpus') }}" class="nav-item" data-page="dashboard" data-tooltip="Dashboard">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- Dropdown Manajemen Buku -->
            <div class="dropdown-container">
                <button class="sidebar-dropdown-toggle" data-target="buku-dropdown-perpus" data-tooltip="Manajemen Buku">
                    <div class="dropdown-main-content">
                        <div class="nav-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="nav-text">Manajemen Buku</span>
                    </div>
                    <div class="dropdown-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="dropdown-menu-sidebar" id="buku-dropdown-perpus">
                    <a href="{{ route('books.index') }}" class="submenu-item" data-page="buku-paket">
                        <div class="submenu-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span>Buku Paket</span>
                    </a>
                    <a href="{{ route('book-loans.index') }}" class="submenu-item" data-page="peminjaman-buku">
                        <div class="submenu-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <span>Peminjaman</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Wali Kelas --}}
        @if($role === 'Wali Kelas')
            <!-- Group Title -->
            <div class="nav-group-title">Main Menu</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard.walas') }}" class="nav-item" data-page="dashboard" data-tooltip="Dashboard">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>

            @if($class)
                <!-- Data Rapor -->
                <a href="{{ route('rapor.students', ['classId' => $class->class_id]) }}" class="nav-item" data-page="data-nilai" data-tooltip="Data Rapor Siswa">
                    <div class="nav-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span class="nav-text">Data Rapor Siswa</span>
                </a>
            @endif
        @endif
    </nav>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdown toggles
    const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('data-target');
            const targetDropdown = document.getElementById(targetId);

            if (targetDropdown) {
                // Toggle dropdown
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
    const navItems = document.querySelectorAll('.nav-item, .submenu-item');

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

    // Restore dropdown states dari localStorage
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
        const activeNavItem = document.querySelector(`[data-page="${activePage}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');

            // Buka parent dropdown jika item dalam dropdown
            const parentDropdown = activeNavItem.closest('.dropdown-menu-sidebar');
            if (parentDropdown) {
                parentDropdown.classList.add('show');
                const parentToggle = document.querySelector(`[data-target="${parentDropdown.id}"]`);
                if (parentToggle) {
                    parentToggle.classList.add('show');
                }
            }
        }
    }

    // Handle responsive sidebar collapse
    function handleResize() {
        const sidebar = document.querySelector('.sidebar');
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
        } else {
            sidebar.classList.remove('collapsed');
        }
    }

    // Initial check
    handleResize();

    // Listen for resize events
    window.addEventListener('resize', handleResize);
});
</script>
