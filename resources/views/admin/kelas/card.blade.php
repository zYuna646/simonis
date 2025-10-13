@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Manajemen Kelas</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelas as $item)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                <div class="p-5 bg-blue-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $item->name }}</h3>
                    <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Tingkat {{ $item->tingkat ?? 'Belum diatur' }}</span>
                    <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">{{ $item->totalSiswa() }} Siswa</span>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Guru Pengajar:</p>
                        <p class="font-medium text-gray-800">{{ $item->user->name }}</p>
                    </div>
                    
                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="{{ route('admin.kelas.siswa.index', $item->id) }}" class="px-3 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                        
                        @if(auth()->user()->hasRole('admin') || auth()->id() == $item->user_id)
                            <a href="{{ route('admin.kelas.edit', $item->id) }}" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            
                            @if(auth()->user()->hasRole('admin'))
                            <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">Tidak ada data kelas</p>
            </div>
        @endforelse
    </div>
</div>
@endsection