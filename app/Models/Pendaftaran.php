<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'pendaftaran_id';
    public $timestamps = false;

    protected $fillable = [
        'pasien_id',
        'tanggal_daftar',
        'nomor_antrian',
        'jenis_kunjungan',
        'keluhan_utama',
        'staf_pendaftaran_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_daftar' => 'datetime',
        ];
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }

    public function stafPendaftaran(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'staf_pendaftaran_id', 'staf_id');
    }

    public function pemeriksaan(): HasOne
    {
        return $this->hasOne(Pemeriksaan::class, 'pendaftaran_id', 'pendaftaran_id');
    }
}
