<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(TimezoneSeeder::class);
        $this->call(\Database\Seeders\OrganizationSeeder::class);
        $this->call(RecordSeeder::class);
        $this->call(CountrySeeder::class);
    }
}
