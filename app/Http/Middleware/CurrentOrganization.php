<?php

namespace App\Http\Middleware;

use App\base\IResponseCode;
use App\Helpers\ApiHelper;
use App\repositories\OrganizationRepository;
use App\User;
use Closure;

class CurrentOrganization
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
        $user = /* @var $orgRepo OrganizationRepository */
        $orgRepo = new OrganizationRepository();
        /* @var $user User */
        $user = ApiHelper::getApiUser();
        if($user){
            $currentOrganization = $orgRepo->findCurrentOrganization($user->id);
            if($currentOrganization){
                $request->organization = $currentOrganization;
                $request->attributes->set('organization',$currentOrganization);

                return $next($request);
            }
            return api_response(null,['current_org' => 'No Current Organization'],IResponseCode::NOT_ENOUGH_PERMISSIONS);
        }
        return api_response(null,['auth' => 'User not logged in'],null,IResponseCode::USER_NOT_LOGGED_IN);
    }
}
