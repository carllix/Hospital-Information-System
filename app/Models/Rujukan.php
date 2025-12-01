<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rujukan extends Model
{
    protected $table = 'rujukan';
    protected $primaryKey = 'rujukan_id';
    public $timestamps = false;

    protected $fillable = [
        'pemeriksaan_id',
        'pasien_id',
        'tujuan_rujukan',
        'rs_tujuan',
        'dokter_spesialis_tujuan',
        'alasan_rujukan',
        'diagnosa_sementara',
        'tanggal_rujukan',
        'dokter_perujuk_id',
    ];

    protected $casts = [
        'tanggal_rujukan' => 'date',
    ];

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }

    public function dokterPerujuk(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_perujuk_id', 'dokter_id');
    }
}
