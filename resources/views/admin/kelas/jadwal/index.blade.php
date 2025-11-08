@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Jadwal Mingguan: {{ $kelas->name }}</h2>
        <div class="flex space-x-2">
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
            <a href="{{ route('admin.kelas.jadwal.create', $kelas->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Jadwal
            </a>
            @endif
            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-6">
        @foreach($days as $key => $label)
        <div class="border rounded-lg">
            <div class="px-4 py-2 bg-yellow-100 border-b text-yellow-800 font-semibold">{{ strtoupper($label) }}</div>
            <div class="p-4">
                @php $list = $grouped[$key]; @endphp
                @if($list->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Mata Pelajaran</th>
                                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
                                <th class="py-2 px-4 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach($list as $i => $j)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $i + 1 }}</td>
                                <td class="py-2 px-4">{{ $j->mataPelajaran->name }}</td>
                                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
                                <td class="py-2 px-4 text-center">
                                    <form action="{{ route('admin.kelas.jadwal.destroy', [$kelas->id, $j->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus entri jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada jadwal untuk hari {{ strtolower($label) }}.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection