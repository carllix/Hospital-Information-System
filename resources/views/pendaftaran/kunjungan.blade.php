@extends('layouts.dashboard')

@section('title', 'Pendaftaran Kunjungan')
@section('dashboard-title', 'Pendaftaran Kunjungan')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Cari Pasien</h2>
        <p class="text-sm text-gray-600 mb-4">Cari pasien berdasarkan NIK</p>

        <div class="flex gap-3">
            <input
                type="text"
                id="searchInput"
                placeholder="Masukkan NIK (16 digit)"
                maxlength="16"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
            >
            <button
                id="searchBtn"
                class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium hover:cursor-pointer"
            >
                Cari
            </button>
        </div>

        <div id="searchResults" class="mt-4 hidden">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Hasil Pencarian:</h3>
            <div id="resultsContainer" class="space-y-2"></div>
        </div>

        <div id="noResults" class="mt-4 hidden">
            <div class="bg-[#fff5f8] p-4 rounded-lg">
                <p class="text-sm text-[#d14a7a]">Pasien tidak ditemukan. Silakan daftarkan sebagai pasien baru.</p>
                <a href="{{ route('pendaftaran.pasien-baru') }}" class="text-sm text-[#f56e9d] hover:text-[#d14a7a] font-medium inline-block mt-2">
                    → Daftar Pasien Baru
                </a>
            </div>
        </div>
    </div>

    <div id="formPendaftaran" class="bg-white rounded-lg shadow-md p-6 hidden">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pendaftaran Kunjungan</h2>

        <div id="selectedPatientInfo" class="p-4 rounded-lg mb-6 border border-gray-200">
            <h3 class="text-sm font-semibold text-[#d14a7a] mb-2">Data Pasien:</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-gray-600">No. Rekam Medis:</span>
                    <span id="infoRM" class="font-medium text-gray-800 ml-2"></span>
                </div>
                <div>
                    <span class="text-gray-600">NIK:</span>
                    <span id="infoNIK" class="font-medium text-gray-800 ml-2"></span>
                </div>
                <div>
                    <span class="text-gray-600">Nama:</span>
                    <span id="infoNama" class="font-medium text-gray-800 ml-2"></span>
                </div>
                <div>
                    <span class="text-gray-600">No. Telepon:</span>
                    <span id="infoTelepon" class="font-medium text-gray-800 ml-2"></span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pendaftaran.store') }}">
            @csrf
            <input type="hidden" name="pasien_id" id="pasienId">

            <div class="mb-4">
                <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Dokter <span class="text-red-500">*</span>
                </label>
                <select
                    name="dokter_id"
                    id="dokter_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('dokter_id') border-red-500 @enderror"
                >
                    <option value="">Pilih Dokter</option>
                    @foreach($dokters as $dokter)
                    <option value="{{ $dokter->dokter_id }}">
                        {{ $dokter->nama_lengkap }}
                        @if($dokter->spesialisasi)
                            - {{ $dokter->spesialisasi }}
                        @endif
                    </option>
                    @endforeach
                </select>
                @error('dokter_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="keluhan_utama" class="block text-sm font-medium text-gray-700 mb-2">
                    Keluhan Utama <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="keluhan_utama"
                    id="keluhan_utama"
                    rows="4"
                    required
                    maxlength="500"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                    placeholder="Jelaskan keluhan pasien..."
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                @error('keluhan_utama')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button
                    type="button"
                    id="cancelBtn"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium hover:cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium hover:cursor-pointer"
                >
                    Daftar Kunjungan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchResults = document.getElementById('searchResults');
    const noResults = document.getElementById('noResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const formPendaftaran = document.getElementById('formPendaftaran');
    const cancelBtn = document.getElementById('cancelBtn');

    searchBtn.addEventListener('click', searchPasien);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchPasien();
        }
    });

    cancelBtn.addEventListener('click', function() {
        formPendaftaran.classList.add('hidden');
        document.getElementById('keluhan_utama').value = '';
    });

    function showToast(type, message) {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        };

        const existingToast = document.getElementById('dynamicToast');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.id = 'dynamicToast';
        toast.className = `fixed top-4 right-4 z-50 flex items-center gap-3 ${colors[type] || colors.info} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 translate-y-[-20px]`;
        toast.innerHTML = `
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${icons[type] || icons.info}
            </svg>
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-y-[-20px]');
        }, 100);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-[-20px]');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function searchPasien() {
        const search = searchInput.value.trim();

        if (search.length < 16) {
            showToast('warning', 'NIK harus 16 digit');
            return;
        }

        searchBtn.disabled = true;
        searchBtn.textContent = 'Mencari...';

        fetch('{{ route("pendaftaran.search-pasien") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ search: search })
        })
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = '';
            formPendaftaran.classList.add('hidden');

            if (data.length === 0) {
                searchResults.classList.add('hidden');
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
                searchResults.classList.remove('hidden');

                data.forEach(pasien => {
                    const resultCard = document.createElement('div');
                    resultCard.className = 'border border-[#fdd5e0] rounded-lg p-4 hover:bg-[#fff5f8] cursor-pointer transition-colors';
                    resultCard.innerHTML = `
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">No. RM:</span>
                                <span class="font-medium text-gray-800 ml-2">${pasien.no_rekam_medis}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">NIK:</span>
                                <span class="font-medium text-gray-800 ml-2">${pasien.nik}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium text-gray-800 ml-2">${pasien.nama_lengkap}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">No. Telepon:</span>
                                <span class="font-medium text-gray-800 ml-2">${pasien.no_telepon}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-600">Alamat:</span>
                                <span class="font-medium text-gray-800 ml-2">${pasien.alamat}</span>
                            </div>
                        </div>
                    `;
                    resultCard.addEventListener('click', () => selectPasien(pasien));
                    resultsContainer.appendChild(resultCard);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Terjadi kesalahan saat mencari pasien');
        })
        .finally(() => {
            searchBtn.disabled = false;
            searchBtn.textContent = 'Cari';
        });
    }

    function selectPasien(pasien) {
        document.getElementById('pasienId').value = pasien.pasien_id;
        document.getElementById('infoRM').textContent = pasien.no_rekam_medis;
        document.getElementById('infoNIK').textContent = pasien.nik;
        document.getElementById('infoNama').textContent = pasien.nama_lengkap;
        document.getElementById('infoTelepon').textContent = pasien.no_telepon;

        searchResults.classList.add('hidden');
        formPendaftaran.classList.remove('hidden');
    }
});
</script>
@endsection
