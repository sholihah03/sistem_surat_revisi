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
        Schema::create('tb_kadaluwarsa', function (Blueprint $table) {
            $table->id('id_kadaluwarsa');
            $table->string('rt_alamat')->nullable();
            $table->string('rw_alamat')->nullable();
            $table->string('nama_kepala_keluarga', 225);
            $table->string('path_file_kk', 225);
            $table->string('nama_lengkap');
            $table->string('no_kk', 16);
            $table->string('nik', 16)->unique();
            $table->string('no_hp', 13);
            $table->string('email')->unique();
            $table->string('nama_jalan', 225);
            $table->string('kelurahan', 225);
            $table->string('kecamatan', 225);
            $table->string('kabupaten_kota', 225);
            $table->string('provinsi', 225)->nullable();
            $table->string('kode_pos', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kadaluwarsa');
    }
};
