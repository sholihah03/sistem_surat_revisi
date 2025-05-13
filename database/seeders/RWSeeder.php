<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_rw')->insert([
            [
                'no_rw' => '007',
                'nama_lengkap_rw' => 'Ketua RW 007',
                'email_rw' => 'lihasholihah418@gmail.com',
                'no_hp_rw' => '081234567890',
                'password' => Hash::make('rw_007'),
                'profile_rw' => null,
                'ttd_digital' => null,
                'ttd_digital_bersih' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
