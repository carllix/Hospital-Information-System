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

        // Antrian hari ini = semua pasien yang belum selesai (menunggu + dipanggil)
        $antrianHariIni = Pendaftaran::whereDate('tanggal_kunjungan', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereHas('jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            })
            ->count();

        // Pasien ditangani hari ini = yang sudah selesai hari ini
        $pasienDitanganiHariIni = Pendaftaran::whereDate('tanggal_kunjungan', today())
            ->where('status', 'selesai')
            ->whereHas('jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            })
            ->count();

        // Total pasien bulan ini = yang sudah selesai bulan ini
        $totalPasienBulanIni = Pendaftaran::whereMonth('tanggal_kunjungan', now()->month)
            ->whereYear('tanggal_kunjungan', now()->year)
            ->where('status', 'selesai')
            ->whereHas('jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            })
            ->count();

        return view('dokter.dashboard', compact('dokter', 'antrianHariIni', 'pasienDitanganiHariIni', 'totalPasienBulanIni'));
    }

    public function antrian(Request $request): View
    {
        $dokter = Auth::user()->dokter;

        // Get tanggal from request, default to today
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        // Antrian = semua pasien yang daftar ke dokter ini pada tanggal tertentu (menunggu + dipanggil)
        $antrianPasien = Pendaftaran::with(['pasien', 'jadwalDokter'])
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereHas('jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            })
            ->orderBy('nomor_antrian')->get();

        return view('dokter.antrian', compact('antrianPasien'));
    }

    /**
     * Form Pemeriksaan Pasien
     */
    public function formPemeriksaan($id): View
    {
        $pendaftaran = Pendaftaran::with(['pasien'])->findOrFail($id);

        // Ambil atau buat pemeriksaan record jika belum ada
        $pemeriksaan = Pemeriksaan::firstOrCreate(
            ['pendaftaran_id' => $id],
            [
                'tanggal_pemeriksaan' => now(),
                'status' => 'dalam_pemeriksaan',
            ]
        );

        // Riwayat pemeriksaan pasien ini (3 terakhir)
        $riwayatPemeriksaan = Pemeriksaan::with(['pendaftaran.jadwalDokter.dokter'])
            ->whereHas('pendaftaran', function($query) use ($pendaftaran) {
                $query->where('pasien_id', $pendaftaran->pasien_id);
            })
            ->where('pemeriksaan_id', '!=', $pemeriksaan->pemeriksaan_id) // Exclude pemeriksaan saat ini
            ->where('status', 'selesai') // Hanya yang sudah selesai
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->take(3)
            ->get();

        // Data untuk tindak lanjut (inline form)
        $obatList = Obat::where('is_deleted', false)->where('stok', '>', 0)->orderBy('nama_obat')->get();
        $jenisTestLab = ['darah_lengkap', 'urine', 'gula_darah', 'kolesterol', 'radiologi', 'lainnya'];

        return view('dokter.form-pemeriksaan', compact('pendaftaran', 'pemeriksaan', 'riwayatPemeriksaan', 'obatList', 'jenisTestLab'));
    }
    /**
     * Simpan Pemeriksaan + Tindak Lanjut (Resep/Lab/Rujukan) sekaligus dalam 1 form
     */
    public function storePemeriksaan(Request $request): RedirectResponse
    {
        $rules = [
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
            'tindak_lanjut' => 'required|in:selesai,resep,lab,rujukan',
        ];

        // Validasi tambahan jika resep dipilih
        if ($request->tindak_lanjut === 'resep') {
            $rules['obat'] = 'required|array|min:1';
            $rules['obat.*.obat_id'] = 'required|exists:obat,obat_id';
            $rules['obat.*.jumlah'] = 'required|integer|min:1';
            $rules['obat.*.dosis'] = 'required|string';
            $rules['obat.*.aturan_pakai'] = 'required|string';
        }

        // Validasi tambahan jika lab dipilih
        if ($request->tindak_lanjut === 'lab') {
            $rules['jenis_pemeriksaan_lab'] = 'required|in:darah_lengkap,urine,gula_darah,kolesterol,radiologi,lainnya';
            $rules['catatan_lab'] = 'nullable|string';
        }

        // Validasi tambahan jika rujukan dipilih
        if ($request->tindak_lanjut === 'rujukan') {
            $rules['tujuan_rujukan'] = 'required|string|max:255';
            $rules['rs_tujuan'] = 'nullable|string|max:255';
            $rules['dokter_spesialis_tujuan'] = 'nullable|string|max:100';
            $rules['alasan_rujukan'] = 'required|string';
            $rules['diagnosa_sementara'] = 'nullable|string';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);

            // Cari pemeriksaan yang sudah dibuat
            $pemeriksaan = Pemeriksaan::where('pendaftaran_id', $request->pendaftaran_id)->firstOrFail();

            // Update pemeriksaan dengan data dari form dokter
            $pemeriksaan->update([
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
                'status' => 'selesai',
            ]);

            $tindakLanjut = $request->tindak_lanjut;
            $message = 'Pemeriksaan berhasil disimpan!';

            // [A] Simpan Resep jika dipilih
            if ($tindakLanjut === 'resep' && $request->has('obat')) {
                $resep = Resep::create([
                    'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                    'tanggal_resep' => now(),
                    'status' => 'menunggu',
                ]);

                foreach ($request->obat as $obatData) {
                    if (!empty($obatData['obat_id'])) {
                        DetailResep::create([
                            'resep_id' => $resep->resep_id,
                            'obat_id' => $obatData['obat_id'],
                            'jumlah' => $obatData['jumlah'],
                            'dosis' => $obatData['dosis'],
                            'aturan_pakai' => $obatData['aturan_pakai'],
                        ]);
                    }
                }
                $message .= ' Resep obat telah dibuat.';
            }

            // [B] Simpan Permintaan Lab jika dipilih
            if ($tindakLanjut === 'lab' && $request->filled('jenis_pemeriksaan_lab')) {
                PermintaanLab::create([
                    'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                    'tanggal_permintaan' => now(),
                    'jenis_pemeriksaan' => $request->jenis_pemeriksaan_lab,
                    'catatan_permintaan' => $request->catatan_lab,
                    'status' => 'menunggu',
                ]);
                $message .= ' Permintaan lab telah dibuat.';
            }

            // [C] Simpan Rujukan jika dipilih
            if ($tindakLanjut === 'rujukan' && $request->filled('tujuan_rujukan')) {
                Rujukan::create([
                    'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                    'tujuan_rujukan' => $request->tujuan_rujukan,
                    'rs_tujuan' => $request->rs_tujuan,
                    'dokter_spesialis_tujuan' => $request->dokter_spesialis_tujuan,
                    'alasan_rujukan' => $request->alasan_rujukan,
                    'diagnosa_sementara' => $request->diagnosa_sementara ?? $request->diagnosa,
                    'tanggal_rujukan' => now(),
                ]);
                $message .= ' Rujukan telah dibuat.';
            }

            // Update status pendaftaran
            $pendaftaran->update(['status' => 'selesai']);

            DB::commit();

            return redirect()->route('dokter.antrian')->with('success', $message);
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
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'pendaftaran.jadwalDokter.dokter', 'resep.detailResep.obat'])->findOrFail($pemeriksaanId);

        // Authorization check menggunakan relationship chain
        if ($pemeriksaan->pendaftaran->jadwalDokter->dokter->dokter_id !== Auth::user()->dokter->dokter_id) {
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
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'pendaftaran.jadwalDokter.dokter', 'permintaanLab'])->findOrFail($pemeriksaanId);

        // Authorization check menggunakan relationship chain
        if ($pemeriksaan->pendaftaran->jadwalDokter->dokter->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Akses ditolak.');
        }

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
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien', 'pendaftaran.jadwalDokter.dokter', 'rujukan'])->findOrFail($pemeriksaanId);

        // Authorization check menggunakan relationship chain
        if ($pemeriksaan->pendaftaran->jadwalDokter->dokter->dokter_id !== Auth::user()->dokter->dokter_id) {
            abort(403, 'Akses ditolak.');
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

    public function riwayat(Request $request)
    {
        $dokter = auth()->user()->dokter;

        $query = Pemeriksaan::with(['pendaftaran.pasien', 'pendaftaran.jadwalDokter'])
            ->whereHas('pendaftaran.jadwalDokter', function($q) use ($dokter) {
                $q->where('dokter_id', $dokter->dokter_id);
            });

        // Filter by search (nama pasien or no rekam medis)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereHas('pendaftaran.pasien', function($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(no_rekam_medis) LIKE ?', ["%{$search}%"]);
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->tanggal_sampai);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $riwayatPemeriksaan = $query->orderBy('tanggal_pemeriksaan', 'desc')
            ->paginate(10);

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
        $dokter = Auth::user()->dokter()->with(['jadwalDokter' => function($query) {
            $query->where('is_deleted', false)
                  ->orderByRaw("CASE
                      WHEN hari_praktik = 'Senin' THEN 1
                      WHEN hari_praktik = 'Selasa' THEN 2
                      WHEN hari_praktik = 'Rabu' THEN 3
                      WHEN hari_praktik = 'Kamis' THEN 4
                      WHEN hari_praktik = 'Jumat' THEN 5
                      WHEN hari_praktik = 'Sabtu' THEN 6
                      WHEN hari_praktik = 'Minggu' THEN 7
                      ELSE 8
                  END")
                  ->orderBy('waktu_mulai');
        }])->first();
        return view('dokter.profile-dokter', compact('dokter'));
    }

    public function editProfile(): View {
        $dokter = Auth::user()->dokter;
        return view('dokter.edit-profile', compact('dokter'));
    }

    public function updateProfile(Request $request): RedirectResponse {
        $dokter = Auth::user()->dokter;

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'no_telepon' => 'required|string|max:15',
            'spesialisasi' => 'required|string|max:100',
            'no_str' => 'required|string|max:17',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Validate and update password if provided
        if ($request->filled('current_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }

            Auth::user()->update([
                'password' => $request->new_password
            ]);
        }

        try {
            $dokter->update($validated);

            $message = 'Profil berhasil diperbarui.';
            if ($request->filled('new_password')) {
                $message = 'Profil dan password berhasil diperbarui.';
            }

            return redirect()->route('dokter.profile')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil.'])->withInput();
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