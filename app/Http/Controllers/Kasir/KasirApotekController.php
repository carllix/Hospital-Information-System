<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirApotekController extends Controller
{
    // 1. Dashboard with statistics and pending payments
    public function dashboard(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        
        // FIXED: Filter by jenis_item in detail_tagihan instead of jenis_tagihan
        $tagihanPending = Tagihan::where('status', 'belum_bayar')
            ->whereHas('detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })
            ->with(['pemeriksaan.pendaftaran.pasien', 'detailTagihan']) 
            ->latest()
            ->paginate(10);

        // Today's statistics
        $todayStats = $this->getDashboardStats($startDate, $endDate);
        
        // Recent transactions
        $recentTransactions = $this->getRecentTransactions(5);

        return view('kasir-apotek.dashboard', compact(
            'tagihanPending', 
            'todayStats', 
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }

    // 2. Show specific prescription bill
    public function show(Tagihan $tagihan)
    {
        if ($tagihan->status === 'lunas') {
            return redirect()->route('kasir-apotek.dashboard')->with('error', 'Tagihan sudah lunas.');
        }

        // FIXED: Check jenis_item in detail_tagihan
        $hasObat = $tagihan->detailTagihan()->where('jenis_item', 'obat')->exists();
        if (!$hasObat) {
            return redirect()->route('kasir-apotek.dashboard')->with('error', 'Tagihan bukan untuk apotek.');
        }

        $tagihan->load(['pemeriksaan.pendaftaran.pasien', 'detailTagihan']);

        return view('kasir-apotek.pembayaran', compact('tagihan'));
    }

    // 3. Process pharmacy payment
    public function processPayment(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|in:tunai,debit,kredit,transfer,qris,asuransi',
            'catatan' => 'nullable|string|max:255'
        ]);

        $jumlahBayar = $request->jumlah_bayar;
        $totalTagihan = $tagihan->total_tagihan;

        if ($jumlahBayar < $totalTagihan) {
             return back()->with('error', 'Pembayaran harus lunas. Jumlah yang dibayar kurang dari total tagihan.');
        }

        try {
            DB::beginTransaction();

            // Generate nomor kwitansi
            $noKwitansi = 'APT-' . now()->format('YmdHis') . '-' . $tagihan->tagihan_id;

            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->tagihan_id,
                'jumlah_bayar' => $totalTagihan,
                'metode_pembayaran' => strtolower($request->metode_pembayaran),
                'catatan' => $request->catatan ?? 'Pembayaran apotek lunas',
                'tanggal_bayar' => now(),
                'kasir_id' => auth()->user()->staf->staf_id ?? auth()->id(),
                'no_kwitansi' => $noKwitansi,
            ]);

            $tagihan->update(['status' => 'lunas']);

            DB::commit();

            $kembalian = max(0, $jumlahBayar - $totalTagihan);

            return redirect()->route('kasir-apotek.invoice', $tagihan)
                             ->with('success', 'Pembayaran apotek berhasil diproses!')
                             ->with('kembalian', $kembalian);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    // 4. Show pharmacy invoice
    public function invoice(Tagihan $tagihan)
    {
        $tagihan->load(['pemeriksaan.pendaftaran.pasien', 'detailTagihan', 'pembayaran']);

        $pembayaranTerakhir = $tagihan->pembayaran;
        $kembalian = session('kembalian', 0);

        return view('kasir-apotek.invoice', compact('tagihan', 'pembayaranTerakhir', 'kembalian'));
    }

    // 5. Transaction history
    public function riwayat(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $search = $request->get('search');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Tagihan::where('status', 'lunas')
            ->whereHas('detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })
            ->with(['pemeriksaan.pendaftaran.pasien', 'pembayaran'])
            ->whereBetween('created_at', [$start, $end]);

        if ($search) {
            $query->whereHas('pemeriksaan.pendaftaran.pasien', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_rekam_medis', 'like', "%{$search}%");
            })->orWhere('tagihan_id', 'like', "%{$search}%");
        }

        $riwayat = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $summary = $this->getPeriodSummary($startDate, $endDate);

        return view('kasir-apotek.riwayat', compact('riwayat', 'startDate', 'endDate', 'search', 'summary'));
    }

    // 6. Financial reports
    public function laporan(Request $request)
    {
        $period = $request->get('period', 'month');
        $customStart = $request->get('custom_start');
        $customEnd = $request->get('custom_end');

        [$startDate, $endDate] = $this->getDateRange($period, $customStart, $customEnd);
        
        $laporan = $this->generateFinancialReport($startDate, $endDate);

        return view('kasir-apotek.laporan', compact('laporan', 'period', 'startDate', 'endDate'));
    }

    // Helper Methods
    private function getDashboardStats($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        return [
            'total_pendapatan' => Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('jumlah_bayar'),
            
            'jumlah_transaksi' => Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end])
            ->count(),
            
            'tagihan_pending' => Tagihan::where('status', 'belum_bayar')
                ->whereHas('detailTagihan', function($q) {
                    $q->where('jenis_item', 'obat');
                })->count(),
                
            'rata_rata_transaksi' => Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end])
            ->avg('jumlah_bayar') ?? 0,
        ];
    }

    private function getRecentTransactions($limit = 5)
    {
        return Tagihan::where('status', 'lunas')
            ->whereHas('detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })
            ->with(['pemeriksaan.pendaftaran.pasien', 'pembayaran'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    private function getPeriodSummary($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return [
            'total_revenue' => Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('jumlah_bayar'),
            
            'transaction_count' => Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end])
            ->count(),
            
            'obat_count' => Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
                $q->where('jenis_item', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end])
            ->count(),
        ];
    }

    private function getDateRange($period, $customStart = null, $customEnd = null)
    {
        switch ($period) {
            case 'today':
                return [Carbon::today(), Carbon::today()];
            case 'week':
                return [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()];
            case 'month':
                return [Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()];
            case 'custom':
                return [
                    Carbon::parse($customStart ?? Carbon::today()),
                    Carbon::parse($customEnd ?? Carbon::today())
                ];
            default:
                return [Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()];
        }
    }

    private function generateFinancialReport($startDate, $endDate)
    {
        $start = $startDate->copy()->startOfDay();
        $end = $endDate->copy()->endOfDay();
        
        $pembayaran = Pembayaran::whereHas('tagihan.detailTagihan', function($q) {
            $q->where('jenis_item', 'obat');
        })->with(['tagihan.pemeriksaan.pendaftaran.pasien'])
        ->whereBetween('tanggal_bayar', [$start, $end])
        ->get();

        return [
            'total_revenue' => $pembayaran->sum('jumlah_bayar'),
            'transactions' => $pembayaran,
            'by_payment_method' => $pembayaran->groupBy('metode_pembayaran')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('jumlah_bayar')
                ];
            }),
            'daily_breakdown' => $pembayaran->groupBy(function($item) {
                return Carbon::parse($item->tanggal_bayar)->format('Y-m-d');
            })->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('jumlah_bayar')
                ];
            })
        ];
    }
}