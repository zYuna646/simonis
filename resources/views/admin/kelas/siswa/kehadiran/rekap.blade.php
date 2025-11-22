@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Rekap Kehadiran Kelas: {{ $kelas->name }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.kelas.kehadiran.rekap.pdf', $kelas->id) }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition" target="_blank">
                <i class="fas fa-file-pdf mr-2"></i>Print PDF
            </a>
            <a href="{{ route('admin.kelas.siswa.index', [$kelas->id, 'context' => request()->get('context', 'kehadiran')]) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
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
                    <th colspan="{{ max(1, $pertemuan->count()) }}" class="px-3 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Pertemuan / Tanggal</th>
                    <th colspan="4" class="px-3 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Jumlah</th>
                </tr>
                <tr>
                    @forelse($pertemuan as $i => $tgl)
                        <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">{{ is_object($tgl) ? $tgl->format('d/m') : \Carbon\Carbon::parse($tgl)->format('d/m') }}</th>
                    @empty
                        <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">-</th>
                    @endforelse
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Hadir</th>
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Tidak Hadir</th>
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Izin</th>
                    <th class="px-2 py-2 border-b bg-gray-50 text-center font-medium text-gray-600">Sakit</th>
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
                            $st = $index[$s->id][$keyDate] ?? null;
                            $label = $st === 'hadir' ? 'H' : ($st === 'tidak_hadir' ? 'TH' : ($st === 'ijin' ? 'I' : ($st === 'sakit' ? 'S' : '')));
                        @endphp
                        <td class="px-3 py-2 text-center">{{ $label }}</td>
                    @empty
                        <td class="px-3 py-2 text-center">-</td>
                    @endforelse
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['hadir'] ?? 0 }}</td>
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['tidak_hadir'] ?? 0 }}</td>
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['ijin'] ?? 0 }}</td>
                    <td class="px-3 py-2 text-center">{{ $totals[$s->id]['sakit'] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
