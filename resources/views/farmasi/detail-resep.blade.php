@extends('layouts.dashboard')

@section('title', 'Detail Resep')
@section('dashboard-title', 'Detail Resep #' . $resep->resep_id)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('farmasi.daftar-resep') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Resep
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Resep -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Pasien & Dokter -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Resep</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Pasien</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $resep->pasien->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $resep->pasien->no_rekam_medis }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dokter Peresep</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $resep->dokter->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $resep->dokter->spesialisasi }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Resep</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resep->tanggal_resep->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <p class="mt-1">
                            @if($resep->status == 'menunggu')
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            @elseif($resep->status == 'diproses')
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Diproses
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Daftar Obat -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Obat</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Obat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aturan Pakai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($resep->detailResep as $detail)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $detail->obat->nama_obat }}</div>
                                    <div class="text-xs text-gray-500">{{ $detail->obat->kategori }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $detail->jumlah }} {{ $detail->obat->satuan }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $detail->aturan_pakai }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($detail->obat->stok >= $detail->jumlah)
                                        <span class="text-sm font-medium text-green-600">
                                            {{ $detail->obat->stok }} {{ $detail->obat->satuan }}
                                        </span>
                                    @elseif($detail->obat->stok > 0)
                                        <span class="text-sm font-medium text-orange-600">
                                            {{ $detail->obat->stok }} {{ $detail->obat->satuan }} (Kurang)
                                        </span>
                                    @else
                                        <span class="text-sm font-medium text-red-600">
                                            Stok Habis
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Informasi Apoteker -->
            @if($resep->apoteker)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Diproses Oleh</h3>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                        <span class="text-pink-600 font-bold text-lg">
                            {{ substr($resep->apoteker->nama_lengkap, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $resep->apoteker->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $resep->apoteker->nip }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
                
                @if($resep->status == 'menunggu')
                    <form method="POST" action="{{ route('farmasi.proses-resep', $resep->resep_id) }}" onsubmit="return confirm('Yakin ingin memproses resep ini?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Proses Resep
                        </button>
                    </form>
                @elseif($resep->status == 'diproses' && $resep->apoteker_id == Auth::user()->staf->staf_id)
                    <form method="POST" action="{{ route('farmasi.selesaikan-resep', $resep->resep_id) }}" onsubmit="return confirm('Yakin ingin menyelesaikan resep ini? Stok obat akan dikurangi.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Selesaikan Resep
                        </button>
                    </form>
                @endif

                <a href="{{ route('farmasi.daftar-resep') }}" class="mt-3 block w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                    Kembali
                </a>
            </div>

            <!-- Info Pemeriksaan -->
            @if($resep->pemeriksaan)
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 mb-2">Diagnosa</h4>
                <p class="text-sm text-blue-800">{{ $resep->pemeriksaan->diagnosa }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection
