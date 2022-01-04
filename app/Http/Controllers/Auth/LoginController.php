<?php

namespace App\Http\Controllers\Auth;

use App\base\IResponseCode;
use App\base\IStatus;
use App\base\IUserType;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Organization;
use App\repositories\OrganizationRepository;
use App\repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Glide\Api\Api;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['verify' => 1]);
    }

    public function apiLogin(Request $request)
    {

        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return api_error(['error' => 'Invalid credentials'], IResponseCode::USER_NOT_LOGGED_IN);

            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
//            return response()->json(['error' => 'could_not_create_token'], 500);
            return api_error(['error' => 'Could not create token'], IResponseCode::INTERNAL_SERVER_ERROR);
        }

        // all good so return the token
//        return response()->json(compact('token'));

        $user = User::whereEmail($request->email)->first();
        if ($user->verify != IStatus::ACTIVE) {
            $message = 'Your email verification is pending. Please verify your account from your email.';
            $result = ApiHelper::apiResponse('', ['verify' => $message]);
            return response($result, 400);
        }

        $userRepo = new UserRepository();
        $orgRepository = new OrganizationRepository();
        $role = $userRepo->getUserCurrentRole($user->id);
        $organization = $orgRepository->findCurrentOrganization($user->id);
        $user['role'] = $role;
        $user->api_token = $token;

        //region check for card info getting if user is using some paid service.
        $isPlanPaid = $orgRepository->checkStripeSubscriptionPaymentInfo($user);

        if (!$isPlanPaid && !$user->isCurrentRole(IUserType::SUPER_ADMIN)) {
            $message = 'Your subscription payment is pending.';
            $isOwner = $userRepo->isUserOwner($user);
            $haveMultipleOrganizations = $userRepo->haveMultipleOrganizations($user);

            $dataToSend = [
                'is_owner' => $isOwner,
                'multiple_org' => $haveMultipleOrganizations,
                'is_payment_pending' => true,
                'user_id' => $user->id,
                'temp_token' => $token,
                'organization_id' => $organization->id,
                'plan_id' => $organization->plan_id,
                'plan_payment_status' => $organization->plan_payment_status
            ];
            $result = ApiHelper::apiResponse($dataToSend, null, $message);

            return response($result, IResponseCode::SUCCESS);
        }

        //saving last login user.
        $userRepo->addLastLogin($user,Organization::find($organization->id));

        //saving last login for organization.
        Organization::where('id', $organization->id)->update(['last_login' => $user->last_login]);

        //endregion
        $result = ApiHelper::apiResponse($user, null, 'Welcome to Member me');
        $org = $orgRepository->getPendingOrg($user);
        if (!empty($org)) {
            $result['pending_org'] = $org;
        }

        return response()->json($result, IResponseCode::SUCCESS, [], JSON_NUMERIC_CHECK);
    }
}
