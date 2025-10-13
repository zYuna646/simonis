<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;
use App\Models\KelasSiswa;
use Illuminate\Support\Facades\DB;

class KelasSiswaController extends Controller
{
    public function index($id)
    {
        $kelas = Kelas::findOrFail($id);
        $siswaIds = KelasSiswa::where('kelas_id', $id)->pluck('user_id')->toArray();
        $siswa = User::whereIn('id', $siswaIds)
            ->whereHas('roles', function($q) {
                $q->where('name', 'siswa');
            })
            ->get();
        
        return view('admin.kelas.siswa.index', compact('kelas', 'siswa'));
    }
    
    public function create($id)
    {
        $kelas = Kelas::findOrFail($id);
        $siswaIds = KelasSiswa::where('kelas_id', $id)->pluck('user_id')->toArray();
        $siswa = User::whereHas('roles', function($q) {
                $q->where('name', 'siswa');
            })
            ->whereNotIn('id', $siswaIds)
            ->get();
            
        return view('admin.kelas.siswa.create', compact('kelas', 'siswa'));
    }
    
    public function store(Request $request, $id)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:users,id'
        ]);
        
        $kelas = Kelas::findOrFail($id);
        
        foreach ($request->siswa_ids as $siswaId) {
            KelasSiswa::create([
                'kelas_id' => $id,
                'user_id' => $siswaId
            ]);
        }
        
        return redirect()->route('admin.kelas.siswa.index', $id)
            ->with('success', 'Siswa berhasil ditambahkan ke kelas');
    }
    
    public function destroy($kelasId, $siswaId)
    {
        KelasSiswa::where('kelas_id', $kelasId)
            ->where('user_id', $siswaId)
            ->delete();
            
        return redirect()->route('admin.kelas.siswa.index', $kelasId)
            ->with('success', 'Siswa berhasil dihapus dari kelas');
    }
}