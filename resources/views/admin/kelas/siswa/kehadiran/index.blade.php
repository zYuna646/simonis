@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Kehadiran Siswa: {{ $siswa->name }}</h2>
        <div class="flex space-x-2">
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
            <a href="{{ route('admin.kelas.siswa.kehadiran.create', [$kelas->id, $siswa->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Kehadiran
            </a>
            @endif
            <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nama Siswa:</p>
                <p class="font-medium text-gray-800">{{ $siswa->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kelas:</p>
                <p class="font-medium text-gray-800">{{ $kelas->name }}</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($kehadirans as $kehadiran)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $kehadiran->tanggal->format('d-m-Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($kehadiran->status == 'hadir')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                        @elseif($kehadiran->status == 'tidak_hadir')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Hadir</span>
                        @elseif($kehadiran->status == 'ijin')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Ijin</span>
                        @elseif($kehadiran->status == 'sakit')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Sakit</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $kehadiran->keterangan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.kelas.siswa.kehadiran.edit', [$kelas->id, $siswa->id, $kehadiran->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.kelas.siswa.kehadiran.destroy', [$kelas->id, $siswa->id, $kehadiran->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kehadiran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-400">Tidak ada akses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data kehadiran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection