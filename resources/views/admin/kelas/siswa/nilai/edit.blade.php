@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Edit Nilai: {{ $siswa->name }}</h2>
        <a href="{{ route('admin.kelas.siswa.nilai.index', [$kelas->id, $siswa->id]) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
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
            <div>
                <p class="text-sm text-gray-600">Mata Pelajaran (otomatis):</p>
                <p class="font-medium text-gray-800">{{ $mataPelajaran->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Guru Pengajar:</p>
                <p class="font-medium text-gray-800">{{ $mataPelajaran->guru->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
    <form action="{{ route('admin.kelas.siswa.nilai.update', [$kelas->id, $siswa->id, $nilai->id]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $nilai->tanggal->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('tanggal')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Nilai</label>
            <select name="jenis" id="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="ulangan" {{ old('jenis', $nilai->jenis) == 'ulangan' ? 'selected' : '' }}>Ulangan</option>
                <option value="tugas" {{ old('jenis', $nilai->jenis) == 'tugas' ? 'selected' : '' }}>Tugas</option>
                <option value="praktek" {{ old('jenis', $nilai->jenis) == 'praktek' ? 'selected' : '' }}>Praktek</option>
                <option value="remedial" {{ old('jenis', $nilai->jenis) == 'remedial' ? 'selected' : '' }}>Remedial</option>
            </select>
            @error('jenis')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">Nilai (0-100)</label>
            <input type="number" name="nilai" id="nilai" min="0" max="100" step="0.01" value="{{ old('nilai', $nilai->nilai) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('nilai')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan', $nilai->keterangan) }}</textarea>
            @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Simpan Perubahan</button>
        </div>
    </form>
    @else
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>Anda tidak memiliki akses untuk mengedit nilai.</p>
    </div>
    @endif
</div>
@endsection