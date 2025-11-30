<?php

namespace Database\Factories;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasienFactory extends Factory
{
    protected $model = Pasien::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'pasien']),
            'no_rekam_medis' => 'RM' . fake()->unique()->numerify('######'),
            'nama_lengkap' => fake()->name(),
            'nik' => fake()->unique()->numerify('################'),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['Laki-Laki', 'Perempuan']),
            'alamat' => fake()->address(),
            'provinsi' => fake()->state(),
            'kota_kabupaten' => fake()->city(),
            'kecamatan' => fake()->streetName(),
            'kewarganegaraan' => 'Indonesia',
            'no_telepon' => fake()->phoneNumber(),
            'golongan_darah' => fake()->randomElement(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'wearable_device_id' => null,
        ];
    }
}
