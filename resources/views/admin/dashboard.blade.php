@extends('dashboard')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    @if(Auth::user()->hasRole('admin'))
    <!-- Dashboard Admin -->
    <h2 class="text-lg font-medium text-gray-900 mb-4">Dashboard Admin</h2>
    <p class="text-gray-600">Selamat datang di panel admin Simonis, {{ Auth::user()->name }}!</p>
    
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalUsers'] }}</dd>
                </dl>
            </div>
        </div>
        
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Peran</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalRoles'] }}</dd>
                </dl>
            </div>
        </div>
        
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Izin</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalPermissions'] }}</dd>
                </dl>
            </div>
        </div>
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Guru</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalGuru'] }}</dd>
                </dl>
            </div>
        </div>
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Orang Tua</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalOrangTua'] }}</dd>
                </dl>
            </div>
        </div>
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Siswa</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalSiswa'] }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    @elseif(Auth::user()->hasRole('guru'))
    <!-- Dashboard Guru -->
    <h2 class="text-lg font-medium text-gray-900 mb-4">Dashboard Guru</h2>
    <p class="text-gray-600">Selamat datang di panel guru Simonis, {{ Auth::user()->name }}!</p>
    
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-2">
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Kelas</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalKelas'] }}</dd>
                </dl>
            </div>
        </div>
        
        <!-- Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Siswa</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $data['totalSiswa'] }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Kelas Yang Anda Ajar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($data['kelas'] as $kelas)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                <div class="p-5 bg-blue-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $kelas->name }}</h3>
                    <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Tingkat {{ $kelas->tingkat ?? 'Belum diatur' }}</span>
                    <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">{{ $kelas->totalSiswa() }} Siswa</span>
                </div>
                <div class="p-5">
                    <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <i class="fas fa-users mr-2"></i>Lihat Siswa
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    @elseif(Auth::user()->hasRole('siswa'))
    <!-- Dashboard Siswa -->
    <h2 class="text-lg font-medium text-gray-900 mb-4">Dashboard Siswa</h2>
    <p class="text-gray-600">Selamat datang di panel siswa Simonis, {{ Auth::user()->name }}!</p>
    
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Kelas Anda</h3>
        <!-- Filter Mapel Global untuk Semua Kelas -->
        @php
            $allMapel = collect($data['mapel'] ?? [])
                ->flatten(1)
                ->unique('id')
                ->values();
        @endphp
        <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 flex items-end space-x-2">
            <div>
                <label for="global_mapel_id" class="block text-xs font-medium text-gray-700 mb-1">Pilih Mapel (Global)</label>
                <select id="global_mapel_id" name="mapel_id" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($allMapel as $mp)
                        <option value="{{ $mp->id }}" {{ request('mapel_id') == $mp->id ? 'selected' : '' }}>{{ $mp->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filter</button>
        </form>
        
        @foreach($data['kelas'] as $kelas)
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition mb-8">
            <div class="p-5 bg-blue-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">{{ $kelas->name }}</h3>
                <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Tingkat {{ $kelas->tingkat ?? 'Belum diatur' }}</span>
                <p class="mt-2 text-sm text-gray-600">Guru: {{ $kelas->user->name }}</p>
            </div>
            
            <div class="p-5">
                @php
                    $selectedMapelId = request('mapel_id') ? (int)request('mapel_id') : null;
                    $hasSelectedMapel = $selectedMapelId
                        ? collect($data['mapel'][$kelas->id] ?? [])->contains(function($m) use ($selectedMapelId) { return $m->id == $selectedMapelId; })
                        : false;
                @endphp
                @if($selectedMapelId)
                    @if($hasSelectedMapel)
                        <p class="text-sm text-gray-600 mb-2">Filter mapel: <span class="font-medium">{{ optional(collect($data['mapel'][$kelas->id] ?? [])->firstWhere('id', $selectedMapelId))->name }}</span></p>

                        @php
                            $filteredKehadiran = collect($data['kehadiran'][$kelas->id] ?? [])
                                ->filter(function($k) use ($selectedMapelId) { return (int)$k->mata_pelajaran_id === $selectedMapelId; })
                                ->values();
                            $filteredNilai = collect($data['nilai'][$kelas->id] ?? [])
                                ->filter(function($n) use ($selectedMapelId) { return (int)$n->mata_pelajaran_id === $selectedMapelId; })
                                ->values();
                        @endphp

                        <h4 class="font-medium text-gray-900 mb-3">Kehadiran Terbaru</h4>
                        @if($filteredKehadiran->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                        <th class="py-2 px-4 text-left">Tanggal</th>
                                        <th class="py-2 px-4 text-left">Status</th>
                                        <th class="py-2 px-4 text-left">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                    @foreach($filteredKehadiran as $kehadiran)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $kehadiran->tanggal->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4">
                                            @if($kehadiran->status == 'hadir')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Hadir</span>
                                            @elseif($kehadiran->status == 'tidak_hadir')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Tidak Hadir</span>
                                            @elseif($kehadiran->status == 'ijin')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Ijin</span>
                                            @elseif($kehadiran->status == 'sakit')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Sakit</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4">{{ $kehadiran->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.kelas.siswa.kehadiran.index', [$kelas->id, Auth::id()]) }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua kehadiran →</a>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">Belum ada data kehadiran untuk mapel ini.</p>
                        @endif

                        <h4 class="font-medium text-gray-900 mb-3 mt-6">Nilai Terbaru</h4>
                        @if($filteredNilai->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                        <th class="py-2 px-4 text-left">Tanggal</th>
                                        <th class="py-2 px-4 text-left">Jenis</th>
                                        <th class="py-2 px-4 text-left">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                    @foreach($filteredNilai as $nilai)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $nilai->tanggal->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4">
                                            @if($nilai->jenis == 'ulangan')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Ulangan</span>
                                            @elseif($nilai->jenis == 'tugas')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Tugas</span>
                                            @elseif($nilai->jenis == 'praktek')
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Praktek</span>
                                            @elseif($nilai->jenis == 'remedial')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Remedial</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4">{{ $nilai->nilai }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.kelas.siswa.nilai.index', [$kelas->id, Auth::id()]) }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua nilai →</a>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">Belum ada data nilai untuk mapel ini.</p>
                        @endif
                    @else
                        <div class="mt-2">
                            <p class="text-gray-500 text-sm">Mapel yang dipilih tidak tersedia di kelas ini.</p>
                        </div>
                    @endif
                @else
                    <div class="mt-4">
                        <p class="text-gray-500 text-sm">Silakan pilih mapel pada filter di atas untuk melihat kehadiran dan nilai.</p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    @elseif(Auth::user()->hasRole('orang_tua'))
    <!-- Dashboard Orang Tua -->
    <h2 class="text-lg font-medium text-gray-900 mb-4">Dashboard Orang Tua</h2>
    <p class="text-gray-600">Selamat datang di panel orang tua Simonis, {{ Auth::user()->name }}!</p>
    
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Data Anak</h3>
        @php
            $selectedAnakId = request('anak_id') ? (int)request('anak_id') : null;
            $selectedMapelId = request('mapel_id') ? (int)request('mapel_id') : null;
            $mapelOptions = collect();
            if ($selectedAnakId) {
                $kelasListForChild = $data['kelas'][$selectedAnakId] ?? [];
                $mapelOptions = collect($kelasListForChild)
                    ->flatMap(function($kelas) use ($data) {
                        return $data['mapel'][$kelas->id] ?? [];
                    })
                    ->unique('id')
                    ->values();
            }
        @endphp

        <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 flex flex-wrap items-end gap-3">
            <div>
                <label for="anak_id" class="block text-xs font-medium text-gray-700 mb-1">Pilih Anak</label>
                <select id="anak_id" name="anak_id" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Anak --</option>
                    @foreach(($data['anak'] ?? []) as $anakOption)
                        <option value="{{ $anakOption->id }}" {{ request('anak_id') == $anakOption->id ? 'selected' : '' }}>{{ $anakOption->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="mapel_id_parent" class="block text-xs font-medium text-gray-700 mb-1">Pilih Mapel</label>
                <select id="mapel_id_parent" name="mapel_id" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" {{ $selectedAnakId ? '' : 'disabled' }}>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapelOptions as $mp)
                        <option value="{{ $mp->id }}" {{ request('mapel_id') == $mp->id ? 'selected' : '' }}>{{ $mp->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filter</button>
        </form>
        @if(!$selectedAnakId)
            <p class="text-gray-500 text-sm mb-6">Silakan pilih anak lalu mapel untuk melihat data kehadiran dan nilai.</p>
        @endif
        
        @if($selectedAnakId)
            @foreach($data['anak'] as $anak)
                @if($anak->id != $selectedAnakId)
                    @continue
                @endif
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition mb-8">
                    <div class="p-5 bg-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $anak->name }}</h3>
                        <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">NISN: {{ $anak->nisn }}</span>
                    </div>

                    @if(isset($data['kelas'][$anak->id]) && count($data['kelas'][$anak->id]) > 0)
                        @foreach($data['kelas'][$anak->id] as $kelas)
                        @php
                            $hasSelectedMapel = $selectedMapelId
                                ? collect($data['mapel'][$kelas->id] ?? [])->contains(function($m) use ($selectedMapelId) { return $m->id == $selectedMapelId; })
                                : false;
                            $filteredKehadiran = collect($data['kehadiran'][$anak->id][$kelas->id] ?? [])
                                ->filter(function($k) use ($selectedMapelId) { return $selectedMapelId ? (int)$k->mata_pelajaran_id === $selectedMapelId : false; })
                                ->values();
                            $filteredNilai = collect($data['nilai'][$anak->id][$kelas->id] ?? [])
                                ->filter(function($n) use ($selectedMapelId) { return $selectedMapelId ? (int)$n->mata_pelajaran_id === $selectedMapelId : false; })
                                ->values();
                        @endphp
                        <div class="p-5 border-b border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Kelas: {{ $kelas->name }}</h4>
                            <p class="text-sm text-gray-600 mb-4">Guru: {{ $kelas->user->name }}</p>

                            @if($selectedMapelId)
                                @if($hasSelectedMapel)
                                    <p class="text-sm text-gray-600 mb-2">Filter mapel: <span class="font-medium">{{ optional(collect($data['mapel'][$kelas->id] ?? [])->firstWhere('id', $selectedMapelId))->name }}</span></p>
                                @else
                                    <div class="mt-2">
                                        <p class="text-gray-500 text-sm">Mapel yang dipilih tidak tersedia di kelas ini.</p>
                                    </div>
                                @endif
                            @else
                                <div class="mt-2">
                                    <p class="text-gray-500 text-sm">Silakan pilih mapel untuk melihat data kehadiran dan nilai.</p>
                                </div>
                            @endif

                            <h5 class="font-medium text-gray-900 mb-3">Kehadiran Terbaru</h5>
                            @if($filteredKehadiran->count() > 0 && $hasSelectedMapel)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead>
                                        <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                            <th class="py-2 px-4 text-left">Tanggal</th>
                                            <th class="py-2 px-4 text-left">Status</th>
                                            <th class="py-2 px-4 text-left">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm">
                                        @foreach($filteredKehadiran as $kehadiran)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="py-2 px-4">{{ $kehadiran->tanggal->format('d/m/Y') }}</td>
                                            <td class="py-2 px-4">
                                                @if($kehadiran->status == 'hadir')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Hadir</span>
                                                @elseif($kehadiran->status == 'tidak_hadir')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Tidak Hadir</span>
                                                @elseif($kehadiran->status == 'ijin')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Ijin</span>
                                                @elseif($kehadiran->status == 'sakit')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Sakit</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4">{{ $kehadiran->keterangan ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.kelas.siswa.kehadiran.index', [$kelas->id, $anak->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua kehadiran →</a>
                            </div>
                            @else
                            <p class="text-gray-500 text-sm">Belum ada data kehadiran untuk mapel ini.</p>
                            @endif

                            <h5 class="font-medium text-gray-900 mb-3 mt-6">Nilai Terbaru</h5>
                            @if($filteredNilai->count() > 0 && $hasSelectedMapel)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead>
                                        <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                            <th class="py-2 px-4 text-left">Tanggal</th>
                                            <th class="py-2 px-4 text-left">Jenis</th>
                                            <th class="py-2 px-4 text-left">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm">
                                        @foreach($filteredNilai as $nilai)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="py-2 px-4">{{ $nilai->tanggal->format('d/m/Y') }}</td>
                                            <td class="py-2 px-4">
                                                @if($nilai->jenis == 'ulangan')
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Ulangan</span>
                                                @elseif($nilai->jenis == 'tugas')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Tugas</span>
                                                @elseif($nilai->jenis == 'praktek')
                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Praktek</span>
                                                @elseif($nilai->jenis == 'remedial')
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Remedial</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4">{{ $nilai->nilai }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.kelas.siswa.nilai.index', [$kelas->id, $anak->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat semua nilai →</a>
                            </div>
                            @else
                            <p class="text-gray-500 text-sm">Belum ada data nilai untuk mapel ini.</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="p-5">
                            <p class="text-gray-500">Belum terdaftar di kelas manapun</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
        
        @if(!$selectedAnakId)
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-gray-500">Silakan pilih anak dan mapel untuk melihat data.</p>
        </div>
        @endif

        @if(count($data['anak']) == 0)
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-gray-500">Tidak ada data anak yang terdaftar dengan NKK yang sama</p>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection