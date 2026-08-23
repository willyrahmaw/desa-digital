<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_sosial', function (Blueprint $table) {
            $table->tinyInteger('desil')->unsigned()->nullable()->after('status_ekonomi')->comment('Desil Kemensos 1-10');
        });
    }

    public function down(): void
    {
        Schema::table('data_sosial', function (Blueprint $table) {
            $table->dropColumn('desil');
        });
    }
};
