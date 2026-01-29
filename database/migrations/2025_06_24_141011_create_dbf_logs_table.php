<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dbf_logs', function (Blueprint $table) {
             $table->id();
    $table->string('action'); // insert, update, delete
    $table->string('table');  // contoh: 'aduan'
    $table->json('data');     // field untuk dikirim ke DBF
    $table->string('status')->default('pending'); // pending, done, failed
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dbf_logs');
    }
};
