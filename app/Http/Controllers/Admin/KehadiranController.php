<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\User;
use App\Models\KelasSiswa;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    /**
     * Resolve mata pelajaran yang diajar guru untuk kelas ini.
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
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas($kelas->id);
        $mataPelajaran = $mataPelajaranId ? MataPelajaran::with('guru')->find($mataPelajaranId) : null;
        
        return view('admin.kelas.siswa.kehadiran.create', compact('kelas', 'siswa', 'mataPelajaran'));
    }

    public function store(Request $request, $kelasId, $userId)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,ijin,sakit',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);
        
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('kehadiran', 'public');
        }

        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas((int)$kelasId);
        
        Kehadiran::create([
            'user_id' => $userId,
            'kelas_id' => $kelasId,
            'mata_pelajaran_id' => $mataPelajaranId,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'lampiran' => $lampiranPath,
        ]);
        
        return redirect()->route('admin.kelas.siswa.kehadiran.index', [$kelasId, $userId])
            ->with('success', 'Data kehadiran berhasil ditambahkan');
    }

    public function edit($kelasId, $userId, $kehadiranId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswa = User::findOrFail($userId);
        $kehadiran = Kehadiran::findOrFail($kehadiranId);
        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas($kelas->id);
        $mataPelajaran = $mataPelajaranId ? MataPelajaran::with('guru')->find($mataPelajaranId) : null;
        
        return view('admin.kelas.siswa.kehadiran.edit', compact('kelas', 'siswa', 'kehadiran', 'mataPelajaran'));
    }

    public function update(Request $request, $kelasId, $userId, $kehadiranId)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,ijin,sakit',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);
        
        $kehadiran = Kehadiran::findOrFail($kehadiranId);
        
        $data = [
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ];

        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas((int)$kelasId);
        $data['mata_pelajaran_id'] = $mataPelajaranId;
        
        if ($request->hasFile('lampiran')) {
            if (!empty($kehadiran->lampiran)) {
                Storage::disk('public')->delete($kehadiran->lampiran);
            }
            $data['lampiran'] = $request->file('lampiran')->store('kehadiran', 'public');
        }
        
        $kehadiran->update($data);
        
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

    // === INPUT MASSAL KEHADIRAN UNTUK SELURUH SISWA DI DALAM KELAS ===
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

        return view('admin.kelas.siswa.kehadiran.bulk_create', compact('kelas', 'siswa', 'mataPelajaran'));
    }

    public function bulkStore(Request $request, $kelasId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,tidak_hadir,ijin,sakit',
            'keterangan' => 'array',
            'keterangan.*' => 'nullable|string',
            'simpan' => 'array',
            // lampiran bisa satu file (global) atau array per user
            'lampiran' => 'nullable',
        ]);

        $mataPelajaranId = $this->resolveMataPelajaranIdForKelas((int)$kelasId);
        $simpan = $request->input('simpan', []);
        $statusInput = $request->input('status', []);
        $keteranganInput = $request->input('keterangan', []);
        $lampiranFiles = $request->file('lampiran');
        $globalLampiran = null;
        if ($lampiranFiles && !is_array($lampiranFiles)) {
            // Satu file berlaku untuk semua user
            $globalLampiran = $lampiranFiles;
        }

        foreach ($simpan as $userId => $checked) {
            if (!isset($statusInput[$userId])) {
                continue;
            }

            $existing = Kehadiran::where('kelas_id', $kelasId)
                ->where('user_id', $userId)
                ->whereDate('tanggal', $request->tanggal)
                ->first();
            
            $file = null;
            if (is_array($lampiranFiles)) {
                $file = $lampiranFiles[$userId] ?? null;
            } else {
                $file = $globalLampiran;
            }

            if ($existing) {
                $updateData = [
                    'status' => $statusInput[$userId],
                    'keterangan' => $keteranganInput[$userId] ?? null,
                    'mata_pelajaran_id' => $mataPelajaranId,
                ];
                if ($file) {
                    if (!empty($existing->lampiran)) {
                        Storage::disk('public')->delete($existing->lampiran);
                    }
                    $updateData['lampiran'] = $file->store('kehadiran', 'public');
                }
                $existing->update($updateData);
            } else {
                $path = $file ? $file->store('kehadiran', 'public') : null;
                Kehadiran::create([
                    'user_id' => $userId,
                    'kelas_id' => $kelasId,
                    'mata_pelajaran_id' => $mataPelajaranId,
                    'status' => $statusInput[$userId],
                    'tanggal' => $request->tanggal,
                    'keterangan' => $keteranganInput[$userId] ?? null,
                    'lampiran' => $path,
                ]);
            }
        }

        return redirect()->route('admin.kelas.siswa.index', $kelasId)
            ->with('success', 'Kehadiran siswa berhasil ditambahkan secara massal');
    }
    // Rekap kehadiran seluruh siswa dalam kelas
    public function rekapKelas($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswaList = User::whereIn('id', KelasSiswa::where('kelas_id', $kelasId)->pluck('user_id'))
            ->orderBy('name')
            ->get();

        $pertemuan = Kehadiran::where('kelas_id', $kelasId)
            ->select('tanggal')
            ->distinct()
            ->orderBy('tanggal')
            ->get()
            ->pluck('tanggal');

        $records = Kehadiran::where('kelas_id', $kelasId)->get();
        $index = [];
        foreach ($records as $rec) {
            $keyDate = $rec->tanggal instanceof \Carbon\Carbon ? $rec->tanggal->format('Y-m-d') : (string)$rec->tanggal;
            $index[$rec->user_id][$keyDate] = $rec->status;
        }

        // hitung total per status per siswa
        $totals = [];
        foreach ($siswaList as $s) {
            $userRecs = $records->where('user_id', $s->id);
            $totals[$s->id] = [
                'hadir' => $userRecs->where('status', 'hadir')->count(),
                'tidak_hadir' => $userRecs->where('status', 'tidak_hadir')->count(),
                'ijin' => $userRecs->where('status', 'ijin')->count(),
                'sakit' => $userRecs->where('status', 'sakit')->count(),
            ];
        }

        return view('admin.kelas.siswa.kehadiran.rekap', compact('kelas', 'siswaList', 'pertemuan', 'index', 'totals'));
    }

    public function rekapKelasPdf($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $siswaList = User::whereIn('id', KelasSiswa::where('kelas_id', $kelasId)->pluck('user_id'))
            ->orderBy('name')
            ->get();

        $pertemuan = Kehadiran::where('kelas_id', $kelasId)
            ->select('tanggal')
            ->distinct()
            ->orderBy('tanggal')
            ->get()
            ->pluck('tanggal');

        $records = Kehadiran::where('kelas_id', $kelasId)->get();
        $index = [];
        foreach ($records as $rec) {
            $keyDate = $rec->tanggal instanceof \Carbon\Carbon ? $rec->tanggal->format('Y-m-d') : (string)$rec->tanggal;
            $index[$rec->user_id][$keyDate] = $rec->status;
        }

        $totals = [];
        foreach ($siswaList as $s) {
            $userRecs = $records->where('user_id', $s->id);
            $totals[$s->id] = [
                'hadir' => $userRecs->where('status', 'hadir')->count(),
                'tidak_hadir' => $userRecs->where('status', 'tidak_hadir')->count(),
                'ijin' => $userRecs->where('status', 'ijin')->count(),
                'sakit' => $userRecs->where('status', 'sakit')->count(),
            ];
        }

        $html = view('admin.kelas.siswa.kehadiran.rekap_pdf', compact('kelas', 'siswaList', 'pertemuan', 'index', 'totals'))->render();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'rekap-kehadiran-' . preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($kelas->name)) . '.pdf';
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}