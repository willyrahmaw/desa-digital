<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Template Surat
        Schema::create('template_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori_surat'); // SKTM, Domisili, Keterangan Usaha, Kelahiran, Kematian, Belum Menikah, Kehilangan, Pindah, Pengantar
            $table->longText('content'); // Fabric.js JSON format
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Surat Generated
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nomor_surat');
            $table->string('jenis_surat'); // Backup/lookup string
            $table->string('penduduk_nik', 255);
            $table->foreign('penduduk_nik')->references('nik')->on('penduduk')->onDelete('restrict');
            $table->foreignId('template_id')->constrained('template_surat')->onDelete('restrict');
            $table->string('file_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'signed'])->default('pending');
            $table->date('tanggal_terbit');
            $table->foreignId('petugas_id')->nullable()->constrained('user')->onDelete('set null');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('signed_by_perangkat_id')->nullable()->constrained('perangkat_desa')->onDelete('set null');
            $table->json('meta_data')->nullable(); // For dynamic placeholder custom inputs
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
        Schema::dropIfExists('template_surat');
    }
};
