<?php

namespace App\base;

interface IUserType{

    const SUPER_ADMIN = 1;
    const ADMINISTRATOR = 2;
    const MANAGER = 3;
    const ORGANIZATION = 3;   //TODO remove this line after some time. user manager instead of this constant


    const MEMBER = 4;
    const ORG_OFFICER =5;
    const CONTRIBUTER = 6;
    const VIEWER = 7;

}
