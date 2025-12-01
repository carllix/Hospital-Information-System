<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'pembayaran_id';
    public $timestamps = false;

    protected $fillable = [
        'tagihan_id',
        'tanggal_bayar',
        'metode_pembayaran',
        'jumlah_bayar',
        'kasir_id',
        'no_kwitansi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
            'created_at' => 'datetime',
            'jumlah_bayar' => 'decimal:2',
        ];
    }

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id', 'tagihan_id');
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'kasir_id', 'staf_id');
    }
}
