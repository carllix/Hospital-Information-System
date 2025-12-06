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
        'tujuan_rujukan',
        'rs_tujuan',
        'dokter_spesialis_tujuan',
        'alasan_rujukan',
        'diagnosa_sementara',
        'tanggal_rujukan',
    ];

    protected $casts = [
        'tanggal_rujukan' => 'date',
    ];

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }
}
