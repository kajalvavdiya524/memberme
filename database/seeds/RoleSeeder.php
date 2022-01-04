<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* @var $role Role */
        $role = new Role();
        $role->id = \App\base\IUserType::SUPER_ADMIN;
        $role->name = 'Super Admin';
        $role->save();

        $role = new Role();
        $role->id = \App\base\IUserType::ADMINISTRATOR;
        $role->name = 'Administrator';
        $role->save();

        $role = new Role();
        $role->id = \App\base\IUserType::MANAGER;
        $role->name = 'Manager';
        $role->save();

        $role = new Role();
        $role->id = \App\base\IUserType::MEMBER;
        $role->name = 'Member';
        $role->save();


        $role = new Role();
        $role->id = \App\base\IUserType::CONTRIBUTER;
        $role->name = 'Contributor';
        $role->save();


        $role = new Role();
        $role->id = \App\base\IUserType::VIEWER;
        $role->name = 'Viewer';
        $role->save();


        $role = new Role();
        $role->id = \App\base\IUserType::ORG_OFFICER;
        $role->name = 'Office';
        $role->save();


    }
}
