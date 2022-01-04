<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 10/29/2017
 * Time: 1:50 PM
 */

namespace App\Helpers;


use App\base\IUserType;

class CommonHelper
{
    public static function authorisedPersonList()
    {
        return [
          IUserType::SUPER_ADMIN, IUserType::MANAGER, IUserType::ADMINISTRATOR,
        ];
    }

    public static function UserTypeList()
    {

        return [
            'Manager' => IUserType::MANAGER,
            'Office' => IUserType::ORG_OFFICER,
            'Contributor' => IUserType::CONTRIBUTER,
            'Viewer' => IUserType::VIEWER,
        ];
    }
}