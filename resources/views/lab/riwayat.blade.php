@extends('layouts.dashboard')

@section('title', 'Riwayat Pemeriksaan Lab')

@section('content')
<div class="space-y-8">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Riwayat Pemeriksaan</h1>
            <p class="text-sm text-gray-500 mt-1">Arsip pemeriksaan laboratorium yang telah Anda tangani.</p>
        </div>
    </div>

    {{-- Statistik Riwayat (Modern Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card Total --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pemeriksaan</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $riwayatPermintaan->total() }}</h3>
                <p class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-lg mt-3 inline-block font-medium">
                    Keseluruhan
                </p>
            </div>
            <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        {{-- Card Bulan Ini --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{-- Note: Ini hanya menghitung data di halaman aktif pagination --}}
                    {{ $riwayatPermintaan->where('tanggal_permintaan', '>=', now()->startOfMonth())->count() }}
                </h3>
                <p class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-lg mt-3 inline-block font-medium">
                    {{ now()->translatedFormat('F Y') }}
                </p>
            </div>
            <div class="p-3 bg-green-100 rounded-xl text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        {{-- Card Minggu Ini --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Minggu Ini</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{-- Note: Ini hanya menghitung data di halaman aktif pagination --}}
                    {{ $riwayatPermintaan->where('tanggal_permintaan', '>=', now()->startOfWeek())->count() }}
                </h3>
                <p class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-lg mt-3 inline-block font-medium">
                    Minggu aktif
                </p>
            </div>
            <div class="p-3 bg-purple-100 rounded-xl text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Daftar Riwayat</h3>
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('lab.riwayat') }}" id="search-form" class="relative hidden sm:block">
                <input type="text"
                       name="search"
                       id="search-input"
                       value="{{ request('search') }}"
                       placeholder="Cari pasien..."
                       class="pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>

        @if($riwayatPermintaan->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter Pengirim</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemeriksaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @foreach($riwayatPermintaan as $permintaan)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 block">
                                    {{ $permintaan->tanggal_permintaan ? $permintaan->tanggal_permintaan->format('d/m/Y') : '-' }}
                                </span>
                                <span class="text-xs text-gray-500 block mt-0.5">
                                    {{ $permintaan->tanggal_permintaan ? $permintaan->tanggal_permintaan->format('H:i') : '-' }} WIB
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    {{-- PERBAIKAN: Akses Pasien --}}
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                                    </span>
                                    <span class="text-xs font-mono text-gray-500 mt-0.5">
                                        {{ $permintaan->pemeriksaan->pendaftaran->pasien->no_rm ?? $permintaan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    {{-- PERBAIKAN: Akses Dokter --}}
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'Dokter Tidak Ditemukan' }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->spesialis ?? 'Umum' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 group-hover:bg-white transition-colors">
                                    {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($permintaan->status == 'diproses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5 animate-pulse"></span>
                                        Diproses
                                    </span>
                                @elseif($permintaan->status == 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        {{ ucfirst($permintaan->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('lab.detail-permintaan', $permintaan->permintaan_lab_id) }}" 
                                       class="text-gray-400 hover:text-blue-600 transition-colors" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    
                                    @if($permintaan->status == 'diproses')
                                        <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-sm" title="Input Hasil">
                                            Input
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $riwayatPermintaan->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-gray-900 font-medium">Belum ada riwayat</h3>
                <p class="text-gray-500 text-sm mt-1">Anda belum memiliki riwayat pemeriksaan laboratorium.</p>
                <a href="{{ route('lab.daftar-permintaan') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors text-sm font-medium shadow-sm">
                    Lihat Permintaan Baru
                </a>
            </div>
        @endif
    </div>

    {{-- Statistik Breakdown (Visual Cards) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Statistik Per Jenis Pemeriksaan</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $jenisPemeriksaanLabels = [
                    'darah_lengkap' => ['label' => 'Darah Lengkap', 'color' => 'bg-red-50 text-red-700 border-red-100'],
                    'urine' => ['label' => 'Urine', 'color' => 'bg-yellow-50 text-yellow-700 border-yellow-100'],
                    'gula_darah' => ['label' => 'Gula Darah', 'color' => 'bg-pink-50 text-pink-700 border-pink-100'],
                    'kolesterol' => ['label' => 'Kolesterol', 'color' => 'bg-orange-50 text-orange-700 border-orange-100'],
                    'radiologi' => ['label' => 'Radiologi', 'color' => 'bg-gray-50 text-gray-700 border-gray-200'],
                    'lainnya' => ['label' => 'Lainnya', 'color' => 'bg-blue-50 text-blue-700 border-blue-100'],
                ];
            @endphp
            @foreach($jenisPemeriksaanLabels as $key => $style)
                @php
                    // Note: Count ini hanya menghitung dari data pagination yang sedang aktif
                    $count = $riwayatPermintaan->where('jenis_pemeriksaan', $key)->count();
                @endphp
                <div class="flex flex-col items-center justify-center p-4 rounded-xl border {{ $style['color'] }} transition-transform hover:scale-105">
                    <span class="text-2xl font-bold">{{ $count }}</span>
                    <span class="text-xs font-medium mt-1">{{ $style['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
// Debounce function for search
let searchTimeout;
const searchInput = document.getElementById('search-input');
const searchForm = document.getElementById('search-form');

if (searchInput) {
    // Auto-focus search input if search parameter exists in URL (even if empty)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        searchInput.focus();
        // Set cursor to end of text
        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500); // 500ms delay
    });
}
</script>
@endsection