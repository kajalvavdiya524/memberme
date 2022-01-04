<?php
/**
 * Created by PhpStorm.
 * User: Feci
 * Date: 9/4/2017
 * Time: 1:47 AM
 */

namespace App\base;


interface IStatus
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    const PENDING = 3;
    const PENDING_NEW = 4;
    const PENDING_RENEWAL = 5;
    const SUSPENDED = 6;
    const RESIGNED = 7;
    const ON_HOLD = 8;
    const OVER_DUE = 9;
    const EXPIRED = 10;

    const PAYMENT_STATUS = [
        'PAID' => 100,
        'DUE' => 101,
        'OVER_DUE' => 102,
    ];

    const PASSED = 'passed';
    const FAILED = 'failed';

    const STRIPE_ACTIVE_STATUS = "active";
    const STRIPE_PENDING_STATUS = "pending";
    const STRIPE_INACTIVE_STATUS = "inactive";
}
