<?php

namespace Database\Seeders;

use App\Models\KategoriPenyaluran;
use Illuminate\Database\Seeder;

class KategoriPenyaluranSeeder extends Seeder
{
    /**
     * Seed default kategori dan aturan penyaluran sesuai desain.
     */
    public function run(): void
    {
        $rules = [
            [
                'kategori_kata_kunci' => 'Transkrip nilai, nilai, KRS',
                'tipe_tujuan' => 'Program Studi',
                'rekomendasi_default' => 'Program Studi pelapor',
                'status' => 'Aktif',
                'urutan' => 1,
            ],
            [
                'kategori_kata_kunci' => 'Perkuliahan, dosen, kurikulum',
                'tipe_tujuan' => 'Program Studi',
                'rekomendasi_default' => 'Program Studi pelapor',
                'status' => 'Aktif',
                'urutan' => 2,
            ],
            [
                'kategori_kata_kunci' => 'Akademik umum, jadwal, layanan akademik',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Akademik',
                'status' => 'Aktif',
                'urutan' => 3,
            ],
            [
                'kategori_kata_kunci' => 'Fasilitas dan sarana',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Logistik dan Aset',
                'status' => 'Aktif',
                'urutan' => 4,
            ],
            [
                'kategori_kata_kunci' => 'Layanan teknologi informasi',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Pusat Teknologi Informasi',
                'status' => 'Aktif',
                'urutan' => 5,
            ],
            [
                'kategori_kata_kunci' => 'Keuangan',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Keuangan',
                'status' => 'Aktif',
                'urutan' => 6,
            ],
            [
                'kategori_kata_kunci' => 'Kemahasiswaan',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Kemahasiswaan',
                'status' => 'Aktif',
                'urutan' => 7,
            ],
            [
                'kategori_kata_kunci' => 'Karier, magang, alumni',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Pengembangan Karir, Alumni, & Endowment',
                'status' => 'Aktif',
                'urutan' => 8,
            ],
            [
                'kategori_kata_kunci' => 'Penelitian dan pengabdian masyarakat',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Penelitian dan Pengabdian Masyarakat',
                'status' => 'Aktif',
                'urutan' => 9,
            ],
            [
                'kategori_kata_kunci' => 'Kerja sama dan internasional',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
                'status' => 'Aktif',
                'urutan' => 10,
            ],
            [
                'kategori_kata_kunci' => 'Pelayanan staf atau dosen',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Sumber Daya Manusia',
                'status' => 'Aktif',
                'urutan' => 11,
            ],
            [
                'kategori_kata_kunci' => 'Aspirasi umum dan lainnya',
                'tipe_tujuan' => 'Direktorat',
                'rekomendasi_default' => 'Direktorat Sekretariat & Perencanaan Strategis',
                'status' => 'Aktif',
                'urutan' => 12,
            ],
        ];

        foreach ($rules as $rule) {
            KategoriPenyaluran::updateOrCreate(
                ['kategori_kata_kunci' => $rule['kategori_kata_kunci']],
                $rule
            );
        }
    }
}
