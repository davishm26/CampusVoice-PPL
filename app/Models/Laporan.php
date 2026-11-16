<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_tiket',
        'kategori',
        'kategori_label',
        'judul',
        'deskripsi',
        'lokasi',
        'berkas_bukti',
        'berkas_nama',
        'berkas_ukuran',
        'is_anonim',
        'status',
        'prioritas',
        'unit_penangan',
        'unit_penangan_id',
        'catatan_internal',
        'progres',
        'rating',
        'ulasan',
    ];

    protected function casts(): array
    {
        return [
            'is_anonim' => 'boolean',
            'progres' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unitPenangan(): BelongsTo
    {
        return $this->belongsTo(UnitPenangan::class, 'unit_penangan_id');
    }

    public function tanggapans(): HasMany
    {
        return $this->hasMany(LaporanTanggapan::class)->orderBy('created_at', 'asc');
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(LaporanTracking::class)->orderBy('created_at', 'desc');
    }

    public function getShortKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'sarana_prasarana' => 'Satpras',
            'kemahasiswaan' => 'Kemahasiswaan',
            'ssc' => 'SSC',
            'akademik' => 'Akademik',
            'administrasi_keuangan' => 'Keuangan',
            'lainnya' => 'Lainnya',
            default => $this->kategori_label ?: ucfirst($this->kategori),
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'Diproses' => ['text' => '#7C3AED', 'bg' => '#F5F3FF', 'dot' => '#7C3AED'],
            'Diteruskan' => ['text' => '#2563EB', 'bg' => '#EFF6FF', 'dot' => '#2563EB'],
            'Selesai' => ['text' => '#16A34A', 'bg' => '#ECFDF5', 'dot' => '#16A34A'],
            'Ditolak' => ['text' => '#DC2626', 'bg' => '#FEF2F2', 'dot' => '#DC2626'],
            default => ['text' => '#D97706', 'bg' => '#FFF7ED', 'dot' => '#D97706'], // Menunggu Verifikasi
        };
    }

    public function getFormattedTanggalAttribute(): string
    {
        return $this->created_at ? $this->created_at->wib()->isoFormat('D MMM YYYY') : '-';
    }

    public function getFormattedDateTimeAttribute(): string
    {
        return $this->created_at ? $this->created_at->wib()->isoFormat('D MMMM YYYY, HH.mm') . ' WIB' : '-';
    }

    public function getDisplayPelaporAttribute(): string
    {
        if ($this->is_anonim) {
            return 'Mahasiswa (Anonim)';
        }

        $pelapor = $this->user;
        if (!$pelapor) {
            return 'Mahasiswa';
        }

        return $pelapor->name . ($pelapor->nim ? ' • ' . $pelapor->nim : '');
    }

    public function getBerkasExtensionAttribute(): string
    {
        $source = $this->berkas_bukti ?: $this->berkas_nama ?: '';

        return strtolower(pathinfo($source, PATHINFO_EXTENSION));
    }

    public function getBerkasUrlAttribute(): ?string
    {
        if (!$this->berkas_bukti) {
            return null;
        }

        return route('laporan.bukti', $this->nomor_tiket);
    }
}

