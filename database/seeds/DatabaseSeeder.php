<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LocationsTableSeeder::class,
            RolesTableSeeder::class,
            UnitkerjasTableSeeder::class,
            UsersTableSeeder::class,
            JamKerjaTableSeeder::class,
        ]);
    }
}
