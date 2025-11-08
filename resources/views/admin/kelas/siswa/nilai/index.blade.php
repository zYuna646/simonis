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

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700">Mata Pelajaran</th>
                    <th class="px-4 py-2 text-left text-gray-700">Nilai</th>
                    <th class="px-4 py-2 text-left text-gray-700">Jenis</th>
                    <th class="px-4 py-2 text-left text-gray-700">Tanggal</th>
                    <th class="px-4 py-2 text-left text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilais as $n)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $n->mataPelajaran->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $n->nilai }}</td>
                    <td class="px-4 py-2">{{ $n->jenis }}</td>
                    <td class="px-4 py-2">{{ $n->tanggal ? $n->tanggal->format('d-m-Y') : '-' }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.kelas.siswa.nilai.edit', [$kelas->id, $siswa->id, $n->id]) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">Edit</a>
                        <form action="{{ route('admin.kelas.siswa.nilai.destroy', [$kelas->id, $siswa->id, $n->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus nilai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada nilai untuk siswa ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection