<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_tiket')->unique()->index();
            $table->string('kategori'); // sarana_prasarana, kemahasiswaan, ssc, akademik, administrasi_keuangan, lainnya
            $table->string('kategori_label')->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->string('berkas_bukti')->nullable();
            $table->string('berkas_nama')->nullable();
            $table->string('berkas_ukuran')->nullable();
            $table->boolean('is_anonim')->default(false);
            $table->string('status')->default('Menunggu Verifikasi'); // Menunggu Verifikasi, Diteruskan, Diproses, Selesai, Ditolak
            $table->string('prioritas')->default('Normal'); // Prioritas Rendah, Normal, Prioritas Sedang, Prioritas Tinggi
            $table->string('unit_penangan')->default('Belum Ditentukan');
            $table->unsignedTinyInteger('progres')->default(0); // 0 - 100
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};

