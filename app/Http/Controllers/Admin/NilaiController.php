<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\User;
use App\Models\KelasSiswa;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Ambil mata pelajaran yang diajar oleh guru untuk kelas ini.
     * Prioritas: guru login; jika admin, gunakan guru yang diassign ke kelas.
     * Tidak bergantung pada hari.
     */
    protected function resolveMataPelajaranIdForKelas(int $kelasId): ?int
    {
        $kelas = Kelas::find($kelasId);
        if (!$kelas) {
            return null;
        }

        $guruId = auth()->user()->hasRole('guru') ? auth()->id() : ($kelas->user_id ?? null);

        if ($guruId) {
            $jadwalDenganGuru = Jadwal::where('kelas_id', $kelasId)
                ->whereHas('mataPelajaran', function($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })
                ->first();
            if ($jadwalDenganGuru) {
                return $jadwalDenganGuru->mata_pelajaran_id;
            }
        }

        $jadwalPertama = Jadwal::where('kelas_id', $kelasId)->first();
        return $jadwalPertama ? $jadwalPertama->mata_pelajaran_id : null;
    }
    public function index($kelasId, $userId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $nilais = Nilai::where('kelas_id', $kelasId)
            ->where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas($kelas->id);
        $mataPelajaran = $mataPelajaranId ? MataPelajaran::with('guru')->find($mataPelajaranId) : null;
        
        return view('admin.kelas.siswa.nilai.index', compact('kelas', 'siswa', 'nilais', 'mataPelajaran'));
    }

    public function create($kelasId, $userId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas($kelas->id);
        $mataPelajaran = $mataPelajaranId ? MataPelajaran::with('guru')->find($mataPelajaranId) : null;
        
        return view('admin.kelas.siswa.nilai.create', compact('kelas', 'siswa', 'mataPelajaran'));
    }

    public function store(Request $request, $kelasId, $userId)
    {
        $request->validate([
            'jenis' => 'required|in:ulangan,tugas,praktek,remedial',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);
        
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas((int)$kelasId);
        
        Nilai::create([
            'user_id' => $userId,
            'kelas_id' => $kelasId,
            'mata_pelajaran_id' => $mataPelajaranId,
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
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas($kelas->id);
        $mataPelajaran = $mataPelajaranId ? MataPelajaran::with('guru')->find($mataPelajaranId) : null;
        
        return view('admin.kelas.siswa.nilai.edit', compact('kelas', 'siswa', 'nilai', 'mataPelajaran'));
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
        $data = [
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
        ];
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas((int)$kelasId);
        $data['mata_pelajaran_id'] = $mataPelajaranId;
        $nilai->update($data);
        
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

        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas($kelas->id);
        $mataPelajaran = $mataPelajaranId ? MataPelajaran::with('guru')->find($mataPelajaranId) : null;

        // Gunakan view create yang sama, tetapi akan mendeteksi koleksi siswa untuk mode bulk
        return view('admin.kelas.siswa.nilai.create', compact('kelas', 'siswa', 'mataPelajaran'));
    }

    public function bulkStore(Request $request, $kelasId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:ulangan,tugas,praktek,remedial',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'array',
            'keterangan.*' => 'nullable|string',
            'simpan' => 'array',
        ]);

        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas((int)$kelasId);
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
                'mata_pelajaran_id' => $mataPelajaranId,
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