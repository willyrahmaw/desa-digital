<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Activity Log
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('user')->onDelete('set null');
            $table->string('action');
            $table->text('description');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        // 2. Login Log
        Schema::create('login_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('user')->onDelete('set null');
            $table->string('email');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->enum('status', ['success', 'failed']);
            $table->timestamp('login_at')->useCurrent();
        });

        // 3. Pengaturan
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group'); // 'profil', 'surat', 'website'
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
        Schema::dropIfExists('login_log');
        Schema::dropIfExists('activity_log');
    }
};
