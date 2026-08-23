<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Klasifikasi Surat
        Schema::create('klasifikasi_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 50)->unique();
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'arsip'])->default('aktif');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 2. Pengaturan Penomoran
        Schema::create('pengaturan_penomoran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_format');
            $table->string('jenis_surat')->unique(); // e.g. "SKTM", "SKU", "SKD", "SKCK" etc.
            $table->string('format_nomor');
            $table->string('separator', 5)->default('/');
            $table->enum('reset_nomor', ['none', 'yearly', 'monthly', 'daily', 'manual'])->default('none');
            $table->integer('digit_nomor')->default(3);
            $table->string('awalan')->nullable();
            $table->string('akhiran')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Document Sequence Tracker
        Schema::create('document_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('format_id')->constrained('pengaturan_penomoran')->onDelete('cascade');
            $table->string('sequence_key'); // e.g. "global", "2026", "2026-07"
            $table->integer('current_value')->default(0);
            $table->timestamps();

            $table->unique(['format_id', 'sequence_key']);
        });

        // 4. Riwayat Nomor Surat
        Schema::create('riwayat_nomor_surat', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nomor_surat')->unique();
            $table->string('jenis_surat');
            $table->foreignId('template_id')->nullable()->constrained('template_surat')->onDelete('set null');
            $table->string('penduduk_nik', 255)->nullable();
            $table->foreign('penduduk_nik')->references('nik')->on('penduduk')->onDelete('set null');
            $table->date('tanggal');
            $table->foreignId('petugas_id')->nullable()->constrained('user')->onDelete('set null');
            $table->enum('status', ['draft', 'digunakan', 'dibatalkan', 'dicetak', 'diarsipkan'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_nomor_surat');
        Schema::dropIfExists('document_sequences');
        Schema::dropIfExists('pengaturan_penomoran');
        Schema::dropIfExists('klasifikasi_surat');
    }
};
