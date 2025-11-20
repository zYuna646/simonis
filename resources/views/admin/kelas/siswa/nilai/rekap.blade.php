@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Rekap Nilai Kelas {{ $kelas->name }}</h2>
            @if($mataPelajaran)
                <p class="text-sm text-gray-600 mt-1">Mata Pelajaran: {{ $mataPelajaran->name }} @if($mataPelajaran->guru) (Guru: {{ $mataPelajaran->guru->name }}) @endif</p>
            @endif
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.kelas.nilai.rekap.pdf', $kelas->id) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                <i class="fas fa-file-pdf mr-2"></i>Unduh PDF
            </a>
            <a href="{{ route('admin.kelas.siswa.index', [$kelas->id, 'context' => 'nilai']) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-sm">
            <thead>
                <tr>
                    <th rowspan="2" class="px-3 py-2 border-b bg-gray-50 text-left font-medium text-gray-600">No</th>
                    <th rowspan="2" class="px-3 py-2 border-b bg-gray-50 text-left font-medium text-gray-600">NISN</th>
                    <th rowspan="2" class="px-3 py-2 border-b bg-gray-50 text-left font-medium text-gray-600">Nama</th>
                    <th colspan="{{ max(1, $pertemuan->count()) }}" class="px-3 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Nilai / Tanggal</th>
                    <th colspan="4" class="px-3 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Jumlah</th>
                </tr>
                <tr>
                    @forelse($pertemuan as $tgl)
                        <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">{{ is_object($tgl) ? $tgl->format('d/m') : \Carbon\Carbon::parse($tgl)->format('d/m') }}</th>
                    @empty
                        <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">-</th>
                    @endforelse
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Ulangan</th>
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Tugas</th>
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Praktek</th>
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Remedial</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($siswaList as $idx => $s)
                <tr>
                    <td class="px-3 py-2">{{ $idx + 1 }}</td>
                    <td class="px-3 py-2">{{ $s->nisn ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $s->name }}</td>
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
                        <td class="px-3 py-2 text-center">{{ $cell !== '' ? $cell : '-' }}</td>
                    @empty
                        <td class="px-3 py-2 text-center">-</td>
                    @endforelse
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['ulangan'] ?? 0 }}</td>
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['tugas'] ?? 0 }}</td>
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['praktek'] ?? 0 }}</td>
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['remedial'] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection