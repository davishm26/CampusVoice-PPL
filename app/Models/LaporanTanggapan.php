<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanTanggapan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'user_id',
        'nama_pengirim',
        'role_pengirim',
        'pesan',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->nama_pengirim ?? ''));
        $initials = '';
        foreach ($words as $w) {
            $initials .= mb_strtoupper(mb_substr($w, 0, 1));
        }
        return substr($initials, 0, 2) ?: 'CV';
    }

    public function getFormattedWaktuAttribute(): string
    {
        if (!$this->created_at) {
            return '-';
        }

        if ($this->created_at->isToday()) {
            return 'Hari ini, ' . $this->created_at->format('H.i');
        } elseif ($this->created_at->isYesterday()) {
            return 'Kemarin, ' . $this->created_at->format('H.i');
        }

        return $this->created_at->locale('id')->isoFormat('D MMM, HH.mm');
    }
}

