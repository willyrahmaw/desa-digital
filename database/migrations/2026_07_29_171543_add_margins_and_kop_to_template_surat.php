<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_surat', function (Blueprint $table) {
            $table->integer('margin_top')->default(20);
            $table->integer('margin_bottom')->default(20);
            $table->integer('margin_left')->default(20);
            $table->integer('margin_right')->default(20);
            $table->boolean('dengan_kop')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('template_surat', function (Blueprint $table) {
            $table->dropColumn(['margin_top', 'margin_bottom', 'margin_left', 'margin_right', 'dengan_kop']);
        });
    }
};
