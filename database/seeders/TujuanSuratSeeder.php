<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TujuanSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_tujuan_surat')->insert([
            [
                'nama_tujuan' => 'Keamanan dan Ketertiban',
                'nomor_surat' => '300',
                'deskripsi' => 'Tujuan berkaitan dengan keamanan lingkungan, penertiban warga dan ketertiban umum.'
            ],
            [
                'nama_tujuan' => 'Kesejahteraan Rakyat',
                'nomor_surat' => '400',
                'deskripsi' => 'Surat yang berkaitan dengan bantuan, subsidi, dan layanan pemerintah untuk meningkatkan kesejahteraan keluarga pra sejahtera dan masyarakat kurang mampu.'
            ],
            [
                'nama_tujuan' => 'Keluarga Pra Sejahtera',
                'nomor_surat' => '401',
                'deskripsi' => 'Pengajuan surat untuk keluarga dengan status pra sejahtera.'
            ],
            [
                'nama_tujuan' => 'Subsidi',
                'nomor_surat' => '403',
                'deskripsi' => 'Surat keterangan untuk pengajuan atau pencairan subsidi pemerintah.'
            ],
            [
                'nama_tujuan' => 'Program Raskin',
                'nomor_surat' => '404',
                'deskripsi' => 'Surat untuk pengajuan bantuan beras miskin (raskin).'
            ],
            [
                'nama_tujuan' => 'Bantuan Langsung Tunai',
                'nomor_surat' => '405',
                'deskripsi' => 'Surat keterangan untuk menerima bantuan langsung tunai (BLT).'
            ],
            [
                'nama_tujuan' => 'Kesehatan',
                'nomor_surat' => '440',
                'deskripsi' => 'Surat pengantar untuk keperluan pelayanan kesehatan.'
            ],
            [
                'nama_tujuan' => 'Pendidikan',
                'nomor_surat' => '420',
                'deskripsi' => 'Surat pengantar atau rekomendasi terkait pendidikan, seperti beasiswa.'
            ],
            [
                'nama_tujuan' => 'Kerawanan Sosial',
                'nomor_surat' => '463.3',
                'deskripsi' => 'Tujuan untuk kasus kerawanan sosial di masyarakat.'
            ],
            [
                'nama_tujuan' => 'Bantuan Sosial',
                'nomor_surat' => '460',
                'deskripsi' => 'Pengajuan surat untuk menerima bantuan sosial.'
            ],
            [
                'nama_tujuan' => 'Pendaftaran Penduduk',
                'nomor_surat' => '474',
                'deskripsi' => 'Surat untuk keperluan pendaftaran data kependudukan.'
            ],
            [
                'nama_tujuan' => 'Kelahiran',
                'nomor_surat' => '474.1',
                'deskripsi' => 'Pengajuan surat pengantar untuk keperluan akta kelahiran.'
            ],
            [
                'nama_tujuan' => 'Perkawinan/Perceraian/Rujuk',
                'nomor_surat' => '474.2',
                'deskripsi' => 'Surat pengantar untuk keperluan pencatatan pernikahan, perceraian, atau rujuk.'
            ],
            [
                'nama_tujuan' => 'Kematian',
                'nomor_surat' => '474.3',
                'deskripsi' => 'Surat pengantar laporan atau pencatatan kematian.'
            ],
            [
                'nama_tujuan' => 'Kartu Tanda Penduduk (KTP)',
                'nomor_surat' => '474.4',
                'deskripsi' => 'Surat pengantar untuk pembuatan atau pengurusan KTP.'
            ],
            [
                'nama_tujuan' => 'Perpindahan Penduduk',
                'nomor_surat' => '475',
                'deskripsi' => 'Surat pengantar untuk pindah domisili atau mutasi penduduk.'
            ],
            [
                'nama_tujuan' => 'Perdagangan',
                'nomor_surat' => '510',
                'deskripsi' => 'Surat keterangan usaha atau kegiatan perdagangan.'
            ],
            [
                'nama_tujuan' => 'Perijinan, Kios, PKL',
                'nomor_surat' => '510.16',
                'deskripsi' => 'Surat pengantar atau pernyataan untuk izin usaha kaki lima atau kios.'
            ],
            [
                'nama_tujuan' => 'Penerapan Sembako',
                'nomor_surat' => '510.21',
                'deskripsi' => 'Surat pengantar untuk distribusi atau akses terhadap sembako.'
            ],
            [
                'nama_tujuan' => 'Akses Usaha Perdagangan',
                'nomor_surat' => '517',
                'deskripsi' => 'Surat pengantar untuk dukungan akses usaha perdagangan.'
            ],
            [
                'nama_tujuan' => 'Koperasi',
                'nomor_surat' => '518',
                'deskripsi' => 'Pengajuan surat untuk mendirikan atau mengelola koperasi.'
            ],
            [
                'nama_tujuan' => 'Pertanian',
                'nomor_surat' => '520',
                'deskripsi' => 'Surat pengantar untuk program bantuan atau kegiatan pertanian.'
            ],
            [
                'nama_tujuan' => 'Tenaga Kerja',
                'nomor_surat' => '560',
                'deskripsi' => 'Surat keterangan tenaga kerja atau lamaran kerja.'
            ],
            [
                'nama_tujuan' => 'Persyaratan TKI/TKW',
                'nomor_surat' => '560.9',
                'deskripsi' => 'Surat pengantar untuk keperluan kerja ke luar negeri (TKI/TKW).'
            ],
        ]);
    }
}
