<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
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

        /* Metric Cards */
        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .metric-card-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .metric-icon {
            width: 45px;
            height: 45px;
            background: rgba(66, 102, 185, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4266B9;
        }

        .metric-info h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .metric-info p {
            font-size: 24px;
            font-weight: bold;
        }

        /* Graph Section */
        .graph-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .graph-title {
            margin-bottom: 20px;
        }

        /* Bottom Grid */
        .bottom-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .calendar-section, .activity-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .month-selector {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table th {
            text-align: left;
            color: #666;
            font-weight: normal;
            padding: 10px 0;
        }

        .activity-table td {
            padding: 10px 0;
            border-top: 1px solid #eee;
        }

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #eee;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .btn-primary, .bg-primary {
            background-color: #4266B9 !important;
            border-color: #4266B9 !important;
        }

        .btn-primary:hover {
            background-color: #365796 !important;
            border-color: #365796 !important;
        }

        .text-primary {
            color: #4266B9 !important;
        }

        /* Calendar adjustments */
        #calendar {
            font-size: 0.9em;
        }

        .fc .fc-toolbar-title {
            font-size: 1.2em;
        }

        .fc .fc-col-header-cell-cushion {
            font-size: 0.8em;
            padding: 2px 4px;
        }

        .fc .fc-daygrid-day-frame {
            min-height: 2em;
        }

        .fc .fc-daygrid-day-number {
            font-size: 0.8em;
            padding: 2px;
        }

        .holiday-list {
            margin-top: 15px;
            font-size: 0.9em;
        }

        .holiday-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .holiday-date {
            font-weight: bold;
            color: #4266B9;
        }

        @media (max-width: 1024px) {
            .metrics {
                grid-template-columns: repeat(2, 1fr);
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo-text, .nav-text {
                display: none;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
@if(Auth::guard('employee')->check())
<body class="bg-light">
    <div class="d-flex">
        @include('components.sidebar')

        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="dropdown">
                    @include('components.profiladmin')
                </div>
            </div>

            <!-- Metrics -->
            <div class="metrics">
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="metric-info">
                            <h3>Siswa</h3><p>{{ $totalSiswa }}</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="metric-info">
                            <h3>Pegawai</h3><p>{{ $totalPegawai }}</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon"><i class="fas fa-user-check"></i></div>
                        <div class="metric-info">
                            <h3>Pegawai hadir hari ini</h3><p>{{ $pegawaiHadir }}</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-card-content">
                        <div class="metric-icon"><i class="fas fa-user-check"></i></div>
                        <div class="metric-info">
                            <h3>Siswa hadir hari ini</h3><p>{{ $siswaHadir }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="graph-section">
                <h2 class="graph-title">Grafik Presensi Hari ini</h2>
                <div>
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="bottom-grid">
                <!-- Aktivitas -->
                <div class="activity-section">
                    <h2>Aktivitas Terakhir</h2>
                    <table class="activity-table table table-bordered">
                        <thead>
                            <tr><th>Program</th><th>Aktivitas Terakhir</th><th>Waktu</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($activities as $activity)
                            <tr>
                                <td>{{ $activity['program'] ?? '-' }}</td>
                                <td>{{ $activity['aktivitas'] ?? '-' }}</td>
                                <td>{{ isset($activity['waktu']) ? \Carbon\Carbon::parse($activity['waktu'])->diffForHumans() : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada aktivitas terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Calendar and Holidays -->
                <div class="calendar-section">
                    <div id="calendar"></div>
                    <div class="holiday-list">
                        <h5>Libur Bulan Ini</h5>
                        <div id="holidays-container">
                            <!-- Holiday items will be added here by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Modal -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    @error('new_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End main-content -->
    </div> <!-- End d-flex -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        // Grafik Presensi
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Siswa', 'Pegawai'],
                datasets: [{
                    label: 'Jumlah Hadir',
                    data: [{{ $siswaHadir }}, {{ $pegawaiHadir }}],
                    backgroundColor: ['#4266B9', '#58A6FF']
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Kalender dan Hari Libur
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const holidaysContainer = document.getElementById('holidays-container');

            // Sample holiday data - replace with your actual data
            const holidays = {
                '2023-11-01': 'Libur Nasional - Hari Libur Contoh 1',
                '2023-11-10': 'Hari Pahlawan',
                '2023-11-25': 'Libur Akhir Semester'
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {!! $calendarEvents ?? '[]' !!},
                locale: 'id',
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'prev,next'
                },
                height: 'auto',
                dayCellContent: function(arg) {
                    return { html: '<div class="fc-daygrid-day-number">' + arg.dayNumberText + '</div>' };
                },
                datesSet: function(info) {
                    // Update holidays display when month changes
                    updateHolidaysDisplay(info.start, info.end);
                }
            });

            calendar.render();

            // Function to update holidays display
            function updateHolidaysDisplay(start, end) {
                holidaysContainer.innerHTML = '';

                // Filter holidays for the current month view
                const currentMonthHolidays = Object.entries(holidays).filter(([dateStr]) => {
                    const date = new Date(dateStr);
                    return date >= start && date <= end;
                });

                if (currentMonthHolidays.length === 0) {
                    holidaysContainer.innerHTML = '<p>Tidak ada hari libur bulan ini.</p>';
                    return;
                }

                currentMonthHolidays.forEach(([dateStr, description]) => {
                    const date = new Date(dateStr);
                    const holidayItem = document.createElement('div');
                    holidayItem.className = 'holiday-item';

                    const dateElement = document.createElement('span');
                    dateElement.className = 'holiday-date';
                    dateElement.textContent = date.getDate() + ' ' + date.toLocaleString('id-ID', { month: 'long' });

                    const descElement = document.createElement('span');
                    descElement.textContent = description;

                    holidayItem.appendChild(dateElement);
                    holidayItem.appendChild(descElement);
                    holidaysContainer.appendChild(holidayItem);
                });
            }

            // Initial holidays display
            const currentView = calendar.view;
            updateHolidaysDisplay(currentView.activeStart, currentView.activeEnd);
        });
    </script>
</body>
@endif
</html>
