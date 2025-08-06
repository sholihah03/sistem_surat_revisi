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
            $table->string('no_rt', 225);
            $table->string('nama_lengkap_rt', 225);
            $table->string('email_rt', 225);
            $table->string('no_hp_rt', 225);
            $table->string('password', 225);
            $table->string('profile_rt')->nullable();
            $table->text('ttd_digital')->nullable();
            $table->text('ttd_digital_bersih')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rt');
    }
};
