@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Dokter')
@section('dashboard-title', 'Tambah Jadwal Dokter')

@section('content')
<x-toast type="error" :message="session('error')" />

<div class="mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Tambah Jadwal Praktik</h2>
                <p class="text-sm text-gray-600 mt-1">Lengkapi form di bawah untuk menambahkan jadwal</p>
            </div>
            <a href="{{ route('admin.jadwal-dokter.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.jadwal-dokter.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Dokter <span class="text-red-500">*</span>
                </label>
                <select name="dokter_id" id="dokter_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('dokter_id') border-red-500 @enderror">
                    <option value="">Pilih Dokter</option>
                    @foreach($dokters as $dokter)
                    <option value="{{ $dokter->dokter_id }}" {{ old('dokter_id') == $dokter->dokter_id ? 'selected' : '' }}>
                        {{ $dokter->nama_lengkap }} - {{ $dokter->spesialisasi }}
                    </option>
                    @endforeach
                </select>
                @error('dokter_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="hari_praktik" class="block text-sm font-medium text-gray-700 mb-2">
                    Hari Praktik <span class="text-red-500">*</span>
                </label>
                <select name="hari_praktik" id="hari_praktik" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('hari_praktik') border-red-500 @enderror">
                    <option value="">Pilih Hari</option>
                    <option value="Senin" {{ old('hari_praktik') === 'Senin' ? 'selected' : '' }}>Senin</option>
                    <option value="Selasa" {{ old('hari_praktik') === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                    <option value="Rabu" {{ old('hari_praktik') === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                    <option value="Kamis" {{ old('hari_praktik') === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                    <option value="Jumat" {{ old('hari_praktik') === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                    <option value="Sabtu" {{ old('hari_praktik') === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    <option value="Minggu" {{ old('hari_praktik') === 'Minggu' ? 'selected' : '' }}>Minggu</option>
                </select>
                @error('hari_praktik')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('waktu_mulai') border-red-500 @enderror">
                    @error('waktu_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('waktu_selesai') border-red-500 @enderror">
                    @error('waktu_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="max_pasien" class="block text-sm font-medium text-gray-700 mb-2">
                    Maksimal Pasien <span class="text-red-500">*</span>
                </label>
                <input type="number" name="max_pasien" id="max_pasien" value="{{ old('max_pasien', 20) }}" min="1" max="100" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('max_pasien') border-red-500 @enderror">
                @error('max_pasien')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Jumlah maksimal pasien yang dapat dilayani per hari</p>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('admin.jadwal-dokter.index') }}" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection