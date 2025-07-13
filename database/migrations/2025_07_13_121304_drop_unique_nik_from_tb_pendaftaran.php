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
        Schema::table('tb_pendaftaran', function (Blueprint $table) {
            // Drop unique index pada kolom email
            $table->dropUnique('tb_pendaftaran_nik_unique');

            // (Opsional) kalau kamu mau tambahkan index biasa:
            // $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pendaftaran', function (Blueprint $table) {
            // Tambahkan kembali unique constraint
            $table->unique('nik');
        });
    }
};
