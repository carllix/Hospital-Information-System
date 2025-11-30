<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KasirKlinikController extends Controller
{
    public function dashboard(): View
    {
        return view('kasir-klinik.dashboard');
    }
}
