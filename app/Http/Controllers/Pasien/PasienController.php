<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function dashboard(): View
    {
        return view('pasien.dashboard');
    }

    public function pembayaran(): View
    {
        return view('pasien.pembayaran');
    }

    public function rekamMedis(): View
    {
        return view('pasien.rekam-medis');
    }

    public function healthMonitoring(): View
    {
        return view('pasien.health-monitoring');
    }
}
