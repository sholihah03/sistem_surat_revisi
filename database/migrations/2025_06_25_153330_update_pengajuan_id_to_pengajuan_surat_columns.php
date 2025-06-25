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
        // Tabel RT
        Schema::table('tb_hasil_surat_ttd_rt', function (Blueprint $table) {
            if (Schema::hasColumn('tb_hasil_surat_ttd_rt', 'pengajuan_id')) {
                $table->dropColumn('pengajuan_id');
            }

            $table->unsignedBigInteger('pengajuan_surat_id')->nullable()->after('id_hasil_surat_ttd_rt');
            $table->unsignedBigInteger('pengajuan_surat_lain_id')->nullable()->after('pengajuan_surat_id');

            $table->foreign('pengajuan_surat_id')->references('id_pengajuan_surat')->on('tb_pengajuan_surat')->onDelete('set null');
            $table->foreign('pengajuan_surat_lain_id')->references('id_pengajuan_surat_lain')->on('tb_pengajuan_surat_lain')->onDelete('set null');
        });

        // Tabel RW
        Schema::table('tb_hasil_surat_ttd_rw', function (Blueprint $table) {
            if (Schema::hasColumn('tb_hasil_surat_ttd_rw', 'pengajuan_id')) {
                $table->dropColumn('pengajuan_id');
            }

            $table->unsignedBigInteger('pengajuan_surat_id')->nullable()->after('id_hasil_surat_ttd_rw');
            $table->unsignedBigInteger('pengajuan_surat_lain_id')->nullable()->after('pengajuan_surat_id');

            $table->foreign('pengajuan_surat_id')->references('id_pengajuan_surat')->on('tb_pengajuan_surat')->onDelete('set null');
            $table->foreign('pengajuan_surat_lain_id')->references('id_pengajuan_surat_lain')->on('tb_pengajuan_surat_lain')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback untuk tabel RT
        Schema::table('tb_hasil_surat_ttd_rt', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_surat_id']);
            $table->dropForeign(['pengajuan_surat_lain_id']);
            $table->dropColumn(['pengajuan_surat_id', 'pengajuan_surat_lain_id']);

            $table->unsignedBigInteger('pengajuan_id')->nullable()->after('jenis');
        });

        // Rollback untuk tabel RW
        Schema::table('tb_hasil_surat_ttd_rw', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_surat_id']);
            $table->dropForeign(['pengajuan_surat_lain_id']);
            $table->dropColumn(['pengajuan_surat_id', 'pengajuan_surat_lain_id']);

            $table->unsignedBigInteger('pengajuan_id')->nullable()->after('jenis');
        });
    }
};
