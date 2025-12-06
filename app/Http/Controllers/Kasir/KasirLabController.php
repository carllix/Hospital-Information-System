<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Ini KasirLab apa KasirApotek ya bang wkwkwk
class KasirApotekController extends Controller
{
    // Helper: Safely parse and set date boundaries (internal use)
    private function parseDateBoundaries($date) {
        $carbonDate = ($date instanceof Carbon) ? $date : Carbon::parse($date);
        return $carbonDate;
    }

    // 1. Dashboard with statistics and pending payments
    public function dashboard(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        
        // Pending pharmacy payments
        $tagihanPending = Tagihan::where('jenis_tagihan', 'obat') 
            ->where('status', 'belum_bayar') 
            ->with(['pasien', 'pendaftaran']) 
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

        if ($tagihan->jenis_tagihan !== 'obat') {
            return redirect()->route('kasir-apotek.dashboard')->with('error', 'Tagihan bukan untuk apotek.');
        }

        $tagihan->load(['pasien', 'pendaftaran', 'detailTagihan']);

        return view('kasir-apotek.pembayaran', compact('tagihan'));
    }

    // 3. Process pharmacy payment
    public function processPayment(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|in:Tunai,Debit,Transfer',
            'catatan' => 'nullable|string|max:255'
        ]);

        $jumlahBayar = $request->jumlah_bayar;
        $totalTagihan = $tagihan->total_tagihan;

        if ($jumlahBayar < $totalTagihan) {
             return back()->with('error', 'Pembayaran harus lunas. Jumlah yang dibayar kurang dari total tagihan.');
        }

        try {
            DB::beginTransaction();

            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->tagihan_id,
                'jumlah_bayar' => $totalTagihan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan ?? 'Pembayaran apotek lunas',
                'tanggal_bayar' => now(),
                'kasir_id' => auth()->id(),
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
        $tagihan->load(['pasien', 'detailTagihan', 'pembayaran' => function($query) {
            $query->latest('tanggal_bayar')->limit(1);
        }]);
        
        $pembayaranTerakhir = $tagihan->pembayaran->first();
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

        $query = Tagihan::where('jenis_tagihan', 'obat')
            ->where('status', 'lunas')
            ->with(['pasien', 'pembayaran'])
            ->whereBetween('created_at', [$start, $end]); // FIXED DATE

        if ($search) {
            $query->whereHas('pasien', function($q) use ($search) {
                // Asumsi nama pasien di kolom 'nama' dan nomor rekam medis di 'no_rm'
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_rm', 'like', "%{$search}%"); 
            })->orWhere('tagihan_id', 'like', "%{$search}%");
        }

        // FIX: Use orderBy instead of latest() before paginate
        $riwayat = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $summary = $this->getPeriodSummary($startDate, $endDate);

        return view('kasir-apotek.riwayat', compact('riwayat', 'startDate', 'endDate', 'search', 'summary'));
    }

    // 6. Financial reports
    public function laporan(Request $request)
    {
        $period = $request->get('period', 'month'); // Default changed to month for a meaningful report
        $customStart = $request->get('custom_start');
        $customEnd = $request->get('custom_end');

        [$startDate, $endDate] = $this->getDateRange($period, $customStart, $customEnd);
        
        $laporan = $this->generateFinancialReport($startDate, $endDate);

        return view('kasir-apotek.laporan', compact('laporan', 'period', 'startDate', 'endDate'));
    }

    // Helper Methods
    private function getDashboardStats($startDate, $endDate)
    {
        $start = $this->parseDateBoundaries($startDate)->startOfDay();
        $end = $this->parseDateBoundaries($endDate)->endOfDay();
        
        return [
            'total_pendapatan' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
            ->sum('jumlah_bayar'),
            
            'jumlah_transaksi' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
            ->count(),
            
            'tagihan_pending' => Tagihan::where('jenis_tagihan', 'obat')
                ->where('status', 'belum_bayar')->count(),
                
            'rata_rata_transaksi' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
            ->avg('jumlah_bayar') ?? 0,
        ];
    }

    private function getRecentTransactions($limit = 5)
    {
        return Tagihan::where('jenis_tagihan', 'obat')
            ->where('status', 'lunas')
            ->with(['pasien', 'pembayaran'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    private function getPeriodSummary($startDate, $endDate)
    {
        $start = $this->parseDateBoundaries($startDate)->startOfDay();
        $end = $this->parseDateBoundaries($endDate)->endOfDay();

        return [
            'total_revenue' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
            ->sum('jumlah_bayar'),
            
            'transaction_count' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
            ->count(),
            
            'obat_count' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'obat');
            })->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
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
        
        $pembayaran = Pembayaran::whereHas('tagihan', function($q) {
            $q->where('jenis_tagihan', 'obat');
        })->with(['tagihan.pasien'])
        ->whereBetween('tanggal_bayar', [$start, $end]) // FIXED DATE
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