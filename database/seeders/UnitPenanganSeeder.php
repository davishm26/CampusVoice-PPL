<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\UnitPenangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnitPenanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $direktoratList = [
            'Direktorat Sekretariat & Perencanaan Strategis',
            'Direktorat Bandung Techno Park',
            'Direktorat Akademik',
            'Direktorat Pasca Sarjana dan Advance Learning',
            'Direktorat Logistik dan Aset',
            'Direktorat Keuangan',
            'Direktorat Sumber Daya Manusia',
            'Direktorat Pusat Teknologi Informasi',
            'Direktorat Pemasaran & Admisi',
            'Direktorat Pengembangan Karir, Alumni, & Endowment',
            'Direktorat Kemahasiswaan',
            'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
            'Direktorat Penelitian dan Pengabdian Masyarakat',
        ];

        $prodiList = [
            'Program Studi S1 Sistem Informasi',
            'Program Studi S1 Teknologi Informasi',
            'Program Studi S1 Teknik Informatika',
            'Program Studi S1 Rekayasa Perangkat Lunak',
            'Program Studi S1 Sains Data',
            'Program Studi S1 Teknik Elektro',
            'Program Studi S1 Teknik Industri',
            'Program Studi S1 Teknik Komputer',
            'Program Studi S1 Teknik Logistik',
            'Program Studi S1 Bisnis Digital',
            'Program Studi S1 Teknik Telekomunikasi',
        ];

        // Disable foreign key checks for safe truncation
        Schema::disableForeignKeyConstraints();
        UnitPenangan::truncate();
        Schema::enableForeignKeyConstraints();

        $now = now();

        foreach ($direktoratList as $nama) {
            UnitPenangan::create([
                'nama_unit' => $nama,
                'tipe' => 'Direktorat',
                'status' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($prodiList as $nama) {
            UnitPenangan::create([
                'nama_unit' => $nama,
                'tipe' => 'Program Studi',
                'status' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Hubungkan Petugas Unit yang ada di tabel users ke unit_penangan_id
        $defaultUnit = UnitPenangan::where('nama_unit', 'Direktorat Logistik dan Aset')->first()
            ?? UnitPenangan::where('tipe', 'Direktorat')->first();

        $petugasUsers = User::where('role', 'Petugas Unit')->get();
        foreach ($petugasUsers as $petugas) {
            $unitMatch = null;
            if ($petugas->fakultas) {
                $unitMatch = UnitPenangan::where('nama_unit', $petugas->fakultas)->first()
                    ?? UnitPenangan::where('nama_unit', 'like', '%' . $petugas->fakultas . '%')->first();
            }
            if (!$unitMatch && $petugas->prodi) {
                $unitMatch = UnitPenangan::where('nama_unit', 'like', '%' . $petugas->prodi . '%')->first();
            }
            if ($unitMatch) {
                $petugas->update([
                    'unit_penangan_id' => $unitMatch->id,
                    'fakultas' => $unitMatch->nama_unit,
                ]);
            } elseif (!$petugas->fakultas && $defaultUnit) {
                $petugas->update([
                    'unit_penangan_id' => $defaultUnit->id,
                    'fakultas' => $defaultUnit->nama_unit,
                ]);
            }
        }

        // Sinkronkan unit_penangan_id pada laporan yang sudah ada
        $allUnits = UnitPenangan::all();
        $laporans = Laporan::whereNotNull('unit_penangan')->get();
        foreach ($laporans as $lap) {
            $matched = $allUnits->first(function ($u) use ($lap) {
                return strcasecmp($u->nama_unit, $lap->unit_penangan) === 0
                    || str_contains(strtolower($u->nama_unit), strtolower($lap->unit_penangan))
                    || str_contains(strtolower($lap->unit_penangan), strtolower($u->nama_unit));
            });
            if ($matched) {
                $lap->update([
                    'unit_penangan_id' => $matched->id,
                    'unit_penangan' => $matched->nama_unit,
                ]);
            }
        }
    }
}
