<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTagihan extends Model
{
    protected $table = 'detail_tagihan';
    protected $primaryKey = 'detail_tagihan_id';
    public $timestamps = false;

    protected $fillable = [
        'tagihan_id',
        'layanan_id',       
        'detail_resep_id',  
        'hasil_lab_id',      
        'jenis_item',
        'nama_item',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id', 'tagihan_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function detailResep(): BelongsTo
    {
        return $this->belongsTo(DetailResep::class, 'detail_resep_id', 'detail_resep_id');
    }

    public function hasilLab(): BelongsTo
    {
        return $this->belongsTo(HasilLab::class, 'hasil_lab_id', 'hasil_lab_id');
    }
}