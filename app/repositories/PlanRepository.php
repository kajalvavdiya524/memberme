<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/17/2018
 * Time: 7:29 PM
 */

namespace App\repositories;


use App\Feature;
use App\Organization;
use App\Plan;
use App\User;
use DB;

class PlanRepository
{
    public function __construct()
    {

    }

    /**
     * @param Organization $organization
     * @param User $user
     * @param Feature $feature
     * @param null $limit
     * @param null $expiry
     */
    public function addNewUserFeature(Organization $organization, User $user, Feature $feature, $limit = null, $expiry = null)
    {
        DB::table('feature_user')->insert([
            'feature_id' => $feature->id,
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'limit' => $limit,
            'expiry' => $expiry,
        ]);
    }
}