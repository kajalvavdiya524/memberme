<?php
namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $admin = new User;
        $admin->first_name = "Brent";
        $admin->last_name = "Thomson";
        $admin->email = "admin@validate.co.nz";
        $admin->contact_no = null;
        $admin->password = bcrypt("membermebrent");
        $admin->user_type_id = \App\base\IUserType::SUPER_ADMIN;
        $admin->status_id = \App\base\IStatus::ACTIVE;
        $admin->verify = \App\base\IStatus::ACTIVE;
        $admin->activate = \App\base\IStatus::ACTIVE;
        $admin->api_token = Str::random(60);
        $admin->save();
        $admin->roles()->attach(Role::find(\App\base\IUserType::SUPER_ADMIN));
    }
}
