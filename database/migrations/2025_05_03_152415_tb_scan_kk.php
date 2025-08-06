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
        Schema::create('tb_scan_kk', function (Blueprint $table) {
            $table->id('id_scan');
            $table->foreignId('alamat_id')->nullable()->constrained('tb_alamat', 'id_alamat')->onDelete('cascade');
            // $table->foreignId('rw_id')->nullable()->constrained('tb_rw', 'id_rw')->onDelete('cascade');
            // $table->foreignId('rt_id')->nullable()->constrained('tb_rt', 'id_rt')->onDelete('cascade');
            $table->string('nama_kepala_keluarga', 225);
            $table->string('no_kk_scan', 225);
            $table->string('path_file_kk', 225);
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak']);
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_scan_kk');
    }
};
