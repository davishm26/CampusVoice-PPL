<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'peran',
        'aktivitas',
        'kategori',
        'status',
        'record_terkait',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedWaktuAttribute(): string
    {
        $time = $this->created_at;
        return $time ? $time->wib()->isoFormat('D MMM YYYY, HH.mm') : '-';
    }

    /**
     * Helper untuk mencatat log aktivitas secara konsisten di seluruh sistem.
     */
    public static function catat(
        string $aktivitas,
        ?string $recordTerkait = null,
        string $kategori = 'Umum',
        string $status = 'Berhasil',
        ?User $user = null,
        ?string $ipAddress = null
    ): self {
        $actor = $user ?? Auth::user();
        $ip = $ipAddress ?? Request::ip() ?? '127.0.0.1';

        return self::create([
            'user_id' => $actor?->id,
            'nama_pengguna' => $actor?->name ?? 'Sistem',
            'peran' => $actor?->role ?? 'Sistem',
            'aktivitas' => $aktivitas,
            'kategori' => $kategori,
            'status' => $status,
            'record_terkait' => $recordTerkait ?: '—',
            'ip_address' => $ip,
        ]);
    }
}
