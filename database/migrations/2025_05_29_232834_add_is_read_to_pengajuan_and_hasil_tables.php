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
        Schema::table('tb_pengajuan_surat', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('status');
        });

        Schema::table('tb_pengajuan_surat_lain', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('status_pengajuan_lain');
        });

        Schema::table('tb_hasil_surat_ttd_rw', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('file_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });

        Schema::table('tb_pengajuan_surat_lain', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });

        Schema::table('tb_hasil_surat_ttd_rw', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};
