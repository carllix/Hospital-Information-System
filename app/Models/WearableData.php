<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WearableData extends Model
{
    protected $table = 'wearable_data';
    protected $primaryKey = 'wearable_data_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only has created_at

    protected $fillable = [
        'pasien_id',
        'device_id',
        'timestamp',
        'heart_rate',
        'oxygen_saturation',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'heart_rate' => 'integer',
        'oxygen_saturation' => 'integer',
        'created_at' => 'datetime',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }
}
