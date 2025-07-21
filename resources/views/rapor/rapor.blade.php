<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Kelas | E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4266B9;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body {
            background-color: #f5f7fb;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            padding: 16px 20px;
            border: none;
        }

        .search-container {
            position: relative;
            margin-bottom: 24px;
        }

        .search-container input {
            border-radius: 50px;
            padding: 12px 20px 12px 50px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary-color);
        }

        .search-container .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .class-card {
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            cursor: pointer;
        }

        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .class-card .card-body {
            padding: 20px;
        }

        .class-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .class-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            color: #777;
        }

        .class-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .student-count {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .student-count i {
            margin-right: 5px;
        }

        .class-teacher {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .class-teacher i {
            margin-right: 5px;
            color: var(--primary-color);
        }

        .class-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .filter-section:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .filter-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .filter-title i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .form-select, .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary-color);
        }

        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .info-alert {
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }

        .info-alert i {
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-text {
            color: #888;
            font-size: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .class-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .header-actions {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
@if(Auth::guard('employee')->check())
<body>
<div class="d-flex">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Header dengan Profil Admin -->
        @include('components.profiladmin')

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="fs-4 fw-bold mb-1">Data Kelas</h2>
                <p class="text-muted mb-0">Pilih kelas untuk melihat data nilai siswa</p>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Cari kelas..." class="form-control" id="searchInput">
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="filter-title">
                <i class="fas fa-sliders-h"></i>
                <h5 class="mb-0">Filter Data Kelas</h5>
            </div>
            <form id="filterForm" method="GET" action="{{ route('rapor.classes') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="academicYearSelect" class="form-label">Tahun Ajaran</label>
                    <select id="academicYearSelect" name="academic_year_id" class="form-control">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($academicYears as $tahun)
                            <option value="{{ $tahun->id }}" {{ request('academic_year_id') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="class_level" class="form-label">Tingkatan Kelas</label>
                    <select name="class_level" id="class_level" class="form-select">
                        <option value="">Semua Tingkatan</option>
                        @foreach($classLevels as $level)
                            @php
                                $angka = match($level->class_level) {
                                    'X' => 10,
                                    'XI' => 11,
                                    'XII' => 12,
                                    default => ''
                                };
                            @endphp
                            <option value="{{ $level->class_level }}" {{ request('class_level') == $level->class_level ? 'selected' : '' }}>
                                {{ $level->class_level }} (kelas {{ $angka }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('rapor.classes') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Class Grid -->
        <div class="class-grid">
            @forelse($classes as $class)
            <div class="card class-card" onclick="window.location.href='{{ route('rapor.students', $class->class_id) }}'">
                <div class="card-body">
                    <div class="class-icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <h5 class="class-title">{{ $class->class_name }}</h5>
                    <p class="class-teacher">
                        <i class="fas fa-user-tie"></i> {{ $class->employee->fullname ?? 'Belum ada wali kelas' }}
                    </p>
                    <div class="class-meta">
                        <span><i class="fas fa-calendar-alt me-1"></i> {{ $class->academic_year->name ?? 'Tahun Ajaran' }}</span>
                        <span><i class="fas fa-book me-1"></i> {{ $class->major ?? 'Jurusan' }}</span>
                    </div>
                    <div class="class-footer">
                        <span class="student-count">
                            <i class="fas fa-users"></i> {{ $class->students_count ?? 0 }} Siswa
                        </span>
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-right"></i> Lihat Siswa
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <h5>Belum Ada Kelas</h5>
                        <p class="empty-text">Belum ada data kelas yang tersedia.</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality (client-side)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const classCards = document.querySelectorAll('.class-card');

            classCards.forEach(card => {
                const className = card.querySelector('.class-title').textContent.toLowerCase();
                const teacherName = card.querySelector('.class-teacher').textContent.toLowerCase();
                const classMeta = card.querySelector('.class-meta').textContent.toLowerCase();

                if (className.includes(searchValue) || teacherName.includes(searchValue) || classMeta.includes(searchValue)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Auto submit when class level changes
    const classLevelSelect = document.getElementById('class_level');
    if (classLevelSelect) {
        classLevelSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }

    // Add click event to all class cards
    const classCards = document.querySelectorAll('.class-card');
    classCards.forEach(card => {
        card.addEventListener('click', function() {
            window.location.href = this.getAttribute('onclick').match(/window\.location\.href='([^']+)'/)[1];
        });
    });
});
</script>
</body>
</html>
@endif
