<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membersihkan user lama dan memasukkan 5 akun mahasiswa resmi CampusVoice.
     */
    public function run(): void
    {
        // 1. Bersihkan seluruh data user lama agar tidak ada duplikat/data sampah
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $defaultPassword = Hash::make('12345678');
        $now = Carbon::now();
        $fakultas = 'Direktorat Kampus Surabaya';

        // 2. Daftar 5 Akun Mahasiswa Resmi CampusVoice
        $mahasiswa = [
            [
                'name'          => 'Dimas Arya Irwansyah',
                'email'         => 'dimasaryairwansyah@student.telkomuniversity.ac.id',
                'nim'           => '102062400113',
                'prodi'         => 'S1 Sistem Informasi',
                'fakultas'      => $fakultas,
                'phone'         => '081234567890',
                'role'          => 'Mahasiswa',
                'status_akun'   => 'Aktif',
                'password'      => $defaultPassword,
                'email_verified_at' => $now,
            ],
            [
                'name'          => 'Davis Tahana May Giantama',
                'email'         => 'davistahanamaygiantama@student.telkomuniversity.ac.id',
                'nim'           => '102062400214',
                'prodi'         => 'S1 Sistem Informasi',
                'fakultas'      => $fakultas,
                'phone'         => '082345678901',
                'role'          => 'Mahasiswa',
                'status_akun'   => 'Aktif',
                'password'      => $defaultPassword,
                'email_verified_at' => $now,
            ],
            [
                'name'          => 'Revan Permanah Akim Saputra',
                'email'         => 'revanpermanahakimsaputra@student.telkomuniversity.ac.id',
                'nim'           => '102062400315',
                'prodi'         => 'S1 Sistem Informasi',
                'fakultas'      => $fakultas,
                'phone'         => '083456789012',
                'role'          => 'Mahasiswa',
                'status_akun'   => 'Aktif',
                'password'      => $defaultPassword,
                'email_verified_at' => $now,
            ],
            [
                'name'          => 'Jonathan Rionaldo Pratama',
                'email'         => 'jonathanrionaldopratama@student.telkomuniversity.ac.id',
                'nim'           => '102062400416',
                'prodi'         => 'S1 Sistem Informasi',
                'fakultas'      => $fakultas,
                'phone'         => '084567890123',
                'role'          => 'Mahasiswa',
                'status_akun'   => 'Aktif',
                'password'      => $defaultPassword,
                'email_verified_at' => $now,
            ],
            [
                'name'          => 'RR Khaila Widya Loka',
                'email'         => 'rrkhailawidyaloka@student.telkomuniversity.ac.id',
                'nim'           => '102062400517',
                'prodi'         => 'S1 Sistem Informasi',
                'fakultas'      => $fakultas,
                'phone'         => '085678901234',
                'role'          => 'Mahasiswa',
                'status_akun'   => 'Aktif',
                'password'      => $defaultPassword,
                'email_verified_at' => $now,
            ],
        ];

        foreach ($mahasiswa as $data) {
            User::create($data);
        }

        // 3. Tambahan Akun Staf (Admin SSC & Petugas Unit) untuk operasional pengaduan
        User::create([
            'name'              => 'Admin SSC',
            'email'             => 'admin.ssc@telkomuniversity.ac.id',
            'nim'               => '991001',
            'prodi'             => 'Layanan Mahasiswa',
            'fakultas'          => 'Student Service Center',
            'phone'             => '081122334455',
            'role'              => 'Admin SSC',
            'status_akun'       => 'Aktif',
            'password'          => $defaultPassword,
            'email_verified_at' => $now,
        ]);

        User::create([
            'name'              => 'Budi Santoso',
            'email'             => 'budi.santoso@telkomuniversity.ac.id',
            'nim'               => '992002',
            'prodi'             => 'Logistik & Sarana',
            'fakultas'          => 'Direktorat Logistik dan Aset',
            'phone'             => '081122334466',
            'role'              => 'Petugas Unit',
            'status_akun'       => 'Aktif',
            'password'          => $defaultPassword,
            'email_verified_at' => $now,
        ]);

        $this->command->info('✅ Berhasil membersihkan user lama dan men-seed 5 akun mahasiswa (password: 12345678).');
    }
}

