<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanBuktiUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_buat_laporan_menjaga_input_file_tetap_di_luar_template_x_if(): void
    {
        User::factory()->create(['role' => 'Mahasiswa']);

        $response = $this->get(route('laporan.create'));

        $response->assertStatus(200);
        $html = $response->getContent();

        $inputPos = strpos($html, 'name="berkas_bukti"');
        $templatePos = strpos($html, '<template x-if="!fileSelected">');

        $this->assertNotFalse($inputPos);
        $this->assertNotFalse($templatePos);
        $this->assertLessThan($templatePos, $inputPos);
    }

    public function test_unggah_bukti_pendukung_tersimpan_dan_terlihat_di_semua_detail(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        User::factory()->create(['role' => 'Admin SSC']);
        User::factory()->create(['role' => 'Petugas Unit']);

        $file = UploadedFile::fake()->image('ac_rusak.jpg', 640, 480);

        $response = $this->actingAs($mahasiswa)->post(route('laporan.store'), [
            'kategori' => 'sarana_prasarana',
            'judul' => 'AC Ruang A-301 Rusak',
            'deskripsi' => 'AC di ruang kelas A-301 tidak dingin sejak pagi sehingga perkuliahan terganggu.',
            'lokasi' => 'Gedung A Ruang 301',
            'berkas_bukti' => $file,
        ]);

        $laporan = Laporan::firstOrFail();

        $response->assertRedirect(route('laporan.success', ['tiket' => $laporan->nomor_tiket]));
        $this->assertNotNull($laporan->berkas_bukti);
        $this->assertSame('ac_rusak.jpg', $laporan->berkas_nama);
        Storage::disk('public')->assertExists($laporan->berkas_bukti);

        $buktiUrl = route('laporan.bukti', $laporan->nomor_tiket);

        // Endpoint penyaji file bukti dapat diakses
        $fileResponse = $this->get($buktiUrl);
        $fileResponse->assertStatus(200);

        // Terlihat di detail mahasiswa
        $detailResponse = $this->actingAs($mahasiswa)->get(route('laporan.detail', $laporan->nomor_tiket));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('ac_rusak.jpg');
        $detailResponse->assertSee($buktiUrl, false);

        // Terlihat di detail verifikasi Admin SSC
        $adminResponse = $this->get(route('admin.verifikasi.detail', $laporan->nomor_tiket));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('ac_rusak.jpg');
        $adminResponse->assertSee($buktiUrl, false);

        // Terlihat di halaman penanganan Petugas Unit
        $unitResponse = $this->get(route('unit.laporan-ditugaskan.detail', $laporan->nomor_tiket));
        $unitResponse->assertStatus(200);
        $unitResponse->assertSee('ac_rusak.jpg');
        $unitResponse->assertSee($buktiUrl, false);
    }

    public function test_laporan_tanpa_bukti_tidak_menampilkan_lampiran_palsu_di_unit(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        User::factory()->create(['role' => 'Petugas Unit']);

        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00099',
            'kategori' => 'akademik',
            'kategori_label' => 'Akademik',
            'judul' => 'Jadwal bentrok',
            'deskripsi' => 'Jadwal kuliah praktikum dan teori bentrok pada hari Selasa pagi.',
            'lokasi' => 'Kampus Surabaya',
            'status' => 'Diteruskan',
            'prioritas' => 'Sedang',
            'unit_penangan' => 'Direktorat Akademik',
            'progres' => 20,
        ]);

        $unitResponse = $this->get(route('unit.laporan-ditugaskan.detail', $laporan->nomor_tiket));
        $unitResponse->assertStatus(200);
        $unitResponse->assertDontSee('foto_lampiran_laporan.jpg');
        $unitResponse->assertSee('Tidak ada lampiran bukti pendukung.');
    }
}
