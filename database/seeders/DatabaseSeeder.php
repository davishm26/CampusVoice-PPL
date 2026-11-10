<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\Laporan;
use App\Models\LaporanTanggapan;
use App\Models\LaporanTracking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean student accounts (0 initial reports).
     */
    public function run(): void
    {
        $fakultas = 'Direktorat Kampus Surabaya';
        $password = Hash::make('password');
        $now = Carbon::now();

        $students = [
            [
                'name' => 'Davista Hana',
                'email' => 'davistahana@student.telkomuniversity.ac.id',
                'nim' => '102062400113',
                'prodi' => 'S1 Sistem Informasi',
                'fakultas' => $fakultas,
                'phone' => '0812 3456 7890',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmadfauzi@student.telkomuniversity.ac.id',
                'nim' => '102062400114',
                'prodi' => 'S1 Informatika',
                'fakultas' => $fakultas,
                'phone' => '0812 9876 5432',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'sitinurhaliza@student.telkomuniversity.ac.id',
                'nim' => '102062400115',
                'prodi' => 'S1 Rekayasa Perangkat Lunak',
                'fakultas' => $fakultas,
                'phone' => '0813 1122 3344',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budisantoso@student.telkomuniversity.ac.id',
                'nim' => '102062400116',
                'prodi' => 'S1 Sistem Informasi',
                'fakultas' => $fakultas,
                'phone' => '0813 5566 7788',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Rian Pratama',
                'email' => 'rianpratama@student.telkomuniversity.ac.id',
                'nim' => '102062400117',
                'prodi' => 'S1 Teknologi Informasi',
                'fakultas' => $fakultas,
                'phone' => '0812 4455 6677',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Putri Wulandari',
                'email' => 'putriwulandari@student.telkomuniversity.ac.id',
                'nim' => '102062400118',
                'prodi' => 'S1 Bisnis Digital',
                'fakultas' => $fakultas,
                'phone' => '0812 7788 9900',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Dimas Anggara',
                'email' => 'dimasanggara@student.telkomuniversity.ac.id',
                'nim' => '102062400119',
                'prodi' => 'S1 Teknik Telekomunikasi',
                'fakultas' => $fakultas,
                'phone' => '0813 8899 0011',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Anisa Rahmawati',
                'email' => 'anisarahmawati@student.telkomuniversity.ac.id',
                'nim' => '102062400120',
                'prodi' => 'S1 Sains Data',
                'fakultas' => $fakultas,
                'phone' => '0812 1234 9876',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Fajar Nugraha',
                'email' => 'fajarnugraha@student.telkomuniversity.ac.id',
                'nim' => '102062400121',
                'prodi' => 'S1 Teknik Komputer',
                'fakultas' => $fakultas,
                'phone' => '0813 4321 8765',
                'role' => 'Mahasiswa',
            ],
            [
                'name' => 'Nadia Safitri',
                'email' => 'nadiasafitri@student.telkomuniversity.ac.id',
                'nim' => '102062400122',
                'prodi' => 'S1 Desain Komunikasi Visual',
                'fakultas' => $fakultas,
                'phone' => '0812 6789 1234',
                'role' => 'Mahasiswa',
            ],
        ];

        Schema::disableForeignKeyConstraints();
        LaporanTanggapan::truncate();
        LaporanTracking::truncate();
        AppNotification::truncate();
        Laporan::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'nim' => $student['nim'],
                    'prodi' => $student['prodi'],
                    'fakultas' => $student['fakultas'],
                    'phone' => $student['phone'],
                    'role' => $student['role'],
                    'password' => $password,
                    'email_verified_at' => $now,
                ]
            );
        }
    }
}
