<?php

namespace App\Http\Controllers\Kiosk;

use App\base\IResponseCode;
use App\base\IStatus;
use App\Kiosk;
use App\Organization;
use App\repositories\KioskRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /* @var $kioskRepository KioskRepository*/
    public $kioskRepository;

    /**
     * AuthController constructor.
     * @param KioskRepository $kioskRepository
     */
    public function __construct(KioskRepository $kioskRepository)
    {
        $this->kioskRepository = $kioskRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validationRules = [
            'id' => 'required|exists:organizations,id',
            'pass' => 'required|exists:organizations,password',
        ];

        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {
            $org_id = $request->get('id');
            $password = $request->get('pass');

            $organization = Organization::where([
                'id' => $org_id,
                'password' => $password,
            ])->select(['name', 'api_token'])->first();

            $validator->after(function ($validator) use ($organization) {
                if (empty($organization)) {
                    $validator->getMessageBag()->add('auth' , 'Invalid Credentials');
                }
            });

            if (!$validator->fails()) {
                return api_response($organization,null,'Successfully login into kiosk',IResponseCode::SUCCESS,IStatus::INACTIVE);
            } else {
                return api_error($validator->errors(),IResponseCode::USER_NOT_LOGGED_IN);
            }
        } else {
            return api_error($validator->errors(),IResponseCode::USER_NOT_LOGGED_IN);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function macLogin(Request $request)
    {
        $validationRules = [
            'mac' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {
            $macAddress = $request->get('mac');

            $isValidMac = $this->kioskRepository->validateMac($macAddress);

            if(!$isValidMac) {
                return api_error(['mac' => 'Invalid Mac Address.'], IResponseCode::USER_NOT_LOGGED_IN);
            }

            $kiosk = $this->kioskRepository->findOrCreate($macAddress);

            if(empty($kiosk->organization)){
                return api_error(['error' => 'Organization is not set against this kiosk. Please contact administrator.'],IResponseCode::USER_NOT_LOGGED_IN);
            }

            if (!$validator->fails()) {
                return api_response($kiosk,null,'Successfully login into kiosk',IResponseCode::SUCCESS,IStatus::INACTIVE);
            } else {
                return api_error($validator->errors(),IResponseCode::USER_NOT_LOGGED_IN);
            }
        } else {
            return api_error($validator->errors(),IResponseCode::USER_NOT_LOGGED_IN);
        }
    }

    /**
     * Only accesable by super admin
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPassword(Request $request,$id)
    {
        $organization =  Organization::find($id);
        if(empty($organization))
            return api_error(['id' => 'Invalid Id']);

        return api_response($organization->password,null,'Organization Password For Kiosk',IResponseCode::SUCCESS,IStatus::INACTIVE);
    }
}
