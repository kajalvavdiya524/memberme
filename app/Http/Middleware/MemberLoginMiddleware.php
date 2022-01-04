<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class MemberLoginMiddleware
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
        $member = Auth::guard('member')->user();
        if(!empty($member)){
            $organization = $member->organization;
            if(!empty($organization->timezone->timezone)){
                try{
                    date_default_timezone_set($organization->timezone->timezone);
                }catch (\Exception $exception){

                }
            }
            $request->member= $member;
            $request->attributes->set('member',$member);
            return $next($request);
        }else{
            return api_error(['auth' => ['Invalid Member']]);
        }
    }
}
