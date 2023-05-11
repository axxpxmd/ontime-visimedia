<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id'   => '1',
            'uker_id'   => '1',
            'atasan_id' => '2',
            'lokasi_id' => '["1"]',
            'nama'      => 'Administrator',
            // 'nrp'       => '123456789',
            'username'  => 'admin',
            'foto'      => 'default.jpg',
            'password'  => Hash::make('123456789')
        ]);

        DB::table('users')->insert([
            'role_id'   => '2',
            'uker_id'   => '1',
            'atasan_id' => '0',
            'lokasi_id' => '["1"]',
            'nama'      => 'atasan',
            // 'nrp'       => '112233445',
            'username'  => 'atasan',
            'foto'      => 'default.jpg',
            'password'  => Hash::make('atasan')
        ]);

        DB::table('users')->insert([
            'role_id'   => '3',
            'uker_id'   => '1',
            'atasan_id' => '2',
            'lokasi_id' =>'["1"]',
            'nama'      => 'pegawai',
            // 'nrp'       => '987654321',
            'username'  => 'pegawai',
            'foto'      => 'default.jpg',
            'password'  => Hash::make('pegawai')
        ]);
    }
}
