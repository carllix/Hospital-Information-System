<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';
    protected $primaryKey = 'layanan_id';
    public $timestamps = true;

    protected $fillable = [
        'kode_layanan',
        'nama_layanan',
        'kategori',
        'harga',
        'is_deleted',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function detailTagihan(): HasMany
    {
        return $this->hasMany(DetailTagihan::class, 'layanan_id', 'layanan_id');
    }
}
