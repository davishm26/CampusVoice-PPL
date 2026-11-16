<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UnitAccountSeeder extends Seeder
{
    /**
     * Daftar seluruh unit penangan resmi CampusVoice beserta akun Petugas Unit-nya.
     *
     * @return array<int, array<string, string>>
     */
    public static function definitions(): array
    {
        return [
            // Program Studi (11 unit)
            [
                'unit' => 'Program Studi S1 Sistem Informasi',
                'sub_unit' => 'Prodi S1 Sistem Informasi',
                'name' => 'Petugas S1 Sistem Informasi',
                'email' => 'unit.si@telkomuniversity.ac.id',
                'nim' => '992101',
                'phone' => '081122330101',
            ],
            [
                'unit' => 'Program Studi S1 Teknologi Informasi',
                'sub_unit' => 'Prodi S1 Teknologi Informasi',
                'name' => 'Petugas S1 Teknologi Informasi',
                'email' => 'unit.ti@telkomuniversity.ac.id',
                'nim' => '992102',
                'phone' => '081122330102',
            ],
            [
                'unit' => 'Program Studi S1 Teknik Informatika',
                'sub_unit' => 'Prodi S1 Teknik Informatika',
                'name' => 'Petugas S1 Teknik Informatika',
                'email' => 'unit.if@telkomuniversity.ac.id',
                'nim' => '992103',
                'phone' => '081122330103',
            ],
            [
                'unit' => 'Program Studi S1 Rekayasa Perangkat Lunak',
                'sub_unit' => 'Prodi S1 Rekayasa Perangkat Lunak',
                'name' => 'Petugas S1 RPL',
                'email' => 'unit.rpl@telkomuniversity.ac.id',
                'nim' => '992104',
                'phone' => '081122330104',
            ],
            [
                'unit' => 'Program Studi S1 Sains Data',
                'sub_unit' => 'Prodi S1 Sains Data',
                'name' => 'Petugas S1 Sains Data',
                'email' => 'unit.sd@telkomuniversity.ac.id',
                'nim' => '992105',
                'phone' => '081122330105',
            ],
            [
                'unit' => 'Program Studi S1 Teknik Elektro',
                'sub_unit' => 'Prodi S1 Teknik Elektro',
                'name' => 'Petugas S1 Teknik Elektro',
                'email' => 'unit.te@telkomuniversity.ac.id',
                'nim' => '992106',
                'phone' => '081122330106',
            ],
            [
                'unit' => 'Program Studi S1 Teknik Industri',
                'sub_unit' => 'Prodi S1 Teknik Industri',
                'name' => 'Petugas S1 Teknik Industri',
                'email' => 'unit.industri@telkomuniversity.ac.id',
                'nim' => '992107',
                'phone' => '081122330107',
            ],
            [
                'unit' => 'Program Studi S1 Teknik Komputer',
                'sub_unit' => 'Prodi S1 Teknik Komputer',
                'name' => 'Petugas S1 Teknik Komputer',
                'email' => 'unit.tk@telkomuniversity.ac.id',
                'nim' => '992108',
                'phone' => '081122330108',
            ],
            [
                'unit' => 'Program Studi S1 Teknik Logistik',
                'sub_unit' => 'Prodi S1 Teknik Logistik',
                'name' => 'Petugas S1 Teknik Logistik',
                'email' => 'unit.tl@telkomuniversity.ac.id',
                'nim' => '992109',
                'phone' => '081122330109',
            ],
            [
                'unit' => 'Program Studi S1 Bisnis Digital',
                'sub_unit' => 'Prodi S1 Bisnis Digital',
                'name' => 'Petugas S1 Bisnis Digital',
                'email' => 'unit.bd@telkomuniversity.ac.id',
                'nim' => '992110',
                'phone' => '081122330110',
            ],
            [
                'unit' => 'Program Studi S1 Teknik Telekomunikasi',
                'sub_unit' => 'Prodi S1 Teknik Telekomunikasi',
                'name' => 'Petugas S1 Teknik Telekomunikasi',
                'email' => 'unit.tt@telkomuniversity.ac.id',
                'nim' => '992111',
                'phone' => '081122330111',
            ],

            // Direktorat (13 unit)
            [
                'unit' => 'Direktorat Sekretariat & Perencanaan Strategis',
                'sub_unit' => 'Sekretariat & Perencanaan Strategis',
                'name' => 'Petugas Sekretariat & Strategis',
                'email' => 'unit.sekretariat@telkomuniversity.ac.id',
                'nim' => '992201',
                'phone' => '081122330201',
            ],
            [
                'unit' => 'Direktorat Bandung Techno Park',
                'sub_unit' => 'Bandung Techno Park',
                'name' => 'Petugas Bandung Techno Park',
                'email' => 'unit.btp@telkomuniversity.ac.id',
                'nim' => '992202',
                'phone' => '081122330202',
            ],
            [
                'unit' => 'Direktorat Akademik',
                'sub_unit' => 'Layanan Akademik',
                'name' => 'Petugas Akademik',
                'email' => 'unit.akademik@telkomuniversity.ac.id',
                'nim' => '992203',
                'phone' => '081122330203',
            ],
            [
                'unit' => 'Direktorat Pasca Sarjana dan Advance Learning',
                'sub_unit' => 'Pasca Sarjana & Advance Learning',
                'name' => 'Petugas Pasca Sarjana',
                'email' => 'unit.pascasarjana@telkomuniversity.ac.id',
                'nim' => '992204',
                'phone' => '081122330204',
            ],
            [
                'unit' => 'Direktorat Logistik dan Aset',
                'sub_unit' => 'Logistik & Sarana',
                'name' => 'Budi Santoso',
                'email' => 'unit.logistik@telkomuniversity.ac.id',
                'nim' => '992002',
                'phone' => '081122334466',
            ],
            [
                'unit' => 'Direktorat Keuangan',
                'sub_unit' => 'Administrasi & Keuangan',
                'name' => 'Petugas Keuangan',
                'email' => 'unit.keuangan@telkomuniversity.ac.id',
                'nim' => '992206',
                'phone' => '081122330206',
            ],
            [
                'unit' => 'Direktorat Sumber Daya Manusia',
                'sub_unit' => 'Sumber Daya Manusia',
                'name' => 'Petugas SDM',
                'email' => 'unit.sdm@telkomuniversity.ac.id',
                'nim' => '992207',
                'phone' => '081122330207',
            ],
            [
                'unit' => 'Direktorat Pusat Teknologi Informasi',
                'sub_unit' => 'Pusat Teknologi Informasi',
                'name' => 'Petugas PuTI',
                'email' => 'unit.puti@telkomuniversity.ac.id',
                'nim' => '992208',
                'phone' => '081122330208',
            ],
            [
                'unit' => 'Direktorat Pemasaran & Admisi',
                'sub_unit' => 'Pemasaran & Admisi',
                'name' => 'Petugas Pemasaran & Admisi',
                'email' => 'unit.admisi@telkomuniversity.ac.id',
                'nim' => '992209',
                'phone' => '081122330209',
            ],
            [
                'unit' => 'Direktorat Pengembangan Karir, Alumni, & Endowment',
                'sub_unit' => 'Pengembangan Karir & Alumni',
                'name' => 'Petugas Karir & Alumni',
                'email' => 'unit.karir@telkomuniversity.ac.id',
                'nim' => '992210',
                'phone' => '081122330210',
            ],
            [
                'unit' => 'Direktorat Kemahasiswaan',
                'sub_unit' => 'Layanan Kemahasiswaan',
                'name' => 'Petugas Kemahasiswaan',
                'email' => 'unit.kemahasiswaan@telkomuniversity.ac.id',
                'nim' => '992211',
                'phone' => '081122330211',
            ],
            [
                'unit' => 'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
                'sub_unit' => 'Kerjasama Strategis & KUI',
                'name' => 'Petugas Kerjasama & KUI',
                'email' => 'unit.kerjasama@telkomuniversity.ac.id',
                'nim' => '992212',
                'phone' => '081122330212',
            ],
            [
                'unit' => 'Direktorat Penelitian dan Pengabdian Masyarakat',
                'sub_unit' => 'Penelitian & Pengabdian Masyarakat',
                'name' => 'Petugas PPM',
                'email' => 'unit.ppm@telkomuniversity.ac.id',
                'nim' => '992213',
                'phone' => '081122330213',
            ],

            // Satpras (1 unit)
            [
                'unit' => 'SATPRAS (Sarana Prasarana)',
                'sub_unit' => 'Sarana & Prasarana',
                'name' => 'Petugas SATPRAS',
                'email' => 'unit.satpras@telkomuniversity.ac.id',
                'nim' => '992301',
                'phone' => '081122330301',
            ],
        ];
    }

    /**
     * Buat atau perbarui akun Petugas Unit untuk setiap unit penangan resmi.
     */
    public function run(): void
    {
        $password = Hash::make('12345678');
        $now = Carbon::now();
        $units = self::definitions();

        $legacyUnitUser = User::where('email', 'unit@telkomuniversity.ac.id')->first();
        if ($legacyUnitUser) {
            if (! User::where('email', 'unit.logistik@telkomuniversity.ac.id')->exists()) {
                $legacyUnitUser->update(['email' => 'unit.logistik@telkomuniversity.ac.id']);
            } else {
                $legacyUnitUser->delete();
            }
        }

        foreach ($units as $entry) {
            User::updateOrCreate(
                ['email' => $entry['email']],
                [
                    'name' => $entry['name'],
                    'nim' => $entry['nim'],
                    'prodi' => $entry['sub_unit'],
                    'fakultas' => $entry['unit'],
                    'phone' => $entry['phone'],
                    'role' => 'Petugas Unit',
                    'status_akun' => 'Aktif',
                    'password' => $password,
                    'email_verified_at' => $now,
                ]
            );
        }

        $this->command?->info('✅ ' . count($units) . ' akun Petugas Unit (Program Studi, Direktorat, & SATPRAS) berhasil di-seed (password: 12345678).');
    }
}
