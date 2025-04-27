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
        Schema::create('tb_anggota_keluarga', function (Blueprint $table) {
            $table->id('id_keluarga');
            $table->foreignId('scan_kk_id')->constrained('tb_scan_kk', 'id_scan')->onDelete('cascade');
            $table->string('nama_lengkap_anggota', 225);
            $table->string('nik', 16);
            $table->string('jenis_kelamin_anggota', 225);
            $table->string('tempat_lahir_anggota', 225);
            $table->string('tanggal_lahir_anggota', 225);
            $table->string('hubungan_keluarga', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_anggota_keluarga');
    }



};
