<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Buku - {{ $student->fullname }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin-bottom: 5px; }
        .header p { margin-top: 0; }
        .student-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 0.8em; }
        .text-center { text-align: center; }
        .status-borrowed { color: #ffc107; font-weight: bold; }
        .status-returned { color: #28a745; font-weight: bold; }
        .status-overdue { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Peminjaman Buku</h2>
        <p>Perpustakaan Sekolah - Tahun Ajaran {{ $activeAcademicYear->year_name ?? '' }}</p>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td width="20%"><strong>NIS</strong></td>
                <td width="30%">{{ $student->id_student }}</td>
                <td width="20%"><strong>Kelas</strong></td>
                <td>{{ $student->class->class_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Nama Siswa</strong></td>
                <td>{{ $student->fullname }}</td>
                <td><strong>Jenis Kelamin</strong></td>
                <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Buku</th>
                <th>Judul Buku</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Tgl Kembali</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookLoans as $index => $loan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $loan->book->code ?? '-' }}</td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($loan->status === 'Dikembalikan' && $loan->return_date)
                        {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($loan->status == 'Dipinjam')
                        @php
                            $isOverdue = now()->gt($loan->due_date);
                        @endphp
                        <span class="{{ $isOverdue ? 'status-overdue' : 'status-borrowed' }}">
                            {{ $isOverdue ? 'Terlambat' : 'Dipinjam' }}
                        </span>
                    @else
                        <span class="status-returned">Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $printDate }}</p>
    </div>
</body>
</html>
