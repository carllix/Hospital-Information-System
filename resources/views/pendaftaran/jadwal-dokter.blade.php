@extends('layouts.dashboard')

@section('title', 'Jadwal Dokter')
@section('dashboard-title', 'Jadwal Dokter')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Jadwal Praktik Dokter</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi jadwal praktik dokter untuk pasien</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Dokter</p>
                <p class="text-3xl font-bold text-[#f56e9d]">{{ $dokters->count() }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Dokter
                    </label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama dokter..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                    />
                </div>

                <div>
                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Spesialisasi
                    </label>
                    <select
                        name="spesialisasi"
                        id="spesialisasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                    >
                        <option value="">Semua Spesialisasi</option>
                        @foreach($spesialisasiList as $spesialisasi)
                        <option value="{{ $spesialisasi }}" {{ request('spesialisasi') === $spesialisasi ? 'selected' : '' }}>
                            {{ $spesialisasi }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="doctorsContainer">
        <div id="doctorsContent" class="space-y-4">
        @if($dokters->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada dokter ditemukan</p>
        </div>
        @else
        @foreach($dokters as $dokter)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- Doctor Info -->
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 w-16 h-16 bg-[#fff5f8] rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#f56e9d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>

                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $dokter->nama_lengkap }}</h3>
                            @if($dokter->spesialisasi)
                            <p class="text-sm text-[#f56e9d] font-medium mt-1">{{ $dokter->spesialisasi }}</p>
                            @endif
                            @if($dokter->no_str)
                            <p class="text-xs text-gray-500 mt-1">STR: {{ $dokter->no_str }}</p>
                            @endif
                            @if($dokter->no_telepon)
                            <p class="text-sm text-gray-600 mt-2">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $dokter->no_telepon }}
                                </span>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($dokter->jadwal_praktik && count($dokter->jadwal_praktik) > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Jadwal Praktik:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($dokter->jadwal_praktik as $jadwal)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#f56e9d] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $jadwal['hari'] }}</p>
                                    <p class="text-xs text-gray-600">{{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 italic">Jadwal praktik belum tersedia</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const spesialisasiSelect = document.getElementById('spesialisasi');
    let debounceTimer = null;

    function debounce(func, delay) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };
    }

    function fetchData() {
        const params = new URLSearchParams({
            search: searchInput.value,
            spesialisasi: spesialisasiSelect.value
        });

        const url = '{{ route("pendaftaran.jadwal-dokter") }}?' + params.toString();

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');

                const newContent = doc.getElementById('doctorsContent');
                const newTotal = doc.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]');

                if (newContent) {
                    document.getElementById('doctorsContent').innerHTML = newContent.innerHTML;
                }

                if (newTotal) {
                    document.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]').textContent = newTotal.textContent;
                }

                window.history.pushState({}, '', url);
            }
        };

        xhr.send();
    }

    const debouncedFetch = debounce(() => fetchData(), 500);

    searchInput.addEventListener('input', debouncedFetch);
    spesialisasiSelect.addEventListener('change', debouncedFetch);
});
</script>
@endsection
