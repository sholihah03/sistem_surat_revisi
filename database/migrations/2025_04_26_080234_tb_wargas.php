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
        Schema::create('tb_wargas', function (Blueprint $table) {
            $table->id('id_warga');
            $table->foreignId('scan_kk_id')->nullable()->constrained('tb_scan_kk', 'id_scan')->onDelete('cascade');
            $table->string('nama_lengkap', 225);
            $table->string('email', 225);
            $table->string('no_kk', 225);
            $table->string('no_hp', 225);
            $table->string('rt', 8);
            $table->string('rw', 8);
            $table->string('otp_code', 225)->nullable();
            $table->dateTime('otp_expired_at')->nullable();
            $table->boolean('status_verifikasi')->default(false);
            $table->string('remember_token', 225)->nullable();
            $table->string('password', 225)->nullable();
            $table->boolean('login')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_wargas');
    }
};
