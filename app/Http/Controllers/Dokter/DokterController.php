<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function dashboard(): View
    {
        return view('dokter.dashboard');
    }
}
