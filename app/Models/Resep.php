<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';
    protected $primaryKey = 'resep_id';
    public $timestamps = true;

    protected $fillable = [
        'pemeriksaan_id',
        'tanggal_resep',
        'status',
        'apoteker_id',
        'catatan_apoteker',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_resep' => 'datetime',
        ];
    }

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    public function apoteker(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'apoteker_id', 'staf_id');
    }

    public function detailResep(): HasMany
    {
        return $this->hasMany(DetailResep::class, 'resep_id', 'resep_id');
    }
}
