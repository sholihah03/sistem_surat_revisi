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
        Schema::table('tb_hasil_surat_ttd_rw', function (Blueprint $table) {
            $table->string('token', 100)->nullable()->unique()->after('file_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_hasil_surat_ttd_rw', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
