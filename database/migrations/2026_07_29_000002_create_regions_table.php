<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dusun', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        Schema::create('rw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('cascade');
            $table->string('nomor');
            $table->timestamps();
            $table->unique(['dusun_id', 'nomor']);
        });

        Schema::create('rt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rw_id')->constrained('rw')->onDelete('cascade');
            $table->string('nomor');
            $table->timestamps();
            $table->unique(['rw_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rt');
        Schema::dropIfExists('rw');
        Schema::dropIfExists('dusun');
    }
};
