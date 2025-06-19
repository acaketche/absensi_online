<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 5px 0; font-size: 18px; }
        .header h2, .header h3 { margin: 0; font-size: 12px; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px 8px; font-size: 10px; }
        th { background-color: #e8e8e8; text-align: center; }
        td { vertical-align: middle; }
        .footer { margin-top: 30px; font-size: 10px; text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h1>LAPORAN ABSENSI SISWA</h1>
</div>

<table style="margin-bottom: 10px; font-size: 10px; width: 50%;">
    <tr><td>Tahun Akademik</td><td>: {{ $academicYear }}</td></tr>
    <tr><td>Semester</td><td>: {{ $semester }}</td></tr>
    <tr><td>Kelas</td><td>: {{ $class }}</td></tr>
    <tr><td>Status</td><td>: {{ $status }}</td></tr>
    <tr>
        <td>Periode</td>
        <td>:
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }}
            s/d
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 30px;">No</th>
            <th style="width: 80px;">NIPD</th>
            <th>Nama Siswa</th>
            <th style="width: 80px;">Kelas</th>
            <th style="width: 80px;">Tanggal</th>
            <th style="width: 70px;">Jam Masuk</th>
            <th style="width: 70px;">Jam Keluar</th>
            <th style="width: 80px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($attendances as $index => $attendance)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $attendance->student->id_student ?? '-' }}</td>
                <td>{{ $attendance->student->fullname ?? '-' }}</td>
                <td style="text-align: center;">{{ $attendance->class->class_name ?? '-' }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $attendance->check_in_time ?? '-' }}</td>
                <td style="text-align: center;">{{ $attendance->check_out_time ?? '-' }}</td>
                <td style="text-align: center;">{{ $attendance->status->status_name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data absensi</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Dicetak pada: {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
