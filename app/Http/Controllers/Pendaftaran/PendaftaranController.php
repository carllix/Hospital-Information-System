<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function dashboard(): View
    {
        return view('pendaftaran.dashboard');
    }

    public function pasienManagement(): View
    {
        return view('pendaftaran.pasien-management');
    }
}
