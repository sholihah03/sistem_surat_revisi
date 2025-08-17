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
        Schema::table('tb_otp', function (Blueprint $table) {
            $table->unsignedBigInteger('warga_id')->nullable()->after('pendaftaran_id');
            $table->unsignedBigInteger('rt_id')->nullable()->after('warga_id');
            $table->unsignedBigInteger('rw_id')->nullable()->after('rt_id');

            // Kalau mau sekalian tambahin foreign key
            // $table->foreign('warga_id')->references('id_warga')->on('tb_wargas')->onDelete('cascade');
            // $table->foreign('rt_id')->references('id_rt')->on('tb_rt')->onDelete('cascade');
            // $table->foreign('rw_id')->references('id_rw')->on('tb_rw')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_otp', function (Blueprint $table) {
            // Kalau pakai foreign key jangan lupa drop dulu
            // $table->dropForeign(['warga_id']);
            // $table->dropForeign(['rt_id']);
            // $table->dropForeign(['rw_id']);

            $table->dropColumn(['warga_id', 'rt_id', 'rw_id']);
        });
    }
};
