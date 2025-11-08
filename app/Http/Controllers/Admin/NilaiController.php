<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\User;
use App\Models\KelasSiswa;
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

    // === BULK INPUT NILAI UNTUK SELURUH SISWA DI DALAM KELAS ===
    public function bulkCreate($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswaIds = KelasSiswa::where('kelas_id', $kelasId)->pluck('user_id')->toArray();
        $siswa = User::whereIn('id', $siswaIds)
            ->whereHas('roles', function($q) { $q->where('name', 'siswa'); })
            ->orderBy('name')
            ->get();

        // Gunakan view create yang sama, tetapi akan mendeteksi koleksi siswa untuk mode bulk
        return view('admin.kelas.siswa.nilai.create', compact('kelas', 'siswa'));
    }

    public function bulkStore(Request $request, $kelasId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:ulangan,tugas,praktek,remedial',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'array',
            'keterangan.*' => 'nullable|string',
            'simpan' => 'array',
        ]);

        $simpan = $request->input('simpan', []);
        $nilaiInput = $request->input('nilai', []);
        $keteranganInput = $request->input('keterangan', []);

        foreach ($simpan as $userId => $checked) {
            if (!isset($nilaiInput[$userId])) {
                continue; // lewati jika nilai kosong
            }

            Nilai::create([
                'user_id' => $userId,
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'jenis' => $request->jenis,
                'tanggal' => $request->tanggal,
                'nilai' => $nilaiInput[$userId],
                'keterangan' => $keteranganInput[$userId] ?? null,
            ]);
        }

        return redirect()->route('admin.kelas.siswa.index', $kelasId)
            ->with('success', 'Nilai siswa berhasil ditambahkan secara massal');
    }
}