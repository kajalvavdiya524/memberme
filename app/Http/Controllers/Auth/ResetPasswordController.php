<?php

namespace App\Http\Controllers\Auth;

use App\base\IStatus;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/email-verified';

    protected $redirectPath = '/email-verified';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function rules()
    {
        return [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function credentials(Request $request)
    {
        return $request->only(  'password','password_confirmation','token');
    }

    public function reset(Request $request)
    {
        $validator = validator($request->all(),$this->rules());
        if($validator->fails() && $request->api == IStatus::ACTIVE){
            $errors = $validator->errors();
            $result = ApiHelper::apiResponse([],$errors,'Validation Errors');
            return response($result,400);
        }
        if(!$validator->fails()){
            $this->validate($request, $this->rules(), $this->validationErrorMessages());
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        if(isset($request->api)?: null == IStatus::ACTIVE){
            if($response == 'passwords.user'){
                $result = ApiHelper::apiResponse(null, ['email' => 'Email Could not be found in our records'],null);
                return response($result, 404);
            }else if($response == 'passwords.token'){
                $result = ApiHelper::apiResponse(null, ['token' => 'Token Mismatch Exception'],null);
                return response($result, 404);
            } else {
                $user = User::whereEmail($request->email);
                $result = ApiHelper::apiResponse($user, null, 'Password has been changed for this user.');
                return response($result, 200);
            }
        }
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

}
