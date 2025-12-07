@extends('layouts.dashboard')

@section('title', 'Edit Profil | Ganesha Hospital')
@section('dashboard-title', 'Edit Profil Pasien')

@section('content')
@if($errors->has('error'))
<x-toast type="error" :message="$errors->first('error')" />
@endif

@if($errors->has('wearable_device_id'))
<x-toast type="error" :message="$errors->first('wearable_device_id')" />
@endif

<div class="w-full min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil</h2>

        <form method="POST" action="{{ route('pasien.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" id="nik" value="{{ $pasien->nik }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">NIK tidak dapat diubah</p>
                </div>

                <div>
                    <label for="no_rekam_medis" class="block text-sm font-medium text-gray-700 mb-2">No Rekam Medis</label>
                    <input type="text" id="no_rekam_medis" value="{{ $pasien->no_rekam_medis }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Nomor rekam medis tidak dapat diubah</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" value="{{ $pasien->user->email }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah</p>
                </div>

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pasien->nama_lengkap) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $pasien->tempat_lahir) }}" required maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('tempat_lahir') border-red-500 @enderror" placeholder="Contoh: Bandung">
                    @error('tempat_lahir')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('tanggal_lahir') border-red-500 @enderror">
                    @error('tanggal_lahir')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No Telepon <span class="text-red-500">*</span></label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $pasien->no_telepon) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('no_telepon') border-red-500 @enderror">
                    @error('no_telepon')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="golongan_darah" class="block text-sm font-medium text-gray-700 mb-2">Golongan Darah</label>
                    <select id="golongan_darah" name="golongan_darah"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('golongan_darah') border-red-500 @enderror">
                        <option value="">Pilih Golongan Darah</option>
                        @foreach(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gol)
                        <option value="{{ $gol }}" {{ old('golongan_darah', $pasien->golongan_darah) == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                        @endforeach
                    </select>
                    @error('golongan_darah')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', $pasien->provinsi) }}" required maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('provinsi') border-red-500 @enderror" placeholder="Contoh: Jawa Barat">
                    @error('provinsi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kota_kabupaten" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                    <input type="text" id="kota_kabupaten" name="kota_kabupaten" value="{{ old('kota_kabupaten', $pasien->kota_kabupaten) }}" required maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('kota_kabupaten') border-red-500 @enderror" placeholder="Contoh: Bandung">
                    @error('kota_kabupaten')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                    <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $pasien->kecamatan) }}" required maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('kecamatan') border-red-500 @enderror" placeholder="Contoh: Coblong">
                    @error('kecamatan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                <textarea id="alamat" name="alamat" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('alamat') border-red-500 @enderror">{{ old('alamat', $pasien->alamat) }}</textarea>
                @error('alamat')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="wearable_device_id" class="block text-sm font-medium text-gray-700 mb-2">Wearable Device ID</label>
                <input type="text" id="wearable_device_id" name="wearable_device_id" value="{{ old('wearable_device_id', $pasien->wearable_device_id) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('wearable_device_id') border-red-500 @enderror"
                    placeholder="Masukkan ID perangkat wearable Anda">
            </div>

            <!-- Password Change Section -->
            <div class="border-t border-gray-300 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h3>
                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('current_password') border-red-500 @enderror"
                            placeholder="Masukkan password saat ini">
                        @error('current_password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" id="new_password" name="new_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('new_password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('new_password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]"
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('pasien.profile') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors hover:cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection