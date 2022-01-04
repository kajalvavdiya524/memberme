<?php

namespace App\Http\Middleware;

use App\base\IUserType;
use App\Helpers\ApiHelper;
use App\Organization;
use App\repositories\OrganizationRepository;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* @var $user User*/
        $user = ApiHelper::getApiUser();
        if($user){
            if($user->hasRole(IUserType::SUPER_ADMIN))
            {
                if (!empty($request->organization_id)) {
                    $organization = Organization::find($request->organization_id);
                }else{
                    $orgRepo = new OrganizationRepository();
                    $organization = $orgRepo->getAdminCurrentOrg($user->id);
                }
                $organization = Organization::find($organization->id);
                $request->organization = $organization;
                $request->attributes->set('organization', $organization);
                return $next($request);
            }
            $result =  ApiHelper::apiResponse(null,['permissions' => 'This user have not permissions for this task']);
            return response($result,500);
        }
        $result =  ApiHelper::apiResponse(null,['Auth' => 'User not logged in']);
        return response($result,403);
    }
}
