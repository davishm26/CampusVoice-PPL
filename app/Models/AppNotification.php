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

        $created = $this->created_at->wib();
        $now = now()->wib();
        $diffInMinutes = (int) floor($created->diffInMinutes($now));

        if ($diffInMinutes < 1) {
            return 'Baru saja';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit lalu';
        } elseif ($created->isToday()) {
            $diffInHours = max(1, (int) floor($created->diffInHours($now)));
            return $diffInHours . ' jam lalu';
        } elseif ($created->isYesterday()) {
            return 'Kemarin, ' . $created->format('H.i') . ' WIB';
        }

        return $created->isoFormat('D MMM YYYY');
    }
}

