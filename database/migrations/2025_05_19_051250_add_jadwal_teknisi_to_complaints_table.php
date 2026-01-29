<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->date('jadwal_teknisi')->nullable();
        $table->boolean('user_selesai')->default(false);
    });
}

public function down()
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->dropColumn(['jadwal_teknisi', 'user_selesai']);
    });
}

};
