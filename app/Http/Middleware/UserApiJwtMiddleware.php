<?php

namespace App\Http\Middleware;

use App\base\IResponseCode;
use App\Helpers\ApiHelper;
use Closure;

class UserApiJwtMiddleware
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
        $user = ApiHelper::getApiUser();
        if(!$user){
            return api_error(['error' => 'Unauthenticated'],IResponseCode::USER_NOT_LOGGED_IN);
        }
        return $next($request);
    }
}
