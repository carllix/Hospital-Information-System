@extends('layouts.dashboard')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Dokter</h1>
                <span class="px-3 py-1 bg-pink-50 text-pink-600 text-xs font-semibold rounded-full border border-pink-100">
                    {{ $dokter->spesialisasi ?? 'Dokter Umum' }}
                </span>
            </div>
            <p class="text-gray-500 mt-1">Selamat bertugas, <span class="font-semibold text-gray-700">Dr. {{ $dokter->nama_lengkap }}</span> 👋</p>
        </div>
        <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-right">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                <p class="text-xl font-bold text-gray-800">{{ now()->format('H:i') }} <span class="text-sm font-normal text-gray-400">WIB</span></p>
            </div>
            <div class="p-2 bg-pink-50 rounded-lg text-pink-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between z-10 relative">
                <div>
                    <p class="text-sm font-medium text-gray-500">Antrian Menunggu</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $antrianHariIni }}</h3>
                    <p class="text-xs text-blue-500 mt-1 font-medium bg-blue-50 inline-block px-2 py-1 rounded-md">
                        Hari Ini
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between z-10 relative">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pasien Selesai</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $pasienDitanganiHariIni }}</h3>
                    <p class="text-xs text-green-500 mt-1 font-medium bg-green-50 inline-block px-2 py-1 rounded-md">
                        Sudah Diperiksa
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between z-10 relative">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pasien</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPasienBulanIni }}</h3>
                    <p class="text-xs text-pink-500 mt-1 font-medium bg-pink-50 inline-block px-2 py-1 rounded-md">
                        Bulan Ini
                    </p>
                </div>
                <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="w-2 h-6 bg-pink-500 rounded-full mr-3"></span>
                Antrian Pasien Hari Ini
            </h2>
            </div>
        
        <div class="overflow-x-auto">
            @if($antrianPasien->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-gray-900 font-medium text-lg">Semua Bersih!</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-sm">Tidak ada antrian pasien saat ini. Silakan istirahat sejenak atau cek riwayat pemeriksaan.</p>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Antrian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keluhan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($antrianPasien as $antrian)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-lg border border-pink-100 group-hover:bg-pink-600 group-hover:text-white transition-all">
                                            {{ $antrian->nomor_antrian }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $antrian->pasien->nama_lengkap }}</span>
                                        <span class="text-xs text-gray-500 font-mono mt-0.5">RM: {{ $antrian->pasien->no_rm }}</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">{{ $antrian->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                            <span class="text-[10px] text-gray-400">• {{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} Thn</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 line-clamp-2 max-w-xs">{{ $antrian->keluhan ?? 'Tidak ada keluhan spesifik' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($antrian->status === 'menunggu')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                            <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($antrian->status === 'dipanggil')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-1.5"></span>
                                            Dipanggil
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($antrian->status === 'menunggu')
                                            <form method="POST" action="{{ route('dokter.panggil-pasien', $antrian->pendaftaran_id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" title="Panggil Pasien">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 shadow-sm transition-all hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Periksa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection