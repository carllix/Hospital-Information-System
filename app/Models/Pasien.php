<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\PasienFactory::new();
    }

    protected $table = 'pasien';
    protected $primaryKey = 'pasien_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'no_rekam_medis',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kewarganegaraan',
        'no_telepon',
        'golongan_darah',
        'wearable_device_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'pasien_id', 'pasien_id');
    }

    public function pemeriksaan(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class, 'pasien_id', 'pasien_id');
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'pasien_id', 'pasien_id');
    }

    public function wearableData(): HasMany
    {
        return $this->hasMany(WearableData::class, 'pasien_id', 'pasien_id');
    }
}
