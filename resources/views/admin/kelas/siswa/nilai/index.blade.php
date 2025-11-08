@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Nilai Siswa: {{ $siswa->name }} (Kelas {{ $kelas->name }})</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.kelas.siswa.nilai.create', [$kelas->id, $siswa->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Nilai
            </a>
            <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Siswa
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(isset($mataPelajaran))
        <div class="bg-blue-50 border border-blue-100 text-blue-800 p-4 mb-4 rounded">
            <p>
                Mata Pelajaran (otomatis): <span class="font-medium">{{ $mataPelajaran->name ?? '-' }}</span>
                @if($mataPelajaran && $mataPelajaran->guru)
                    — Guru: <span class="font-medium">{{ $mataPelajaran->guru->name }}</span>
                @endif
            </p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700">Mata Pelajaran</th>
                    <th class="px-4 py-2 text-left text-gray-700">Nilai</th>
                    <th class="px-4 py-2 text-left text-gray-700">Jenis</th>
                    <th class="px-4 py-2 text-left text-gray-700">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilais as $n)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $n->mataPelajaran->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $n->nilai }}</td>
                    <td class="px-4 py-2">{{ $n->jenis }}</td>
                    <td class="px-4 py-2">{{ $n->tanggal ? $n->tanggal->format('d-m-Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada nilai untuk siswa ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection