@extends('layouts.dashboard')

@section('title', 'Antrian Pasien')

@section('content')
<div class="space-y-6">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Antrian Pasien</h1>
            <p class="text-gray-500 mt-1 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>

        {{-- Status Antrian --}}
        <div class="bg-white px-5 py-3 rounded-lg border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#f56e9d] opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#f56e9d]"></span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Sisa Antrian</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">
                    {{ $antrianPasien->where('status', 'menunggu')->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Tanggal</h2>
        <div>
            <label for="tanggal_filter" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Kunjungan
            </label>
            <input
                type="date"
                name="tanggal_filter"
                id="tanggal_filter"
                value="{{ request('tanggal', now()->format('Y-m-d')) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
            >
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Daftar Antrian Pasien</h2>
        </div>

        @if($antrianPasien->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada antrian pasien</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($antrianPasien as $antrian)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $antrian->nomor_antrian }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $antrian->pasien->nama_lengkap }}</div>
                            <div class="text-gray-500">{{ $antrian->pasien->no_rm }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $antrian->keluhan_utama }}">
                                {{ $antrian->keluhan_utama ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($antrian->status === 'menunggu')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($antrian->status === 'dipanggil')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Dipanggil
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            {{-- Periksa Button --}}
                            @if($antrian->status === 'dipanggil')
                            <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-[#f56e9d] text-white text-xs font-medium rounded hover:bg-[#d14a7a] transition-colors">
                                Periksa
                            </a>
                            @else
                            <button disabled
                               class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-400 text-xs font-medium rounded cursor-not-allowed">
                                Periksa
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('tanggal_filter');
    let debounceTimer = null;

    function debounce(func, delay) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };
    }

    function filterByDate() {
        const tanggal = tanggalInput.value;
        const url = new URL(window.location.href);
        url.searchParams.set('tanggal', tanggal);
        window.location.href = url.toString();
    }

    const debouncedFilter = debounce(() => filterByDate(), 500);

    tanggalInput.addEventListener('change', debouncedFilter);
});
</script>

@endsection