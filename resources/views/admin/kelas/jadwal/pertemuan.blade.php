@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Kelas {{ $kelas->name }}</h2>
            <p class="text-gray-600">{{ $jadwal->mataPelajaran->name }} - Pertemuan {{ $pertemuan }}</p>
            <p class="text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($tanggalPertemuan)->format('d F Y') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Daftar Kehadiran Siswa -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kehadiran Siswa</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Nama</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">NISN</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Status</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Keterangan</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pertemuanData as $kehadiran)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b border-gray-200">{{ $kehadiran->user->name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $kehadiran->user->nisn }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @if($kehadiran->status == 'hadir')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Hadir</span>
                                @elseif($kehadiran->status == 'tidak_hadir')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Tidak Hadir</span>
                                @elseif($kehadiran->status == 'ijin')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ijin</span>
                                @elseif($kehadiran->status == 'sakit')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Sakit</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $kehadiran->keterangan ?? '-' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @if($kehadiran->lampiran)
                                    <a href="{{ asset('storage/' . $kehadiran->lampiran) }}" target="_blank" class="text-blue-600 hover:underline">
                                        <i class="fas fa-file-alt mr-1"></i>Lihat
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada data kehadiran untuk pertemuan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection