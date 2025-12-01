<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilLab extends Model
{
    protected $table = 'hasil_lab';
    protected $primaryKey = 'hasil_lab_id';
    public $timestamps = false;

    protected $fillable = [
        'permintaan_lab_id',
        'jenis_test',
        'parameter',
        'hasil',
        'satuan',
        'nilai_normal',
        'keterangan',
        'file_hasil_url',
        'tanggal_hasil',
        'petugas_lab_id',
    ];

    protected $casts = [
        'tanggal_hasil' => 'datetime',
    ];

    public function permintaanLab(): BelongsTo
    {
        return $this->belongsTo(PermintaanLab::class, 'permintaan_lab_id', 'permintaan_lab_id');
    }

    public function petugasLab(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'petugas_lab_id', 'staf_id');
    }
}
