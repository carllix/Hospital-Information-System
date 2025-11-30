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
            'pendaftaran' => '/pendaftaran/dashboard',
            'dokter' => '/dokter/dashboard',
            'apoteker' => '/farmasi/dashboard',
            'lab' => '/lab/dashboard',
            'kasir_klinik' => '/kasir-klinik/dashboard',
            'kasir_apotek' => '/kasir-apotek/dashboard',
            'kasir_lab' => '/kasir-lab/dashboard',
        };
    }
}
