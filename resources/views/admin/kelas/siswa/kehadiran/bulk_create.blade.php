@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Input Kehadiran Massal: {{ $kelas->name }}</h2>
        <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    @if(!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru')))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>Anda tidak memiliki akses untuk menambahkan kehadiran massal.</p>
        </div>
    @else
        <form action="{{ route('admin.kelas.kehadiran.bulk.store', $kelas->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50">
                        @if($mataPelajaran)
                            <p class="text-gray-800 font-medium">{{ $mataPelajaran->name }}</p>
                            <p class="text-gray-600 text-sm">Guru: {{ $mataPelajaran->guru->name ?? '-' }}</p>
                        @else
                            <p class="text-gray-600">Tidak ada mata pelajaran terdeteksi untuk guru/kelas ini.</p>
                        @endif
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label for="lampiran" class="block text-sm font-medium text-gray-700 mb-1">Lampiran (Opsional, berlaku untuk semua baris)</label>
                    <input type="file" name="lampiran" id="lampiran" class="w-full">
                    @error('lampiran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-4 py-3 border-b bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Simpan?</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($siswa as $i => $s)
                        <tr>
                            <td class="px-4 py-3">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">{{ $s->nisn ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $s->name }}</td>
                            <td class="px-4 py-3">
                                <select name="status[{{ $s->id }}]" class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="hadir" {{ old('status.' . $s->id) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="tidak_hadir" {{ old('status.' . $s->id) == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="ijin" {{ old('status.' . $s->id) == 'ijin' ? 'selected' : '' }}>Ijin</option>
                                    <option value="sakit" {{ old('status.' . $s->id) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="keterangan[{{ $s->id }}]" value="{{ old('keterangan.' . $s->id) }}" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="simpan[{{ $s->id }}]" value="1" {{ old('simpan.' . $s->id, '1') ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">Tidak ada siswa di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    @endif
</div>
@endsection