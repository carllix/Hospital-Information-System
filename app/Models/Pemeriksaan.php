<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';
    protected $primaryKey = 'pemeriksaan_id';
    public $timestamps = false;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'dokter_id',
        'tanggal_pemeriksaan',
        'anamnesa',
        'pemeriksaan_fisik',
        'tekanan_darah',
        'suhu_tubuh',
        'berat_badan',
        'tinggi_badan',
        'diagnosa',
        'icd10_code',
        'tindakan_medis',
        'catatan_dokter',
        'status_pasien',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemeriksaan' => 'datetime',
            'suhu_tubuh' => 'decimal:1',
            'berat_badan' => 'decimal:2',
            'tinggi_badan' => 'decimal:2',
        ];
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id', 'dokter_id');
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'pendaftaran_id');
    }

    public function resep(): HasOne
    {
        return $this->hasOne(Resep::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    public function permintaanLab(): HasMany
    {
        return $this->hasMany(PermintaanLab::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }
}
