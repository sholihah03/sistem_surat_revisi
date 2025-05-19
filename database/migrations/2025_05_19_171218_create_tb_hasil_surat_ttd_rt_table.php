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
        Schema::create('tb_hasil_surat_ttd_rt', function (Blueprint $table) {
            $table->id('id_hasil_surat_ttd_rt');
            $table->string('jenis'); // 'biasa' atau 'lain'
            $table->unsignedBigInteger('pengajuan_id'); // ID dari pengajuan surat
            $table->text('file_surat'); // path atau isi base64 dari surat PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_hasil_surat_ttd_rt');
    }
};
