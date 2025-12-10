@extends('layouts.dashboard')

@section('title', 'Edit Dokter')
@section('dashboard-title', 'Edit Dokter')

@section('content')
<x-toast type="error" :message="session('error')" />

<div class="mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Data Dokter</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi dokter</p>
            </div>
            <a href="{{ route('admin.dokter.show', $dokter->dokter_id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.dokter.update', $dokter->dokter_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP RS</label>
                        <input type="text" value="{{ $dokter->nip_rs }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        <p class="mt-1 text-xs text-gray-500">NIP RS tidak dapat diubah</p>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $dokter->user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $dokter->nama_lengkap) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nama_lengkap') border-red-500 @enderror">
                        @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $dokter->nik) }}" maxlength="16" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nik') border-red-500 @enderror">
                        @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-Laki" {{ old('jenis_kelamin', $dokter->jenis_kelamin) === 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $dokter->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $dokter->tempat_lahir) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tempat_lahir') border-red-500 @enderror">
                        @error('tempat_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $dokter->tanggal_lahir->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $dokter->no_telepon) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('no_telepon') border-red-500 @enderror">
                        @error('no_telepon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Alamat</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('alamat') border-red-500 @enderror">{{ old('alamat', $dokter->alamat) }}</textarea>
                            @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $dokter->provinsi) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('provinsi') border-red-500 @enderror">
                            @error('provinsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kota_kabupaten" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota/Kabupaten <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kota_kabupaten" id="kota_kabupaten" value="{{ old('kota_kabupaten', $dokter->kota_kabupaten) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kota_kabupaten') border-red-500 @enderror">
                            @error('kota_kabupaten')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan', $dokter->kecamatan) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kecamatan') border-red-500 @enderror">
                            @error('kecamatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Profesi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Spesialisasi <span class="text-red-500">*</span>
                        </label>
                        <select name="spesialisasi" id="spesialisasi" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('spesialisasi') border-red-500 @enderror">
                            <option value="">Pilih Spesialisasi</option>
                            <option value="Umum" {{ old('spesialisasi', $dokter->spesialisasi) === 'Umum' ? 'selected' : '' }}>Umum</option>
                            <option value="Penyakit Dalam" {{ old('spesialisasi', $dokter->spesialisasi) === 'Penyakit Dalam' ? 'selected' : '' }}>Penyakit Dalam</option>
                            <option value="Anak" {{ old('spesialisasi', $dokter->spesialisasi) === 'Anak' ? 'selected' : '' }}>Anak</option>
                            <option value="Kandungan" {{ old('spesialisasi', $dokter->spesialisasi) === 'Kandungan' ? 'selected' : '' }}>Kandungan</option>
                            <option value="Jantung" {{ old('spesialisasi', $dokter->spesialisasi) === 'Jantung' ? 'selected' : '' }}>Jantung</option>
                            <option value="Bedah" {{ old('spesialisasi', $dokter->spesialisasi) === 'Bedah' ? 'selected' : '' }}>Bedah</option>
                            <option value="Mata" {{ old('spesialisasi', $dokter->spesialisasi) === 'Mata' ? 'selected' : '' }}>Mata</option>
                            <option value="THT" {{ old('spesialisasi', $dokter->spesialisasi) === 'THT' ? 'selected' : '' }}>THT</option>
                            <option value="Kulit dan Kelamin" {{ old('spesialisasi', $dokter->spesialisasi) === 'Kulit dan Kelamin' ? 'selected' : '' }}>Kulit dan Kelamin</option>
                            <option value="Saraf" {{ old('spesialisasi', $dokter->spesialisasi) === 'Saraf' ? 'selected' : '' }}>Saraf</option>
                            <option value="Jiwa" {{ old('spesialisasi', $dokter->spesialisasi) === 'Jiwa' ? 'selected' : '' }}>Jiwa</option>
                            <option value="Paru" {{ old('spesialisasi', $dokter->spesialisasi) === 'Paru' ? 'selected' : '' }}>Paru</option>
                            <option value="Orthopedi" {{ old('spesialisasi', $dokter->spesialisasi) === 'Orthopedi' ? 'selected' : '' }}>Orthopedi</option>
                            <option value="Urologi" {{ old('spesialisasi', $dokter->spesialisasi) === 'Urologi' ? 'selected' : '' }}>Urologi</option>
                            <option value="Radiologi" {{ old('spesialisasi', $dokter->spesialisasi) === 'Radiologi' ? 'selected' : '' }}>Radiologi</option>
                            <option value="Anestesi" {{ old('spesialisasi', $dokter->spesialisasi) === 'Anestesi' ? 'selected' : '' }}>Anestesi</option>
                            <option value="Patologi Klinik" {{ old('spesialisasi', $dokter->spesialisasi) === 'Patologi Klinik' ? 'selected' : '' }}>Patologi Klinik</option>
                        </select>
                        @error('spesialisasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_str" class="block text-sm font-medium text-gray-700 mb-2">
                            No. STR <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_str" id="no_str" value="{{ old('no_str', $dokter->no_str) }}" maxlength="17" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('no_str') border-red-500 @enderror">
                        @error('no_str')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('admin.dokter.show', $dokter->dokter_id) }}" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection