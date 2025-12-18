<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pasien\PasienController;

Route::post('/store-realtime-data', [PasienController::class, 'storeRealtimeData']);