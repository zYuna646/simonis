<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\User;
use App\Models\KelasSiswa;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('admin')) {
            // Data untuk admin
            $data['totalUsers'] = User::count();
            $data['totalRoles'] = Role::count();
            $data['totalPermissions'] = Permission::count();
            // Tambahan: statistik per role
            $data['totalGuru'] = \App\Models\User::role('guru')->count();
            $data['totalSiswa'] = \App\Models\User::role('siswa')->count();
            $data['totalOrangTua'] = \App\Models\User::role('orang_tua')->count();
        } 
        elseif ($user->hasRole('guru')) {
            // Data untuk guru
            $kelas = Kelas::where('user_id', $user->id)->get();
            $data['kelas'] = $kelas;
            $data['totalKelas'] = $kelas->count();
            $data['totalSiswa'] = 0;
            
            foreach ($kelas as $k) {
                $data['totalSiswa'] += $k->totalSiswa();
            }
        } 
        elseif ($user->hasRole('siswa')) {
            // Data untuk siswa
            $kelasIds = KelasSiswa::where('user_id', $user->id)->pluck('kelas_id')->toArray();
            $kelas = Kelas::whereIn('id', $kelasIds)->get();
            
            $data['kelas'] = $kelas;
            $data['kehadiran'] = [];
            $data['nilai'] = [];
            $data['mapel'] = [];
            $selectedMapelId = request()->get('mapel_id');
            $selectedKelasId = (int) request()->get('kelas_id');
            
            foreach ($kelas as $k) {
                // Opsi mapel per kelas (unik)
                $mapelOptions = \App\Models\Jadwal::with('mataPelajaran')
                    ->where('kelas_id', $k->id)
                    ->get()
                    ->map(function($j){ return $j->mataPelajaran; })
                    ->filter()
                    ->unique('id')
                    ->values();
                $data['mapel'][$k->id] = $mapelOptions;

                $data['kehadiran'][$k->id] = Kehadiran::where('user_id', $user->id)
                    ->where('kelas_id', $k->id)
                    ->when($selectedMapelId && $selectedKelasId === $k->id, function($q) use ($selectedMapelId) {
                        $q->where('mata_pelajaran_id', (int) $selectedMapelId);
                    })
                    ->orderBy('tanggal', 'desc')
                    ->take(5)
                    ->get();
                    
                $data['nilai'][$k->id] = Nilai::where('user_id', $user->id)
                    ->where('kelas_id', $k->id)
                    ->when($selectedMapelId && $selectedKelasId === $k->id, function($q) use ($selectedMapelId) {
                        $q->where('mata_pelajaran_id', (int) $selectedMapelId);
                    })
                    ->orderBy('tanggal', 'desc')
                    ->take(5)
                    ->get();
            }
        } 
        elseif ($user->hasRole('orang_tua')) {
            // Data untuk orang tua
            // Utamakan relasi berdasarkan NISN anak yang disimpan di akun orang tua
            $anak = collect();
            if (!empty($user->nisn)) {
                $anak = User::where('nisn', $user->nisn)
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'siswa');
                    })
                    ->get();
            }

            // Fallback: jika NISN tidak ada/anak tidak ditemukan, gunakan NKK lama bila tersedia
            if ($anak->isEmpty() && !empty($user->nkk)) {
                $anak = User::where('nkk', $user->nkk)
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'siswa');
                    })
                    ->get();
            }

            $data['anak'] = $anak;
            $data['kehadiran'] = [];
            $data['nilai'] = [];
            $data['kelas'] = [];
            $data['mapel'] = [];
            
            foreach ($anak as $a) {
                $kelasIds = KelasSiswa::where('user_id', $a->id)->pluck('kelas_id')->toArray();
                $kelasAnak = Kelas::whereIn('id', $kelasIds)->get();
                $data['kelas'][$a->id] = $kelasAnak;
                
                foreach ($kelasAnak as $k) {
                    // Opsi mapel unik per kelas untuk anak ini (berdasarkan jadwal)
                    $mapelOptionsPerKelas = \App\Models\Jadwal::with('mataPelajaran')
                        ->where('kelas_id', $k->id)
                        ->get()
                        ->map(function($j){ return $j->mataPelajaran; })
                        ->filter()
                        ->unique('id')
                        ->values();
                    $data['mapel'][$k->id] = $mapelOptionsPerKelas;

                    $data['kehadiran'][$a->id][$k->id] = Kehadiran::where('user_id', $a->id)
                        ->where('kelas_id', $k->id)
                        ->orderBy('tanggal', 'desc')
                        ->take(5)
                        ->get();
                        
                    $data['nilai'][$a->id][$k->id] = Nilai::where('user_id', $a->id)
                        ->where('kelas_id', $k->id)
                        ->orderBy('tanggal', 'desc')
                        ->take(5)
                        ->get();
                }
            }
        }

        return view('admin.dashboard', compact('data'));
    }
}