<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanAnonimTest extends TestCase
{
    use RefreshDatabase;

    public function test_fitur_laporan_anonim_berjalan_konsisten_di_semua_role(): void
    {
        $mahasiswa = User::factory()->create([
            'name' => 'Rahasia Mahasiswa',
            'nim' => '102062409999',
            'prodi' => 'S1 Sistem Informasi',
            'role' => 'Mahasiswa',
        ]);

        $admin = User::factory()->create([
            'name' => 'Siti Rahmawati',
            'role' => 'Admin SSC',
            'fakultas' => 'Student Service Center',
        ]);

        $petugas = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => 'Petugas Unit',
            'prodi' => 'Logistik & Sarana',
            'fakultas' => 'Direktorat Logistik dan Aset',
        ]);

        User::factory()->create([
            'name' => 'Rizky Pratama',
            'role' => 'Super Admin',
        ]);

        // 1. Mahasiswa kirim laporan anonim
        $storeResponse = $this->actingAs($mahasiswa)->post(route('laporan.store'), [
            'kategori' => 'sarana_prasarana',
            'judul' => 'Proyektor Bermasalah',
            'deskripsi' => 'Proyektor di ruang kelas sering mati mendadak saat perkuliahan.',
            'lokasi' => 'Ruang 3.01',
            'is_anonim' => '1',
        ]);

        $laporan = Laporan::firstOrFail();
        $storeResponse->assertRedirect(route('laporan.success', ['tiket' => $laporan->nomor_tiket]));
        $this->assertTrue($laporan->is_anonim);

        // 2. Mahasiswa mengirim tanggapan di laporan anonimnya
        $this->actingAs($mahasiswa)->post(route('laporan.tanggapan', $laporan->nomor_tiket), [
            'pesan' => 'Mohon segera dicek sebelum jadwal ujian.',
        ])->assertRedirect(route('laporan.detail', $laporan->nomor_tiket));

        // Detail mahasiswa menampilkan Mahasiswa (Anonim) & tidak membocorkan nama di diskusi
        $detailMahasiswa = $this->actingAs($mahasiswa)->get(route('laporan.detail', $laporan->nomor_tiket));
        $detailMahasiswa->assertStatus(200);
        $detailMahasiswa->assertSee('Laporan Anonim');
        $detailMahasiswa->assertSee('Mahasiswa (Anonim)');
        $detailMahasiswa->assertDontSee('Rahasia Mahasiswa');

        // 3. Admin SSC juga TIDAK dapat melihat identitas asli pelapor pada laporan anonim penuh
        $adminDashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $adminDashboard->assertStatus(200);
        $adminDashboard->assertSee('Pelapor anonim');
        $adminDashboard->assertSee('Identitas disembunyikan');
        $adminDashboard->assertDontSee('Rahasia Mahasiswa');
        $adminDashboard->assertDontSee('S1 Sistem Informasi');

        $adminVerifikasi = $this->actingAs($admin)->get(route('admin.verifikasi'));
        $adminVerifikasi->assertStatus(200);
        $adminVerifikasi->assertDontSee('Rahasia Mahasiswa');
        $adminVerifikasi->assertDontSee('S1 Sistem Informasi');

        $verifikasiDetail = $this->actingAs($admin)->get(route('admin.verifikasi.detail', $laporan->nomor_tiket));
        $verifikasiDetail->assertStatus(200);
        $verifikasiDetail->assertSee('Laporan Anonim');
        $verifikasiDetail->assertSee('Identitas disembunyikan');
        $verifikasiDetail->assertDontSee('Rahasia Mahasiswa');
        $verifikasiDetail->assertDontSee('102062409999');

        // Admin SSC teruskan laporan ke unit
        $this->actingAs($admin)->postJson(route('admin.verifikasi.submit', $laporan->nomor_tiket), [
            'action' => 'teruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
        ])->assertStatus(200);

        $monitoringDetail = $this->actingAs($admin)->get(route('admin.monitoring.detail', $laporan->nomor_tiket));
        $monitoringDetail->assertStatus(200);
        $monitoringDetail->assertSee('Laporan Anonim');
        $monitoringDetail->assertSee('Identitas disembunyikan');
        $monitoringDetail->assertSee('Pelapor Anonim');
        $monitoringDetail->assertDontSee('Rahasia Mahasiswa');
        $monitoringDetail->assertDontSee('102062409999');

        // 4. Petugas Unit TIDAK boleh melihat nama mahasiswa baik di info pelapor maupun di riwayat tanggapan
        $unitDetail = $this->actingAs($petugas)->get(route('unit.laporan-ditugaskan.detail', $laporan->nomor_tiket));
        $unitDetail->assertStatus(200);
        $unitDetail->assertSee('Laporan Anonim');
        $unitDetail->assertSee('Identitas disembunyikan');
        $unitDetail->assertSee('Pelapor Anonim');
        $unitDetail->assertDontSee('Rahasia Mahasiswa');
        $unitDetail->assertDontSee('102062409999');

        // 5. Petugas Unit memperbarui penanganan dan mengirim tanggapan
        $this->actingAs($petugas)->post(route('unit.laporan-ditugaskan.update', $laporan->nomor_tiket), [
            'status' => 'Diproses',
            'progres' => 50,
            'catatan' => 'Sedang dilakukan pengecekan kabel HDMI.',
            'action_type' => 'simpan',
        ])->assertRedirect(route('unit.laporan-ditugaskan.detail', $laporan->nomor_tiket));

        $this->assertDatabaseHas('laporan_tanggapans', [
            'laporan_id' => $laporan->id,
            'nama_pengirim' => 'Budi Santoso',
            'role_pengirim' => 'Petugas Unit',
            'pesan' => 'Sedang dilakukan pengecekan kabel HDMI.',
        ]);

        // 6. Super Admin tidak menampilkan nama asli pelapor anonim pada log aktivitas
        $superAdminLog = $this->get(route('superadmin.log'));
        $superAdminLog->assertStatus(200);
        $superAdminLog->assertSee('Pelapor Anonim');
        $superAdminLog->assertDontSee('Rahasia Mahasiswa');
    }
}
