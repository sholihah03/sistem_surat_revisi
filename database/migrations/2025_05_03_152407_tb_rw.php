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
        Schema::create('tb_rw', function (Blueprint $table) {
            $table->id('id_rw');
            $table->string('no_rw', 225);
            $table->string('nama_lengkap_rw', 225);
            $table->string('email_rw', 225);
            $table->string('no_hp_rw', 225);
            $table->string('password', 225);
            $table->string('profile_rw')->nullable();
            $table->text('ttd_digital')->nullable();
            $table->text('ttd_digital_bersih')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rw');
    }
};
