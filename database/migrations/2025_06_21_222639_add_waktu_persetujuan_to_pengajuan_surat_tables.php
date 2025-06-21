<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tb_pengajuan_surat', function (Blueprint $table) {
            $table->timestamp('waktu_persetujuan_rt')->nullable()->after('status_rt');
            $table->timestamp('waktu_persetujuan_rw')->nullable()->after('status_rw');
        });

        Schema::table('tb_pengajuan_surat_lain', function (Blueprint $table) {
            $table->timestamp('waktu_persetujuan_rt_lain')->nullable()->after('status_rt_pengajuan_lain');
            $table->timestamp('waktu_persetujuan_rw_lain')->nullable()->after('status_rw_pengajuan_lain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tb_pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn(['waktu_persetujuan_rt', 'waktu_persetujuan_rw']);
        });

        Schema::table('tb_pengajuan_surat_lain', function (Blueprint $table) {
            $table->dropColumn(['waktu_persetujuan_rt_lain', 'waktu_persetujuan_rw_lain']);
        });
    }
};
