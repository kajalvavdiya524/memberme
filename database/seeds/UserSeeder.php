<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->first_name = "Test User";
        $user->last_name = "Test User";
        $user->email = "test@memberme.me";
        $user->contact_no = null;
        $user->password = bcrypt("adminn");
        $user->user_type_id = \App\base\IUserType::MANAGER;
        $user->status_id = \App\base\IStatus::ACTIVE;
        $user->verify = \App\base\IStatus::ACTIVE;
        $user->activate = \App\base\IStatus::ACTIVE;
        $user->api_token = Str::random(60);
        $user->save();

        $user->roles()->save(Role::find(\App\base\IUserType::MANAGER), ['organization_id' => 15550]);
        $user->roles()->save(Role::find(\App\base\IUserType::MEMBER), ['organization_id' => 15551]);
        $user->roles()->save(Role::find(\App\base\IUserType::MANAGER), ['organization_id' => 15552]);

        $user->organizations()->save(\App\Organization::find(15550));
        $user->organizations()->save(\App\Organization::find(15551));
        $user->organizations()->save(\App\Organization::find(15552));
    }
}
