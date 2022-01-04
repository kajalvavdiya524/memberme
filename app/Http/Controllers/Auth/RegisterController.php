<?php

namespace App\Http\Controllers\Auth;

use App\base\IStatus;
use App\base\IUserType;
use App\Exceptions\ApiException;
use App\Helpers\ApiHelper;
use App\Organization;
use App\PaymentType;
use App\Plan;
use App\repositories\OrganizationRepository;
use App\repositories\StripeRepository;
use App\repositories\UserRepository;
use App\StripeSubscription;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Stripe;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /* @var $organizationRepo OrganizationRepository*/
    public $organizationRepo;

    /**
     * @var $userRepo UserRepository
     */
    public $userRepo;

    /**
     * @var $stripeRepo StripeRepository
     */
    public $stripeRepo;
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->organizationRepo = new OrganizationRepository();
        $this->userRepo = new UserRepository();
        $this->stripeRepo = new StripeRepository();
    }


    public function messages()
    {
        return [
            'email.unique' => 'Please login to add new Organization',
        ];
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = $this->messages();
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',/*|unique:users*/
            'password' => 'required|string|min:6|confirmed',
            'plan_id' => 'required|exists:plans,id'
        ],$message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $result
     * @return User
     * @throws ApiException
     */
    protected function create(array $result)
    {
        //region User retrieval or creation
        if($result['email'] != null){
            $user = User::whereEmail($result['email'])->first();
            if(!$user){
                $user = new User();
                $user->fill($result);
                $user->password = bcrypt($result['password']);
                $user->user_type_id = IUserType::MANAGER;
                $user->status_id = IStatus::ACTIVE;
                $user->verify_token = Str::random(50);
                $user->api_token = Str::random(60);
                $user->save();
                $newUser = true;
            }else{
                $newUser = false;
            }
        }else{
            throw new ApiException(['email' => 'Email is required']);
        }
        //endregion

        /* @var $userRepository UserRepository */
        $userRepository = new UserRepository();
        $data = $userRepository->registerOrganization($result); //registering organization in our system as new organization.

        //region Adding Manager Role to the signing up user
        if($newUser){
            $user->roles()->attach(IUserType::MANAGER,[
                'current' => IStatus::ACTIVE,
                'organization_id' => $data['org_id'],
            ]);
        }
        //endregion

        //region Re-attaching organization with user
        $organization = Organization::find($data['org_id']);
        $organization->user_id = $user->id;
        $organization->update();
        //endregion

        $this->organizationRepo->setupNewOrganization($organization);       // setting up necessary things for newly created organization

        $emailSent = $userRepository->sendEmail($user, $organization);      // sending verification email to user to verify organization. - Email Verification.

        $adminEmailSent = $userRepository->sendAdminEmail($organization); //sending email to admin, that a new organization is created.

        if($emailSent){
            return [
                'user' => $user,
                'plan_payment_status' => $organization->plan_payment_status,        //setting plan_payment_status in response to redirection for card details if not trail.
                'organization_id' => $organization->id,
            ];
        }else{
            return false;                       //returning false to new Register Client.
        }
    }

    public function sendEmailDone($email, $verify_token)
    {
        $user  = User::where(['email' => $email,'verify_token' => $verify_token])->first();
        if($user){
            $user->verify = IStatus::ACTIVE;
            $user->verify_token = null;
            $user->update();
        }

        return redirect(route('email-verified'));
//        return redirect(\Config::get('global.LOGIN_URL'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     * @throws ApiException
     */
    public function apiRegister(\Illuminate\Http\Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails())
        {
            $errors = $validator->errors();
            $result = ApiHelper::apiResponse(null,$errors);
            return response($result,500);
        }

        $data = $this->create($request->all());
        $user = $data['user'];
        event(new Registered($user));
        if($user){
            $result = ApiHelper::apiResponse($data,null,'Your account is pending verification. Check your email to verify your account');
            return response($result ,200);
        }else{
            return response(ApiHelper::apiResponse(null,['email' => 'Verification Email not sent due to internal server error']),500);;
        }
        /*return response($result, 200)->header('Access-Control-Allow-Origin','*')
            ->header("Access-Control-Allow-Headers", "Content-Type, Authorization, X-Requested-With");*/
    }

    public function getCardInfo(\Illuminate\Http\Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'number'           => 'required',
            'cvc' => 'required',
            'exp_month'           => 'required',
            'exp_year' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $organization = Organization::whereId($request->get('organization_id'))->with('owner','plan')->first();

        //region Creating Token For Card Details in Stripe. ( This will return Response if there is any problem. )
        $token = Stripe::tokens()->create([
            'card' => [
                'number'    => $request->get('number'),
                'exp_month' => $request->get('exp_month'),
                'exp_year'  => $request->get('exp_year'),
                'cvc'        => $request->get('cvc'),
            ],
        ]);
        //endregion

        if($token){     //if card validated then create customer, attach card to him and make a subscription by getting payment.

            $stripeCustomer = $this->stripeRepo->createCustomerInStripe($organization->owner->email);   // creating stripe customer
            $organization->stripe_customer_id = array_get($stripeCustomer,'id');
            $organization->save();


            $stripeCard = $this->stripeRepo->createPaymentCard(array_get($stripeCustomer,'id'), array_get($token,'id'));    // creating stripe card and this will be default card for this customer.

            if (!empty($organization->plan->ref_id)){
                $subscription = $this->stripeRepo->createSubscription(array_get($stripeCustomer,'id'),$organization->plan->ref_id); // subscription Created on stripe and payment will be deducted from the provided card at the creation of the subscription.
            }else{
                return api_error(['error' => 'Plan not found against this organization']);
            }

            if(!empty($subscription)){
                $stripeSubscription = $this->organizationRepo->addStripeSubscription($organization, $subscription);   // syncing stripe api returned subscription to the system against organization.
                return api_response($stripeSubscription);
            }else{
                return api_error(['error' => 'Unable to Create Subscription. Please contact administrator']);
            }
        }
    }
}
