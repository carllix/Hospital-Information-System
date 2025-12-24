@extends('layouts.dashboard')

@section('title', 'Dashboard Laboratorium')
@section('dashboard-title', 'Dashboard Staf Laboratorium')

@section('content')
<div class="space-y-6">
    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <x-toast :type="session('success') ? 'success' : 'error'" :message="session('success') ?? session('error')" />
    @endif

    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->staf->nama_lengkap }}!</h2>
                <p class="mt-2 text-gray-600">Kelola dan proses permintaan pemeriksaan laboratorium</p>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Permintaan Menunggu --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Permintaan Menunggu</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $permintaanMenunggu }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu diproses</p>
            </div>
        </div>

        {{-- Sedang Diproses --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Sedang Diproses</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $permintaanDiproses }}</p>
                <p class="text-xs text-gray-500 mt-1">Dalam pemeriksaan</p>
            </div>
        </div>

        {{-- Selesai Hari Ini --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Selesai Hari Ini</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $permintaanSelesaiHariIni }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        {{-- Total Bulan Ini --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Bulan Ini</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $totalPermintaanBulanIni }}</p>
                <p class="text-xs text-gray-500 mt-1">Bulan ini</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Daftar Permintaan -->
            <a href="{{ route('lab.daftar-permintaan') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Daftar Permintaan</p>
                    <p class="text-sm text-gray-600">Lihat semua permintaan lab</p>
                </div>
            </a>

            <!-- Riwayat Pemeriksaan -->
            <a href="{{ route('lab.riwayat') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Riwayat Pemeriksaan</p>
                    <p class="text-sm text-gray-600">Lihat hasil pemeriksaan</p>
                </div>
            </a>

            <!-- Laporan -->
            <a href="{{ route('lab.laporan') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Laporan</p>
                    <p class="text-sm text-gray-600">Lihat laporan laboratorium</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Permintaan Lab Menunggu --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Permintaan Lab Menunggu</h3>
                <a href="{{ route('lab.daftar-permintaan') }}" class="text-sm text-[#f56e9d] hover:text-[#d14a7a] font-medium">
                    Lihat Semua →
                </a>
            </div>
        </div>

        @if($permintaanMenungguList->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter Pengirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pemeriksaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permintaanMenungguList as $permintaan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{-- Mengambil nama pasien via pemeriksaan -> pendaftaran --}}
                                    {{ $permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{-- Pastikan kolom ini sesuai dengan database (no_rm) --}}
                                    RM: {{ $permintaan->pemeriksaan->pendaftaran->pasien->no_rm ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{-- Mengambil dokter via pendaftaran -> jadwalDokter --}}
                                    {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'Dokter Tidak Ditemukan' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#f56e9d]/10 text-[#f56e9d]">
                                    {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($permintaan->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($permintaan->status ?? 'Menunggu') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" class="text-[#f56e9d] hover:text-[#d14a7a]">Proses</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2">Tidak ada permintaan lab yang menunggu</p>
            </div>
        @endif
    </div>
</div>
@endsection
