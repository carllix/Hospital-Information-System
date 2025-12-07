@extends('layouts.app')

@section('title', 'Register | Ganesha Hospital')

@section('content')
<div class="flex h-screen overflow-hidden">
    <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 fixed left-0 top-0 h-screen" style="background: linear-gradient(to bottom right, #f56e9d, #d14a7a);">
        <div class="text-center">
            <div class="mb-8">
                <img src="{{ asset('images/GaTal-logo.png') }}" alt="Ganesha Hospital Logo" class="w-64 mx-auto">
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">Ganesha Hospital</h1>
            <p class="text-pink-100 text-lg">Bijaksana dalam Merawat, Tulus dalam Melayani</p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 lg:ml-auto overflow-y-auto bg-gray-50">
        <div class="flex items-center justify-center min-h-screen p-8">
            <div class="w-full max-w-2xl">
                <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4">
                    <div class="mb-6 text-center">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Akun Pasien</h2>
                        <p class="text-gray-600">Silakan lengkapi informasi Anda untuk mendaftar</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('email') border-red-500 @enderror"
                                required
                                placeholder="example@gmail.com">
                            @error('email')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pr-12 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('password') border-red-500 @enderror"
                                        required
                                        placeholder="Min. 8 karakter">
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                                        <svg id="togglePasswordIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pr-12 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                                        required
                                        placeholder="Ulangi password">
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmationIcon')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                                        <svg id="togglePasswordConfirmationIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-700 mb-4">Data Pasien</h3>

                            <div class="mb-4">
                                <label for="nama_lengkap" class="block text-gray-700 text-sm font-semibold mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nama_lengkap"
                                    id="nama_lengkap"
                                    value="{{ old('nama_lengkap') }}"
                                    placeholder="Nama sesuai KTP"
                                    class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nama_lengkap') border-red-500 @enderror"
                                    required
                                    maxlength="100">
                                @error('nama_lengkap')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nik" class="block text-gray-700 text-sm font-semibold mb-2">
                                    NIK <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nik"
                                    id="nik"
                                    value="{{ old('nik') }}"
                                    placeholder="16 digit"
                                    class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nik') border-red-500 @enderror"
                                    required
                                    maxlength="16"
                                    pattern="[0-9]{16}">
                                @error('nik')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="tempat_lahir" class="block text-gray-700 text-sm font-semibold mb-2">
                                        Tempat Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="tempat_lahir"
                                        id="tempat_lahir"
                                        value="{{ old('tempat_lahir') }}"
                                        placeholder="Contoh: Bandung"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tempat_lahir') border-red-500 @enderror"
                                        required
                                        maxlength="100">
                                    @error('tempat_lahir')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="tanggal_lahir" class="block text-gray-700 text-sm font-semibold mb-2">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        name="tanggal_lahir"
                                        id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir') }}"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror"
                                        required>
                                    @error('tanggal_lahir')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="jenis_kelamin" class="block text-gray-700 text-sm font-semibold mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="jenis_kelamin"
                                    id="jenis_kelamin"
                                    class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror"
                                    required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="block text-gray-700 text-sm font-semibold mb-2">
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    name="alamat"
                                    id="alamat"
                                    placeholder="Alamat lengkap (Jalan, RT/RW, Kelurahan, dll)"
                                    class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('alamat') border-red-500 @enderror"
                                    rows="3"
                                    required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="provinsi" class="block text-gray-700 text-sm font-semibold mb-2">Provinsi <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        name="provinsi"
                                        id="provinsi"
                                        value="{{ old('provinsi') }}"
                                        placeholder="Contoh: Jawa Barat"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('provinsi') border-red-500 @enderror"
                                        required
                                        maxlength="100">
                                    @error('provinsi')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="kota_kabupaten" class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        name="kota_kabupaten"
                                        id="kota_kabupaten"
                                        value="{{ old('kota_kabupaten') }}"
                                        placeholder="Contoh: Bandung"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kota_kabupaten') border-red-500 @enderror"
                                        required
                                        maxlength="100">
                                    @error('kota_kabupaten')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="kecamatan" class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        name="kecamatan"
                                        id="kecamatan"
                                        value="{{ old('kecamatan') }}"
                                        placeholder="Contoh: Coblong"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kecamatan') border-red-500 @enderror"
                                        required
                                        maxlength="100">
                                    @error('kecamatan')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="no_telepon" class="block text-gray-700 text-sm font-semibold mb-2">
                                        No. Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="no_telepon"
                                        id="no_telepon"
                                        value="{{ old('no_telepon') }}"
                                        placeholder="08xxxxxxxxxx"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('no_telepon') border-red-500 @enderror"
                                        required
                                        maxlength="15">
                                    @error('no_telepon')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="golongan_darah" class="block text-gray-700 text-sm font-semibold mb-2">Golongan Darah</label>
                                    <select
                                        name="golongan_darah"
                                        id="golongan_darah"
                                        class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                                        <option value="">Pilih</option>
                                        <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                                        <option value="A+" {{ old('golongan_darah') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('golongan_darah') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('golongan_darah') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('golongan_darah') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('golongan_darah') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('golongan_darah') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('golongan_darah') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('golongan_darah') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- reCAPTCHA Widget -->
                        <div class="mb-6">
                            <div class="flex justify-center">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                        </div>
                        @error('g-recaptcha-response')
                        <p class="text-red-500 text-xs italic mt-2 text-center">{{ $message }}</p>
                        @enderror

                        <style>
                            /* Center reCAPTCHA challenge popup and backdrop */
                            body>div[style*="position: fixed"] {
                                background-color: rgba(0, 0, 0, 0.5) !important;
                            }

                            body>div>div>iframe[src*="recaptcha/api2/bframe"] {
                                position: fixed !important;
                                top: 50% !important;
                                left: 50% !important;
                                transform: translate(-50%, -50%) !important;
                                z-index: 9999 !important;
                            }
                        </style>

                        <div class="flex items-center justify-between mt-6">
                            <button
                                type="submit"
                                class="w-full text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:ring-offset-2 transition-colors duration-200" style="background-color: #f56e9d;">
                                Daftar
                            </button>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="font-semibold" style="color: #f56e9d;">
                                    Login di sini
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
            `;
            } else {
                passwordInput.type = 'password';
                icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
            }
        }
    </script>
    @endsection