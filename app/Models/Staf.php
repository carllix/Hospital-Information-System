<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staf extends Model
{
    use HasFactory;

    protected $table = 'staf';
    protected $primaryKey = 'staf_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kewarganegaraan',
        'no_telepon',
        'bagian',
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
}
