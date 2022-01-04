<?php

namespace App\Http\Controllers\Auth;

use App\base\IStatus;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Session;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {

        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if(isset($request->api)?: null == IStatus::ACTIVE){

            if($response == 'passwords.user'){
                $result = ApiHelper::apiResponse(null, 'Email Could not be found in our records',null);
                return response($result, 404);
            }else {
                $result = ApiHelper::apiResponse([], null, 'Password Reset Email has been sent to your email');
                return response($result, 200);
            }
        }
        /*return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);*/
        Session::flash('verify' , 'We have sent you password resetting link at your email');
        return redirect('login');
    }
}
