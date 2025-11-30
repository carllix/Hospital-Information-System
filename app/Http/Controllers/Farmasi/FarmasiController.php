<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FarmasiController extends Controller
{
    public function dashboard(): View
    {
        return view('farmasi.dashboard');
    }
}
