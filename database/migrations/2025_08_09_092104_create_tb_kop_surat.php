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
        Schema::create('tb_kop_surat', function (Blueprint $table) {
            $table->id('id_kop_surat');
            $table->string('nama_jalan');
            $table->string('no_kantor');
            $table->string('no_telepon');
            $table->string('kode_pos');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kop_surat');
    }
};
