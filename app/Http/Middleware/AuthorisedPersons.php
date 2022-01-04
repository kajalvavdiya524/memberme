<?php

namespace App\Http\Middleware;

use App\base\IResponseCode;
use App\base\IStatus;
use App\base\IUserType;
use App\Helpers\ApiHelper;
use App\Helpers\CommonHelper;
use App\Organization;
use App\repositories\OrganizationRepository;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

class AuthorisedPersons
{
    public static $organization;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {

        /* @var $orgRepo OrganizationRepository */
        $orgRepo = new OrganizationRepository();
        /* @var $user User */
        $user = ApiHelper::getApiUser();

        if ($user) {
            $organization = null;
            if (!empty($request->organization_id)) {
                $organization = Organization::find($request->organization_id);
            }
            if (!$organization) {
                $organization = $orgRepo->findCurrentOrganization($user->id);
            }

            if(!empty($organization->plan_expiry)){
                $planExpiry = new Carbon($organization->plan_expiry);
                $todayDate = Carbon::now();
                if($planExpiry->endOfDay() <= $todayDate->endOfDay() ){
                    $organization->plan_payment_status = IStatus::INACTIVE;
                    $organization->save();
                    return api_response(null,['error' => 'Plan have been expired. Please do your payment and try again.']);
                }
            }

            if (!empty($organization)) {
                if ($user->hasAnyRole(CommonHelper::authorisedPersonList())) {
                    if ($user->hasRole(IUserType::MANAGER)) {
                        $validatedOrganization = $orgRepo->findOrgWithRoleId($user->id, $organization->id, IUserType::MANAGER, IStatus::ACTIVE);
                        if (!$validatedOrganization) {
                            return api_response(null, ['permissions' => 'This user is not manager of this organization'], null, IResponseCode::NOT_ENOUGH_PERMISSIONS);
                        }
                        /** @var Organization $request ->organization */
                    }

                    /** @var Organization $request ->organization */
                    $organization = Organization::find($organization->id);
                    $request->organization = $organization;
                    $request->attributes->set('organization', $organization);
                    AuthorisedPersons::$organization = $organization;

                    if(!empty($organization->timezone->timezone)){
                        try{
                            date_default_timezone_set($organization->timezone->timezone);
                        }catch (\Exception $exception){
                            try{
                                date_default_timezone_set('Pacific/Auckland');
                            }catch (\Exception $exception){

                            }
                        }
                    }

                    $response = $next($request);

                    if(! $request->cookie('time_zone') && ! is_null($organization) && !empty($organization->timezone->timezone))
                    {
                        $response->withCookie(cookie('time_zone', $organization->timezone->timezone, 120));
                    }
                    return $response;
                } else {
                    $result = ApiHelper::apiResponse(null, ['permissions' => 'This user have not permissions for this task']);
                    return response($result, 500);
                }
            } else {
                return api_response(null, ['organization_id' => 'No Current Organization']);
            }
        } else {
            $result = ApiHelper::apiResponse(null, ['Auth' => 'User not logged in']);
            return response($result, 403);
        }
    }

    public static function loggedInOrganization(Organization $organization =  null ){
        if(!empty($organization)){
            AuthorisedPersons::$organization =  $organization;

        }
        return AuthorisedPersons::$organization;
    }
}
