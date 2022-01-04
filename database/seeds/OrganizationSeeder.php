<?php
namespace Database\Seeders;
use App\base\IStatus;
use App\Timezone;
use Config;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organization= new \App\Organization();
        $organization->name = 'Memberme';
        $organization->id = Config::get('MEMBERME_ID');
        $organization->current = IStatus::ACTIVE;
        $organization->password = Str::random(10);
        $organization->api_token = Str::random(60);
        $organization->scanner_token  = Str::random(60);
        $timezone = Timezone::where('timezone' , Timezone::PACIFIC_AUCKLAND)->first();  //setting timezone for this organization
        $organization->timezone_id = array_get($timezone,'id');
        $organization->status = IStatus::INACTIVE;
        $organization->save();
    }
}
