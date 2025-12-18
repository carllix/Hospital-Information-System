<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'tagihan_id';
    public $timestamps = true;

    protected $fillable = [
        'pemeriksaan_id',
        'total_tagihan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_tagihan' => 'decimal:2',
        ];
    }

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    public function detailTagihan(): HasMany
    {
        return $this->hasMany(DetailTagihan::class, 'tagihan_id', 'tagihan_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id', 'tagihan_id');
    }
}
