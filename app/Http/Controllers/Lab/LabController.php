<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\HasilLab;
use App\Models\PermintaanLab;
use App\Models\Staf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LabController extends Controller
{
    /**
     * Dashboard Staf Lab
     */
    public function dashboard(): View
    {
        $petugasLab = Auth::user()->staf;
        
        // Statistik
        $permintaanMenunggu = PermintaanLab::where('status', 'menunggu')->count();
        
        $permintaanDiproses = PermintaanLab::where('status', 'diproses')
            ->where('petugas_lab_id', $petugasLab->staf_id)
            ->count();
            
        $permintaanSelesaiHariIni = PermintaanLab::whereDate('tanggal_permintaan', today())
            ->where('status', 'selesai')
            ->where('petugas_lab_id', $petugasLab->staf_id)
            ->count();
            
        $totalPermintaanBulanIni = PermintaanLab::whereMonth('tanggal_permintaan', now()->month)
            ->whereYear('tanggal_permintaan', now()->year)
            ->where('petugas_lab_id', $petugasLab->staf_id)
            ->count();
        
        // Daftar permintaan menunggu (terbaru)
        $permintaanMenungguList = PermintaanLab::with(['pasien', 'dokter', 'pemeriksaan'])
            ->where('status', 'menunggu')
            ->orderBy('tanggal_permintaan', 'asc')
            ->take(5)
            ->get();
        
        return view('lab.dashboard', compact(
            'petugasLab',
            'permintaanMenunggu',
            'permintaanDiproses',
            'permintaanSelesaiHariIni',
            'totalPermintaanBulanIni',
            'permintaanMenungguList'
        ));
    }

    /**
     * Daftar Permintaan Lab (Semua Status)
     */
    public function daftarPermintaan(Request $request): View
    {
        $status = $request->get('status', 'semua');
        
        $query = PermintaanLab::with(['pasien', 'dokter', 'petugasLab', 'pemeriksaan'])
            ->orderBy('tanggal_permintaan', 'desc');
        
        if ($status !== 'semua') {
            $query->where('status', $status);
        }
        
        $permintaanList = $query->paginate(15);
        
        return view('lab.daftar-permintaan', compact('permintaanList', 'status'));
    }

    /**
     * Detail Permintaan Lab
     */
    public function detailPermintaan($id): View
    {
        $permintaan = PermintaanLab::with([
            'pasien',
            'dokter',
            'petugasLab',
            'pemeriksaan',
            'hasilLab'
        ])->findOrFail($id);
        
        return view('lab.detail-permintaan', compact('permintaan'));
    }

    /**
     * Ambil Permintaan untuk Diproses
     */
    public function ambilPermintaan($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $permintaan = PermintaanLab::findOrFail($id);
            $petugasLab = Auth::user()->staf;
            
            // Cek apakah permintaan masih menunggu
            if ($permintaan->status !== 'menunggu') {
                return back()->with('error', 'Permintaan sudah diambil oleh petugas lain!');
            }
            
            // Update status permintaan
            $permintaan->update([
                'status' => 'diproses',
                'petugas_lab_id' => $petugasLab->staf_id,
            ]);
            
            DB::commit();
            
            return redirect()->route('lab.form-hasil', $id)
                ->with('success', 'Permintaan berhasil diambil! Silakan input hasil pemeriksaan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengambil permintaan: ' . $e->getMessage());
        }
    }

    /**
     * Form Input Hasil Lab
     */
    public function formHasil($id): View
    {
        $permintaan = PermintaanLab::with([
            'pasien',
            'dokter',
            'pemeriksaan',
            'hasilLab'
        ])->findOrFail($id);
        
        $petugasLab = Auth::user()->staf;
        
        // Cek authorization - hanya petugas yang mengambil yang bisa input hasil
        if ($permintaan->petugas_lab_id !== $petugasLab->staf_id) {
            abort(403, 'Anda tidak memiliki akses untuk menginput hasil permintaan ini!');
        }
        
        // Template parameter berdasarkan jenis pemeriksaan
        $parameterTemplates = $this->getParameterTemplates($permintaan->jenis_pemeriksaan);
        
        return view('lab.form-hasil', compact('permintaan', 'parameterTemplates'));
    }

    /**
     * Simpan Hasil Lab
     */
    public function storeHasil(Request $request): RedirectResponse
    {
        $request->validate([
            'permintaan_lab_id' => 'required|exists:permintaan_lab,permintaan_lab_id',
            'hasil' => 'required|array|min:1',
            'hasil.*.parameter' => 'required|string',
            'hasil.*.hasil_nilai' => 'required|string',
            'hasil.*.satuan' => 'nullable|string',
            'hasil.*.nilai_normal' => 'nullable|string',
            'hasil.*.keterangan' => 'nullable|string',
            'file_hasil' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();
            
            $permintaan = PermintaanLab::findOrFail($request->permintaan_lab_id);
            $petugasLab = Auth::user()->staf;
            
            // Cek authorization
            if ($permintaan->petugas_lab_id !== $petugasLab->staf_id) {
                abort(403, 'Anda tidak memiliki akses untuk menginput hasil permintaan ini!');
            }
            
            // Upload file jika ada
            $fileUrl = null;
            if ($request->hasFile('file_hasil')) {
                $file = $request->file('file_hasil');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('hasil-lab', $fileName, 'public');
                $fileUrl = Storage::url($filePath);
            }
            
            // Hapus hasil lab lama jika ada
            $permintaan->hasilLab()->delete();
            
            // Simpan hasil lab baru
            foreach ($request->hasil as $hasilData) {
                HasilLab::create([
                    'permintaan_lab_id' => $permintaan->permintaan_lab_id,
                    'jenis_test' => $permintaan->jenis_pemeriksaan,
                    'parameter' => $hasilData['parameter'],
                    'hasil' => $hasilData['hasil_nilai'],
                    'satuan' => $hasilData['satuan'] ?? null,
                    'nilai_normal' => $hasilData['nilai_normal'] ?? null,
                    'keterangan' => $hasilData['keterangan'] ?? null,
                    'file_hasil_url' => $fileUrl,
                    'tanggal_hasil' => now(),
                    'petugas_lab_id' => $petugasLab->staf_id,
                ]);
            }
            
            // Update status permintaan
            $permintaan->update(['status' => 'selesai']);
            
            DB::commit();
            
            return redirect()->route('lab.daftar-permintaan')
                ->with('success', 'Hasil pemeriksaan laboratorium berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan hasil lab: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Riwayat Pemeriksaan Lab
     * Hanya tampilkan yang dikerjakan oleh petugas yang login
     */
    public function riwayat(): View
    {
        $petugasLab = Auth::user()->staf;
        
        $riwayatPermintaan = PermintaanLab::with(['pasien', 'dokter', 'pemeriksaan'])
            ->where('petugas_lab_id', $petugasLab->staf_id)
            ->orderBy('tanggal_permintaan', 'desc')
            ->paginate(20);
            
        return view('lab.riwayat', compact('riwayatPermintaan'));
    }

    /**
     * Laporan Lab
     */
    public function laporan(Request $request): View
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        
        $permintaanList = PermintaanLab::with(['pasien', 'dokter', 'petugasLab'])
            ->whereMonth('tanggal_permintaan', $bulan)
            ->whereYear('tanggal_permintaan', $tahun)
            ->orderBy('tanggal_permintaan', 'desc')
            ->get();
        
        $statistik = [
            'total' => $permintaanList->count(),
            'selesai' => $permintaanList->where('status', 'selesai')->count(),
            'diproses' => $permintaanList->where('status', 'diproses')->count(),
            'menunggu' => $permintaanList->where('status', 'menunggu')->count(),
        ];
        
        // Statistik per jenis pemeriksaan
        $perJenisPemeriksaan = $permintaanList->groupBy('jenis_pemeriksaan')->map(function($group) {
            return $group->count();
        });
        
        return view('lab.laporan', compact('permintaanList', 'statistik', 'bulan', 'tahun', 'perJenisPemeriksaan'));
    }

    /**
     * Profile Petugas Lab
     */
    public function profile(): View
    {
        $petugasLab = Auth::user()->staf;
        return view('lab.profile', compact('petugasLab'));
    }

    /**
     * Form Edit Profile
     */
    public function editProfile(): View
    {
        $petugasLab = Auth::user()->staf;
        return view('lab.edit-profile', compact('petugasLab'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'provinsi' => 'nullable|string|max:100',
            'kota_kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
        ]);

        try {
            $petugasLab = Auth::user()->staf;
            
            $petugasLab->update([
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
            ]);
            
            return redirect()->route('lab.profile')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper: Get Parameter Templates
     */
    private function getParameterTemplates($jenisPemeriksaan): array
    {
        $templates = [
            'darah_lengkap' => [
                ['parameter' => 'Hemoglobin', 'satuan' => 'g/dL', 'nilai_normal' => '12-16 (Wanita), 14-18 (Pria)'],
                ['parameter' => 'Leukosit', 'satuan' => '/µL', 'nilai_normal' => '4.000-10.000'],
                ['parameter' => 'Eritrosit', 'satuan' => 'juta/µL', 'nilai_normal' => '4.5-5.5 (Wanita), 5-6 (Pria)'],
                ['parameter' => 'Trombosit', 'satuan' => '/µL', 'nilai_normal' => '150.000-400.000'],
                ['parameter' => 'Hematokrit', 'satuan' => '%', 'nilai_normal' => '37-47 (Wanita), 40-50 (Pria)'],
            ],
            'urine' => [
                ['parameter' => 'Warna', 'satuan' => '', 'nilai_normal' => 'Kuning jernih'],
                ['parameter' => 'Kejernihan', 'satuan' => '', 'nilai_normal' => 'Jernih'],
                ['parameter' => 'pH', 'satuan' => '', 'nilai_normal' => '4.5-8.0'],
                ['parameter' => 'Protein', 'satuan' => '', 'nilai_normal' => 'Negatif'],
                ['parameter' => 'Glukosa', 'satuan' => '', 'nilai_normal' => 'Negatif'],
                ['parameter' => 'Leukosit', 'satuan' => '/LPB', 'nilai_normal' => '0-5'],
                ['parameter' => 'Eritrosit', 'satuan' => '/LPB', 'nilai_normal' => '0-3'],
            ],
            'gula_darah' => [
                ['parameter' => 'Gula Darah Puasa', 'satuan' => 'mg/dL', 'nilai_normal' => '<100'],
                ['parameter' => 'Gula Darah 2 Jam PP', 'satuan' => 'mg/dL', 'nilai_normal' => '<140'],
                ['parameter' => 'HbA1c', 'satuan' => '%', 'nilai_normal' => '<5.7'],
            ],
            'kolesterol' => [
                ['parameter' => 'Kolesterol Total', 'satuan' => 'mg/dL', 'nilai_normal' => '<200'],
                ['parameter' => 'HDL', 'satuan' => 'mg/dL', 'nilai_normal' => '>40 (Pria), >50 (Wanita)'],
                ['parameter' => 'LDL', 'satuan' => 'mg/dL', 'nilai_normal' => '<100'],
                ['parameter' => 'Trigliserida', 'satuan' => 'mg/dL', 'nilai_normal' => '<150'],
            ],
            'radiologi' => [
                ['parameter' => 'Hasil Pemeriksaan', 'satuan' => '', 'nilai_normal' => '-'],
            ],
            'lainnya' => [
                ['parameter' => 'Hasil Pemeriksaan', 'satuan' => '', 'nilai_normal' => '-'],
            ],
        ];
        
        return $templates[$jenisPemeriksaan] ?? $templates['lainnya'];
    }
}