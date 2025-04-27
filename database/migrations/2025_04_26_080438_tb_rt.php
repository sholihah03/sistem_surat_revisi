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
        Schema::create('tb_rt', function (Blueprint $table) {
            $table->id('id_rt');
            $table->foreignId('rw_id')->constrained('tb_rw', 'id_rw')->onDelete('cascade');
            $table->string('username', 225)->unique();
            $table->string('nama_lengkap_rt', 225);
            $table->string('password', 225);
            $table->text('ttd_digital')->nullable();
            $table->text('ttd_digital_bersih')->nullable();
            $table->boolean('login')->default(false);
            $table->integer('verifikasiSurat')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rt');
    }
};
