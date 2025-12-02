@extends('layouts.dashboard')

@section('title', 'Riwayat Pemeriksaan Lab')
@section('dashboard-title', 'Riwayat Pemeriksaan Lab')

@section('content')
<div class="space-y-6">
    {{-- Statistik Riwayat --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Pemeriksaan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $riwayatPermintaan->total() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $riwayatPermintaan->where('tanggal_permintaan', '>=', now()->startOfMonth())->count() }}
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Minggu Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $riwayatPermintaan->where('tanggal_permintaan', '>=', now()->startOfWeek())->count() }}
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Pemeriksaan --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Pemeriksaan Lab Anda</h3>
            <p class="text-sm text-gray-600 mt-1">Menampilkan semua pemeriksaan yang telah Anda kerjakan</p>
        </div>

        @if($riwayatPermintaan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter Pengirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pemeriksaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($riwayatPermintaan as $permintaan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-medium text-gray-900">{{ $permintaan->tanggal_permintaan->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $permintaan->tanggal_permintaan->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $permintaan->pasien->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">RM: {{ $permintaan->pasien->no_rekam_medis }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $permintaan->dokter->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $permintaan->dokter->spesialisasi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($permintaan->status == 'diproses')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Sedang Diproses
                                    </span>
                                @elseif($permintaan->status == 'selesai')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('lab.detail-permintaan', $permintaan->permintaan_lab_id) }}" class="text-blue-600 hover:text-blue-900">
                                    Detail
                                </a>
                                @if($permintaan->status == 'diproses')
                                    <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" class="text-green-600 hover:text-green-900">
                                        Input Hasil
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $riwayatPermintaan->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2 text-lg font-medium">Belum ada riwayat pemeriksaan</p>
                <p class="mt-1 text-sm">Riwayat pemeriksaan yang Anda kerjakan akan muncul di sini</p>
                <a href="{{ route('lab.daftar-permintaan') }}" class="mt-4 inline-block px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors">
                    Lihat Permintaan Lab
                </a>
            </div>
        @endif
    </div>

    {{-- Quick Stats --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Per Jenis Pemeriksaan</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $jenisPemeriksaan = [
                    'darah_lengkap' => 'Darah Lengkap',
                    'urine' => 'Urine',
                    'gula_darah' => 'Gula Darah',
                    'kolesterol' => 'Kolesterol',
                    'radiologi' => 'Radiologi',
                    'lainnya' => 'Lainnya'
                ];
            @endphp
            @foreach($jenisPemeriksaan as $key => $label)
                @php
                    $count = $riwayatPermintaan->where('jenis_pemeriksaan', $key)->count();
                @endphp
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-pink-600">{{ $count }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
