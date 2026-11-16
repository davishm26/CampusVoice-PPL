<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPenangan extends Model
{
    use HasFactory;

    protected $table = 'unit_penangan';

    protected $fillable = [
        'nama_unit',
        'tipe',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_penangan_id');
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'unit_penangan_id');
    }
}
