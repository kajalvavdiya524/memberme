<?php

namespace App\Http\Middleware;

use App\base\IResponseCode;
use App\Organization;
use Auth;
use Closure;

class MerchantMiddleware
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
        /** @var Organization $organization */
        $organization = Auth::guard('merchant')->user();
        if(!empty($organization)) {
            $request->organization = $organization;
            $request->attributes->set('organization', $organization);

            if (!empty($organization->timezone->timezone)) {
                try {
                    date_default_timezone_set($organization->timezone->timezone);
                } catch (\Exception $exception) {

                }
            }

            return $next($request);
        }else{
            \Log::error('invaid organization 401');
            return api_error(['auth' => 'Invalid Organization'],IResponseCode::USER_NOT_LOGGED_IN);
        }
    }
}
