<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KasirLabController extends Controller
{
    public function dashboard(): View
    {
        return view('kasir-lab.dashboard');
    }
}
