<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_surat', function (Blueprint $table) {
            $table->string('format_nomor_surat')->default('[NOMOR]/[KODE]/[BULAN]/[TAHUN]');
            $table->string('kop_line_1')->nullable();
            $table->string('kop_line_2')->nullable();
            $table->string('kop_line_3')->nullable();
            $table->string('kop_alamat')->nullable();
            $table->string('kop_kontak')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('template_surat', function (Blueprint $table) {
            $table->dropColumn([
                'format_nomor_surat',
                'kop_line_1',
                'kop_line_2',
                'kop_line_3',
                'kop_alamat',
                'kop_kontak'
            ]);
        });
    }
};
