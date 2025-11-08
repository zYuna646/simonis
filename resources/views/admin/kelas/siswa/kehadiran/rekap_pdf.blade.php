<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Kehadiran Kelas {{ $kelas->name }}</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 4px 6px; }
        th { background: #f2f2f2; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .mb-8 { margin-bottom: 16px; }
    </style>
</head>
<body>
    <h2 class="mb-8">Rekap Kehadiran Kelas: {{ $kelas->name }}</h2>
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="text-left">No</th>
                <th rowspan="2" class="text-left">NISN</th>
                <th rowspan="2" class="text-left">Nama</th>
                <th colspan="{{ max(1, $pertemuan->count()) }}" class="text-center">Pertemuan / Tanggal</th>
                <th colspan="4" class="text-center">Jumlah</th>
            </tr>
            <tr>
                @forelse($pertemuan as $tgl)
                    <th class="text-center">{{ is_object($tgl) ? $tgl->format('d/m') : \Carbon\Carbon::parse($tgl)->format('d/m') }}</th>
                @empty
                    <th class="text-center">-</th>
                @endforelse
                <th class="text-center">Hadir</th>
                <th class="text-center">Tidak Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Sakit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswaList as $idx => $s)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $s->nisn ?? '-' }}</td>
                <td>{{ $s->name }}</td>
                @forelse($pertemuan as $tgl)
                    @php
                        $keyDate = is_object($tgl) ? $tgl->format('Y-m-d') : \Carbon\Carbon::parse($tgl)->format('Y-m-d');
                        $st = $index[$s->id][$keyDate] ?? null;
                        $label = $st === 'hadir' ? 'H' : ($st === 'tidak_hadir' ? 'TH' : ($st === 'ijin' ? 'I' : ($st === 'sakit' ? 'S' : '')));
                    @endphp
                    <td class="text-center">{{ $label }}</td>
                @empty
                    <td class="text-center">-</td>
                @endforelse
                <td class="text-center">{{ $totals[$s->id]['hadir'] ?? 0 }}</td>
                <td class="text-center">{{ $totals[$s->id]['tidak_hadir'] ?? 0 }}</td>
                <td class="text-center">{{ $totals[$s->id]['ijin'] ?? 0 }}</td>
                <td class="text-center">{{ $totals[$s->id]['sakit'] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>