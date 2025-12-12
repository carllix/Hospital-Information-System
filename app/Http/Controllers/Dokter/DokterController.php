<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\PermintaanLab;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Rujukan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\WearableData;

class DokterController extends Controller
{
    // ... (dashboard & antrian tidak berubah) ...

    public function dashboard(): View
    {
        $dokter = Auth::user()->dokter;
        $antrianHariIni = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])->whereDoesntHave('pemeriksaan')->count();
        $pasienDitanganiHariIni = Pemeriksaan::whereDate('tanggal_pemeriksaan', today())
            ->whereHas('pendaftaran.jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            })->count();
        $totalPasienBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', now()->month)
            ->whereYear('tanggal_pemeriksaan', now()->year)
            ->whereHas('pendaftaran.jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            })->count();
        $antrianPasien = Pendaftaran::with(['pasien'])
            ->whereDate('tanggal_daftar', today())->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDoesntHave('pemeriksaan')->orderBy('nomor_antrian')->get();
            
        return view('dokter.dashboard', compact('dokter', 'antrianHariIni', 'pasienDitanganiHariIni', 'totalPasienBulanIni', 'antrianPasien'));
    }

    public function antrian(): View
    {
        $dokter = Auth::user()->dokter;
        $antrianPasien = Pendaftaran::with(['pasien'])
            ->whereDate('tanggal_daftar', today())->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDoesntHave('pemeriksaan')->orderBy('nomor_antrian')->get();
        return view('dokter.antrian', compact('antrianPasien'));
    }

    /**
     * Form Pemeriksaan Pasien
     */
    public function formPemeriksaan($id): View
    {
        $pendaftaran = Pendaftaran::with(['pasien'])->findOrFail($id);
        
        $pemeriksaan = Pemeriksaan::where('pendaftaran_id', $id)->first();
        
        // UBAH INI - tambahkan nested eager loading
        $riwayatPemeriksaan = Pemeriksaan::with(['pendaftaran.jadwalDokter.dokter']) 
            ->whereHas('pendaftaran', function($query) use ($pendaftaran) {
                $query->where('pasien_id', $pendaftaran->pasien_id);
            })
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->take(3)
            ->get();
            
        return view('dokter.form-pemeriksaan', compact('pendaftaran', 'pemeriksaan', 'riwayatPemeriksaan'));
    }
    /**
     * Simpan Pemeriksaan
     */
    public function storePemeriksaan(Request $request): RedirectResponse
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,pendaftaran_id',
            'anamnesa' => 'required|string',
            'pemeriksaan_fisik' => 'nullable|string',
            'tekanan_darah' => 'nullable|string|max:20',
            'suhu_tubuh' => 'nullable|numeric|min:30|max:45',
            'berat_badan' => 'nullable|numeric|min:1|max:300',
            'tinggi_badan' => 'nullable|numeric|min:50|max:250',
            'diagnosa' => 'required|string',
            'icd10_code' => 'nullable|string|max:10',
            'tindakan_medis' => 'nullable|string',
            'catatan_dokter' => 'nullable|string',
            'status_pasien' => 'required|in:selesai_penanganan,dirujuk,perlu_resep,perlu_lab',
        ]);

        try {
            DB::beginTransaction();
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);
            $dokter = Auth::user()->dokter;
            
            $pemeriksaan = Pemeriksaan::updateOrCreate(
                ['pendaftaran_id' => $request->pendaftaran_id],
                [
                    'dokter_id' => $dokter->dokter_id,
                    'tanggal_pemeriksaan' => now(),
                    'anamnesa' => $request->anamnesa,
                    'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                    'tekanan_darah' => $request->tekanan_darah,
                    'suhu_tubuh' => $request->suhu_tubuh,
                    'berat_badan' => $request->berat_badan,
                    'tinggi_badan' => $request->tinggi_badan,
                    'diagnosa' => $request->diagnosa,
                    'icd10_code' => $request->icd10_code,
                    'tindakan_medis' => $request->tindakan_medis,
                    'catatan_dokter' => $request->catatan_dokter,
                    'status_pasien' => $request->status_pasien,
                ]
            );
            
            $pendaftaran->update(['status' => 'selesai']);
            DB::commit();
            
            if ($request->status_pasien === 'perlu_resep') {
                return redirect()->route('dokter.form-resep', $pemeriksaan->pemeriksaan_id)
                    ->with('success', 'Pemeriksaan disimpan. Buat resep.');
            } elseif ($request->status_pasien === 'perlu_lab') {
                return redirect()->route('dokter.form-lab', $pemeriksaan->pemeriksaan_id)
                    ->with('success', 'Pemeriksaan disimpan. Buat permintaan lab.');
            } elseif ($request->status_pasien === 'dirujuk') {
                return redirect()->route('dokter.form-rujukan', $pemeriksaan->pemeriksaan_id)
                    ->with('success', 'Pemeriksaan disimpan. Buat rujukan.');
            }
            
            return redirect()->route('dokter.antrian')->with('success', 'Pemeriksaan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Resep Obat
     */
    public function formResep($pemeriksaanId): View
    {
        // PERBAIKAN: Hapus 'pasien' dan 'dokter' dari with() jika tidak ada di model
        // Gunakan 'pendaftaran.pasien' untuk mengambil data pasien
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'resep.detailResep.obat'])->findOrFail($pemeriksaanId);
        
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Akses ditolak.');
        }
        
        $obatList = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();
        return view('dokter.form-resep', compact('pemeriksaan', 'obatList'));
    }

    /**
     * Simpan Resep
     */
    public function storeResep(Request $request): RedirectResponse
    {
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaan,pemeriksaan_id',
            'obat' => 'required|array|min:1',
            'obat.*.obat_id' => 'required|exists:obat,obat_id',
            'obat.*.jumlah' => 'required|integer|min:1',
            'obat.*.aturan_pakai' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $pemeriksaan = Pemeriksaan::with('pendaftaran')->findOrFail($request->pemeriksaan_id);
            $dokter = Auth::user()->dokter;
            
            if ($pemeriksaan->dokter_id !== $dokter->dokter_id) abort(403);
            
            $resep = Resep::updateOrCreate(
                ['pemeriksaan_id' => $request->pemeriksaan_id],
                [
                    'pasien_id' => $pemeriksaan->pendaftaran->pasien_id, 
                    'dokter_id' => $dokter->dokter_id,
                    'tanggal_resep' => now(),
                    'status' => 'menunggu',
                ]
            );
            
            $resep->detailResep()->delete();
            foreach ($request->obat as $obatData) {
                DetailResep::create([
                    'resep_id' => $resep->resep_id,
                    'obat_id' => $obatData['obat_id'],
                    'jumlah' => $obatData['jumlah'],
                    'aturan_pakai' => $obatData['aturan_pakai'],
                ]);
            }
            DB::commit();
            return redirect()->route('dokter.antrian')->with('success', 'Resep berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Permintaan Lab
     */
    public function formLab($pemeriksaanId): View
    {
        // PERBAIKAN: Hapus 'pasien' dan 'dokter' dari with(), ganti 'pendaftaran.pasien'
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'permintaanLab'])->findOrFail($pemeriksaanId);
        
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) abort(403);
        
        $jenisTestLab = ['Darah Lengkap', 'Urine Lengkap', 'Gula Darah', 'Kolesterol', 'Asam Urat', 'Fungsi Hati', 'Fungsi Ginjal', 'X-Ray', 'CT Scan', 'MRI', 'USG'];
        return view('dokter.form-lab', compact('pemeriksaan', 'jenisTestLab'));
    }

    /**
     * Simpan Permintaan Lab
     */
    public function storeLab(Request $request): RedirectResponse
    {
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaan,pemeriksaan_id',
            'jenis_test' => 'required|array|min:1',
            'jenis_test.*' => 'required|string',
            'catatan_dokter' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $pemeriksaan = Pemeriksaan::with('pendaftaran')->findOrFail($request->pemeriksaan_id);
            $dokter = Auth::user()->dokter;
            
            if ($pemeriksaan->dokter_id !== $dokter->dokter_id) abort(403);
            
            PermintaanLab::where('pemeriksaan_id', $request->pemeriksaan_id)->delete();
            foreach ($request->jenis_test as $test) {
                PermintaanLab::create([
                    'pemeriksaan_id' => $request->pemeriksaan_id,
                    'pasien_id' => $pemeriksaan->pendaftaran->pasien_id,
                    'dokter_id' => $dokter->dokter_id,
                    'jenis_test' => $test,
                    'tanggal_permintaan' => now(),
                    'status' => 'menunggu',
                    'catatan_dokter' => $request->catatan_dokter,
                ]);
            }
            DB::commit();
            return redirect()->route('dokter.antrian')->with('success', 'Permintaan lab berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Rujukan
     */
    public function formRujukan($pemeriksaanId): View
    {
        // PERBAIKAN: Hapus 'pasien' dan 'dokter' dari with()
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'rujukan'])->findOrFail($pemeriksaanId);
        
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) abort(403);
        
        return view('dokter.form-rujukan', compact('pemeriksaan'));
    }

    /**
     * Simpan Rujukan
     */
    public function storeRujukan(Request $request): RedirectResponse
    {
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaan,pemeriksaan_id',
            'tujuan_rujukan' => 'required|in:rumah_sakit,klinik_spesialis,laboratorium',
            'rs_tujuan' => 'required|string|max:200',
            'dokter_spesialis_tujuan' => 'nullable|string|max:100',
            'alasan_rujukan' => 'required|string',
            'diagnosa_sementara' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $pemeriksaan = Pemeriksaan::with('pendaftaran')->findOrFail($request->pemeriksaan_id);
            $dokter = Auth::user()->dokter;
            
            if ($pemeriksaan->dokter_id !== $dokter->dokter_id) abort(403);
            
            Rujukan::updateOrCreate(
                ['pemeriksaan_id' => $request->pemeriksaan_id],
                [
                    'pasien_id' => $pemeriksaan->pendaftaran->pasien_id,
                    'dokter_perujuk_id' => $dokter->dokter_id,
                    'tujuan_rujukan' => $request->tujuan_rujukan,
                    'rs_tujuan' => $request->rs_tujuan,
                    'dokter_spesialis_tujuan' => $request->dokter_spesialis_tujuan,
                    'alasan_rujukan' => $request->alasan_rujukan,
                    'diagnosa_sementara' => $request->diagnosa_sementara,
                    'tanggal_rujukan' => now(),
                ]
            );
            DB::commit();
            return redirect()->route('dokter.antrian')->with('success', 'Rujukan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function riwayat()
    {
        $dokter = auth()->user()->dokter;
        $riwayatPemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'pendaftaran'])
            ->whereHas('pendaftaran.jadwalDokter', function($query) use ($dokter) {
                $query->where('dokter_id', $dokter->dokter_id);
            })
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->paginate(20);
        return view('dokter.riwayat', compact('riwayatPemeriksaan'));
    }

    /**
     * Detail Pemeriksaan
     */
    public function detailPemeriksaan($id): View
    {
        // PERBAIKAN: Hapus 'dokter' dan 'pasien' dari with()
        // Pastikan relasi lain (resep, permintaanLab, rujukan) ada di Model, jika tidak ada hapus juga.
        $pemeriksaan = Pemeriksaan::with([
            'pendaftaran.pasien',
            // 'dokter', <-- INI DIHAPUS
            // 'pendaftaran', <-- Sudah diload lewat pendaftaran.pasien
            'resep.detailResep.obat',
            'permintaanLab.hasilLab',
            'rujukan'
        ])->findOrFail($id);
        
        // if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) abort(403);
        
        return view('dokter.detail-pemeriksaan', compact('pemeriksaan'));
    }

    // ... (Profile & Monitoring tidak perlu ubahan signifikan kecuali jika memakai with('dokter') ) ...

    public function profile(): View {
        $dokter = Auth::user()->dokter;
        return view('dokter.profile-dokter', compact('dokter'));
    }

    public function editProfile(): View {
        $dokter = Auth::user()->dokter;
        return view('dokter.edit-profile', compact('dokter'));
    }

    public function updateProfile(Request $request): RedirectResponse {
        $request->validate([
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'provinsi' => 'nullable|string|max:100',
            'kota_kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'jadwal_praktik' => 'nullable|array',
        ]);
        try {
            $dokter = Auth::user()->dokter;
            $dokter->update([
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
                'jadwal_praktik' => $request->jadwal_praktik,
            ]);
            return redirect()->route('dokter.profile')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    public function monitoringPasien($pasienId): View {
        $pasien = Pasien::findOrFail($pasienId);
        $latestData = WearableData::where('pasien_id', $pasien->pasien_id)->orderBy('timestamp', 'desc')->first();
        $chartData = WearableData::where('pasien_id', $pasien->pasien_id)->orderBy('timestamp', 'desc')->limit(50)->get()->reverse()->values();
        $historicalData = WearableData::where('pasien_id', $pasien->pasien_id)->orderBy('timestamp', 'desc')->limit(20)->get();
        return view('dokter.monitoring-pasien', compact('pasien', 'latestData', 'chartData', 'historicalData'));
    }

    public function getPasienWearableData($pasienId) {
        $latestData = WearableData::where('pasien_id', $pasienId)->orderBy('timestamp', 'desc')->first();
        if (!$latestData) return response()->json(['success' => false, 'message' => 'No data available']);
        return response()->json([
            'success' => true,
            'data' => [
                'heart_rate' => $latestData->heart_rate,
                'oxygen_saturation' => $latestData->oxygen_saturation,
                'timestamp' => $latestData->timestamp->format('Y-m-d H:i:s'),
                'timestamp_display' => $latestData->timestamp->translatedFormat('j F Y H:i'),
            ]
        ]);
    }
}