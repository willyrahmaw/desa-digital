<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kartu Keluarga
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->string('no_kk', 255)->primary();
            $table->text('alamat');
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('restrict');
            $table->foreignId('rw_id')->constrained('rw')->onDelete('restrict');
            $table->foreignId('rt_id')->constrained('rt')->onDelete('restrict');
            $table->string('kepala_keluarga_nik', 255)->nullable(); // Resolved in application level to avoid cycle
            $table->timestamps();
        });

        // 2. Penduduk
        Schema::create('penduduk', function (Blueprint $table) {
            $table->string('nik', 255)->primary();
            $table->string('no_kk', 255)->nullable()->constrained('kartu_keluarga', 'no_kk')->onDelete('set null');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('agama_id')->constrained('agama')->onDelete('restrict');
            $table->foreignId('status_kawin_id')->constrained('status_kawin')->onDelete('restrict');
            $table->foreignId('pendidikan_id')->constrained('pendidikan')->onDelete('restrict');
            $table->foreignId('pekerjaan_id')->constrained('pekerjaan')->onDelete('restrict');
            $table->text('alamat');
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('restrict');
            $table->foreignId('rw_id')->constrained('rw')->onDelete('restrict');
            $table->foreignId('rt_id')->constrained('rt')->onDelete('restrict');
            $table->string('nomor_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->string('qr_code')->nullable();
            $table->foreignId('status_tinggal_id')->constrained('status_tinggal')->onDelete('restrict');
            $table->foreignId('kewarganegaraan_id')->constrained('kewarganegaraan')->onDelete('restrict');
            $table->foreignId('golongan_darah_id')->constrained('golongan_darah')->onDelete('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk');
        Schema::dropIfExists('kartu_keluarga');
    }
};
