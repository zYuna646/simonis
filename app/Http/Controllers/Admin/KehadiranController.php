<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    public function index($kelasId, $userId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $kehadirans = Kehadiran::where('kelas_id', $kelasId)
            ->where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('admin.kelas.siswa.kehadiran.index', compact('kelas', 'siswa', 'kehadirans'));
    }

    public function create($kelasId, $userId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        
        return view('admin.kelas.siswa.kehadiran.create', compact('kelas', 'siswa'));
    }

    public function store(Request $request, $kelasId, $userId)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,ijin,sakit',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
        
        Kehadiran::create([
            'user_id' => $userId,
            'kelas_id' => $kelasId,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()->route('admin.kelas.siswa.kehadiran.index', [$kelasId, $userId])
            ->with('success', 'Data kehadiran berhasil ditambahkan');
    }

    public function edit($kelasId, $userId, $kehadiranId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $kehadiran = Kehadiran::findOrFail($kehadiranId);
        
        return view('admin.kelas.siswa.kehadiran.edit', compact('kelas', 'siswa', 'kehadiran'));
    }

    public function update(Request $request, $kelasId, $userId, $kehadiranId)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,ijin,sakit',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
        
        $kehadiran = Kehadiran::findOrFail($kehadiranId);
        $kehadiran->update([
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()->route('admin.kelas.siswa.kehadiran.index', [$kelasId, $userId])
            ->with('success', 'Data kehadiran berhasil diperbarui');
    }

    public function destroy($kelasId, $userId, $kehadiranId)
    {
        $kehadiran = Kehadiran::findOrFail($kehadiranId);
        $kehadiran->delete();
        
        return redirect()->route('admin.kelas.siswa.kehadiran.index', [$kelasId, $userId])
            ->with('success', 'Data kehadiran berhasil dihapus');
    }
}