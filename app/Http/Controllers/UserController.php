<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IStatus;
use App\base\IUserType;
use App\Helpers\ApiHelper;
use App\Helpers\CommonHelper;
use App\Http\Requests\SaveDetailsRequest;
use App\Http\Requests\SaveUserDetails;
use App\Organization;
use App\repositories\OrganizationRepository;
use App\repositories\UserRepository;
use App\Role;
use App\User;
use App\VerifyUser;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    /* @var $organizationRepo OrganizationRepository */
    public $organizationRepo;

    /* @var $userRepo UserRepository */
    public $userRepo;


    /**
     * UserController constructor.
     * @param OrganizationRepository $organizationRepository
     * @param UserRepository $userRepository
     */
    public function __construct(OrganizationRepository $organizationRepository, UserRepository $userRepository)
    {
        $this->userRepo = new UserRepository();
        $this->organizationRepo = new OrganizationRepository();
    }

    public function check()
    {

    }

    public function getDetails($id)
    {

        if (Auth::check())
            return view('user.get_details', ['user' => Auth::user()]);
        else
            return redirect('/login');
    }

    public function saveDetails(SaveUserDetails $request)
    {
        /**
         * @var $userRep UserRepository
         */
        $userRepo = new UserRepository();
        $userRepo->saveDetails($request);

        return redirect(route('home'));
    }


    /**
     * @api {post} /profile [[val-2-01]] Save profile
     * @apiVersion 0.1.0
     * @apiName [[val-2-01]] Save profile
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Setting
     * @apiPermission Secure
     * @apiDescription Save User Profile
     *
     * @param Request $request
     * @return array
     * @throws TokenMismatchException
     */
    public function saveApiDetails(Request $request)
    {
        /* @var $validator \Illuminate\Contracts\Validation\Validator */
        $validator = SaveUserDetails::requestValidate($request->all());
        if ($validator->fails()) {
            $errors = $validator->errors();
            return api_error($errors, IResponseCode::INVALID_PARAMS);
        }
        /**
         * @var $userRepo UserRepository
         */
        $user = ApiHelper::getApiUser();
        if ($user) {
            $userRepo = new UserRepository();
            $user = $userRepo->saveDetails($request, $user);
            $role = $this->userRepo->getUserCurrentRole($user->id);
            $user['role'] = $role;
            return api_response($user);
        } else {
//            throw new TokenMismatchException('User Not Found Please Login and Try Again.');
            return api_error(['error' => 'User Not Found Please Login and Try Again.']);
        }
    }

    public function verifyEmail()
    {
        Session::flash('verify1', ' Your account is pending verification');
        Session::flash('verify2', 'Check your email to verify your account');

        return redirect('login');
    }

    /**
     *
     * @api {post} /login [[val-01-01]] login
     * @apiVersion 0.1.0
     * @apiName [[val-01-01]]login
     * @apiParam {string} email Email
     * @apiParam {string} password Password
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Authentication
     * @apiPermission Public
     * @apiDescription Api login and return access token
     */

    /**
     *
     * @api {post} /register [[val-01-02]] Registration
     * @apiVersion 0.1.0
     * @apiName [[val-01-02]] Registration
     * @apiParam {string} name Organization Name
     * @apiParam {string} email Registration Email
     * @apiParam {string} password Password
     * @apiParam {string} password_confirmation Conform Password
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Authentication
     * @apiPermission Public
     * @apiDescription Register New Organization with User
     */

    /**
     *
     * @api {post} /sendresetemail [[val-01-03]] Send Reset Email
     * @apiVersion 0.1.0
     * @apiName [[val-01-03]] Send Reset Email
     * @apiParam {string} email Registration Email
     * @apiParam {number} api Api boolean key, set 1 for sending email from api call.
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Authentication
     * @apiPermission Public
     * @apiDescription Send Password Reset Email.
     */

    /**
     * @api {post} /authenticate-token [[val-01-04]] Verify with token
     * @apiVersion 0.1.0
     * @apiName [[val-01-04]] Verify with token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Authentication
     * @apiPermission Public
     * @apiDescription Verify Access Token
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function authenticateToken(Request $request)
    {
        //checking user from provided access token in request.
        $user = ApiHelper::getApiUser();

        if ($user) {
            $role = $this->userRepo->getUserCurrentRole($user->id);
            $user['role'] = $role;
            //$user['api_token']  = JWTAuth::parseToken()->refresh();
            $result = ApiHelper::apiResponse($user);
            return response()->json($result, IResponseCode::SUCCESS, [], JSON_NUMERIC_CHECK);
        } else {
            return api_response([], 'Invalid Token');
        }
    }

    /**
     * @api {post} /save-bulk-details [[val-03-02]] Save Bulk Details
     * @apiVersion 0.1.0
     * @apiName [[val-03-02]] Save Bulk Details
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiParam {number} organization_id Mandatory Organization Id
     * @apiParam {string} first_name Mandatory first_name of Owner of Organization (registered user)
     * @apiParam {string} last_name Mandatory last_name of Owner of Organization (registered user)
     * @apiParam {string} contact_no Mandatory contact_no of Owner of Organization (registered user)
     * @apiParam {string} organization_id Mandatory Organization name
     * @apiParam {string} contact_name Mandatory Name of contact Person
     * @apiParam {string} contact_phone Mandatory Phone of contact Person
     * @apiParam {string} contact_phone Mandatory Phone of contact Person
     * @apiParam {number} industry Industry Mandatory Type of Organization
     * @apiParam {number} physical_country Mandatory Physical Country_id of Organization
     * @apiParam {string} physical_first_address Optional first address line of Organization
     * @apiParam {string} physical_suburb Optional physical_suburb of Organization
     * @apiParam {string} physical_suburb Optional physical_suburb of Organization
     * @apiParam {string} physical_city Mandatory Physical City of Organization
     * @apiParam {string} physical_region Mandatory physical_region of Organization
     * @apiParam {string} physical_postal_code Mandatory physical_postal_code of Organization
     * @apiParam {string} physical_latitude Mandatory physical_latitude of Organization
     * @apiParam {string} physical_longitude Mandatory physical_longitude of Organization
     * @apiParam {number} next_member Mandatory next_member of Organization
     * @apiParam {number} starting_member Mandatory starting_member of Organization
     * @apiParam {number} gst Optional gst of Organization
     *
     * @apiParam {number} postal_country Mandatory postal Country_id of Organization
     * @apiParam {string} postal_first_address Optional first address line of Organization
     * @apiParam {string} postal_suburb Optional postal_suburb of Organization
     * @apiParam {string} postal_suburb Optional postal_suburb of Organization
     * @apiParam {string} postal_city Mandatory postal City of Organization
     * @apiParam {string} postal_region Mandatory postal_region of Organization
     * @apiParam {string} postal_postal_code Mandatory postal_postal_code of Organization
     *
     * @apiGroup Organization
     * @apiPermission Secured (Signed up manager)
     * @apiDescription Send bulk details to save details against user and organization
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": {
     * "id": 15554,
     * "name": "Test Organization 2",
     * "current": null,
     * "user_id": 2,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-11 18:35:34"
     * },
     * "message": "data successfully updated",
     * "data": {
     * "user": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06",
     * "role": {
     * "name": "Manager",
     * "id": 3
     * }
     * },
     * "organization": {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/4685bfe36bb4436372d99df45ce00def.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-13 08:06:47",
     * "owner": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06"
     * }
     * }
     * }
     * }
     *
     * @apiErrorExample {json} Error-Response:
     *
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "organization_id": [
     * "This organization id is not associated with this member"
     * ]
     * }
     * }
     * @param Request $request
     * @return array
     */
    public function saveBulkDetails(Request $request)
    {
        $validator = SaveDetailsRequest::validateRequest($request->all());
        if ($validator->fails()) {
            $error = $validator->errors();
            $result = ApiHelper::apiResponse(null, $error, 'Validation Errors');
            return response($result, 400);
        } else {
            $validator->after(function ($validator) use ($request) {
                $organization = $this->organizationRepo->findByUserId($request->organization_id, ApiHelper::getApiUser()->id);
//                $organization = $this->organizationRepo->getByIdWithRole($request->organization_id, ApiHelper::getApiUser()->id);
                if (!$organization) {
                    $validator->getMessageBag()->add('organization_id', 'This organization id is not associated with this member');
                }
            });
            if (!$validator->fails()) {
                $user = $this->userRepo->saveDetails($request);

                $organization = $this->organizationRepo->saveDetails($request);

                $data ['user'] = $user;
                $data ['organization'] = $organization;
                $data ['user']['role'] = $this->userRepo->getUserCurrentRole($user->id);
                $result = ApiHelper::apiResponse($data, null, 'data successfully updated');

                return response($result, IResponseCode::SUCCESS);
            }
            $error = $validator->errors();
            $result = ApiHelper::apiResponse(null, $error);
            return response($result, 400);
        }
    }

    /**
     * @api {post} /admin/users/add [[ val-07-01 ]] Add User
     *
     * @apiVersion 0.1.0
     * @apiName [[ val-07-01 ]] Add User
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup User
     * @apiParam {string} email Email of the user to which an admin wants to add in the organization
     * @apiParam {number} role_id Role id for creating user
     * @apiParam {number} role_id Role id for creating user
     * @apiParam {number} organization_id organization_id
     * @apiPermission Secured (Super Admin, Administrator, Manager)
     * @apiDescription Add user to specific organization by email conformation
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "email": "faial.arif+019@gmail.com",
     * "role_id": "4",
     * "status": 1,
     * "verify_token": "2Knsjt6dbKvXy0ozptEyRDvDrfH9E4ese3FUSBynIjNBINuJXrdPrtLvbaQU",
     * "data": {
     * "password": "GGsMzp3qA"
     * },
     * "organization_id": "15552",
     * "id": 4
     * },
     * "message": "Verification email sent to the user"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * { "error" : "Unauthenticated" }
     *
     * @permissinos: SuperAdmin, Administrator, Organization Manager
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addUser(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'organization_id' => 'exists:organizations,id',
        ];


        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            $errors = ApiHelper::apiResponse(null, $validator->errors());
            return response($errors, 503);
        } else {

            $user = User::whereEmail($request->email)->first();
            if ($user) {
                $role = $this->organizationRepo->findOrgWithRoleId($user->id, $organization->id, $request->role_id);
                if ($role) {
                    $result = ApiHelper::apiResponse($role, null, 'User already have this role against this organization');
                    return response($result, IResponseCode::SUCCESS);
                }
            }


            $userVerification = VerifyUser::whereEmail($request->email)->whereOrganization_id($request->organization_id)->first();
            if (!$userVerification) {
                $userVerification = new VerifyUser();
                $userVerification->email = $request->email;
                $userVerification->role_id = $request->role_id;
                $userVerification->status = IStatus::ACTIVE;
                $userVerification->verify_token = Str::random(60);
                $userVerification->data = [
                    'password' => Str::random(9)
                ];
                $userVerification->organization_id = $organization->id;
                $userVerification->save();
            } else {
                $userVerification->role_id = $request->role_id;
                $userVerification->organization_id = $organization->id;
                $userVerification->data = [
                    'password' => Str::random(9)
                ];
                $userVerification->update();
            }
            $role = Role::find($request->role_id);
            $adminEmailSent = $this->userRepo->sendAddUserEmail($userVerification, $organization, $role);
            if ($adminEmailSent) {
                $result = ApiHelper::apiResponse($userVerification, null, 'Verification email sent to the user');
                return response($result, IResponseCode::SUCCESS);
            } else {
                $result = ApiHelper::apiResponse(null, ['email' => 'There is some error in sending email']);
                return response($result, IResponseCode::INVALID_PARAMS);
            }
        }
    }

    public function verifyAddUser($token, Request $request)
    {
        if ($token) {
            $verifyUser = VerifyUser::whereVerifyToken($token)->first();
            if ($verifyUser) {
                $user = User::where('email', $verifyUser->email)->first();
                if ($user) {
                    $role = $this->organizationRepo->findOrgWithRoleId($user->id, $verifyUser->organization_id, $verifyUser->role_id);
                    if ($role) {
                        //                        $result = ApiHelper::apiResponse($role, null, 'User already have this role against this organization');
                        $user->verify = IStatus::ACTIVE;
                        $user->save();
                        return redirect(\Config::get('global.LOGIN_URL'));
                    } else {
                        $role = $user->roles()->save(Role::find($verifyUser->role_id), ['organization_id' => $verifyUser->organization_id]);
                        $result = ApiHelper::apiResponse($role, null, 'This role has been assigned to this user.');
                        $user->verify = IStatus::ACTIVE;
                        $user->save();
                        //                        return response($result, IResponseCode::SUCCESS);
                        return redirect(\Config::get('global.LOGIN_URL'));
                    }

                } else {
                    $user = new User();
                    $user->email = $verifyUser->email;
                    $user->password = isset($verifyUser->data->password) ? bcrypt($verifyUser->data->password) : bcrypt(Str::random(9));
                    $user->user_type_id = IUserType::MEMBER;
                    $user->status_id = IStatus::ACTIVE;
                    $user->api_token = Str::random(60);
                    $user->verify = IStatus::ACTIVE;
                    $user->activate = IStatus::INACTIVE;
                    $user->save();

                    $role = $user->roles()->save(Role::find($verifyUser->role_id), ['current' => IStatus::ACTIVE, 'organization_id' => $verifyUser->organization_id]);
                    $result = ApiHelper::apiResponse($role, null, 'This role has been assigned to this user.');
                    return redirect(\Config::get('global.LOGIN_URL'));
                }
            } else {
                throw new TokenMismatchException('Your Verify Token did not match with  our records');
            }
        }
    }

    /**
     * @api {post} /admin/users/get-list [[ val-07-02 ]] Get user list
     * @apiVersion 0.1.0
     * @apiName [[ val-07-02 ]] Get user list
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup User
     * @apiPermission Secured (Super Admin, Administrator, manager)
     * @apiDescription Get user list against an organization.
     *
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getList(Request $request)
    {

        $validation_rules = [
            'organization_id' => 'required|exists:organizations,id'
        ];

        $validator = Validator($request->all(), $validation_rules);

        if (!$validator->fails()) {
            $users = $this->organizationRepo->getUsers($request->organization_id);
            if (!empty($users)) {
                $result = ApiHelper::apiResponse($users, null, 'users against this organizations');
                return response($result, IResponseCode::SUCCESS);
            }
            $result = ApiHelper::apiResponse($users, null, 'no user found against this organization');
            return response($result, IResponseCode::SUCCESS);
        }
        $result = ApiHelper::apiResponse(null, $validator->errors());
        return response($result, IResponseCode::INVALID_PARAMS);
    }

    /**
     * @api {get} /users/get-user-types [[ val-07-03 ]] get user type list
     * @apiVersion 0.1.0
     * @apiName [[ val-07-03 ]] get user type list
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup User
     * @apiPermission Secured (Super Admin, Administrator, manager)
     * @apiDescription Get user list against an organization.
     *
     *
     * @return mixed
     */
    public function getUserTypes()
    {
        $result = ApiHelper::apiResponse(CommonHelper::UserTypeList(), null, 'available users type list');
        return response($result, IResponseCode::SUCCESS);
    }

    /**
     * @api {post} /admin/users/update [[ val-07-04 ]] Update User
     * @apiVersion 0.1.0
     * @apiName [[ val-07-04 ]] Update User
     * @apiParam {number} organization_id Organization Id
     * @apiParam {number} user_id User Id
     * @apiParam {number} role_id Role for this organization
     * @apiParam {number} status Status of thi user against this organization
     * @apiParam {string} notes Notes against user by admins
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup User
     * @apiPermission Secured (Super Admin, Administrator, manager)
     * @apiDescription update a user for an organization
     *
     *
     * @return mixed
     */

    public function updateUser(Request $request)
    {
        $validationRules = [
            'role_id' => 'exists:roles,id',
            'user_id' => 'required|numeric|exists:users,id',
            'status' => 'numeric',
            'organization_id' => 'exists:organizations,id|required_with:role_id,status'
        ];
        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {
            //checking if logged in user is changing its own values
            $loggedInUser = ApiHelper::getApiUser();

            if ($loggedInUser->id == $request->user_id) {
                return response(ApiHelper::apiResponse(null, ['user_id' => 'You can not change your own permissions']), IResponseCode::INVALID_PARAMS);
            }
            $user = User::find($request->user_id);
            if ($user->hasAnyRole([IUserType::ADMINISTRATOR, IUserType::SUPER_ADMIN])) {
                return response(ApiHelper::apiResponse(null, ['user_id' => 'You can not change admin user']), IResponseCode::INVALID_PARAMS);
            }
            //todo update details in user table, if status is 2, remove this organization from this user's account
            if ($user) {
                if (isset($request->notes) && !empty($request->notes)) {
                    $user->notes = (isset($request->notes)) ? $request->notes : null;
                    $user->update();
                }
                //enabling or disabling the user organization.
//                $currentOrganization =  $this->organizationRepo->curre
                $status = null;
                $data = $user;
                if ($request->status == IStatus::INACTIVE) {
                    $organization = $this->userRepo->disableOrganization($request->organization_id, $request->user_id);
                    $status = 2;
                } elseif ($request->status == IStatus::ACTIVE) {
                    $organization = $this->userRepo->enableOrganization($request->organization_id, $request->user_id);
                    $status = 1;
                }
                //getting current role of user against that organization.
                $data['role'] = $this->userRepo->getRole($request->organization_id, $user->id);
                //changing role against that organization
                if (isset($request->role_id)) {
                    $role = $this->userRepo->setUserRole($user->id, $request->organization_id, $request->role_id);
                    $data['role'] = $role;
                }
                if ($status == null)
                    $data['status'] = $this->userRepo->getUserOrganizationStatus($request->organization_id, $request->user_id);
                else
                    $data['status'] = $status;
                $result = ApiHelper::apiResponse($data, null, 'User Has been Updated');
                return response($result, IResponseCode::SUCCESS);
            } else {
                $validator->getMessageBag()->add('user_id', 'Could not found user against this user id');
                $result = ApiHelper::apiResponse(null, $validator->errors());
                return response($result, IResponseCode::INVALID_PARAMS);
            }
        } else {
            $result = ApiHelper::apiResponse(null, $validator->errors());
            return response($result, IResponseCode::INVALID_PARAMS);
        }
    }

    public function updatePassword(Request $request)
    {
        /** @var $organization Organization */

        $validationRules = [
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ];

        $user = ApiHelper::getApiUser();

        $validator = Validator($request->all(), $validationRules);

        if (!$user) {
            return api_error(['auth' => 'User not logged in'], IResponseCode::USER_NOT_LOGGED_IN);
        }

        if (!$user) {
            return api_error(['current_password' => 'Invalid Current Password'], IResponseCode::INVALID_PARAMS);
        }

        if ($validator->fails()) {
            return api_error($validator->errors());
        }


        if (Hash::check($request->get('current_password'), $user->password)) {

            $user->password = Hash::make($request->get('new_password'));

            $user->setRememberToken(Str::random(60));

            $user->save();

            return api_response([], [], 'Password have been updated successfully');

        } else {
            return api_error(['current_password' => 'Invalid Current Password'], IResponseCode::INVALID_PARAMS);
        }


    }

    /**
     * Refresh token if it is able to be refreshed, otherwise ask frontend to logout.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $token = '';
        try{
            $token = JWTAuth::parseToken()->refresh();
        }catch (TokenBlacklistedException $exception){

        }
        return api_response($token);
    }

    /**
     * Deleting a user who's type isn't the master type.!
     * One user can't delete himself.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkUser(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'user_id' => 'required|exists:users,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $user = User::find($request->get('user_id'));

        if (!$user) {
            return api_error(['error' => 'Unable to find user']);
        }

        if ($user->id == ApiHelper::getApiUser()->id) {
            return api_error(['error' => 'You cannot delete yourself.']);
        }
        if ($user->hasRole(IUserType::SUPER_ADMIN)) {
            return api_error(['error' => 'You cannot delete Admins']);
        }

        $this->userRepo->unlinkUserFromOrganization($organization, $user);
        return api_response($this->organizationRepo->getUsers($request->organization_id), null, 'User have been removed.');
    }

    public function reApiLogin(Request $request)
    {
        $authhHeader = $request->header('Authorization');
        if(empty($authhHeader)){
            return api_error(['error' => 'Invalid Authentication']);
        }
        $token = str_replace('Bearer ','',$authhHeader);
        $user = ApiHelper::getApiUser();
        $userRepo = new UserRepository();
        $orgRepository = new OrganizationRepository();
        $role = $userRepo->getUserCurrentRole($user->id);
        $user['role'] = $role;
        $user->api_token = $token;

        $result = ApiHelper::apiResponse($user, null, 'Welcome to Member me');
        $org = $orgRepository->getPendingOrg($user);
        if (!empty($org)) {
            $result['pending_org'] = $org;
        }

        return response()->json($result, IResponseCode::SUCCESS, [], JSON_NUMERIC_CHECK);
    }

    public function sendResetEmail(Request $request)
    {

        $validationRules = [
            'email' => 'required|email',
            'api' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var User $user */
        $user = User::whereEmail($request->get('email'))->first();

        if(!$user){
            return api_error(['This email is not registered']);
        }

        $this->userRepo->sendResetPasswordEmail($user);
        
        return api_response(null,null,'Reset password email is sent to your inbox');
    }

    public function userResetPassword($token)
    {
        $data = explode('-_-', $token);

        $user = User::where([
            'api_token' => $data[0],
            'email' => base64_decode($data[1]),
        ])->first();

        if (empty($user)) {
            return 'Invalid User or Token Expired';
        }
        return view('auth.passwords.reset', ['user' => $user]);
    }

    public function resetUserPassword(Request $request)
    {
        $validationRules = [
            'token' => 'required|exists:users,api_token',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $user = User::where([
            'api_token' => $request->get('token'),
        ])->first();

        if (empty($user )) {
            return "<h6 align='center'> Invalid Token </h6>";
        }

        if(empty($user->reset_password_sent_date_time)){
            return "<h6 align='center'> Invalid Token </h6>";
        }

        $today = Carbon::now();
        $sentDate = new Carbon($user->reset_password_sent_date_time);
        $diff = $today->diffInHours($sentDate);
        if($diff > 24){
            return "<h6 align='center'> Token expired. Please try again.</h6>";
        }

        if ($user ->email != $request->get('email')) {
            return "<h6 align='center'> Please recheck your email. </h6>";
        }

        $user -> password = bcrypt($request->get('password'));
        $user -> api_token = Str::random(60);
        $user -> save();

        return view('email-verified');
    }

    public function apiLogout(Request $request)
    {

        $user = ApiHelper::apiLogout();
        return api_response(null,null,'Logout successfully');
    }
}