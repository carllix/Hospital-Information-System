<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Staf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FarmasiController extends Controller
{
    /**
     * Dashboard Apoteker
     */
    public function dashboard(): View
    {
        $apoteker = Auth::user()->staf;
        
        // Statistik
        $resepMenunggu = Resep::where('status', 'menunggu')->count();
        $resepDiproses = Resep::where('status', 'diproses')
            ->where('apoteker_id', $apoteker->staf_id)
            ->count();
        $resepSelesaiHariIni = Resep::whereDate('created_at', today())
            ->where('status', 'selesai')
            ->where('apoteker_id', $apoteker->staf_id)
            ->count();
        
        // Obat yang stok menipis (< 10)
        $obatStokMenipis = Obat::where('is_deleted', false)
            ->where('stok', '<', 10)
            ->where('stok', '>', 0)
            ->count();

        // Obat yang habis
        $obatHabis = Obat::where('is_deleted', false)
            ->where('stok', '=', 0)
            ->count();
        
        // PERBAIKAN: Menggunakan relasi panjang pemeriksaan.pendaftaran...
        $resepMenungguList = Resep::with([
                'pemeriksaan.pendaftaran.pasien', 
                'pemeriksaan.pendaftaran.jadwalDokter.dokter', 
                'detailResep.obat'
            ])
            ->where('status', 'menunggu')
            ->orderBy('tanggal_resep', 'asc')
            ->take(5)
            ->get();
        
        return view('farmasi.dashboard', compact(
            'apoteker',
            'resepMenunggu',
            'resepDiproses',
            'resepSelesaiHariIni',
            'obatStokMenipis',
            'obatHabis',
            'resepMenungguList'
        ));
    }

    /**
     * Daftar Resep (Semua Status)
     */
    public function daftarResep(Request $request): View
    {
        $status = $request->get('status', 'semua');
        $search = $request->get('search');
        $tanggal = $request->get('tanggal');

        // PERBAIKAN: Menggunakan relasi panjang
        $query = Resep::with([
                'pemeriksaan.pendaftaran.pasien',
                'pemeriksaan.pendaftaran.jadwalDokter.dokter',
                'apoteker',
                'detailResep.obat'
            ])
            ->orderBy('tanggal_resep', 'desc');

        // Filter by status
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        // Filter by search (nama pasien, no rekam medis, nama dokter)
        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function($q) use ($searchLower) {
                $q->whereHas('pemeriksaan.pendaftaran.pasien', function($subQ) use ($searchLower) {
                    $subQ->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$searchLower}%"])
                         ->orWhereRaw('LOWER(no_rekam_medis) LIKE ?', ["%{$searchLower}%"]);
                })
                ->orWhereHas('pemeriksaan.pendaftaran.jadwalDokter.dokter', function($subQ) use ($searchLower) {
                    $subQ->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$searchLower}%"]);
                });
            });
        }

        // Filter by tanggal
        if ($tanggal) {
            $query->whereDate('tanggal_resep', $tanggal);
        }

        $resepList = $query->paginate(10);

        return view('farmasi.daftar-resep', compact('resepList', 'status'));
    }

    /**
     * Detail Resep
     */
    public function detailResep($id): View
    {
        // PERBAIKAN: Menggunakan relasi panjang
        $resep = Resep::with([
            'pemeriksaan.pendaftaran.pasien',
            'pemeriksaan.pendaftaran.jadwalDokter.dokter',
            'apoteker',
            'pemeriksaan',
            'detailResep.obat'
        ])->findOrFail($id);
        
        return view('farmasi.detail-resep', compact('resep'));
    }

    /**
     * Proses Resep (Ambil untuk diproses)
     */
    public function prosesResep($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $resep = Resep::findOrFail($id);
            $apoteker = Auth::user()->staf;
            
            // Cek apakah resep masih menunggu
            if ($resep->status !== 'menunggu') {
                return back()->with('error', 'Resep sudah diproses oleh apoteker lain!');
            }
            
            // Cek ketersediaan stok semua obat
            foreach ($resep->detailResep as $detail) {
                if ($detail->obat->stok < $detail->jumlah) {
                    return back()->with('error', "Stok obat {$detail->obat->nama_obat} tidak mencukupi!");
                }
            }
            
            // Update status resep
            $resep->update([
                'status' => 'diproses',
                'apoteker_id' => $apoteker->staf_id,
            ]);
            
            DB::commit();
            
            return redirect()->route('farmasi.detail-resep', $id)
                ->with('success', 'Resep berhasil diambil untuk diproses!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses resep: ' . $e->getMessage());
        }
    }

    /**
     * Selesaikan Resep (Dispensing obat)
     */
    public function selesaikanResep(Request $request, $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $resep = Resep::with('detailResep.obat')->findOrFail($id);
            $apoteker = Auth::user()->staf;
            
            // Cek authorization
            if ($resep->apoteker_id !== $apoteker->staf_id) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menyelesaikan resep ini!');
            }
            
            if ($resep->status !== 'diproses') {
                return back()->with('error', 'Resep tidak dalam status diproses!');
            }
            
            // Kurangi stok obat
            foreach ($resep->detailResep as $detail) {
                $obat = $detail->obat;
                
                if ($obat->stok < $detail->jumlah) {
                    DB::rollBack();
                    return back()->with('error', "Stok obat {$obat->nama_obat} tidak mencukupi!");
                }
                
                $obat->decrement('stok', $detail->jumlah);
            }
            
            // Update status resep
            $resep->update(['status' => 'selesai']);
            
            DB::commit();
            
            return redirect()->route('farmasi.daftar-resep')
                ->with('success', 'Resep berhasil diselesaikan! Obat telah diberikan kepada pasien.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan resep: ' . $e->getMessage());
        }
    }

    /**
     * Manajemen Stok Obat
     */
    public function stokObat(Request $request): View
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $statusStok = $request->get('status_stok');

        $query = Obat::where('is_deleted', false);

        // Filter by search (case insensitive)
        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function($q) use ($searchLower) {
                $q->whereRaw('LOWER(nama_obat) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(kode_obat) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // Filter by kategori
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Filter by status stok
        if ($statusStok) {
            if ($statusStok === 'habis') {
                $query->where('stok', '=', 0);
            } elseif ($statusStok === 'menipis') {
                $query->where('stok', '>', 0)->where('stok', '<', 10);
            } elseif ($statusStok === 'aman') {
                $query->where('stok', '>=', 10);
            }
        }

        $obatList = $query->orderBy('nama_obat')->paginate(10);

        return view('farmasi.stok-obat', compact('obatList', 'search', 'kategori'));
    }

    /**
     * Form Tambah Obat
     */
    public function tambahObat(): View
    {
        return view('farmasi.tambah-obat');
    }

    /**
     * Simpan Obat Baru
     */
    public function storeObat(Request $request): RedirectResponse
    {
        $request->validate([
            'kode_obat' => 'required|string|max:20|unique:obat,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|in:tablet,kapsul,sirup,salep,injeksi',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            Obat::create(array_merge($request->all(), ['is_deleted' => false]));

            return redirect()->route('farmasi.stok-obat')
                ->with('success', 'Obat berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan obat: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Edit Obat
     */
    public function editObat($id): View
    {
        $obat = Obat::findOrFail($id);

        if ($obat->is_deleted) {
            abort(404);
        }

        return view('farmasi.edit-obat', compact('obat'));
    }

    /**
     * Update Obat
     */
    public function updateObat(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'kode_obat' => 'required|string|max:20|unique:obat,kode_obat,' . $id . ',obat_id',
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|in:tablet,kapsul,sirup,salep,injeksi',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $obat = Obat::findOrFail($id);

            if ($obat->is_deleted) {
                abort(404);
            }

            $obat->update($request->all());

            return redirect()->route('farmasi.stok-obat')
                ->with('success', 'Obat berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui obat: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update Stok Obat (Tambah/Kurang)
     */
    public function updateStok(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'jumlah' => 'required|integer',
            'tipe' => 'required|in:tambah,kurang',
        ]);

        try {
            $obat = Obat::findOrFail($id);

            if ($obat->is_deleted) {
                abort(404);
            }

            if ($request->tipe === 'tambah') {
                $obat->increment('stok', $request->jumlah);
                $message = 'Stok berhasil ditambahkan!';
            } else {
                if ($obat->stok < $request->jumlah) {
                    return back()->with('error', 'Stok tidak mencukupi untuk dikurangi!');
                }
                $obat->decrement('stok', $request->jumlah);
                $message = 'Stok berhasil dikurangi!';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui stok: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Obat
     */
    public function deleteObat($id): RedirectResponse
    {
        try {
            $obat = Obat::findOrFail($id);

            // Soft delete: set is_deleted to true
            $obat->update(['is_deleted' => true]);

            return redirect()->route('farmasi.stok-obat')
                ->with('success', 'Obat berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }

    /**
     * Selesaikan Resep & Buat Tagihan
     */
    public function selesaikanResepDanBuatTagihan($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $resep = Resep::with(['detailResep.obat', 'pemeriksaan.pendaftaran'])->findOrFail($id);
            $apoteker = Auth::user()->staf;
            
            // Cek authorization
            if ($resep->apoteker_id !== $apoteker->staf_id) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menyelesaikan resep ini!');
            }
            
            if ($resep->status !== 'diproses') {
                return back()->with('error', 'Resep tidak dalam status diproses!');
            }
            
            // Ambil detail resep dan hitung total
            $detailReseps = $resep->detailResep;
            $totalTagihanObat = 0;

            // Cek stok dan kurangi stok obat
            foreach ($detailReseps as $detail) {
                $obat = $detail->obat;
                
                if ($obat->stok < $detail->jumlah) {
                    DB::rollBack();
                    return back()->with('error', "Stok obat {$obat->nama_obat} tidak mencukupi!");
                }
                
                // Hitung subtotal untuk tagihan
                $subtotal = $detail->jumlah * $obat->harga;
                $totalTagihanObat += $subtotal;
                
                // Kurangi stok
                $obat->decrement('stok', $detail->jumlah);
            }

            // 1. Buat Tagihan
            // PERBAIKAN: Ambil pasien_id dari relasi panjang
            $pasienId = $resep->pemeriksaan->pendaftaran->pasien_id;

            $tagihan = \App\Models\Tagihan::create([
                'pendaftaran_id' => $resep->pemeriksaan->pendaftaran_id,
                'pasien_id' => $pasienId, 
                'jenis_tagihan' => 'obat',
                'status' => 'belum_bayar',
                'subtotal' => $totalTagihanObat, 
                'total_tagihan' => $totalTagihanObat,
                'tanggal_tagihan' => now(),
            ]);

            // 2. Buat Detail Tagihan
            foreach ($detailReseps as $detail) {
                $subtotal = $detail->jumlah * $detail->obat->harga;
                
                \App\Models\DetailTagihan::create([
                    'tagihan_id' => $tagihan->tagihan_id,
                    'deskripsi_item' => $detail->obat->nama_obat . ' (' . $detail->aturan_pakai . ')',
                    'kuantitas' => $detail->jumlah,
                    'harga_satuan' => $detail->obat->harga,
                    'subtotal' => $subtotal,
                ]);
            }
            
            // 3. Update status resep menjadi 'selesai'
            $resep->update(['status' => 'selesai']);

            DB::commit();

            return redirect()->route('farmasi.daftar-resep')->with('success', 'Resep selesai diselesaikan, tagihan Apotek berhasil dibuat!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan resep dan membuat tagihan: ' . $e->getMessage());
        }
    }
}