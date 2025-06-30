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
            // Ubah warga_id menjadi nullable
            $table->foreignId('warga_id')->nullable()->change();

            // Tambah kolom rt_id dan rw_id
            $table->foreignId('rt_id')
                ->nullable()
                ->after('warga_id')
                ->constrained('tb_rt', 'id_rt')
                ->onDelete('cascade');

            $table->foreignId('rw_id')
                ->nullable()
                ->after('rt_id')
                ->constrained('tb_rw', 'id_rw')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_otp', function (Blueprint $table) {
            $table->dropForeign(['rt_id']);
            $table->dropColumn('rt_id');

            $table->dropForeign(['rw_id']);
            $table->dropColumn('rw_id');

            // Ubah kembali warga_id menjadi NOT NULL (jika sebelumnya wajib)
            $table->foreignId('warga_id')->nullable(false)->change();
        });
    }
};
