<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_alamat', function (Blueprint $table) {
            $table->id('id_alamat');
            $table->string('nama_jalan', 225);
            $table->string('rt_alamat', 225);
            $table->string('rw_alamat', 225);
            $table->string('kelurahan', 225);
            $table->string('kecamatan', 225);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_alamat');
    }
};
