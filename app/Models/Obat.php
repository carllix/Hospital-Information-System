<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $table = 'obat';
    protected $primaryKey = 'obat_id';
    public $timestamps = false;

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'kategori',
        'satuan',
        'stok',
        'harga',
        'deskripsi',
    ];

    protected $casts = [
        'stok' => 'integer',
        'harga' => 'decimal:2',
    ];

    public function detailResep(): HasMany
    {
        return $this->hasMany(DetailResep::class, 'obat_id', 'obat_id');
    }
}
