<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index($kelasId, $userId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $nilais = Nilai::where('kelas_id', $kelasId)
            ->where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('admin.kelas.siswa.nilai.index', compact('kelas', 'siswa', 'nilais'));
    }

    public function create($kelasId, $userId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        
        return view('admin.kelas.siswa.nilai.create', compact('kelas', 'siswa'));
    }

    public function store(Request $request, $kelasId, $userId)
    {
        $request->validate([
            'jenis' => 'required|in:ulangan,tugas,praktek,remedial',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);
        
        Nilai::create([
            'user_id' => $userId,
            'kelas_id' => $kelasId,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()->route('admin.kelas.siswa.nilai.index', [$kelasId, $userId])
            ->with('success', 'Data nilai berhasil ditambahkan');
    }

    public function edit($kelasId, $userId, $nilaiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $nilai = Nilai::findOrFail($nilaiId);
        
        return view('admin.kelas.siswa.nilai.edit', compact('kelas', 'siswa', 'nilai'));
    }

    public function update(Request $request, $kelasId, $userId, $nilaiId)
    {
        $request->validate([
            'jenis' => 'required|in:ulangan,tugas,praktek,remedial',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);
        
        $nilai = Nilai::findOrFail($nilaiId);
        $nilai->update([
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()->route('admin.kelas.siswa.nilai.index', [$kelasId, $userId])
            ->with('success', 'Data nilai berhasil diperbarui');
    }

    public function destroy($kelasId, $userId, $nilaiId)
    {
        $nilai = Nilai::findOrFail($nilaiId);
        $nilai->delete();
        
        return redirect()->route('admin.kelas.siswa.nilai.index', [$kelasId, $userId])
            ->with('success', 'Data nilai berhasil dihapus');
    }
}