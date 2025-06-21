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
        Schema::create('tb_pengajuan_persyaratan', function (Blueprint $table) {
            $table->id('id_pengajuan_persyaratan');
            $table->foreignId('pengajuan_surat_id')->constrained('tb_pengajuan_surat', 'id_pengajuan_surat')->onDelete('cascade');
            $table->foreignId('persyaratan_surat_id')->constrained('tb_persyaratan_surat', 'id_persyaratan_surat')->onDelete('cascade');
            $table->string('dokumen'); // path file upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengajuan_persyaratan');
    }
};
