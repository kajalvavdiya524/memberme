<?php

namespace App\Http\Middleware;

use App\base\IStatus;
use App\Organization;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOrg
{
    /**
     * Handle an incoming request.  And Check if User have pending Organization then send to input form of organization
     * details
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = Auth::id();

        $user = Auth::user();
        if(!empty($user_id)){

            $org = Organization::where('user_id', $user_id)->whereStatus(IStatus::INACTIVE)->first();

            if(empty($org))
            {
                return $next($request);     //if no pending organization send request to next else move to details page
            }else{
                if($user->activate != IStatus::ACTIVE){
                    return redirect(route('user_details',['id' => $user_id]));
                }else{
                    return redirect(route('org_details',['id' => $org->id]));
                }
            }
        }else{
            return redirect('/login');
        }
//        return $next($request);
    }
}
