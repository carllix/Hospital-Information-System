<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email',
        'password',
        'role',
        'is_deleted',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pasien(): HasOne
    {
        return $this->hasOne(Pasien::class, 'user_id', 'user_id');
    }

    public function dokter(): HasOne
    {
        return $this->hasOne(Dokter::class, 'user_id', 'user_id');
    }

    public function staf(): HasOne
    {
        return $this->hasOne(Staf::class, 'user_id', 'user_id');
    }

    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'pasien' => '/pasien/dashboard',
            'dokter' => '/dokter/dashboard',
            'staf' => match ($this->staf?->bagian) {
                'pendaftaran' => '/pendaftaran/dashboard',
                'farmasi' => '/farmasi/dashboard',
                'laboratorium' => '/lab/dashboard',
                'kasir' => '/kasir/dashboard',
                default => '/dashboard',
            },
            'admin' => '/admin/dashboard',
            default => '/dashboard',
        };
    }

    public function generateNoRekamMedis(): string
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
