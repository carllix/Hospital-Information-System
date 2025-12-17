<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\Layanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KasirController extends Controller
{
    /**
     * Dashboard Kasir - Menampilkan pasien siap bayar
     * Pasien siap bayar = pemeriksaan status 'selesai' yang belum ada tagihan atau tagihan belum lunas
     */
    public function dashboard(Request $request)
    {
        $kasir = Auth::user()->staf;

        // Pemeriksaan selesai yang BELUM punya tagihan (perlu dibuat tagihan)
        $pemeriksaanSiapTagihan = Pemeriksaan::with([
                'pendaftaran.pasien',
                'pendaftaran.jadwalDokter.dokter',
                'resep.detailResep.obat',
                'permintaanLab.hasilLab'
            ])
            ->where('status', 'selesai')
            ->whereDoesntHave('tagihan')
            ->orderBy('tanggal_pemeriksaan', 'asc')
            ->get();

        // Tagihan yang sudah dibuat tapi BELUM LUNAS
        $tagihanPending = Tagihan::with([
                'pemeriksaan.pendaftaran.pasien',
                'pemeriksaan.pendaftaran.jadwalDokter.dokter',
                'detailTagihan'
            ])
            ->whereIn('status', ['belum_bayar', 'sebagian'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        // Statistik hari ini
        $todayStats = [
            'total_pendapatan' => Pembayaran::whereDate('tanggal_bayar', today())->sum('jumlah_bayar'),
            'jumlah_transaksi' => Pembayaran::whereDate('tanggal_bayar', today())->count(),
            'tagihan_pending' => Tagihan::whereIn('status', ['belum_bayar', 'sebagian'])->count(),
            'pasien_siap_tagihan' => $pemeriksaanSiapTagihan->count(),
        ];

        // Transaksi terakhir
        $recentTransactions = Tagihan::with(['pemeriksaan.pendaftaran.pasien', 'pembayaran'])
            ->where('status', 'lunas')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'kasir',
            'pemeriksaanSiapTagihan',
            'tagihanPending',
            'todayStats',
            'recentTransactions'
        ));
    }

    /**
     * Buat Tagihan untuk pemeriksaan
     * Generate detail_tagihan dari: konsultasi, tindakan, obat, lab
     */
    public function buatTagihan($pemeriksaanId)
    {
        $pemeriksaan = Pemeriksaan::with([
            'pendaftaran.pasien',
            'pendaftaran.jadwalDokter.dokter',
            'resep.detailResep.obat',
            'permintaanLab.hasilLab'
        ])->findOrFail($pemeriksaanId);

        // Cek apakah sudah ada tagihan
        if ($pemeriksaan->tagihan) {
            return redirect()->route('kasir.detail', $pemeriksaan->tagihan->tagihan_id)
                ->with('info', 'Tagihan sudah ada untuk pemeriksaan ini.');
        }

        try {
            DB::beginTransaction();

            $totalTagihan = 0;
            $detailItems = [];

            // [A] KONSULTASI (selalu ada)
            $layananKonsultasi = Layanan::where('kategori', 'konsultasi')
                ->where('is_deleted', false)
                ->first();

            if ($layananKonsultasi) {
                $detailItems[] = [
                    'jenis_item' => 'konsultasi',
                    'layanan_id' => $layananKonsultasi->layanan_id,
                    'nama_item' => $layananKonsultasi->nama_layanan,
                    'jumlah' => 1,
                    'harga_satuan' => $layananKonsultasi->harga,
                    'subtotal' => $layananKonsultasi->harga,
                ];
                $totalTagihan += $layananKonsultasi->harga;
            }

            // [B] TINDAKAN (jika ada tindakan_medis di pemeriksaan)
            if ($pemeriksaan->tindakan_medis) {
                $layananTindakan = Layanan::where('kategori', 'tindakan')
                    ->where('is_deleted', false)
                    ->first();

                if ($layananTindakan) {
                    $detailItems[] = [
                        'jenis_item' => 'tindakan',
                        'layanan_id' => $layananTindakan->layanan_id,
                        'nama_item' => $pemeriksaan->tindakan_medis,
                        'jumlah' => 1,
                        'harga_satuan' => $layananTindakan->harga,
                        'subtotal' => $layananTindakan->harga,
                    ];
                    $totalTagihan += $layananTindakan->harga;
                }
            }

            // [C] OBAT (dari resep jika ada dan sudah selesai)
            if ($pemeriksaan->resep && $pemeriksaan->resep->status === 'selesai') {
                foreach ($pemeriksaan->resep->detailResep as $detail) {
                    $subtotal = $detail->jumlah * $detail->obat->harga;
                    $detailItems[] = [
                        'jenis_item' => 'obat',
                        'detail_resep_id' => $detail->detail_resep_id,
                        'nama_item' => $detail->obat->nama_obat . ' (' . $detail->aturan_pakai . ')',
                        'jumlah' => $detail->jumlah,
                        'harga_satuan' => $detail->obat->harga,
                        'subtotal' => $subtotal,
                    ];
                    $totalTagihan += $subtotal;
                }
            }

            // [D] LAB (dari hasil lab jika ada)
            if ($pemeriksaan->permintaanLab) {
                foreach ($pemeriksaan->permintaanLab as $permintaan) {
                    if ($permintaan->status === 'selesai' && $permintaan->hasilLab->isNotEmpty()) {
                        foreach ($permintaan->hasilLab as $hasil) {
                            // Harga lab bisa diambil dari master atau fixed price
                            $hargaLab = 50000; // Default price, bisa dibuat tabel master harga lab
                            $detailItems[] = [
                                'jenis_item' => 'lab',
                                'hasil_lab_id' => $hasil->hasil_lab_id,
                                'nama_item' => $hasil->jenis_test . ' - ' . $hasil->parameter,
                                'jumlah' => 1,
                                'harga_satuan' => $hargaLab,
                                'subtotal' => $hargaLab,
                            ];
                            $totalTagihan += $hargaLab;
                        }
                    }
                }
            }

            // Buat Tagihan
            $tagihan = Tagihan::create([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'total_tagihan' => $totalTagihan,
                'status' => 'belum_bayar',
            ]);

            // Buat Detail Tagihan
            foreach ($detailItems as $item) {
                DetailTagihan::create(array_merge($item, [
                    'tagihan_id' => $tagihan->tagihan_id,
                ]));
            }

            DB::commit();

            return redirect()->route('kasir.detail', $tagihan->tagihan_id)
                ->with('success', 'Tagihan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Detail Tagihan & Form Pembayaran
     */
    public function detail($tagihanId)
    {
        $tagihan = Tagihan::with([
            'pemeriksaan.pendaftaran.pasien',
            'pemeriksaan.pendaftaran.jadwalDokter.dokter',
            'detailTagihan',
            'pembayaran'
        ])->findOrFail($tagihanId);

        return view('kasir.detail', compact('tagihan'));
    }

    /**
     * Proses Pembayaran
     */
    public function prosesPembayaran(Request $request, $tagihanId)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:tunai,debit,kredit,transfer,qris,asuransi',
            'catatan' => 'nullable|string|max:255',
        ]);

        $tagihan = Tagihan::findOrFail($tagihanId);
        $kasir = Auth::user()->staf;

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas!');
        }

        $jumlahBayar = $request->jumlah_bayar;
        $totalTagihan = $tagihan->total_tagihan;

        // Hitung total yang sudah dibayar sebelumnya
        $sudahDibayar = $tagihan->pembayaran()->sum('jumlah_bayar');
        $sisaTagihan = $totalTagihan - $sudahDibayar;

        if ($jumlahBayar < $sisaTagihan) {
            return back()->with('error', "Pembayaran harus minimal Rp " . number_format($sisaTagihan, 0, ',', '.') . " untuk melunasi tagihan.");
        }

        try {
            DB::beginTransaction();

            // Generate nomor kwitansi
            $noKwitansi = 'KWT-' . now()->format('YmdHis') . '-' . $tagihan->tagihan_id;

            Pembayaran::create([
                'tagihan_id' => $tagihan->tagihan_id,
                'tanggal_bayar' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar' => $sisaTagihan, // Simpan yang dibutuhkan saja
                'kasir_id' => $kasir->staf_id,
                'no_kwitansi' => $noKwitansi,
                'catatan' => $request->catatan,
            ]);

            $tagihan->update(['status' => 'lunas']);

            DB::commit();

            $kembalian = max(0, $jumlahBayar - $sisaTagihan);

            return redirect()->route('kasir.invoice', $tagihan->tagihan_id)
                ->with('success', 'Pembayaran berhasil!')
                ->with('kembalian', $kembalian);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Invoice / Kwitansi
     */
    public function invoice($tagihanId)
    {
        $tagihan = Tagihan::with([
            'pemeriksaan.pendaftaran.pasien',
            'pemeriksaan.pendaftaran.jadwalDokter.dokter',
            'detailTagihan',
            'pembayaran.kasir'
        ])->findOrFail($tagihanId);

        $pembayaranTerakhir = $tagihan->pembayaran()->latest('tanggal_bayar')->first();
        $kembalian = session('kembalian', 0);

        return view('kasir.invoice', compact('tagihan', 'pembayaranTerakhir', 'kembalian'));
    }

    /**
     * Riwayat Transaksi
     */
    public function riwayat(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $search = $request->get('search');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Tagihan::with([
                'pemeriksaan.pendaftaran.pasien',
                'pemeriksaan.pendaftaran.jadwalDokter.dokter',
                'pembayaran',
                'detailTagihan'
            ])
            ->where('status', 'lunas')
            ->whereHas('pembayaran', function($q) use ($start, $end) {
                $q->whereBetween('tanggal_bayar', [$start, $end]);
            });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('pemeriksaan.pendaftaran.pasien', function($pasienQuery) use ($search) {
                    $pasienQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('no_rekam_medis', 'like', "%{$search}%");
                })->orWhere('tagihan_id', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Summary
        $summary = [
            'total_pendapatan' => Pembayaran::whereBetween('tanggal_bayar', [$start, $end])->sum('jumlah_bayar'),
            'jumlah_transaksi' => Pembayaran::whereBetween('tanggal_bayar', [$start, $end])->count(),
        ];

        return view('kasir.riwayat', compact('riwayat', 'startDate', 'endDate', 'search', 'summary'));
    }

    /**
     * Laporan Keuangan
     */
    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $start = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $end = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

        $pembayaranList = Pembayaran::with([
                'tagihan.pemeriksaan.pendaftaran.pasien',
                'tagihan.detailTagihan',
                'kasir'
            ])
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        // Statistik
        $statistik = [
            'total_pendapatan' => $pembayaranList->sum('jumlah_bayar'),
            'jumlah_transaksi' => $pembayaranList->count(),
            'by_metode' => $pembayaranList->groupBy('metode_pembayaran')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('jumlah_bayar'),
                ];
            }),
            'by_jenis_item' => $pembayaranList->flatMap(function($p) {
                return $p->tagihan->detailTagihan ?? collect();
            })->groupBy('jenis_item')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('subtotal'),
                ];
            }),
        ];

        return view('kasir.laporan', compact('pembayaranList', 'statistik', 'bulan', 'tahun'));
    }
}
