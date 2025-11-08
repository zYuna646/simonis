@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Data Kelas</h2>
        <a href="{{ route('admin.kelas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Tambah Kelas
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nama Kelas</th>
                    <th class="py-3 px-6 text-left">Guru</th>
                    {{-- <th class="py-3 px-6 text-center">Aksi</th> --}}
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($kelas as $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left">{{ $item->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $item->user->name }}</td>
                        {{-- <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('admin.kelas.jadwal.index', $item->id) }}" class="w-4 mr-4 transform hover:text-yellow-600 hover:scale-110 transition" title="Jadwal">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                                <a href="{{ route('admin.kelas.edit', $item->id) }}" class="w-4 mr-4 transform hover:text-blue-500 hover:scale-110 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-4 transform hover:text-red-500 hover:scale-110 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-3 px-6 text-center">Tidak ada data kelas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection