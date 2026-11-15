<?php

namespace Tests\Feature;

use App\Models\KategoriPenyaluran;
use Database\Seeders\KategoriPenyaluranSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminKategoriTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_kategori_dan_penyaluran_dapat_diakses_dengan_data_seeder(): void
    {
        $this->seed(KategoriPenyaluranSeeder::class);

        $response = $this->get(route('superadmin.kategori'));

        $response->assertStatus(200);
        $response->assertSee('Kategori dan Aturan Penyaluran');
        $response->assertSee('Transkrip nilai, nilai, KRS');
        $response->assertSee('Direktorat Sekretariat & Perencanaan Strategis', false);
        $this->assertEquals(12, KategoriPenyaluran::count());
    }

    public function test_super_admin_dapat_menambah_mengubah_dan_menghapus_aturan_penyaluran(): void
    {
        $createResponse = $this->postJson(route('superadmin.kategori.store'), [
            'kategori_kata_kunci' => 'Beasiswa, KIP Kuliah',
            'tipe_tujuan' => 'Direktorat',
            'rekomendasi_default' => 'Direktorat Kemahasiswaan',
            'status' => 'Aktif',
        ]);

        $createResponse->assertStatus(201);
        $ruleId = $createResponse->json('rule.id');

        $this->assertDatabaseHas('kategori_penyalurans', [
            'id' => $ruleId,
            'kategori_kata_kunci' => 'Beasiswa, KIP Kuliah',
            'tipe_tujuan' => 'Direktorat',
            'rekomendasi_default' => 'Direktorat Kemahasiswaan',
            'status' => 'Aktif',
        ]);

        $updateResponse = $this->putJson(route('superadmin.kategori.update', $ruleId), [
            'kategori_kata_kunci' => 'Beasiswa, UKT',
            'tipe_tujuan' => 'Direktorat',
            'rekomendasi_default' => 'Direktorat Keuangan',
            'status' => 'Nonaktif',
        ]);

        $updateResponse->assertStatus(200);

        $this->assertDatabaseHas('kategori_penyalurans', [
            'id' => $ruleId,
            'kategori_kata_kunci' => 'Beasiswa, UKT',
            'rekomendasi_default' => 'Direktorat Keuangan',
            'status' => 'Nonaktif',
        ]);

        $deleteResponse = $this->deleteJson(route('superadmin.kategori.destroy', $ruleId));
        $deleteResponse->assertStatus(200);

        $this->assertDatabaseMissing('kategori_penyalurans', [
            'id' => $ruleId,
        ]);
    }
}
