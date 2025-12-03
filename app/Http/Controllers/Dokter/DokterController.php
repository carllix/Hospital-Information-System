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

class DokterController extends Controller
{
    /**
     * Dashboard Dokter
     */
    public function dashboard(): View
    {
        $dokter = Auth::user()->dokter;
        
        // Statistik
        // Antrian = Pendaftaran yang belum ada pemeriksaan
        $antrianHariIni = Pendaftaran::whereDate('tanggal_daftar', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDoesntHave('pemeriksaan') // Belum diperiksa
            ->count();
            
        // Pasien yang sudah diperiksa oleh dokter ini hari ini
        $pasienDitanganiHariIni = Pemeriksaan::whereDate('tanggal_pemeriksaan', today())
            ->where('dokter_id', $dokter->dokter_id)
            ->count();
            
        // Total pasien bulan ini
        $totalPasienBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', now()->month)
            ->whereYear('tanggal_pemeriksaan', now()->year)
            ->where('dokter_id', $dokter->dokter_id)
            ->count();
            
        // Antrian hari ini - Semua pasien yang belum diperiksa
        $antrianPasien = Pendaftaran::with(['pasien'])
            ->whereDate('tanggal_daftar', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDoesntHave('pemeriksaan') // Belum ada pemeriksaan
            ->orderBy('nomor_antrian')
            ->get();
            
        return view('dokter.dashboard', compact(
            'dokter',
            'antrianHariIni',
            'pasienDitanganiHariIni',
            'totalPasienBulanIni',
            'antrianPasien'
        ));
    }

    /**
     * Daftar Antrian Pasien
     */
    public function antrian(): View
    {
        $dokter = Auth::user()->dokter;
        
        // Semua antrian yang belum diperiksa
        $antrianPasien = Pendaftaran::with(['pasien'])
            ->whereDate('tanggal_daftar', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDoesntHave('pemeriksaan') // Belum diperiksa
            ->orderBy('nomor_antrian')
            ->get();
            
        return view('dokter.antrian', compact('antrianPasien'));
    }

    /**
     * Panggil Pasien (Update Status)
     */
    public function panggilPasien(Request $request, $id): RedirectResponse
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            $pendaftaran->update(['status' => 'dipanggil']);
            
            return back()->with('success', 'Pasien berhasil dipanggil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memanggil pasien: ' . $e->getMessage());
        }
    }

    /**
     * Form Pemeriksaan Pasien
     */
    public function formPemeriksaan($id): View
    {
        $pendaftaran = Pendaftaran::with(['pasien'])->findOrFail($id);
        
        // Cek apakah sudah ada pemeriksaan untuk pendaftaran ini
        $pemeriksaan = Pemeriksaan::where('pendaftaran_id', $id)->first();
        
        // Riwayat pemeriksaan pasien (3 terakhir)
        $riwayatPemeriksaan = Pemeriksaan::with(['dokter'])
            ->where('pasien_id', $pendaftaran->pasien_id)
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
            
            // Simpan pemeriksaan - INI YANG ASSIGN DOKTER KE PASIEN
            $pemeriksaan = Pemeriksaan::updateOrCreate(
                ['pendaftaran_id' => $request->pendaftaran_id],
                [
                    'pasien_id' => $pendaftaran->pasien_id,
                    'dokter_id' => $dokter->dokter_id, // Dokter yang login yang periksa
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
            
            // Update status pendaftaran
            $pendaftaran->update(['status' => 'selesai']);
            
            DB::commit();
            
            // Redirect berdasarkan status pasien
            if ($request->status_pasien === 'perlu_resep') {
                return redirect()->route('dokter.form-resep', $pemeriksaan->pemeriksaan_id)
                    ->with('success', 'Pemeriksaan berhasil disimpan! Silakan buat resep.');
            } elseif ($request->status_pasien === 'perlu_lab') {
                return redirect()->route('dokter.form-lab', $pemeriksaan->pemeriksaan_id)
                    ->with('success', 'Pemeriksaan berhasil disimpan! Silakan buat permintaan lab.');
            } elseif ($request->status_pasien === 'dirujuk') {
                return redirect()->route('dokter.form-rujukan', $pemeriksaan->pemeriksaan_id)
                    ->with('success', 'Pemeriksaan berhasil disimpan! Silakan buat rujukan.');
            }
            
            return redirect()->route('dokter.antrian')
                ->with('success', 'Pemeriksaan berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pemeriksaan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Resep Obat
     */
    public function formResep($pemeriksaanId): View
    {
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'resep.detailResep.obat'])->findOrFail($pemeriksaanId);
        
        // Cek apakah dokter yang login adalah dokter yang memeriksa
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
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
            
            $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);
            $dokter = Auth::user()->dokter;
            
            // Cek authorization
            if ($pemeriksaan->dokter_id !== $dokter->dokter_id) {
                abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
            }
            
            // Buat atau update resep
            $resep = Resep::updateOrCreate(
                ['pemeriksaan_id' => $request->pemeriksaan_id],
                [
                    'pasien_id' => $pemeriksaan->pasien_id,
                    'dokter_id' => $dokter->dokter_id,
                    'tanggal_resep' => now(),
                    'status' => 'menunggu',
                ]
            );
            
            // Hapus detail resep lama jika ada
            $resep->detailResep()->delete();
            
            // Simpan detail resep baru
            foreach ($request->obat as $obatData) {
                DetailResep::create([
                    'resep_id' => $resep->resep_id,
                    'obat_id' => $obatData['obat_id'],
                    'jumlah' => $obatData['jumlah'],
                    'aturan_pakai' => $obatData['aturan_pakai'],
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('dokter.antrian')
                ->with('success', 'Resep berhasil dibuat dan dikirim ke farmasi!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat resep: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Permintaan Lab
     */
    public function formLab($pemeriksaanId): View
    {
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'permintaanLab'])->findOrFail($pemeriksaanId);
        
        // Cek authorization
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
        }
        
        $jenisTestLab = [
            'Darah Lengkap',
            'Urine Lengkap',
            'Gula Darah',
            'Kolesterol',
            'Asam Urat',
            'Fungsi Hati',
            'Fungsi Ginjal',
            'X-Ray',
            'CT Scan',
            'MRI',
            'USG',
        ];
        
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
            
            $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);
            $dokter = Auth::user()->dokter;
            
            // Cek authorization
            if ($pemeriksaan->dokter_id !== $dokter->dokter_id) {
                abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
            }
            
            // Hapus permintaan lab lama jika ada
            PermintaanLab::where('pemeriksaan_id', $request->pemeriksaan_id)->delete();
            
            // Simpan permintaan lab baru
            foreach ($request->jenis_test as $test) {
                PermintaanLab::create([
                    'pemeriksaan_id' => $request->pemeriksaan_id,
                    'pasien_id' => $pemeriksaan->pasien_id,
                    'dokter_id' => $dokter->dokter_id,
                    'jenis_test' => $test,
                    'tanggal_permintaan' => now(),
                    'status' => 'menunggu',
                    'catatan_dokter' => $request->catatan_dokter,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('dokter.antrian')
                ->with('success', 'Permintaan lab berhasil dibuat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat permintaan lab: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Rujukan
     */
    public function formRujukan($pemeriksaanId): View
    {
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'rujukan'])->findOrFail($pemeriksaanId);
        
        // Cek authorization
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
        }
        
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
            
            $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);
            $dokter = Auth::user()->dokter;
            
            // Cek authorization
            if ($pemeriksaan->dokter_id !== $dokter->dokter_id) {
                abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
            }
            
            // Simpan rujukan
            Rujukan::updateOrCreate(
                ['pemeriksaan_id' => $request->pemeriksaan_id],
                [
                    'pasien_id' => $pemeriksaan->pasien_id,
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
            
            return redirect()->route('dokter.antrian')
                ->with('success', 'Rujukan berhasil dibuat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat rujukan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Riwayat Pemeriksaan
     * Hanya tampilkan pemeriksaan yang dilakukan oleh dokter yang login
     */
    public function riwayat(): View
    {
        $dokter = Auth::user()->dokter;
        
        // Filter berdasarkan dokter_id di tabel pemeriksaan
        $riwayatPemeriksaan = Pemeriksaan::with(['pasien', 'pendaftaran'])
            ->where('dokter_id', $dokter->dokter_id) // Filter per dokter
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->paginate(20);
            
        return view('dokter.riwayat', compact('riwayatPemeriksaan'));
    }

    /**
     * Detail Pemeriksaan
     */
    public function detailPemeriksaan($id): View
    {
        $pemeriksaan = Pemeriksaan::with([
            'pasien',
            'dokter',
            'pendaftaran',
            'resep.detailResep.obat',
            'permintaanLab.hasilLab',
            'rujukan'
        ])->findOrFail($id);
        
        // Cek authorization - hanya dokter yang memeriksa yang bisa lihat
        if ($pemeriksaan->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Anda tidak memiliki akses ke pemeriksaan ini.');
        }
        
        return view('dokter.detail-pemeriksaan', compact('pemeriksaan'));
    }

    /**
     * Profil Dokter
     */
    public function profile(): View
    {
        $dokter = Auth::user()->dokter;
        return view('dokter.profile-dokter', compact('dokter'));
    }

    /**
     * Form Edit Profil
     */
    public function editProfile(): View
    {
        $dokter = Auth::user()->dokter;
        return view('dokter.edit-profile', compact('dokter'));
    }

    /**
     * Update Profil
     */
    public function updateProfile(Request $request): RedirectResponse
    {
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
            
            return redirect()->route('dokter.profile')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }
}