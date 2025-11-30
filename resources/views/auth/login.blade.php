@extends('layouts.app')

@section('title', 'Login | Hospital Information System')

@section('content')
<div class="flex min-h-screen">
    <div class="hidden lg:flex lg:w-1/2 bg-green-700 items-center justify-center p-12">
        <div class="text-center">
            <div class="mb-8">
                <svg class="w-48 h-48 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">Hospital Information System</h1>
            <p class="text-green-100 text-lg">Sistem Informasi Manajemen Rumah Sakit Terpadu</p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-lg rounded-lg px-8 pt-8 pb-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                    <p class="text-gray-600">Silakan login untuk melanjutkan</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required
                            autofocus
                            placeholder="nama@email.com"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pr-12 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                required
                                placeholder="Masukkan password"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            >
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

                    <div class="flex items-center justify-between mb-6">
                        <button
                            type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                        >
                            Login
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-semibold">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
