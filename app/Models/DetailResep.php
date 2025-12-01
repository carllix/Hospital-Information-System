<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailResep extends Model
{
    protected $table = 'detail_resep';
    protected $primaryKey = 'detail_resep_id';
    public $timestamps = false;

    protected $fillable = [
        'resep_id',
        'obat_id',
        'jumlah',
        'dosis',
        'aturan_pakai',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class, 'resep_id', 'resep_id');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'obat_id', 'obat_id');
    }
}
