<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $this->validateRegistration($request);

        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'pasien',
            ]);

            $this->createPasien($user, $validated);

            DB::commit();

            Auth::login($user);

            return redirect($user->getDashboardRoute());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required', 'captcha'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'size:16', 'unique:pasien,nik'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
            'alamat' => ['required', 'string'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kota_kabupaten' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'no_telepon' => ['required', 'string', 'max:15'],
            'golongan_darah' => ['nullable', 'in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-'],
        ]);
    }

    private function createPasien(User $user, array $validated): void
    {
        Pasien::create([
            'user_id' => $user->user_id,
            'no_rekam_medis' => $this->generateNoRekamMedis(),
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'provinsi' => $validated['provinsi'],
            'kota_kabupaten' => $validated['kota_kabupaten'],
            'kecamatan' => $validated['kecamatan'],
            'no_telepon' => $validated['no_telepon'],
            'golongan_darah' => $validated['golongan_darah'] ?? null,
        ]);
    }

    private function generateNoRekamMedis(): string
    {
        $today = now()->format('Ymd');

        // Get last patient registered today
        $lastPasien = Pasien::where('no_rekam_medis', 'LIKE', "RM-{$today}-%")
            ->orderBy('pasien_id', 'desc')
            ->first();

        if (!$lastPasien) {
            // First patient today
            $nomorUrut = 1;
        } else {
            // Extract last 4 digits and increment
            $lastNumber = (int) substr($lastPasien->no_rekam_medis, -4);
            $nomorUrut = $lastNumber + 1;
        }

        return 'RM-' . $today . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
    }
}
