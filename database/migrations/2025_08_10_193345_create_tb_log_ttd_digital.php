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
        Schema::create('tb_log_ttd_digital', function (Blueprint $table) {
            $table->bigIncrements('id_log_ttd');

            // siapa/jenis penandatangan
            $table->enum('jenis_penandatangan', ['rt', 'rw', 'warga', 'system'])->nullable()->comment('Jenis akun yang melakukan aksi');
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('warga_id')->nullable();

            // referensi dokumen / pengajuan
            $table->unsignedBigInteger('pengajuan_surat_id')->nullable();
            $table->unsignedBigInteger('pengajuan_surat_lain_id')->nullable();

            // aksi yang dicatat
            $table->string('aksi', 50)->comment('upload_ttd, edit_ttd, sign_dokumen, verifikasi, download, revoke, dll');
            $table->string('file_ttd', 255)->nullable()->comment('path/nama file ttd jika relevan');
            $table->string('hash_dokumen', 128)->nullable()->comment('sha256 hash dokumen pada saat aksi');
            $table->string('token_verifikasi', 150)->nullable()->comment('token publik / token verifikasi terkait');

            // info teknis
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('lokasi_approx', 255)->nullable()->comment('opsional: lokasi GPS / kota bila ada');
            $table->enum('status_verifikasi', ['valid','invalid','unknown'])->default('unknown')->nullable();

            // metadata fleksibel (contoh: browser fingerprint, nama file asli, alasan penolakan)
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->json('metadata')->nullable();
            } else {
                $table->text('metadata')->nullable();
            }

            $table->timestamps();

            // indexes & foreign keys (sesuai konvensi db kamu)
            $table->index(['jenis_penandatangan']);
            $table->index(['pengajuan_surat_id']);
            $table->index(['pengajuan_surat_lain_id']);
            $table->index(['token_verifikasi']);

            // FK constraints (set null on delete to keep log)
            $table->foreign('rt_id')->references('id_rt')->on('tb_rt')->onDelete('SET NULL');
            $table->foreign('rw_id')->references('id_rw')->on('tb_rw')->onDelete('SET NULL');
            $table->foreign('warga_id')->references('id_warga')->on('tb_wargas')->onDelete('SET NULL');
            $table->foreign('pengajuan_surat_id')->references('id_pengajuan_surat')->on('tb_pengajuan_surat')->onDelete('SET NULL');
            $table->foreign('pengajuan_surat_lain_id')->references('id_pengajuan_surat_lain')->on('tb_pengajuan_surat_lain')->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('tb_log_ttd_digital', function (Blueprint $table) {
            $table->dropForeign(['rt_id']);
            $table->dropForeign(['rw_id']);
            $table->dropForeign(['warga_id']);
            $table->dropForeign(['pengajuan_surat_id']);
            $table->dropForeign(['pengajuan_surat_lain_id']);
        });
        Schema::dropIfExists('tb_log_ttd_digital');
    }
};
