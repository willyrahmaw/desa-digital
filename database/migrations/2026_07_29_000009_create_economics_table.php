<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. UMKM Kategori
        Schema::create('umkm_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. UMKM Pelaku
        Schema::create('umkm_pelaku', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_hp');
            $table->text('alamat');
            $table->foreignId('user_id')->nullable()->constrained('user')->onDelete('set null');
            $table->timestamps();
        });

        // 3. UMKM Produk
        Schema::create('umkm_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaku_id')->constrained('umkm_pelaku')->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi');
            $table->decimal('harga', 15, 2);
            $table->string('foto')->nullable();
            $table->foreignId('kategori_id')->constrained('umkm_kategori')->onDelete('restrict');
            $table->string('whatsapp'); // Contact for order
            $table->timestamps();
        });

        // 4. BUMDes Unit Usaha
        Schema::create('bumdes_unit', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->string('penanggung_jawab');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // 5. BUMDes Laporan
        Schema::create('bumdes_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('bumdes_unit')->onDelete('cascade');
            $table->enum('jenis', ['keuangan', 'kegiatan']);
            $table->string('judul');
            $table->string('file_path');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 6. APBDes Budgeting
        Schema::create('apbdes', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->enum('tipe', ['pendapatan', 'belanja', 'pembiayaan']);
            $table->string('kategori'); // e.g., Dana Desa, Alokasi Dana Desa, Belanja Pegawai
            $table->decimal('jumlah', 15, 2);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbdes');
        Schema::dropIfExists('bumdes_laporan');
        Schema::dropIfExists('bumdes_unit');
        Schema::dropIfExists('umkm_produk');
        Schema::dropIfExists('umkm_pelaku');
        Schema::dropIfExists('umkm_kategori');
    }
};
