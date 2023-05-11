<?php

use Illuminate\Database\Seeder;

class UnitkerjasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // BIDANG PENGELOLAAN TIK DAN PERSANDIAN
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Aplikasi dan Integrasi Sistem Informasi',
            'initial'       => 'AISI'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Infrastruktur dan Jaringan Komunikasi',
            'initial'       => 'IJK'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Persandian dan Keamanan Informasi',
            'initial'       => 'PKI'
        ]);
        
        // BIDANG PENGELOLAAN INFORMASI KOMUNIKASI DAN KEHUMASAN
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Pengelolaan Opini dan Informasi Publik',
            'initial'       => 'POIP'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Media dan Kemitraan Komunikasi Publik',
            'initial'       => 'MKKP'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Kehumasan',
            'initial'       => 'HUMAS'
        ]);
        
        // BIDANG SMART CITY STATISTIK DAN LPSE
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Pengembangan SDM TIK dan Kerjasama Smart City',
            'initial'       => 'TIK'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi Pengelolaan Data dan Statistik',
            'initial'       => 'PDS'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Seksi LPSE',
            'initial'       => 'LPSE'
        ]);
        
        // SEKRETARIAT
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Sub. Bagian Perencanaan',
            'initial'       => 'SBP'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Sub. Bagian Keuangan',
            'initial'       => 'SBK'
        ]);
        
        DB::table('unitkerjas')->insert([
            'unit_kerja'    => 'Sub. Bagian Umum dan Kepegawaian',
            'initial'       => 'SBUK'
        ]);
    }
}
