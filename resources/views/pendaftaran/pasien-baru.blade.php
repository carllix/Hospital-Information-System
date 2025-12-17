@extends('layouts.dashboard')

@section('title', 'Pendaftaran Pasien Baru | Pendaftaran Ganesha Hospital')
@section('dashboard-title', 'Pendaftaran Pasien Baru')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pendaftaran Pasien Baru</h2>
    <p class="text-sm text-gray-600 mb-6">Lengkapi data pasien baru yang belum terdaftar di sistem</p>

    <form method="POST" action="{{ route('pendaftaran.pasien-baru.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                    NIK <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nik"
                    id="nik"
                    required
                    maxlength="16"
                    value="{{ old('nik') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nik') border-red-500 @enderror"
                    placeholder="16 digit">
                @error('nik')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nama_lengkap"
                    id="nama_lengkap"
                    required
                    maxlength="100"
                    value="{{ old('nama_lengkap') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                    placeholder="Nama sesuai KTP">
                @error('nama_lengkap')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="example@gmail.com">
                @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Telepon -->
            <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                    No. Telepon <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="no_telepon"
                    id="no_telepon"
                    required
                    maxlength="15"
                    value="{{ old('no_telepon') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('no_telepon') border-red-500 @enderror"
                    placeholder="08xxxxxxxxxx">
                @error('no_telepon')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat Lahir -->
            <div>
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                    Tempat Lahir <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="tempat_lahir"
                    id="tempat_lahir"
                    required
                    maxlength="100"
                    value="{{ old('tempat_lahir') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tempat_lahir') border-red-500 @enderror"
                    placeholder="Contoh: Bandung">
                @error('tempat_lahir')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    name="tanggal_lahir"
                    id="tanggal_lahir"
                    required
                    value="{{ old('tanggal_lahir') }}"
                    max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select
                    name="jenis_kelamin"
                    id="jenis_kelamin"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Golongan Darah -->
            <div>
                <label for="golongan_darah" class="block text-sm font-medium text-gray-700 mb-2">
                    Golongan Darah
                </label>
                <select
                    name="golongan_darah"
                    id="golongan_darah"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                    <option value="">Pilih Golongan Darah</option>
                    @foreach(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gol)
                    <option value="{{ $gol }}" {{ old('golongan_darah') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Alamat Section -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Alamat Lengkap</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <!-- Provinsi -->
                <div>
                    <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="provinsi"
                        id="provinsi"
                        required
                        maxlength="100"
                        value="{{ old('provinsi') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('provinsi') border-red-500 @enderror"
                        placeholder="Contoh: Jawa Barat">
                    @error('provinsi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota/Kabupaten -->
                <div>
                    <label for="kota_kabupaten" class="block text-sm font-medium text-gray-700 mb-2">
                        Kota/Kabupaten <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="kota_kabupaten"
                        id="kota_kabupaten"
                        required
                        maxlength="100"
                        value="{{ old('kota_kabupaten') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kota_kabupaten') border-red-500 @enderror"
                        placeholder="Contoh: Bandung">
                    @error('kota_kabupaten')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Kecamatan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="kecamatan"
                        id="kecamatan"
                        required
                        maxlength="100"
                        value="{{ old('kecamatan') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kecamatan') border-red-500 @enderror"
                        placeholder="Contoh: Coblong">
                    @error('kecamatan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="alamat"
                    id="alamat"
                    rows="3"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('alamat') border-red-500 @enderror"
                    placeholder="Alamat lengkap (Jalan, RT/RW, Kelurahan, dll)">{{ old('alamat') }}</textarea>
                @error('alamat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-[#fff5f8] p-4 rounded-lg">
            <p class="text-sm text-[#d14a7a]">
                <strong>Informasi:</strong>
                Akun login untuk pasien akan dibuat otomatis dengan email yang diinputkan. Password default akan dkirmkan melalui email yang didaftarkan.
            </p>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6 justify-end">
            <button
                type="reset"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Reset Form
            </button>
            <button
                type="submit"
                id="submitBtn"
                class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="btnText">Daftar Pasien</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', function(e) {
            // Disable button
            submitBtn.disabled = true;

            // Show loading spinner and change text
            loadingSpinner.classList.remove('hidden');
            btnText.textContent = 'Mendaftar...';
        });
    });
</script>
@endsection