<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'laporan_id',
        'tiket',
        'icon',
        'title',
        'desc',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function getFormattedTimeAttribute(): string
    {
        if (!$this->created_at) {
            return '-';
        }

        $diffInMinutes = $this->created_at->diffInMinutes();
        if ($diffInMinutes < 1) {
            return 'Baru saja';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit lalu';
        } elseif ($this->created_at->isToday()) {
            return $this->created_at->diffInHours() . ' jam lalu';
        } elseif ($this->created_at->isYesterday()) {
            return 'Kemarin, ' . $this->created_at->format('H.i');
        }

        return $this->created_at->locale('id')->isoFormat('D MMM YYYY');
    }
}

