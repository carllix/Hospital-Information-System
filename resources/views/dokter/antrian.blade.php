@extends('layouts.dashboard')

@section('title', 'Antrian Pasien')

@section('content')
<div class="space-y-6">
    {{-- Header with Filter --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Antrian Pasien</h2>
                <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse(request('tanggal', now()->format('Y-m-d')))->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Antrian</p>
                <p class="text-3xl font-bold text-[#f56e9d]">{{ $antrianPasien->count() }}</p>
            </div>
        </div>

        {{-- Filter --}}
        <div>
            <label for="tanggal_filter" class="block text-sm font-medium text-gray-700 mb-2">
                Pilih Tanggal
            </label>
            <input
                type="date"
                name="tanggal_filter"
                id="tanggal_filter"
                value="{{ request('tanggal', now()->format('Y-m-d')) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($antrianPasien->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada antrian untuk tanggal yang dipilih</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($antrianPasien as $antrian)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#fff5f8] text-[#d14a7a]">
                                {{ $antrian->nomor_antrian }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $antrian->pasien->nama_lengkap }}</div>
                            <div class="text-sm text-gray-600">{{ $antrian->pasien->no_rm }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs">{{ $antrian->keluhan_utama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $antrian->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($antrian->status === 'menunggu')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($antrian->status === 'dipanggil')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Dipanggil
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($antrian->status === 'dipanggil')
                            <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}"
                                class="px-3 py-2 text-xs bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm">
                                Periksa
                            </a>
                            @else
                            <button type="button" disabled
                                class="px-3 py-2 text-xs bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
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