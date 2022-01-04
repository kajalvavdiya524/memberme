<?php

namespace App\Http\Controllers\Kiosk;

use App\Advertising;
use App\base\IResponseCode;
use App\base\IUserType;
use App\Helpers\ApiHelper;
use App\Kiosk;
use App\KioskBackground;
use App\Organization;
use App\repositories\KioskRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KioskController extends Controller
{

    /**
     * @var $kioskRepository KioskRepository
     */
    public $kioskRepository;

    public function __construct(KioskRepository $kioskRepository)
    {
        $this->kioskRepository = $kioskRepository;
    }

    /**
     * This api will return all kiosk templates related to this organization.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllKioskTemplates(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $kioskTemplates = $organization->kioskTemplates;
        return api_response($kioskTemplates);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        $kioskList = Kiosk::with([
            'organization' => function ($query) {
                $query->select('organizations.id', 'organizations.name');
            },
            'advertising' => function ($query){
                $query->select('advertisings.id','advertisings.template_no');
            }
        ])->get();



        $result = [];
        for ($i = 0; $i < $kioskList->count(); $i++) {
            $result[] = $kioskList[$i]->makeVisible('mac');
        }
        return api_response($result);
    }

    public function setKioskOrganization(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'kiosk_id' => 'required|exists:kiosks,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /* @var  $user User */
        $user = ApiHelper::getApiUser();
        if (!$user) {
            return api_error(['error' => 'User not logged in'], IResponseCode::USER_NOT_LOGGED_IN);
        }

        /* @var $kiosk  Kiosk */
        $kiosk = Kiosk::find($request->get('kiosk_id'));
        if (empty($kiosk)) {
            return api_error(['error' => 'Invalid Kiosk ID']);
        }

        if ($user->hasRole(IUserType::SUPER_ADMIN)) {
            $organization = Organization::find($request->get('organization_id'));
        } else {
            $organization = $user->organizations()->where([
                'id' => $request->get('organization_id')
            ])->first();
        }

        /* @var $organization Organization */
        if (!$organization) {
            return api_error(['error' => 'Invalid Organization']);
        }

        $organization->kiosks()->save($kiosk);
        $kiosk->status = Kiosk::STATUS['ASSIGNED'];
        $kiosk->save();
        $kiosk->refresh();

        return api_response($kiosk);
    }

    public function update(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'kiosk_id' => 'required|exists:kiosks,id',
            'background_id' => 'exists:kiosk_backgrounds,id',
            'status' => "in:" . Kiosk::STATUS['PENDING'] . "," . Kiosk::STATUS['ASSIGNED'] . "," . Kiosk::STATUS['DISABLED'],
            'name' => 'string',
            'advertising_id' => 'exists:advertisings,template_no',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /* @var $kiosk Kiosk */
        $kiosk = Kiosk::find($request->get('kiosk_id'));

        if (!empty($kiosk)) {
            if (!empty($request->get('name'))) {
                $kiosk->name = $request->get('name');
            }
            if (!empty($request->get('status'))) {
                $kiosk->status = $request->get('status');
            }
            if (!empty($request->get('background_id'))) {
                $kiosk->background_id = $request->get('background_id');
            }
            if (!empty($request->get('advertising_id'))) {
                /* @var  $advertising Advertising */
                $advertising = Advertising::
                    where([
                        'template_no' => $request->get('advertising_id'),
                        'organization_id' => $request->get('organization_id'),
                    ])
                    ->first();

                if(!empty($advertising)){
                    $kiosk->advertising_id = $advertising->id;
                }
            }
            $kiosk->update();
        } else {
            return api_error(['error' => 'Invalid kiosk_id']);
        }

//        if (!empty($background)) {
//            $organization = $kiosk->organization;
//            if (!empty($organization)) {
//                $organization->kioskBackgrounds()->detach();
//                $organization->kioskBackgrounds()->save($background);
//            }
//        }

        $kiosk = Kiosk::whereId($request->get('kiosk_id'))->where('kiosks.id',$kiosk->id)->with([
            'advertising' => function($query){
                $query->select('advertisings.id','advertisings.template_no','advertisings.name');
            },
            'background',
        ])->first();
        return api_response($kiosk, null, 'Kiosk updated successfully');
    }

    public function delete(Request $request)
    {
        $validationRules = [
            'kiosk_id' => 'required|exists:kiosks,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $kiosk = Kiosk::find($request->get('kiosk_id'));

        if ($kiosk) {
            $kiosk->delete();
        }

        return api_response([], null, 'Deleted Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssignedTemplates(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        /**\
         * @var $kiosk Kiosk
         */
        $kiosk = $organization->kiosks()->where('mac',$request->get('mac'))->first();
        if(empty($kiosk)){
            return api_error(['error' => 'Invalid Mac. Please Login with mac First'], IResponseCode::INVALID_PARAMS);
        }

        $data['backgroundTemplate'] = $kiosk->background;
        $data['advertisingTemplate'] = $kiosk->advertising()->with('advertisingImages')->get();
        $data['voucherParameter'] = $organization->kioskVoucherParameter;

        return api_response($data);
    }

    public function addVoucherParameter(Request $request)
    {

        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'sound' => 'max:700',
            'frequency' => 'numeric',
            'duration' => 'numeric',
            'days_before' => 'numeric',
            'days_after' => 'numeric',
            'email_voucher' => 'numeric',
            'show_in_app' => 'numeric',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        if(!$organization){
            return api_error(['error' => 'Invalid Organization']);
        }

        $data = $request->all();

        if(!empty($data['voucher_parameter_id'])){
            $voucherParameter = $organization->voucherParameters()->find($data['voucher_parameter_id']);
            if(empty($voucherParameter)){
                return api_error(['error' => 'Invalid Voucher Parameter']);
            }
        }

        if(count($data) < 2 ){
            return api_error(['error' => 'Please add atleast one field.']);
        }

        $kioskVoucherParameter = $this->kioskRepository->addOrUpdateKioskVoucherParameter($organization ,$request->all());

        return api_response($kioskVoucherParameter);
    }
}
