@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Kehadiran: {{ $siswa->name }} (Kelas {{ $kelas->name }})</h2>
        <div class="flex space-x-2">
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
            <a href="{{ route('admin.kelas.siswa.kehadiran.create', [$kelas->id, $siswa->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Kehadiran
            </a>
            @endif
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
                    <th class="px-4 py-2 text-left text-gray-700">Tanggal</th>
                    <th class="px-4 py-2 text-left text-gray-700">Status</th>
                    <th class="px-4 py-2 text-left text-gray-700">Keterangan</th>
                    <th class="px-4 py-2 text-left text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kehadirans as $k)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($k->tanggal)->format('d-m-Y') }}</td>
                    <td class="px-4 py-2">
                        @if($k->status == 'hadir')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                        @elseif($k->status == 'tidak_hadir')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Hadir</span>
                        @elseif($k->status == 'ijin')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Ijin</span>
                        @elseif($k->status == 'sakit')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Sakit</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $k->keterangan ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.kelas.siswa.kehadiran.edit', [$kelas->id, $siswa->id, $k->id]) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">Edit</a>
                        <form action="{{ route('admin.kelas.siswa.kehadiran.destroy', [$kelas->id, $siswa->id, $k->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kehadiran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data kehadiran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection