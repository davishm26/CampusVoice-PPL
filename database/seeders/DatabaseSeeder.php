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
        $this->call([
            UserSeeder::class,
        ]);

        Schema::disableForeignKeyConstraints();
        LaporanTanggapan::truncate();
        LaporanTracking::truncate();
        AppNotification::truncate();
        Laporan::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('✅ Database CampusVoice berhasil di-seed (clean state).');
    }
}
