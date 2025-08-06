<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_wargas', function (Blueprint $table) {
            $table->id('id_warga');
            $table->foreignId('scan_kk_id')->nullable()->constrained('tb_scan_kk', 'id_scan')->onDelete('cascade');
            $table->foreignId('rt_id')->nullable()->constrained('tb_rt', 'id_rt')->onDelete('cascade');
            $table->foreignId('rw_id')->nullable()->constrained('tb_rw', 'id_rw')->onDelete('cascade');
            $table->string('nama_lengkap', 225);
            $table->string('email', 225);
            $table->string('no_kk', 16)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('no_hp', 225);
            $table->string('otp_code', 225)->nullable();
            $table->boolean('status_verifikasi')->default(false);
            $table->string('remember_token', 225)->nullable();
            $table->string('password', 225)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_wargas');
    }
};
