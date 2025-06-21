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
        Schema::create('tb_pengajuan_surat', function (Blueprint $table) {
            $table->id('id_pengajuan_surat');
            $table->foreignId('warga_id')->constrained('tb_wargas', 'id_warga')->onDelete('cascade');
            $table->foreignId('tujuan_surat_id')->constrained('tb_tujuan_surat', 'id_tujuan_surat')->onDelete('cascade');
            $table->foreignId('scan_kk_id')->constrained('tb_scan_kk', 'id_scan')->onDelete('cascade');
            $table->enum('status_rt', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->enum('status_rw', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->string('tempat_lahir', 225)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan', 225)->nullable();
            $table->string('agama', 225)->nullable();
            $table->text('alasan_penolakan_pengajuan')->nullable();
            $table->enum('status_perkawinan', ['kawin', 'belum', 'janda', 'duda'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pengajuan_surat');
    }
};
