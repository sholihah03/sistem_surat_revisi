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
        Schema::create('pengajuan_surat_lain', function (Blueprint $table) {
            $table->id('id_pengajuan_surat_lain');
            $table->foreignId('keluarga_id')->constrained('tb_anggota_keluarga', 'id_keluarga')->onDelete('cascade');
            $table->foreignId('tujuan_surat_id')->constrained('tb_tujuan_surat', 'id_tujuan_surat')->onDelete('cascade');
            $table->string('nomor_surat_pengajuan_lain', 225);
            $table->enum('status_pengajuan_lain', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->string('pekerjaan_pengaju_lain', 8)->nullable();
            $table->string('agama_pengaju_lain', 225)->nullable();
            $table->text('alasan_penolakan_pengajuan_lain')->nullable();
            $table->enum('status_perkawinan_pengaju_lain', ['kawin', 'belum', 'janda', 'duda'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_lain');
    }
};
