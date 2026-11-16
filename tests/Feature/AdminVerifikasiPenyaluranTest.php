<?php

namespace Tests\Feature;

use App\Models\AppNotification;
use App\Models\Laporan;
use App\Models\User;
use Database\Seeders\UnitAccountSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminVerifikasiPenyaluranTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ssc_dapat_meneruskan_laporan_ke_unit_terkait(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $admin = User::factory()->create(['role' => 'Admin SSC']);
        $petugas = User::factory()->create([
            'role' => 'Petugas Unit',
            'prodi' => 'Logistik & Sarana',
            'fakultas' => 'Direktorat Logistik dan Aset',
        ]);

        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00002',
            'kategori' => 'sarana_prasarana',
            'kategori_label' => 'Sarana dan prasarana kampus',
            'judul' => 'AC rusak - 1.02',
            'deskripsi' => 'AC pada kelas 1.02 tidak bisa digunakan saat perkuliahan.',
            'lokasi' => '1.02',
            'status' => 'Menunggu Verifikasi',
            'prioritas' => 'Normal',
            'unit_penangan' => 'Belum Ditentukan',
            'progres' => 10,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.verifikasi.submit', $laporan->nomor_tiket), [
            'action' => 'teruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
            'catatan' => 'Segera tindak lanjuti.',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('laporans', [
            'id' => $laporan->id,
            'status' => 'Diteruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
        ]);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $petugas->id,
            'laporan_id' => $laporan->id,
            'tiket' => 'CV-2026-00002',
        ]);

        $this->assertSame(1, AppNotification::where('user_id', $mahasiswa->id)->where('tiket', 'CV-2026-00002')->count());
    }

    public function test_halaman_unit_dan_super_admin_unit_penangan_tidak_error_kolom_unit(): void
    {
        User::factory()->create([
            'role' => 'Petugas Unit',
            'prodi' => 'Logistik & Sarana',
            'fakultas' => 'Direktorat Logistik dan Aset',
            'status_akun' => 'Aktif',
        ]);
        User::factory()->create(['role' => 'Super Admin']);

        $unitDash = $this->get(route('unit.dashboard'));
        $unitDash->assertStatus(200);
        $unitDash->assertDontSee('Lihat Versi Direktorat');
        $unitDash->assertSee('Direktorat Logistik dan Aset');
        $unitDash->assertSee(route('unit.laporan-ditugaskan'), false);
        $unitDash->assertDontSee(route('laporan.riwayat'), false);

        $this->get(route('unit.laporan-ditugaskan'))->assertStatus(200)->assertSee(route('unit.dashboard'), false)->assertDontSee('href="#"', false);
        $this->get(route('petugas.riwayat'))->assertStatus(200)->assertSee(route('unit.dashboard'), false);
        $this->get(route('unit.profil'))->assertStatus(200)->assertSee(route('unit.dashboard'), false)->assertDontSee('href="#"', false);
        $this->get(route('superadmin.dashboard'))->assertStatus(200);
        $this->get(route('superadmin.unit-penangan'))->assertStatus(200);
    }

    public function test_tampilan_petugas_unit_konsisten_di_semua_halaman(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $petugas = User::factory()->create([
            'name' => 'Petugas Konsisten',
            'role' => 'Petugas Unit',
            'prodi' => 'Logistik & Sarana',
            'fakultas' => 'Direktorat Logistik dan Aset',
            'status_akun' => 'Aktif',
        ]);

        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00050',
            'kategori' => 'sarana_prasarana',
            'kategori_label' => 'Sarana dan prasarana kampus',
            'judul' => 'Proyektor Kelas Bermasalah',
            'deskripsi' => 'Proyektor di ruang 3.04 tidak menampilkan gambar.',
            'lokasi' => '3.04',
            'status' => 'Diteruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
            'progres' => 20,
        ]);

        $routes = [
            route('unit.dashboard'),
            route('unit.laporan-ditugaskan'),
            route('petugas.riwayat'),
            route('unit.notifikasi'),
            route('unit.profil'),
            route('unit.laporan-ditugaskan.detail', $laporan->nomor_tiket),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($petugas)->get($url);
            $response->assertStatus(200);
            $response->assertSee('content-gradient-bg', false);
            $response->assertSee('to top right', false);
            $response->assertSee('Petugas Konsisten');
            $response->assertDontSee('Budi Santoso');
            $response->assertDontSee('19 Sep — 19 Okt 2026');
            $response->assertDontSee('Cari nomor tiket atau laporan');
            $response->assertDontSee('href="#"', false);
        }
    }

    public function test_detail_monitoring_admin_menampilkan_halaman_detail_bukan_halaman_verifikasi(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $admin = User::factory()->create(['role' => 'Admin SSC']);

        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00003',
            'kategori' => 'sarana_prasarana',
            'kategori_label' => 'Sarana dan prasarana kampus',
            'judul' => 'Kursi Rusak',
            'deskripsi' => 'Kursi kuliah di ruang 2.01 patah pada bagian sandaran.',
            'lokasi' => '2.01',
            'status' => 'Diteruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
            'progres' => 20,
        ]);

        $monitoringResponse = $this->actingAs($admin)->get(route('admin.monitoring'));
        $monitoringResponse->assertStatus(200);
        $monitoringResponse->assertSee(url('/admin/monitoring-laporan'), false);
        $monitoringResponse->assertDontSee(url('/admin/verifikasi-laporan') . "/' + row.tiket", false);

        $detailResponse = $this->actingAs($admin)->get(route('admin.monitoring.detail', $laporan->nomor_tiket));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('DETAIL LAPORAN');
        $detailResponse->assertSee('Kursi Rusak');
        $detailResponse->assertSee('Riwayat Status');
        $detailResponse->assertDontSee('Tujuan Penyaluran');
        $detailResponse->assertDontSee('Tolak Laporan');
    }

    public function test_halaman_verifikasi_hanya_menampilkan_filter_pencarian_kategori_dan_rentang_tanggal(): void
    {
        $admin = User::factory()->create(['role' => 'Admin SSC']);

        $response = $this->actingAs($admin)->get(route('admin.verifikasi'));

        $response->assertStatus(200);
        $response->assertSee('Semua Kategori');
        $response->assertSee('Administrasi &amp; Keuangan', false);
        $response->assertSee('Semua Tanggal');
        $response->assertDontSee('Semua Status');
        $response->assertDontSee('Semua Prioritas');
    }

    public function test_halaman_rekapitulasi_menampilkan_filter_periode_kategori_dan_unit_dinamis(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $admin = User::factory()->create(['role' => 'Admin SSC']);

        Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00010',
            'kategori' => 'sarana_prasarana',
            'kategori_label' => 'Sarana dan prasarana kampus',
            'judul' => 'Lampu koridor mati',
            'deskripsi' => 'Lampu koridor lantai 2 gedung A mati sejak kemarin.',
            'lokasi' => 'Gedung A Lt 2',
            'status' => 'Selesai',
            'prioritas' => 'Sedang',
            'unit_penangan' => 'SATPRAS (Sarana Prasarana)',
            'progres' => 100,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekapitulasi'));

        $response->assertStatus(200);
        $response->assertSee('Semua Periode');
        $response->assertSee('Semua Kategori');
        $response->assertSee('Administrasi &amp; Keuangan', false);
        $response->assertSee('Semua Unit');
        $response->assertSee('Program Studi S1 Sistem Informasi');
        $response->assertSee('Direktorat Pusat Teknologi Informasi');
        $response->assertSee('SATPRAS (Sarana Prasarana)');
        $response->assertDontSee('Semua Status');
        $response->assertDontSee('rekapitulasi-pengaduan-oktober-2026');
    }

    public function test_notifikasi_verifikasi_untuk_laporan_yang_sudah_diverifikasi_mengarah_ke_detail_bukan_verifikasi(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $admin = User::factory()->create(['role' => 'Admin SSC']);

        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00011',
            'kategori' => 'sarana_prasarana',
            'kategori_label' => 'Sarana dan prasarana kampus',
            'judul' => 'Kipas rusak',
            'deskripsi' => 'Kipas angin ruang kelas mati total sejak pagi.',
            'lokasi' => 'Ruang 1.05',
            'status' => 'Diteruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
            'progres' => 20,
        ]);

        $notif = AppNotification::create([
            'user_id' => $admin->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'submitted',
            'title' => 'Laporan baru masuk',
            'desc' => 'Laporan CV-2026-00011 menunggu verifikasi SSC.',
            'is_read' => false,
        ]);

        // Membuka halaman notifikasi Admin SSC menandai notif verifikasi lama sebagai dibaca & menampilkan status sudah diverifikasi
        $notifPage = $this->actingAs($admin)->get(route('admin.notifikasi'));
        $notifPage->assertStatus(200);
        $notifPage->assertSee('Sudah diverifikasi');
        $this->assertTrue($notif->fresh()->is_read);

        // Mengklik notifikasi mengarahkan ke halaman detail monitoring, bukan verifikasi
        $this->actingAs($admin)
            ->get(route('notifikasi.read', $notif->id))
            ->assertRedirect(route('admin.monitoring.detail', $laporan->nomor_tiket));

        // Membuka langsung URL verifikasi-detail untuk laporan yang sudah diteruskan juga dialihkan ke detail monitoring
        $this->actingAs($admin)
            ->get(route('admin.verifikasi.detail', $laporan->nomor_tiket))
            ->assertRedirect(route('admin.monitoring.detail', $laporan->nomor_tiket));
    }

    public function test_seeder_unit_account_membuat_akun_untuk_setiap_unit(): void
    {
        $this->seed(UnitAccountSeeder::class);
        $this->seed(UnitAccountSeeder::class);

        $definitions = UnitAccountSeeder::definitions();
        $petugasUsers = User::where('role', 'Petugas Unit')->get();

        $this->assertCount(count($definitions), $petugasUsers);
        $this->assertCount(25, $petugasUsers);
        $this->assertDatabaseHas('users', [
            'email' => 'unit.logistik@telkomuniversity.ac.id',
            'fakultas' => 'Direktorat Logistik dan Aset',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'unit@telkomuniversity.ac.id',
        ]);

        foreach ($definitions as $entry) {
            $user = $petugasUsers->firstWhere('email', $entry['email']);
            $this->assertNotNull($user, "Akun untuk {$entry['unit']} harus ada.");
            $this->assertSame($entry['unit'], $user->unit);
            $this->assertSame('Aktif', $user->status_akun);
            $this->assertTrue(Hash::check('12345678', $user->password));
        }

        // Pastikan penyaluran ke salah satu unit Prodi hanya menotifikasi petugas unit terkait
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $admin = User::factory()->create(['role' => 'Admin SSC']);
        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00099',
            'kategori' => 'akademik',
            'kategori_label' => 'Akademik',
            'judul' => 'Jadwal KRS Bentrok',
            'deskripsi' => 'Jadwal mata kuliah wajib semester ini bentrok.',
            'lokasi' => 'Gedung A',
            'status' => 'Menunggu Verifikasi',
            'prioritas' => 'Sedang',
            'unit_penangan' => 'Belum Ditentukan',
            'progres' => 10,
        ]);

        $this->actingAs($admin)->postJson(route('admin.verifikasi.submit', $laporan->nomor_tiket), [
            'action' => 'teruskan',
            'prioritas' => 'Sedang',
            'unit_penangan' => 'Program Studi S1 Sistem Informasi',
            'catatan' => 'Mohon disesuaikan oleh prodi.',
        ])->assertStatus(200);

        $petugasSi = User::where('email', 'unit.si@telkomuniversity.ac.id')->firstOrFail();
        $petugasTi = User::where('email', 'unit.ti@telkomuniversity.ac.id')->firstOrFail();

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $petugasSi->id,
            'tiket' => 'CV-2026-00099',
        ]);
        $this->assertDatabaseMissing('app_notifications', [
            'user_id' => $petugasTi->id,
            'tiket' => 'CV-2026-00099',
        ]);
    }

    public function test_notifikasi_petugas_unit_dapat_diklik_ke_halaman_tangani_laporan(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'Mahasiswa']);
        $petugas = User::factory()->create([
            'role' => 'Petugas Unit',
            'prodi' => 'Logistik & Sarana',
            'fakultas' => 'Direktorat Logistik dan Aset',
            'status_akun' => 'Aktif',
        ]);

        $laporan = Laporan::create([
            'user_id' => $mahasiswa->id,
            'nomor_tiket' => 'CV-2026-00105',
            'kategori' => 'sarana_prasarana',
            'kategori_label' => 'Sarana dan prasarana kampus',
            'judul' => 'Kursi Rusak',
            'deskripsi' => 'Kursi patah di ruang kelas.',
            'lokasi' => '2.04',
            'status' => 'Diteruskan',
            'prioritas' => 'Tinggi',
            'unit_penangan' => 'Direktorat Logistik dan Aset',
            'progres' => 20,
        ]);

        $notif = AppNotification::create([
            'user_id' => $petugas->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'forward',
            'title' => 'Laporan baru ditugaskan',
            'desc' => 'CV-2026-00105 (Kursi Rusak) telah diteruskan ke Direktorat Logistik dan Aset.',
            'is_read' => false,
        ]);

        $notifPage = $this->actingAs($petugas)->get(route('unit.notifikasi'));
        $notifPage->assertStatus(200);
        $notifPage->assertSee('<a', false);
        $notifPage->assertSee(':href="item.url"', false);
        $notifPage->assertSee('notifikasi', false);
        $notifPage->assertSee('baca', false);

        $this->actingAs($petugas)
            ->get(route('notifikasi.read', $notif->id))
            ->assertRedirect(route('unit.laporan-ditugaskan.detail', $laporan->nomor_tiket));

        $this->assertTrue($notif->fresh()->is_read);

        $notif2 = AppNotification::create([
            'user_id' => $petugas->id,
            'laporan_id' => $laporan->id,
            'tiket' => $laporan->nomor_tiket,
            'icon' => 'response',
            'title' => 'Tanggapan baru',
            'desc' => 'Pelapor mengirim tanggapan baru.',
            'is_read' => false,
        ]);

        $this->actingAs($petugas)
            ->postJson(route('notifikasi.markAll'))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertTrue($notif2->fresh()->is_read);
    }
}
