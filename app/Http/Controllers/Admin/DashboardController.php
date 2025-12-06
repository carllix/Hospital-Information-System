<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PermintaanLab;
use App\Models\Resep;
use App\Models\Staf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalPasien = Pasien::where('is_deleted', false)->count();
        $totalDokter = Dokter::where('is_deleted', false)->count();
        $totalStaf = Staf::where('is_deleted', false)->count();

        $pendaftaranHariIni = Pendaftaran::whereDate('tanggal_kunjungan', $today)->count();

        $pendingResep = Resep::whereIn('status', ['menunggu', 'diproses'])->count();

        $pendingLab = PermintaanLab::whereIn('status', ['menunggu', 'diambil_sampel', 'diproses'])->count();

        return view('admin.dashboard', compact(
            'totalPasien',
            'totalDokter',
            'totalStaf',
            'pendaftaranHariIni',
            'pendingResep',
            'pendingLab'
        ));
    }
}
