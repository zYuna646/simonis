@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Siswa Kelas {{ $kelas->name }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.kelas.siswa.create', $kelas->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Siswa
            </a>
            <a href="{{ route('admin.kelas-card') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($siswa as $s)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                <div class="p-5 bg-blue-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $s->name }}</h3>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Email:</p>
                        <p class="font-medium text-gray-800">{{ $s->email }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">NIS:</p>
                        <p class="font-medium text-gray-800">{{ $s->nis ?? '-' }}</p>
                    </div>
                    
                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="{{ route('admin.kelas.siswa.kehadiran.index', [$kelas->id, $s->id]) }}" class="px-3 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition">
                            <i class="fas fa-calendar-check mr-1"></i> Kehadiran
                        </a>
                        <a href="{{ route('admin.kelas.siswa.nilai.index', [$kelas->id, $s->id]) }}" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition">
                            <i class="fas fa-graduation-cap mr-1"></i> Nilai
                        </a>
                        <form action="{{ route('admin.kelas.siswa.destroy', [$kelas->id, $s->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini dari kelas?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">Belum ada siswa di kelas ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection