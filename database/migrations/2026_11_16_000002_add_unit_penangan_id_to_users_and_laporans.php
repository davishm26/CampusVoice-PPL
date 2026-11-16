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
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'unit_penangan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('unit_penangan_id')->nullable()->after('fakultas')->constrained('unit_penangan')->nullOnDelete();
            });
        }

        if (Schema::hasTable('laporans')) {
            Schema::table('laporans', function (Blueprint $table) {
                if (!Schema::hasColumn('laporans', 'unit_penangan_id')) {
                    $table->foreignId('unit_penangan_id')->nullable()->after('unit_penangan')->constrained('unit_penangan')->nullOnDelete();
                }
                if (!Schema::hasColumn('laporans', 'catatan_internal')) {
                    $table->text('catatan_internal')->nullable()->after('progres');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('laporans')) {
            Schema::table('laporans', function (Blueprint $table) {
                if (Schema::hasColumn('laporans', 'unit_penangan_id')) {
                    $table->dropForeign(['unit_penangan_id']);
                    $table->dropColumn('unit_penangan_id');
                }
                if (Schema::hasColumn('laporans', 'catatan_internal')) {
                    $table->dropColumn('catatan_internal');
                }
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'unit_penangan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['unit_penangan_id']);
                $table->dropColumn('unit_penangan_id');
            });
        }
    }
};
