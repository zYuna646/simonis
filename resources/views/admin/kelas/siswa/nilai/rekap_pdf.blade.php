<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Kelas {{ $kelas->name }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Rekap Nilai Kelas {{ $kelas->name }}</h1>
    @if($mataPelajaran)
        <p>Mata Pelajaran: {{ $mataPelajaran->name }} @if($mataPelajaran->guru) (Guru: {{ $mataPelajaran->guru->name }}) @endif</p>
    @endif
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NISN</th>
                <th rowspan="2">Nama</th>
                <th colspan="{{ max(1, $pertemuan->count()) }}" class="text-center">Nilai / Tanggal</th>
                <th colspan="4" class="text-center">Jumlah</th>
            </tr>
            <tr>
                @forelse($pertemuan as $tgl)
                    <th class="text-center">{{ is_object($tgl) ? $tgl->format('d/m') : \Carbon\Carbon::parse($tgl)->format('d/m') }}</th>
                @empty
                    <th class="text-center">-</th>
                @endforelse
                <th class="text-center">Ulangan</th>
                <th class="text-center">Tugas</th>
                <th class="text-center">Praktek</th>
                <th class="text-center">Remedial</th>
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
                        $dayData = $index[$s->id][$keyDate] ?? [];
                        $labels = ['ulangan' => 'U', 'tugas' => 'T', 'praktek' => 'P', 'remedial' => 'R'];
                        $parts = [];
                        foreach($labels as $jenis => $abbr) {
                            if(isset($dayData[$jenis])) {
                                $vals = array_map(function($v){ return is_numeric($v) ? (string)($v) : (string)$v; }, $dayData[$jenis]);
                                $parts[] = $abbr . ':' . implode(',', $vals);
                            }
                        }
                        $cell = count($parts) ? implode(' ', $parts) : '';
                    @endphp
                    <td class="text-center">{{ $cell !== '' ? $cell : '-' }}</td>
                @empty
                    <td class="text-center">-</td>
                @endforelse
                <td class="text-center">{{ $totals[$s->id]['ulangan'] ?? 0 }}</td>
                <td class="text-center">{{ $totals[$s->id]['tugas'] ?? 0 }}</td>
                <td class="text-center">{{ $totals[$s->id]['praktek'] ?? 0 }}</td>
                <td class="text-center">{{ $totals[$s->id]['remedial'] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>