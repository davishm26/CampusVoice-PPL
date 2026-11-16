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
        if (!Schema::hasTable('unit_penangan')) {
            Schema::create('unit_penangan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_unit')->unique();
                $table->enum('tipe', ['Direktorat', 'Program Studi']);
                $table->string('status')->default('Aktif');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_penangan');
    }
};
