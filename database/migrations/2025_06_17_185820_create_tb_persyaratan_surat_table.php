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
        Schema::create('tb_persyaratan_surat', function (Blueprint $table) {
            $table->id('id_persyaratan_surat');
            $table->foreignId('tujuan_surat_id')->constrained('tb_tujuan_surat', 'id_tujuan_surat')->onDelete('cascade');
            $table->string('nama_persyaratan');
            $table->enum('keterangan', ['kawin', 'belum', 'janda', 'duda'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_persyaratan_surat');
    }
};
