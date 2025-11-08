@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Jadwal untuk Kelas: {{ $kelas->name }}</h2>
        <a href="{{ route('admin.kelas.jadwal.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Jadwal
        </a>
    </div>

    <form method="POST" action="{{ route('admin.kelas.jadwal.store', $kelas->id) }}" class="space-y-4">
        @csrf
        <div>
            <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
            <select id="hari" name="hari" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">-- Pilih Hari --</option>
                @foreach($days as $key => $label)
                    <option value="{{ $key }}" {{ old('hari') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('hari')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
            <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">-- Pilih Mata Pelajaran --</option>
                @foreach($mataPelajarans as $mp)
                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>{{ $mp->name }}</option>
                @endforeach
            </select>
            @error('mata_pelajaran_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end space-x-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Simpan</button>
        </div>
    </form>
</div>
@endsection