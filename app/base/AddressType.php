<?php

namespace App\base;

interface AddressType
{
    const USER = 80;
    const ORGANIZATION = 81;
    const MEMBER = 82;
    const MEMBER_PROFILE = 83;

    const PHYSICAL_ADDRESS = 61;
    const POSTAL_ADDRESS = 62;
}
