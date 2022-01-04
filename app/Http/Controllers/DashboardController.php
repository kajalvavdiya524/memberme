<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IUserType;
use App\Helpers\ApiHelper;
use App\Organization;
use App\repositories\OrganizationRepository;
use App\repositories\SubscriptionRepository;
use App\repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /* @var $organizationRepo OrganizationRepository*/
    public $organizationRepo;

    /* @var $userRepo UserRepository*/
    public $userRepo;

    public function __construct(OrganizationRepository $organizationRepository,UserRepository $userRepository)
    {
        $this->organizationRepo = $organizationRepository;
        $this->userRepo = $userRepository;
    }

    /**
     *@api {get} /dashboard/get-all-details [[val-05-01]] All Dashboard Details
     * @apiVersion 0.1.0
     * @apiName get-all-details
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Dashboard
     * @apiPermission Secured (Any registered User)
     * @apiDescription Get All details against dashboard, User name, current org, org list
     *
     * @apiSuccessExample {json} Success-Example:
     *  {
    "_metadata": {
    "status": "passed"
    },
    "pending_org": null,
    "data": {
    "organizations": [
    {
    "id": 15552,
    "name": "Random Founders",
    "current": 1,
    "user_id": 2,
    "data": {
    "logo": "http://api.memberme.me/storage/organization/logo/4685bfe36bb4436372d99df45ce00def.png"
    },
    "status": 1,
    "created_at": "2017-11-11 18:28:52",
    "updated_at": "2017-11-13 08:06:47",
    "details": {
    "id": 1,
    "organization_id": 15552,
    "bio": null,
    "contact_name": "feci",
    "contact_email": "test@memberme.me",
    "contact_phone": "22222",
    "office_phone": "223322233",
    "industry": 1,
    "account_no": "15552",
    "logo": null,
    "cover": null,
    "physical_address_id": "1",
    "postal_address_id": "4",
    "gst_number": null,
    "starting_member": "1",
    "starting_receipt": "1",
    "next_member": "1",
    "data": null,
    "created_at": "2017-11-11 18:48:50",
    "updated_at": "2017-11-14 18:24:40"
    }
    },
    ],
    "current_organization": null,
    "user_details": {
    "first_name": "Brent",
    "last_name": "Thomson"
    }
    },
    "message": "Dashboard Data Loaded completely"
    }
     *
     * @apiErrorExample {json} Error-Response:
    {
    "message": "Unauthenticated."
    }
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getAllDetails()
    {
        /* @var $user User*/
        $user = ApiHelper::getApiUser();
        if($user){
            $organizations = [];
            if($user->hasRole(IUserType::SUPER_ADMIN)){
                $organizations = $this->organizationRepo->all();
                $currentOrganizatoin = $this->organizationRepo->getAdminCurrentOrg($user->id);
            }else{
                $organizations = $this->organizationRepo->getAllOrg($user->id);
                $currentOrganizatoin = $this->organizationRepo->findCurrentOrganization($user->id);
            }
            $currentMonthBirthdayCount = $this->organizationRepo->getCurrentMonthBirthdayMembersCount($currentOrganizatoin);

            $data = [
                'organizations' => $organizations,
                'current_organization' => $currentOrganizatoin,
                'user_details' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ],
                'current_month_member_birthdays' => $currentMonthBirthdayCount ,
            ];
            $result = ApiHelper::apiResponse($data,null,'Dashboard Data Loaded completely');
            return response($result,200);
        }

        $result = ApiHelper::apiResponse(null,['auth' => 'Authentication error']);
        return response($result,503);
  }

  public function getCurrentMothBirthdayMembers()
  {
      /* @var $user User*/
      $user = ApiHelper::getApiUser();
      if($user){

          if($user->hasRole(IUserType::SUPER_ADMIN)){
              $currentOrganizatoin = $this->organizationRepo->getAdminCurrentOrg($user->id);
          }else{
              $currentOrganizatoin = $this->organizationRepo->findCurrentOrganization($user->id);
          }
          $result = $this->organizationRepo->currentMonthBirthdayMembers($currentOrganizatoin);
          return api_response($result);
      }
      return api_error(['auth' => 'Authentication error'],IResponseCode::USER_NOT_LOGGED_IN);
  }

  public function getSubscriptionStats(Request $request)
  {
      /** @var $organization Organization */
      $organization =  $request->get(Organization::NAME);

      $validationRules = [
          'organization_id' => 'exists:organizations,id'
      ];

      $validator = Validator($request->all(), $validationRules);

      if (!$validator->fails()) {

          $validator->after(function ($validator) {
              //todo After Validation here
          });

          if (!$validator->fails()) {
              $subscriptionRepo = new SubscriptionRepository();
              $subscriptions = $subscriptionRepo->getStats($organization);
              return api_response($subscriptions);
          } else {
              return api_error($validator->errors());
          }

      } else {
          return api_error($validator->errors());
      }
  }
}
