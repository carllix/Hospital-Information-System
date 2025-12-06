<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    protected $primaryKey = 'dokter_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'nip_rs',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'no_telepon',
        'spesialisasi',
        'no_str',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function jadwalDokter(): HasMany
    {
        return $this->hasMany(JadwalDokter::class, 'dokter_id', 'dokter_id');
    }
}
