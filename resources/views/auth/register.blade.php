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
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Akun Baru</h2>
                        <p class="text-gray-600">Silakan lengkapi informasi Anda</p>
                    </div>

                    <div id="roleSelection" class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 text-center">Pilih Role Anda</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="pasien">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 group-hover:text-[#f56e9d] transition-colors">Pasien</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="dokter">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 group-hover:text-[#f56e9d] transition-colors">Dokter</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="pendaftaran">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-[#f56e9d] transition-colors">Staf Pendaftaran</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="apoteker">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 group-hover:text-[#f56e9d] transition-colors">Apoteker</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="lab">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-[#f56e9d] transition-colors">Staf Laboratorium</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="kasir_klinik">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-[#f56e9d] transition-colors">Kasir Klinik</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="kasir_apotek">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-[#f56e9d] transition-colors">Kasir Apotek</h3>
                            </div>

                            <div class="role-card group cursor-pointer border-2 border-gray-300 rounded-lg p-3 text-center hover:border-[#f56e9d] hover:bg-pink-50 transition-all" data-role="kasir_lab">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 group-hover:text-[#f56e9d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="font-semibold text-gray-800 text-sm group-hover:text-[#f56e9d] transition-colors">Kasir Lab</h3>
                            </div>
                        </div>

                        @error('role')
                        <p class="text-red-500 text-xs italic mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="registerForm" style="display: none;">
                        @csrf

                        <input type="hidden" name="role" id="roleInput" value="{{ old('role') }}">

                        <div class="mb-4">
                            <button type="button" id="backToRoleSelection" class="text-sm flex items-center font-semibold" style="color: #f56e9d;">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Kembali ke pilihan role
                            </button>
                        </div>

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

                        <div id="roleSpecificFields"></div>

                        <!-- reCAPTCHA Widget -->
                        <div class="mb-6 flex justify-center">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                        @error('g-recaptcha-response')
                        <p class="text-red-500 text-xs italic mt-2 text-center">{{ $message }}</p>
                        @enderror

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
        window.oldFormValues = @json(old());

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

        // Register form initialization
        (function() {
            const roleSelection = document.getElementById('roleSelection');
            const registerForm = document.getElementById('registerForm');
            const roleInput = document.getElementById('roleInput');
            const container = document.getElementById('roleSpecificFields');
            const backButton = document.getElementById('backToRoleSelection');
            const roleCards = document.querySelectorAll('.role-card');

            if (!roleSelection || !registerForm || !roleInput || !container) {
                return;
            }

            const oldValues = window.oldFormValues || {};

            function showForm(role) {
                roleInput.value = role;
                roleSelection.style.display = 'none';
                registerForm.style.display = 'block';

                updateFormFields(role);
            }

            function showRoleSelection() {
                roleSelection.style.display = 'block';
                registerForm.style.display = 'none';
                roleInput.value = '';
                container.innerHTML = '';

                roleCards.forEach(card => {
                    card.classList.remove('border-[#f56e9d]', 'bg-pink-50');
                    card.classList.add('border-gray-300');
                });
            }

            function updateFormFields(role) {
                if (!role) {
                    container.innerHTML = '';
                    return;
                }

                let fields = '';

                if (role === 'pasien') {
                    fields = getPasienFields(oldValues);
                } else if (role === 'dokter') {
                    fields = getDokterFields(oldValues);
                } else if (['pendaftaran', 'apoteker', 'lab', 'kasir_klinik', 'kasir_apotek', 'kasir_lab'].includes(role)) {
                    fields = getStafFields(role, oldValues);
                }

                container.innerHTML = fields;
            }

            function getPasienFields(oldValues) {
                return `
                <div class="border-t pt-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Data Pasien</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="${oldValues.nama_lengkap || ''}" placeholder="Nama sesuai KTP" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nik" value="${oldValues.nik || ''}" placeholder="16 digit" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="16" pattern="[0-9]{16}">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" value="${oldValues.tempat_lahir || ''}" placeholder="Contoh: Bandung" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" value="${oldValues.tanggal_lahir || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                            <option value="">Pilih</option>
                            <option value="Laki-Laki" ${oldValues.jenis_kelamin === 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                            <option value="Perempuan" ${oldValues.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" placeholder="Alamat lengkap (Jalan, RT/RW, Kelurahan, dll)" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" rows="3" required>${oldValues.alamat || ''}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" value="${oldValues.provinsi || ''}" placeholder="Contoh: Jawa Barat" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                            <input type="text" name="kota_kabupaten" value="${oldValues.kota_kabupaten || ''}" placeholder="Contoh: Bandung" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" name="kecamatan" value="${oldValues.kecamatan || ''}" placeholder="Contoh: Coblong" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" value="${oldValues.kewarganegaraan || ''}" placeholder="Indonesia" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                No. Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_telepon" value="${oldValues.no_telepon || ''}" placeholder="08xxxxxxxxxx" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="15">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Golongan Darah</label>
                        <select name="golongan_darah" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                            <option value="">Pilih</option>
                            <option value="A" ${oldValues.golongan_darah === 'A' ? 'selected' : ''}>A</option>
                            <option value="B" ${oldValues.golongan_darah === 'B' ? 'selected' : ''}>B</option>
                            <option value="AB" ${oldValues.golongan_darah === 'AB' ? 'selected' : ''}>AB</option>
                            <option value="O" ${oldValues.golongan_darah === 'O' ? 'selected' : ''}>O</option>
                            <option value="A+" ${oldValues.golongan_darah === 'A+' ? 'selected' : ''}>A+</option>
                            <option value="A-" ${oldValues.golongan_darah === 'A-' ? 'selected' : ''}>A-</option>
                            <option value="B+" ${oldValues.golongan_darah === 'B+' ? 'selected' : ''}>B+</option>
                            <option value="B-" ${oldValues.golongan_darah === 'B-' ? 'selected' : ''}>B-</option>
                            <option value="AB+" ${oldValues.golongan_darah === 'AB+' ? 'selected' : ''}>AB+</option>
                            <option value="AB-" ${oldValues.golongan_darah === 'AB-' ? 'selected' : ''}>AB-</option>
                            <option value="O+" ${oldValues.golongan_darah === 'O+' ? 'selected' : ''}>O+</option>
                            <option value="O-" ${oldValues.golongan_darah === 'O-' ? 'selected' : ''}>O-</option>
                        </select>
                    </div>
                </div>
            `;
            }

            function getDokterFields(oldValues) {
                return `
                <div class="border-t pt-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Data Dokter</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="${oldValues.nama_lengkap || ''}" placeholder="Nama sesuai KTP" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nik" value="${oldValues.nik || ''}" placeholder="16 digit" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="16" pattern="[0-9]{16}">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" value="${oldValues.tempat_lahir || ''}" placeholder="Contoh: Bandung" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" value="${oldValues.tanggal_lahir || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                            <option value="">Pilih</option>
                            <option value="Laki-Laki" ${oldValues.jenis_kelamin === 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                            <option value="Perempuan" ${oldValues.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" placeholder="Alamat lengkap (Jalan, RT/RW, Kelurahan, dll)" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" rows="3" required>${oldValues.alamat || ''}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" value="${oldValues.provinsi || ''}" placeholder="Contoh: Jawa Barat" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                            <input type="text" name="kota_kabupaten" value="${oldValues.kota_kabupaten || ''}" placeholder="Contoh: Bandung" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" name="kecamatan" value="${oldValues.kecamatan || ''}" placeholder="Contoh: Coblong" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" value="${oldValues.kewarganegaraan || ''}" placeholder="Indonesia" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                No. Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_telepon" value="${oldValues.no_telepon || ''}" placeholder="08xxxxxxxxxx" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="15">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Spesialisasi</label>
                            <select name="spesialisasi" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                                <option value="">Pilih Spesialisasi</option>
                                <option value="Umum" ${oldValues.spesialisasi === 'Umum' ? 'selected' : ''}>Umum</option>
                                <option value="Penyakit Dalam" ${oldValues.spesialisasi === 'Penyakit Dalam' ? 'selected' : ''}>Penyakit Dalam</option>
                                <option value="Anak" ${oldValues.spesialisasi === 'Anak' ? 'selected' : ''}>Anak</option>
                                <option value="Kandungan" ${oldValues.spesialisasi === 'Kandungan' ? 'selected' : ''}>Kandungan</option>
                                <option value="Jantung" ${oldValues.spesialisasi === 'Jantung' ? 'selected' : ''}>Jantung</option>
                                <option value="Bedah" ${oldValues.spesialisasi === 'Bedah' ? 'selected' : ''}>Bedah</option>
                                <option value="Mata" ${oldValues.spesialisasi === 'Mata' ? 'selected' : ''}>Mata</option>
                                <option value="THT" ${oldValues.spesialisasi === 'THT' ? 'selected' : ''}>THT</option>
                                <option value="Kulit dan Kelamin" ${oldValues.spesialisasi === 'Kulit dan Kelamin' ? 'selected' : ''}>Kulit dan Kelamin</option>
                                <option value="Saraf" ${oldValues.spesialisasi === 'Saraf' ? 'selected' : ''}>Saraf</option>
                                <option value="Jiwa" ${oldValues.spesialisasi === 'Jiwa' ? 'selected' : ''}>Jiwa</option>
                                <option value="Paru" ${oldValues.spesialisasi === 'Paru' ? 'selected' : ''}>Paru</option>
                                <option value="Orthopedi" ${oldValues.spesialisasi === 'Orthopedi' ? 'selected' : ''}>Orthopedi</option>
                                <option value="Urologi" ${oldValues.spesialisasi === 'Urologi' ? 'selected' : ''}>Urologi</option>
                                <option value="Radiologi" ${oldValues.spesialisasi === 'Radiologi' ? 'selected' : ''}>Radiologi</option>
                                <option value="Anestesi" ${oldValues.spesialisasi === 'Anestesi' ? 'selected' : ''}>Anestesi</option>
                                <option value="Patologi Klinik" ${oldValues.spesialisasi === 'Patologi Klinik' ? 'selected' : ''}>Patologi Klinik</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">No. STR</label>
                            <input type="text" name="no_str" value="${oldValues.no_str || ''}" placeholder="Nomor Surat Tanda Registrasi" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                        </div>
                    </div>
                </div>
            `;
            }

            function getStafFields(role, oldValues) {
                const roleLabels = {
                    'pendaftaran': 'Staf Pendaftaran',
                    'apoteker': 'Apoteker',
                    'lab': 'Staf Laboratorium',
                    'kasir_klinik': 'Kasir Klinik',
                    'kasir_apotek': 'Kasir Apotek',
                    'kasir_lab': 'Kasir Lab'
                };

                return `
                <div class="border-t pt-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Data ${roleLabels[role]}</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="${oldValues.nama_lengkap || ''}" placeholder="Nama sesuai KTP" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nik" value="${oldValues.nik || ''}" placeholder="16 digit" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="16" pattern="[0-9]{16}">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" value="${oldValues.tempat_lahir || ''}" placeholder="Contoh: Bandung" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" value="${oldValues.tanggal_lahir || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                            <option value="">Pilih</option>
                            <option value="Laki-Laki" ${oldValues.jenis_kelamin === 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                            <option value="Perempuan" ${oldValues.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" placeholder="Alamat lengkap (Jalan, RT/RW, Kelurahan, dll)" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" rows="3" required>${oldValues.alamat || ''}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" value="${oldValues.provinsi || ''}" placeholder="Contoh: Jawa Barat" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                            <input type="text" name="kota_kabupaten" value="${oldValues.kota_kabupaten || ''}" placeholder="Contoh: Bandung" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" name="kecamatan" value="${oldValues.kecamatan || ''}" placeholder="Contoh: Coblong" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Kewarganegaraan</label>
                            <input type="text" name="kewarganegaraan" value="${oldValues.kewarganegaraan || ''}" placeholder="Indonesia" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                No. Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_telepon" value="${oldValues.no_telepon || ''}" placeholder="08xxxxxxxxxx" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="15">
                        </div>
                    </div>
                </div>
            `;
            }

            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    const role = this.dataset.role;

                    roleCards.forEach(c => {
                        c.classList.remove('border-[#f56e9d]', 'bg-pink-50');
                        c.classList.add('border-gray-300');
                    });

                    this.classList.remove('border-gray-300');
                    this.classList.add('border-[#f56e9d]', 'bg-pink-50');

                    showForm(role);
                });
            });

            backButton.addEventListener('click', showRoleSelection);

            if (oldValues.role) {
                const selectedCard = document.querySelector(`.role-card[data-role="${oldValues.role}"]`);
                if (selectedCard) {
                    selectedCard.classList.remove('border-gray-300');
                    selectedCard.classList.add('border-[#f56e9d]', 'bg-pink-50');
                }
                showForm(oldValues.role);
            }
        })();
    </script>
    @endsection