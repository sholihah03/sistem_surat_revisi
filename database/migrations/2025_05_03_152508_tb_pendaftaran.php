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
        Schema::create('tb_pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->foreignId('scan_id')->nullable()->constrained('tb_scan_kk', 'id_scan')->onDelete('cascade');
            $table->foreignId('rt_id')->nullable()->constrained('tb_rt', 'id_rt')->onDelete('cascade');
            $table->foreignId('rw_id')->nullable()->constrained('tb_rw', 'id_rw')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('no_kk', 16);
            $table->string('nik', 16)->unique();
            $table->string('no_hp', 13);
            $table->string('email')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pendaftaran');
    }
};
