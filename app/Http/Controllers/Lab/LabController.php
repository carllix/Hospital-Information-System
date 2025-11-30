<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LabController extends Controller
{
    public function dashboard(): View
    {
        return view('lab.dashboard');
    }
}
