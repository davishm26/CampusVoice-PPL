<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPenyaluran extends Model
{
    protected $fillable = [
        'kategori_kata_kunci',
        'tipe_tujuan',
        'rekomendasi_default',
        'status',
        'urutan',
    ];
}
