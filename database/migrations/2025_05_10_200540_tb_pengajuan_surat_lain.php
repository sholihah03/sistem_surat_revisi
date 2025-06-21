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
        Schema::create('tb_pengajuan_surat_lain', function (Blueprint $table) {
            $table->id('id_pengajuan_surat_lain');
            $table->foreignId('warga_id')->constrained('tb_wargas', 'id_warga')->onDelete('cascade');
            $table->foreignId('scan_kk_id')->constrained('tb_scan_kk', 'id_scan')->onDelete('cascade');
            $table->string('nomor_surat_pengajuan_lain', 225)->nullable();
            $table->enum('status_rt_pengajuan_lain', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->enum('status_rw_pengajuan_lain', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->string('tujuan_manual', 225)->nullable();
            $table->string('tempat_lahir_pengaju_lain', 225)->nullable();
            $table->date('tanggal_lahir_pengaju_lain')->nullable();
            $table->string('pekerjaan_pengaju_lain', 255)->nullable();
            $table->string('agama_pengaju_lain', 225)->nullable();
            $table->text('alasan_penolakan_pengajuan_lain')->nullable();
            $table->enum('status_perkawinan_pengaju_lain', ['kawin', 'belum', 'janda', 'duda'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pengajuan_surat_lain');
    }
};
