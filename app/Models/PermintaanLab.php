<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanLab extends Model
{
    use HasFactory;

    protected $table = 'permintaan_lab';
    protected $primaryKey = 'permintaan_lab_id';
    public $timestamps = false;

    protected $fillable = [
        'pemeriksaan_id',
        'pasien_id',
        'dokter_id',
        'tanggal_permintaan',
        'jenis_pemeriksaan',
        'catatan_permintaan',
        'status',
        'petugas_lab_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_permintaan' => 'datetime',
        ];
    }

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id', 'dokter_id');
    }

    public function petugasLab(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'petugas_lab_id', 'staf_id');
    }

    public function hasilLab(): HasMany
    {
        return $this->hasMany(HasilLab::class, 'permintaan_lab_id', 'permintaan_lab_id');
    }
}
