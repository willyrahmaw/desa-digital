<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('apbdes', 'sub_kategori')) {
            Schema::table('apbdes', function (Blueprint $table) {
                $table->string('sub_kategori')->nullable()->after('tipe');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('apbdes', 'sub_kategori')) {
            Schema::table('apbdes', function (Blueprint $table) {
                $table->dropColumn('sub_kategori');
            });
        }
    }
};
