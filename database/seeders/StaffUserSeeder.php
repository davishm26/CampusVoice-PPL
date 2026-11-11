<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    /**
     * Buat atau perbarui akun Admin SSC, Petugas Unit, dan Super Admin di tabel users.
     */
    public function run(): void
    {
        $password = Hash::make('12345678');
        $now = Carbon::now();

        $accounts = [
            [
                'email'             => 'admin.ssc@telkomuniversity.ac.id',
                'name'              => 'Siti Rahmawati',
                'nim'               => '991001',
                'prodi'             => 'Layanan Mahasiswa',
                'fakultas'          => 'Student Service Center',
                'phone'             => '081122334455',
                'role'              => 'Admin SSC',
                'status_akun'       => 'Aktif',
                'password'          => $password,
                'email_verified_at' => $now,
            ],
            [
                'email'             => 'unit@telkomuniversity.ac.id',
                'name'              => 'Budi Santoso',
                'nim'               => '992002',
                'prodi'             => 'Logistik & Sarana',
                'fakultas'          => 'Direktorat Logistik dan Aset',
                'phone'             => '081122334466',
                'role'              => 'Petugas Unit',
                'status_akun'       => 'Aktif',
                'password'          => $password,
                'email_verified_at' => $now,
            ],
            [
                'email'             => 'superadmin@telkomuniversity.ac.id',
                'name'              => 'Rizky Pratama',
                'nim'               => '993003',
                'prodi'             => 'Pusat Teknologi Informasi',
                'fakultas'          => 'Direktorat Pusat Teknologi Informasi',
                'phone'             => '081122334477',
                'role'              => 'Super Admin',
                'status_akun'       => 'Aktif',
                'password'          => $password,
                'email_verified_at' => $now,
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                $account
            );
        }

        $this->command?->info('✅ Akun Admin SSC, Petugas Unit, dan Super Admin berhasil disimpan di database (password: 12345678).');
    }
}

