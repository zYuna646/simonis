@extends('dashboard')

@section('content')
@php
    $isBulk = isset($siswa) && $siswa instanceof \Illuminate\Support\Collection;
@endphp

<div class="bg-white rounded-lg shadow-md p-6">
    @if($isBulk)
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Input Nilai Massal: {{ $kelas->name }}</h2>
            <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if(!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru')))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>Anda tidak memiliki akses untuk menambahkan nilai massal.</p>
            </div>
        @else
            <form action="{{ route('admin.kelas.nilai.bulk.store', $kelas->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Nilai</label>
                        <select name="jenis" id="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="ulangan" {{ old('jenis') == 'ulangan' ? 'selected' : '' }}>Ulangan</option>
                            <option value="tugas" {{ old('jenis') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                            <option value="praktek" {{ old('jenis') == 'praktek' ? 'selected' : '' }}>Praktek</option>
                            <option value="remedial" {{ old('jenis') == 'remedial' ? 'selected' : '' }}>Remedial</option>
                        </select>
                        @error('jenis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach(\App\Models\MataPelajaran::orderBy('name')->get() as $mp)
                                <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>{{ $mp->name }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                                <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai (0-100)</th>
                                <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 border-b bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Simpan?</th>
                                <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($siswa as $i => $s)
                                @php
                                    $hadir = \App\Models\Kehadiran::where('kelas_id', $kelas->id)
                                        ->where('user_id', $s->id)
                                        ->where('status', 'hadir')
                                        ->count();
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">{{ $s->nisn ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $s->name }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="nilai[{{ $s->id }}]" min="0" max="100" step="0.01" value="{{ old('nilai.' . $s->id) }}" class="w-28 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="keterangan[{{ $s->id }}]" value="{{ old('keterangan.' . $s->id) }}" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="simpan[{{ $s->id }}]" value="1" {{ old('simpan.' . $s->id, '1') ? 'checked' : '' }}>
                                    </td>
                                    <td class="px-4 py-3">hadir {{ $hadir }}x</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada siswa di kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Simpan Nilai Terpilih</button>
                </div>
            </form>
        @endif
    @else
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Tambah Nilai: {{ $siswa->name }}</h2>
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
            </div>
        </div>

        <form action="{{ route('admin.kelas.siswa.nilai.store', [$kelas->id, $siswa->id]) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Nilai</label>
                <select name="jenis" id="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="ulangan" {{ old('jenis') == 'ulangan' ? 'selected' : '' }}>Ulangan</option>
                    <option value="tugas" {{ old('jenis') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                    <option value="praktek" {{ old('jenis') == 'praktek' ? 'selected' : '' }}>Praktek</option>
                    <option value="remedial" {{ old('jenis') == 'remedial' ? 'selected' : '' }}>Remedial</option>
                </select>
                @error('jenis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">Nilai (0-100)</label>
                <input type="number" name="nilai" id="nilai" min="0" max="100" step="0.01" value="{{ old('nilai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('nilai')
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
    @endif
</div>
@endsection