<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agenda
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('lokasi');
            $table->string('kategori');
            $table->timestamps();
        });

        // 2. Album Galeri
        Schema::create('album_galeri', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 3. Foto Galeri
        Schema::create('foto_galeri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('album_galeri')->onDelete('cascade');
            $table->string('file_path');
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 4. Video Galeri
        Schema::create('video_galeri', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('url'); // Youtube or other external link
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_galeri');
        Schema::dropIfExists('foto_galeri');
        Schema::dropIfExists('album_galeri');
        Schema::dropIfExists('agenda');
    }
};
