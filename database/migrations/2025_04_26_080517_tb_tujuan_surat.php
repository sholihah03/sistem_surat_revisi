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
        Schema::create('tb_tujuan_surat', function (Blueprint $table) {
            $table->id('id_tujuan_surat');
            $table->string('nama_tujuan', 225);
            $table->text('deskripsi')->nullable();
            $table->string('nomor_surat', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tujuan_surat');
    }
};
