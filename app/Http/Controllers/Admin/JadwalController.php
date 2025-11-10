<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JadwalController extends Controller
{
    /**
     * Tampilkan jadwal per kelas, terkelompok per hari.
     */
    public function index(int $kelasId): View
    {
        $kelas = Kelas::findOrFail($kelasId);
        $jadwals = Jadwal::with('mataPelajaran')
            ->where('kelas_id', $kelas->id)
            ->orderBy('hari')
            ->get();

        $days = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
        ];

        $grouped = [];
        foreach ($days as $key => $label) {
            $grouped[$key] = $jadwals->where('hari', $key);
        }

        return view('admin.kelas.jadwal.index', compact('kelas', 'days', 'grouped'));
    }

    /**
     * Form tambah jadwal untuk kelas.
     */
    public function create(int $kelasId): View
    {
        $kelas = Kelas::findOrFail($kelasId);
        $mataPelajarans = MataPelajaran::orderBy('name')->get();
        $days = ['senin' => 'Senin','selasa' => 'Selasa','rabu' => 'Rabu','kamis' => 'Kamis','jumat' => 'Jumat','sabtu' => 'Sabtu'];
        return view('admin.kelas.jadwal.create', compact('kelas', 'mataPelajarans', 'days'));
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request, int $kelasId): RedirectResponse
    {
        $kelas = Kelas::findOrFail($kelasId);

        $request->validate([
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
        ]);

        // Cek duplikasi jadwal untuk kombinasi (kelas, mapel, hari)
        $exists = Jadwal::where('kelas_id', $kelas->id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('hari', $request->hari)
            ->exists();

        if ($exists) {
            $dayLabels = [
                'senin' => 'Senin',
                'selasa' => 'Selasa',
                'rabu' => 'Rabu',
                'kamis' => 'Kamis',
                'jumat' => 'Jumat',
                'sabtu' => 'Sabtu',
            ];
            $label = $dayLabels[$request->hari] ?? $request->hari;
            return back()
                ->withErrors(['mata_pelajaran_id' => "Mata pelajaran sudah dijadwalkan pada hari $label untuk kelas ini."])
                ->withInput();
        }

        Jadwal::create([
            'hari' => $request->hari,
            'kelas_id' => $kelas->id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
        ]);

        return redirect()->route('admin.kelas.siswa.index', [$kelas->id, 'context' => $request->input('context')])
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(int $kelasId, Jadwal $jadwal): RedirectResponse
    {
        $this->authorizeAction();
        if ($jadwal->kelas_id != $kelasId) {
            abort(404);
        }
        $jadwal->delete();
        return redirect()->route('admin.kelas.jadwal.index', $kelasId)
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Pembatasan akses untuk admin/guru.
     */
    protected function authorizeAction(): void
    {
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('guru')) {
            abort(403);
        }
    }
    
    /**
     * Tampilkan detail pertemuan untuk jadwal tertentu.
     */
    public function pertemuan(int $kelasId, int $jadwalId, int $pertemuan): View
    {
        $kelas = Kelas::findOrFail($kelasId);
        $jadwal = Jadwal::with('mataPelajaran')->findOrFail($jadwalId);
        
        // Ambil data kehadiran untuk pertemuan tertentu
        $kehadirans = \App\Models\Kehadiran::with('user')
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
            ->whereHas('user', function($query) use ($kelas) {
                $query->whereHas('kelasSiswa', function($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id);
                });
            })
            ->get()
            ->groupBy('tanggal')
            ->values()
            ->all();
            
        // Pastikan pertemuan yang diminta valid
        if ($pertemuan < 1 || $pertemuan > count($kehadirans)) {
            abort(404, 'Pertemuan tidak ditemukan');
        }
        
        // Ambil data kehadiran untuk pertemuan yang diminta (index dimulai dari 0)
        $pertemuanData = $kehadirans[$pertemuan - 1] ?? collect();
        $tanggalPertemuan = $pertemuanData->first()->tanggal ?? null;
        
        return view('admin.kelas.jadwal.pertemuan', compact('kelas', 'jadwal', 'pertemuan', 'pertemuanData', 'tanggalPertemuan'));
    }
}