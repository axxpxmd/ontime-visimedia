<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'nama_lokasi'   => 'Balaikota',
            'latitude'      => '-6.322552996240972',
            'longitude'     => '106.70785840594863',
        ]);
        
        DB::table('locations')->insert([
            'nama_lokasi'   => 'Cilenggang',
            'latitude'      => '-6.303091596407066',
            'longitude'     => '106.66267390523751',
        ]);
    }
}
