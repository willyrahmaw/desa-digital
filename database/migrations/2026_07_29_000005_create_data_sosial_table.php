<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_sosial', function (Blueprint $table) {
            $table->id();
            $table->string('penduduk_nik', 255)->unique();
            $table->foreign('penduduk_nik')->references('nik')->on('penduduk')->onDelete('cascade');
            $table->boolean('dtks')->default(false);
            $table->boolean('pkh')->default(false);
            $table->boolean('bpnt')->default(false);
            $table->boolean('pbi')->default(false);
            $table->boolean('rtlh')->default(false);
            $table->string('disabilitas')->nullable();
            $table->boolean('lansia')->default(false);
            $table->boolean('yatim_piatu')->default(false);
            $table->enum('status_ekonomi', ['Rendah', 'Menengah', 'Tinggi'])->default('Menengah');
            $table->boolean('layak_sktm')->default(false);
            $table->date('tanggal_verifikasi')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('user')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sosial');
    }
};
