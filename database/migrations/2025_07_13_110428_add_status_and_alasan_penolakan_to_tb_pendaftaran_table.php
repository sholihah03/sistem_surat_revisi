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
            $table->enum('status', ['pending', 'ditolak'])->nullable()->after('rw_id')->default('pending');
            $table->string('alasan_penolakan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pendaftaran', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('alasan_penolakan');
        });
    }
};
