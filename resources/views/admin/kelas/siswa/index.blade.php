@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Kelas {{ $kelas->name }}</h2>
        <div class="flex space-x-2">
            @php $context = request()->get('context'); @endphp
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('guru'))
                @if($context === 'kehadiran')
                    <a href="{{ route('admin.kelas.kehadiran.bulk.create', $kelas->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                        <i class="fas fa-calendar-plus mr-2"></i>Input Kehadiran
                    </a>
                    <a href="{{ route('admin.kelas.kehadiran.rekap', $kelas->id) }}" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                        <i class="fas fa-table mr-2"></i>Rekap Kehadiran
                    </a>
                @elseif($context === 'nilai')
                    <a href="{{ route('admin.kelas.nilai.bulk.create', $kelas->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <i class="fas fa-list mr-2"></i>Input Nilai
                    </a>
                @else
                    <a href="{{ route('admin.kelas.kehadiran.bulk.create', $kelas->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                        <i class="fas fa-calendar-plus mr-2"></i>Input Kehadiran
                    </a>
                    <a href="{{ route('admin.kelas.nilai.bulk.create', $kelas->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <i class="fas fa-list mr-2"></i>Input Nilai
                    </a>
                    <a href="{{ route('admin.kelas.kehadiran.rekap', $kelas->id) }}" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                        <i class="fas fa-table mr-2"></i>Rekap Kehadiran
                    </a>
                @endif
            @endif
            @if(Auth::user()->hasRole('admin') && ($context ?? null) !== 'nilai')
            <a href="{{ route('admin.kelas.siswa.create', $kelas->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Siswa
            </a>
            @endif
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

    <!-- Jadwal Kelas -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Jadwal Pelajaran</h3>
            @if(($context ?? null) === 'kehadiran')
            <a href="{{ route('admin.kelas.jadwal.create', [$kelas->id, 'context' => $context]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Jadwal
            </a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Hari</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Mata Pelajaran</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Guru Pengajar</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Pertemuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b border-gray-200">{{ $days[$jadwal->hari] ?? $jadwal->hari }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $jadwal->mataPelajaran->name ?? '-' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $jadwal->mataPelajaran->guru->name ?? '-' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @php
                                    $pertemuanCount = App\Models\Kehadiran::where('kelas_id', $kelas->id)
                                        ->where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
                                        ->select('tanggal')
                                        ->distinct()
                                        ->count();
                                @endphp
                                <div class="flex flex-wrap gap-1">
                                    @for($i = 1; $i <= $pertemuanCount; $i++)
                                        <a href="{{ route('admin.kelas.jadwal.pertemuan', [$kelas->id, $jadwal->id, $i]) }}" 
                                           class="px-2 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition text-xs">
                                            P-{{ $i }}
                                        </a>
                                    @endfor
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-center text-gray-500">Belum ada jadwal untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Siswa</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Nama</th>
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">NISN</th>
                        @if(($context ?? null) !== 'nilai' && Auth::user()->hasRole('admin'))
                        <th class="py-3 px-4 bg-gray-100 font-semibold text-sm text-gray-700 border-b border-gray-200 text-left">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $s)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b border-gray-200 font-medium">{{ $s->name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $s->nisn ?? '-' }}</td>
                            @if(($context ?? null) !== 'nilai' && Auth::user()->hasRole('admin'))
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="flex space-x-2">
                                    @if($context === 'kehadiran')
                                        <a href="{{ route('admin.kelas.siswa.kehadiran.index', [$kelas->id, $s->id]) }}" class="px-3 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition">
                                            <i class="fas fa-calendar-check mr-1"></i> Kehadiran
                                        </a>
                                    @elseif($context === 'nilai')
                                        <a href="{{ route('admin.kelas.siswa.nilai.index', [$kelas->id, $s->id]) }}" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition">
                                            <i class="fas fa-graduation-cap mr-1"></i> Nilai
                                        </a>
                                    @else
                                        <a href="{{ route('admin.kelas.siswa.kehadiran.index', [$kelas->id, $s->id]) }}" class="px-3 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition">
                                            <i class="fas fa-calendar-check mr-1"></i> Kehadiran
                                        </a>
                                        <a href="{{ route('admin.kelas.siswa.nilai.index', [$kelas->id, $s->id]) }}" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition">
                                            <i class="fas fa-graduation-cap mr-1"></i> Nilai
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.kelas.siswa.destroy', [$kelas->id, $s->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini dari kelas?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (($context ?? null) !== 'nilai' && Auth::user()->hasRole('admin')) ? 3 : 2 }}" class="py-4 px-4 text-center text-gray-500">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection