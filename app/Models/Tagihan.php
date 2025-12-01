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
    public $timestamps = false;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'jenis_tagihan',
        'subtotal',
        'total_tagihan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'total_tagihan' => 'decimal:2',
        ];
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'pendaftaran_id');
    }

    public function detailTagihan(): HasMany
    {
        return $this->hasMany(DetailTagihan::class, 'tagihan_id', 'tagihan_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'tagihan_id', 'tagihan_id');
    }
}
