<?php

namespace App\Http\Middleware;

use App\base\IResponseCode;
use Closure;
use Illuminate\Support\Facades\Auth;

class Kiosk
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
        $organization = Auth::guard('kiosk')->user();

        if(!empty($organization)){
            $request->organization = $organization ;
            $request->attributes->set('organization',$organization);

            if(!empty($organization->timezone->timezone)){
                try{
                    date_default_timezone_set($organization->timezone->timezone);
                }catch (\Exception $exception){

                }
            }

            return $next($request);
        }else{
            return api_error(['auth' => 'Invalid Organization'],IResponseCode::USER_NOT_LOGGED_IN);
        }
    }
}
