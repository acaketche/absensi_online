<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Pegawai</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 20px;
        }
        h1 {
            color: #1A237E;
            margin-bottom: 5px;
            font-size: 20px;
            font-weight: 700;
            text-align: center;
        }
        h2 {
            color: #2C3E50;
            margin-bottom: 5px;
            text-align: center;
        }
        h4 {
            color: #34495E;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: normal;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        thead tr {
            background-color: #2980B9;
            color: white;
            text-align: left;
        }
        thead th {
            padding: 10px 12px;
            font-weight: 600;
            border-bottom: 2px solid #1C5980;
        }
        tbody tr:nth-child(even) {
            background-color: #f7f9fc;
        }
        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        tbody tr:hover {
            background-color: #d6eaf8;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #999;
        }
    </style>
</head>
<body>
    <h1>Laporan Absensi Pegawai SMA Negeri 5 Payakumbuh</h1>

    @if(request('status_name'))
        <h2>Status: {{ request('status_name') }}</h2>
    @endif

    @if($start_date && $end_date)
        <h4>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</h4>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Pegawai</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 20%;">Status</th>
                <th style="width: 12%;">Check In</th>
                <th style="width: 13%;">Check Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->employee->fullname }}</td>
                <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d-m-Y') }}</td>
                <td>{{ $attendance->status->status_name }}</td>
                <td>{{ $attendance->check_in ?? '-' }}</td>
                <td>{{ $attendance->check_out ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="no-data">Tidak ada data absensi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
