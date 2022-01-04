<?php
/**
 * Created by PhpStorm.
 * User: Feci
 * Date: 9/4/2017
 * Time: 1:01 AM
 */

namespace App\repositories;


use App\base\IStatus;
use App\EmailTemplate;
use App\Helpers\ApiHelper;
use App\Mail\AddUser;
use App\Mail\AdminEmail;
use App\Mail\VerifyEmail;
use App\Organization;
use App\Services\Sendgrid\SendgridService;
use App\Timezone;
use App\User;
use App\UserLastLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserRepository
{
    /* @var $organizationRepo OrganizationRepository*/
    public $organizationRepo;

    public function __construct()
    {
        $this->organizationRepo = new OrganizationRepository();
    }
    /**
     * Check a if customer already exist. Assign organization to that customer and then send ask to
     * enter Organization details.
     *
     * @param $data array
     * @return null
     */
    public function registerOrganization($data){

        $organization = new Organization();
        $user = User::whereEmail($data['email'])->first();  //checking if customer i

        if(!empty($user))
            $organization->user_id = $user->id;

        $organization->name = $data['name'];
        $organization->current = IStatus::ACTIVE;
        $organization->status = IStatus::INACTIVE;
        $organization->password = Str::random(10);
        $organization->api_token = Str::random(60);
        $organization->scanner_token  = Str::random(60);
        $timezone = Timezone::where('timezone' , Timezone::PACIFIC_AUCKLAND)->first();  //setting timezone for this organization
        $organization->timezone_id = array_get($timezone,'id');
        $organization->save();

        $this->organizationRepo->setPlan($organization,array_get($data,'plan_id'));

        if($user){
            $data = [
                'org_id' => $organization->id,
                'user_id' => $user->id,
            ];
        }else{
            $data = [
                'org_id' => $organization->id,
                'user_id' => null,
            ];
        }
        return $data;
    }

    public function saveDetails(Request $request,$user = null)
    {
        /**
         * @var $user User
         */
        if($user == null){
            $user = User::find(Auth::id());

            if(isset($request->api_token)){
                $user = ApiHelper::getApiUser();
            }
        }

        $user->first_name = $request->first_name?: $user->first_name;
        $user->last_name = $request->last_name?: $user->last_name;
        $user->middle_name = $request->middle_name?: $user->middle_name;
        $user->contact_no = $request->contact_no?:$user->contact_no;
        $user->contact_no = $request->contact_no?:$user->contact_no;
        $user->notes = $request->notes?: $user->notes;
        $user->activate = IStatus::ACTIVE;
        $user->update();

        return $user;
    }


    public function sendEmail( User $user , Organization $organization)
    {
        try{
//            Mail::to($user->email)->send(new VerifyEmail($user,$organization));
            Mail::send('email.sendView',[
                'org' => $organization,
                'user' => $user,
            ],function (\Illuminate\Mail\Message $message) use ($organization, $user) {
                $message
                    ->from(config('emailsettings.ORGANIZATION_SIGNUP_EMAIL'),config('emailsettings.ORGANIZATION_SIGNUP_NAME'))
                    ->subject('Memberme Verification')
                    ->to($user->email)
                    ->embedData([
                        'personalizations' => [
                            [
                                'dynamic_template_data' => [
                                    'orgName' =>  $organization->name,
                                    'verifyURL' => route('sendEmailDone',['email' => $user->email , 'verifyToken' => $user->verify_token])
                                ]
                            ]
                        ],
                        'template_id'      => 'd-564567f3b5ec478fa43cc271abe75c9a',
                    ], 'sendgrid/x-smtpapi');
            });
            return true;
        }catch (\Swift_TransportException $exception){
            dd($exception->getMessage());
        }
    }


    public function sendAdminEmail(Organization $organization)
    {
        try{
            Mail::to(\Config::get('global.SUPER_ADMIN_REGISTRATION_EMAIL'))->send(new AdminEmail($organization));
            return true;
        }catch (\Swift_TransportException $exception){
            return false;
        }
    }


    public function sendAddUserEmail($verifyDetails,Organization $organization,$role)
    {
        try{


            if(!User::whereEmail($verifyDetails->email)->first()){
                Mail::send('email.verifyUser',[
                    'org' => $organization,
                ],function (\Illuminate\Mail\Message $message) use ($organization, $verifyDetails) {
                    $message
                        ->from(config('emailsettings.ORGANIZATION_SIGNUP_EMAIL'),config('emailsettings.ORGANIZATION_SIGNUP_NAME'))
                        ->subject('Add Organization User')
                        ->to($verifyDetails->email)
                        ->embedData([
                            'personalizations' => [
                                [
                                    'dynamic_template_data' => [
                                        'organisation_name' =>  $organization->name,
                                        'verifyURL' => env('APP_URL').'/api/verify/user/'.$verifyDetails->verify_token,
                                        'password' => array_get($verifyDetails,'data',[])->password
                                    ]
                                ]
                            ],
                            'template_id'      => 'd-92f4dd438c534fdf92c3f9dbe17e3313',
                        ], 'sendgrid/x-smtpapi');
                });
            }


            Mail::send('email.verifyUser',[
                'org' => $organization,
            ],function (\Illuminate\Mail\Message $message) use ($organization, $verifyDetails) {
                $message
                    ->from(config('emailsettings.ORGANIZATION_SIGNUP_EMAIL'),config('emailsettings.ORGANIZATION_SIGNUP_NAME'))
                    ->subject('Add Organization User')
                    ->to($verifyDetails->email)
                    ->embedData([
                        'personalizations' => [
                            [
                                'dynamic_template_data' => [
                                    'organisation_name' =>  $organization->name,
                                    'verifyURL' => env('APP_URL').'/api/verify/user/'.$verifyDetails->verify_token
                                ]
                            ]
                        ],
                        'template_id'      => 'd-5d174b5454a94527a4dda053ffb20539',
                    ], 'sendgrid/x-smtpapi');
            });


//            Mail::to($verifyDetails->email)->send(new AddUser($verifyDetails, $organization, $role));
            return true;
        }catch (\Swift_TransportException $exception){
            return false;
        }
    }

    /**
     * @param User $user
     * @param $status int
     * @return User
     */
    public function toggleActivation(User $user, $status)
    {
        $user = User::find($user->id);
        $user->activate = $status;
        $user->update();
        return $user;
    }

    /**
     * Return the current role name against the user
     * @param $user_id
     * @return null
     */
    public function getUserCurrentRole($user_id)
    {

        $current_organization = $this->organizationRepo->findCurrentOrganization($user_id);
        $record = null;
        if($current_organization){
            $role = DB::table('roles');
            $role->join('role_user','role_id', 'roles.id');
            $role->where('role_user.user_id', $user_id);
            $role->where('role_user.status', '!=', IStatus::INACTIVE);
            $role->where('role_user.organization_id', $current_organization->id);
            $role->select(['roles.name as name','roles.id as id']);
            $record = $role->first();
        }
        return $record;
    }

    public function disableOrganization($organization_id, $user_id)
    {
        return $this->organizationRepo->disableOrganization($organization_id, $user_id);
    }

    public function enableOrganization($organization_id, $user_id)
    {
        return $this->organizationRepo->enableOrganization($organization_id, $user_id);
    }

    public function setUserRole($user_id,$organization_id, $role_id)
    {
        $role = User::find($user_id)->roles()->where('organization_id' , $organization_id)->update(['role_id' => $role_id]);
        if($role){
            return User::find($user_id)->roles()->where('organization_id',$organization_id)->first();
        }else{
            return null;
        }
    }

    public function getUserOrganizationStatus($organization_id, $user_id, $field = 'status')
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.organization_id', $organization_id);
        $org->select(['role_user.*']);
        $userRole = $org->first();
        if($userRole){
            return $userRole->$field;
        }else{
            return null;
        }

    }

    public function getRole($organization_id, $user_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->join('roles', 'role_user.role_id', 'roles.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.organization_id', $organization_id);
        $org->select(['roles.*']);
        $userRole = $org->first();

        if($userRole){
            return $userRole;
        }else{
            return null;
        }
    }

    public function unlinkUserFromOrganization(Organization $organization, User $user)
    {

        $query = DB::table('role_user');
        $query->where('role_user.user_id', $user->id);
        $query->where('role_user.organization_id', $organization->id);
        $roleUser = $query->first();
        if($roleUser){
            if($roleUser->current == IStatus::ACTIVE ) {
                $role = DB::table('role_user')->where('user_id','=',$user->id)->where('organization_id', '!=',$organization->id)->limit(1)->update(['current' => IStatus::ACTIVE]);
            }
        }
        $query = DB::table('role_user');
        $query->where('role_user.user_id', $user->id);
        $query->where('role_user.organization_id', $organization->id);
        $query->delete();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isUserOwner(User $user)
    {
        $currentOrganization = $this->organizationRepo->findCurrentOrganization($user->id);
        if( $currentOrganization && $user->id == $currentOrganization->user_id )
        {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function haveMultipleOrganizations(User $user)
    {
        $roleCount = DB::table('role_user')->where('user_id' , '=',$user->id )->count();
        if( $roleCount > 1 )
        {
            return true;
        }
        return false;
    }

    public function sendResetPasswordEmail(User $user, $token = null)
    {
        /** @var SendgridService $sendgridService */
        $sendgridService = new SendgridService();

        /** @var EmailTemplateRepository $emailTemplateRepository */
        $emailTemplateRepository = new EmailTemplateRepository();

        /** @var EmailTemplate $userResetTemplate */
        $userResetTemplate = $emailTemplateRepository->getUserResetTemplate();

        if(!empty($userResetTemplate)){
            $sendgridService->setup(Organization::find(\Config::get('global.MEMBERME_ID'))); //to setup the sendgrid api key of Default organization.
        }

        //dynamic parameters allowed in the template.
        $parameters = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
//            'user_reset_password_link' => url(config('app.url').route('password.reset', $token, false)),
        ];

        if(!empty($token)){
            $parameters['user_reset_password_link'] = url(config('app.url').route('password.reset', $token, false));
        }else{
            $parameters['user_reset_password_link'] = route('resetUserPassword', $user->api_token . '-_-' . base64_encode($user->email));
        }
        try {
            if($userResetTemplate){
                $sendgridService->send(
                    $sendgridService->setTemplateId(
                        $sendgridService->setupMail($user->email, $parameters),
                        $userResetTemplate->template_id
                    )   //setting up the mail to be sent.
                );
                $user->reset_password_sent_date_time = Carbon::now();
                $user->save();
            }else{
                \Log::info('User reset template not found');
            }
        }catch (\Exception $exception ) {
            \Log::info($exception->getMessage(). ' file : ' . $exception->getFile()."line: ". $exception->getLine());
        }
    }

    /**
     *  Add user last login specific to the organization, update if already exists.
     * @param User $user
     * @param Organization $organization
     */
    public function addLastLogin(User $user, Organization $organization)
    {
        $userLastLogin = $organization->lastLogins()->where('user_id' , $user->id)-> first();

        if(empty($userLastLogin)){
            $userLastLogin = new UserLastLogin();
        }
        $userLastLogin->organization_id = $organization->id;
        $userLastLogin->user_id = $user->id;
        $userLastLogin->last_login = Carbon::now();
        $userLastLogin->save();
    }
}
