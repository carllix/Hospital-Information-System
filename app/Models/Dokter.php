<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    protected $primaryKey = 'dokter_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kewarganegaraan',
        'no_telepon',
        'spesialisasi',
        'no_str',
        'jadwal_praktik',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'jadwal_praktik' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
