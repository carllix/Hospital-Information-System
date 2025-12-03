<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirKlinikController extends Controller
{
    // 1. Dashboard with statistics and pending payments
    public function dashboard(Request $request)
    {
        // Get date range for filtering (default: today)
        $startDate = $request->get('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        
        // Pending payments (belum bayar)
        $tagihanPending = Tagihan::whereIn('jenis_tagihan', ['konsultasi', 'tindakan']) 
            ->where('status', 'belum_bayar') 
            ->with(['pasien', 'pendaftaran']) 
            ->latest()
            ->paginate(10);

        // Today's statistics
        $todayStats = $this->getDashboardStats($startDate, $endDate);
        
        // Recent transactions
        $recentTransactions = $this->getRecentTransactions(5);

        return view('kasir-klinik.dashboard', compact(
            'tagihanPending', 
            'todayStats', 
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }

    // 2. Show specific bill details and payment form
    public function show(Tagihan $tagihan)
    {
        // Ensure bill is not paid yet
        if ($tagihan->status === 'lunas') {
            return redirect()->route('kasir-klinik.dashboard')->with('error', 'Tagihan sudah lunas.');
        }

        // Ensure it's a clinic bill
        if (!in_array($tagihan->jenis_tagihan, ['konsultasi', 'tindakan'])) {
            return redirect()->route('kasir-klinik.dashboard')->with('error', 'Tagihan bukan untuk klinik.');
        }

        $tagihan->load(['pasien', 'pendaftaran', 'detailTagihan']);

        return view('kasir-klinik.pembayaran', compact('tagihan'));
    }

    // 3. Process payment
    public function processPayment(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|in:Tunai,Debit,Transfer',
            'catatan' => 'nullable|string|max:255'
        ]);

        $jumlahBayar = $request->jumlah_bayar;
        $totalTagihan = $tagihan->total_tagihan;

        // Must be paid in full
        if ($jumlahBayar < $totalTagihan) {
             return back()->with('error', 'Pembayaran harus lunas. Jumlah yang dibayar kurang dari total tagihan.');
        }

        try {
            DB::beginTransaction();

            // Record payment
            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->tagihan_id,
                'jumlah_bayar' => $totalTagihan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan ?? 'Pembayaran klinik lunas',
                'tanggal_bayar' => now(),
                'kasir_id' => auth()->id(), // Track which cashier processed
            ]);

            // Update bill status
            $tagihan->update(['status' => 'lunas']);

            DB::commit();

            // Calculate change
            $kembalian = max(0, $jumlahBayar - $totalTagihan);

            return redirect()->route('kasir-klinik.invoice', $tagihan)
                             ->with('success', 'Pembayaran berhasil diproses!')
                             ->with('kembalian', $kembalian);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    // 4. Show invoice/receipt
    public function invoice(Tagihan $tagihan)
    {
        $tagihan->load(['pasien', 'detailTagihan', 'pembayaran' => function($query) {
            $query->latest('tanggal_bayar')->limit(1);
        }]);
        
        $pembayaranTerakhir = $tagihan->pembayaran->first();
        $kembalian = session('kembalian', 0);

        return view('kasir-klinik.invoice', compact('tagihan', 'pembayaranTerakhir', 'kembalian'));
    }

    // 5. Transaction history
    public function riwayat(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $search = $request->get('search');

        // Parse dates once and set boundaries for the query
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        $query = Tagihan::whereIn('jenis_tagihan', ['konsultasi', 'tindakan'])
            ->where('status', 'lunas')
            ->with(['pasien', 'pembayaran']);

        // Apply the date filter
        $query->whereBetween('created_at', [$start, $end]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pasien', function($pasienQuery) use ($search) {
                    $pasienQuery->where('nama_lengkap', 'like', "%{$search}%") 
                                ->orWhere('no_rekam_medis', 'like', "%{$search}%"); 
                })->orWhere('tagihan_id', 'like', "%{$search}%");
            });
        }

        // Use orderBy instead of the Eloquent Collection method latest()
        $riwayat = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Summary stats for the period
        $summary = $this->getPeriodSummary($startDate, $endDate);

        return view('kasir-klinik.riwayat', compact('riwayat', 'startDate', 'endDate', 'search', 'summary'));
    }

    // 6. Financial reports
    public function laporan(Request $request)
    {
        $period = $request->get('period', 'month');
        $customStart = $request->get('custom_start');
        $customEnd = $request->get('custom_end');

        [$startDate, $endDate] = $this->getDateRange($period, $customStart, $customEnd);
        
        // Detailed financial data
        $laporan = $this->generateFinancialReport($startDate, $endDate);

        return view('kasir-klinik.laporan', compact('laporan', 'period', 'startDate', 'endDate'));
    }

    // Helper Methods
    private function getDashboardStats($startDate, $endDate)
    {
        return [
            'total_pendapatan' => Pembayaran::whereHas('tagihan', function($q) {
                $q->whereIn('jenis_tagihan', ['konsultasi', 'tindakan']);
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('jumlah_bayar'),
            
            'jumlah_transaksi' => Pembayaran::whereHas('tagihan', function($q) {
                $q->whereIn('jenis_tagihan', ['konsultasi', 'tindakan']);
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count(),
            
            'tagihan_pending' => Tagihan::whereIn('jenis_tagihan', ['konsultasi', 'tindakan'])
                ->where('status', 'belum_bayar')->count(),
                
            'rata_rata_transaksi' => Pembayaran::whereHas('tagihan', function($q) {
                $q->whereIn('jenis_tagihan', ['konsultasi', 'tindakan']);
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->avg('jumlah_bayar') ?? 0,
        ];
    }

    private function getRecentTransactions($limit = 5)
    {
        return Tagihan::whereIn('jenis_tagihan', ['konsultasi', 'tindakan'])
            ->where('status', 'lunas')
            ->with(['pasien', 'pembayaran'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    private function getPeriodSummary($startDate, $endDate)
    {
        return [
            'total_revenue' => Pembayaran::whereHas('tagihan', function($q) {
                $q->whereIn('jenis_tagihan', ['konsultasi', 'tindakan']);
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('jumlah_bayar'),
            
            'transaction_count' => Pembayaran::whereHas('tagihan', function($q) {
                $q->whereIn('jenis_tagihan', ['konsultasi', 'tindakan']);
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count(),
            
            'konsultasi_count' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'konsultasi');
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count(),
            
            'tindakan_count' => Pembayaran::whereHas('tagihan', function($q) {
                $q->where('jenis_tagihan', 'tindakan');
            })->whereBetween('tanggal_bayar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
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
        $pembayaran = Pembayaran::whereHas('tagihan', function($q) {
            $q->whereIn('jenis_tagihan', ['konsultasi', 'tindakan']);
        })->with(['tagihan.pasien'])
        ->whereBetween('tanggal_bayar', [$startDate->startOfDay(), $endDate->endOfDay()])
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
            'by_service_type' => $pembayaran->groupBy('tagihan.jenis_tagihan')->map(function($group) {
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