<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'status',
        'keterangan',
        'waktu',
    ];

    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
        ];
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function getFormattedWaktuAttribute(): string
    {
        $time = $this->waktu ?? $this->created_at;
        return $time ? $time->locale('id')->isoFormat('D MMM YYYY, HH.mm') : '-';
    }
}

