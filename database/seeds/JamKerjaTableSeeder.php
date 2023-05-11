<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JamKerjaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jam_kerja')->insert([
            'N'   => '1',
            'hari'      => 'Senin',
            'mulai_absen'     => '07:00',
            'mulai_kerja'     => '07:30',
            'selesai_kerja' =>'17:00',
        ]);
        DB::table('jam_kerja')->insert([
            'N'   => '2',
            'hari'      => 'Selasa',
            'mulai_absen'     => '07:00',
            'mulai_kerja'     => '07:30',
            'selesai_kerja' =>'17:00',
        ]);
        DB::table('jam_kerja')->insert([
            'N'   => '3',
            'hari'      => 'Rabu',
            'mulai_absen'     => '07:00',
            'mulai_kerja'     => '07:30',
            'selesai_kerja' =>'17:00',
        ]);
        DB::table('jam_kerja')->insert([
            'N'   => '4',
            'hari'      => 'Kamis',
            'mulai_absen'     => '07:00',
            'mulai_kerja'     => '07:30',
            'selesai_kerja' =>'17:00',
        ]);
        DB::table('jam_kerja')->insert([
            'N'   => '5',
            'hari'      => 'Jumat',
            'mulai_absen'     => '07:00',
            'mulai_kerja'     => '07:30',
            'selesai_kerja' =>'17:00',
        ]);

    }
}
