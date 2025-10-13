@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Kehadiran: {{ $siswa->name }}</h2>
        <a href="{{ route('admin.kelas.siswa.kehadiran.index', [$kelas->id, $siswa->id]) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
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

    <form action="{{ route('admin.kelas.siswa.kehadiran.store', [$kelas->id, $siswa->id]) }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('tanggal')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="tidak_hadir" {{ old('status') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                <option value="ijin" {{ old('status') == 'ijin' ? 'selected' : '' }}>Ijin</option>
                <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Simpan</button>
        </div>
    </form>
</div>
@endsection