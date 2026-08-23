<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pengaduan', 'nomor_tiket')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                $table->string('nomor_tiket', 50)->nullable()->unique()->after('id');
                $table->string('telepon', 20)->nullable()->after('pelapor_nik');
                $table->string('email', 100)->nullable()->after('telepon');
                $table->string('lokasi', 200)->nullable()->after('isi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pengaduan', 'nomor_tiket')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                $table->dropColumn(['nomor_tiket', 'telepon', 'email', 'lokasi']);
            });
        }
    }
};
