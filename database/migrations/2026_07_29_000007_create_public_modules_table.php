<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pengaduan
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('pelapor_nik', 255);
            $table->foreign('pelapor_nik')->references('nik')->on('penduduk')->onDelete('cascade');
            $table->string('judul');
            $table->string('kategori');
            $table->text('isi');
            $table->enum('status', ['pending', 'process', 'resolved', 'rejected'])->default('pending');
            $table->text('balasan')->nullable();
            $table->string('lampiran')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Kategori Berita
        Schema::create('kategori_berita', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Berita
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('isi');
            $table->string('cover_image')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('views')->default(0);
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->foreignId('kategori_berita_id')->constrained('kategori_berita')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. Komentar
        Schema::create('komentar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita')->onDelete('cascade');
            $table->string('nama');
            $table->string('email');
            $table->text('isi');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar');
        Schema::dropIfExists('berita');
        Schema::dropIfExists('kategori_berita');
        Schema::dropIfExists('pengaduan');
    }
};
