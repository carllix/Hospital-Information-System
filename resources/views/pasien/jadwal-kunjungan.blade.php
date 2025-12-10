@extends('layouts.dashboard')

@section('title', 'Jadwal Kunjungan')
@section('dashboard-title', 'Jadwal Kunjungan Saya')

@section('content')
<div class="space-y-6">
    <!-- Info Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-2">Jadwal Kunjungan Mendatang</h2>
                <p class="text-sm opacity-90">Daftar janji temu/kunjungan Anda yang akan datang. Pastikan Anda hadir tepat waktu sesuai jadwal.</p>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Daftar Jadwal Kunjungan</h2>
        </div>

        @if($jadwalKunjungan->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Belum ada jadwal kunjungan mendatang</p>
            <a href="{{ route('pasien.pendaftaran-kunjungan') }}" class="mt-4 inline-block px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Daftar Kunjungan Sekarang
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Praktik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwalKunjungan as $kunjungan)
                    <tr class="hover:bg-gray-50 {{ $kunjungan->tanggal_kunjungan == today()->format('Y-m-d') ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->locale('id')->isoFormat('dddd') }}
                            </div>
                            <div class="text-gray-600">
                                {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}
                            </div>
                            @if($kunjungan->tanggal_kunjungan == today()->format('Y-m-d'))
                            <span class="mt-1 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Hari Ini
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#fff5f8] text-[#d14a7a]">
                                {{ $kunjungan->nomor_antrian }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $kunjungan->jadwalDokter->dokter->nama_lengkap ?? '-' }}</div>
                            @if($kunjungan->jadwalDokter?->dokter?->spesialisasi)
                            <div class="text-xs text-gray-500">{{ $kunjungan->jadwalDokter->dokter->spesialisasi }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($kunjungan->jadwalDokter)
                            <div class="font-medium text-gray-900">{{ $kunjungan->jadwalDokter->hari_praktik }}</div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($kunjungan->jadwalDokter->waktu_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($kunjungan->jadwalDokter->waktu_selesai)->format('H:i') }} WIB
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $kunjungan->keluhan_utama }}">
                                {{ $kunjungan->keluhan_utama }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($kunjungan->status === 'menunggu')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($kunjungan->status === 'dipanggil')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Dipanggil
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                Diperiksa
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Info Note -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Catatan:</strong> Harap datang 15 menit sebelum waktu praktik dimulai. Bawa nomor antrian Anda saat check-in di bagian pendaftaran.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
