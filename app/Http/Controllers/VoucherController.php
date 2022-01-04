<?php

namespace App\Http\Controllers;

use App\base\IStatus;
use App\Member;
use App\Organization;
use App\repositories\VoucherRepository;
use App\Voucher;
use App\VoucherParameter;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use Storage;

class VoucherController extends Controller
{
    //
    public $voucherRepo;

    public function __construct()
    {
        $this->voucherRepo = new  VoucherRepository();
    }

    public function createVoucherParameter(Request $request)
    {

        $allowedImageExtensionArray = ['jpeg', 'bmp', 'png', 'JPG'];

        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'voucher_type' => 'required|in:' . implode(",", VoucherParameter::VOUCHER_TYPE),
            'voucher_name' => 'string|required',
            'multisite' => 'numeric',
            'expires' => 'numeric|required|in:' . implode(",", VoucherParameter::EXPIRES),
            'expiry_mode' => 'string|required_if:expires,1|in:' . implode(",", VoucherParameter::EXPIRY_MODE),
            'expiry_period_quantity' => 'numeric|required_if:expiry,period',
            'expiry_period_duration' => 'string|required_if:expiry,period|in:' . implode(",", VoucherParameter::EXPIRY_DURATION),
            'expiry_date' => 'required_if:expiry,date',
            'uses' => 'numeric|required',
            'limited' => 'numeric|required|in:' . implode(",", VoucherParameter::LIMITED_QUANTITY),
            'limited_quantity' => 'numeric|required_if:limited,1',
            'min_value' => 'numeric',
            'max_value' => 'numeric',
            'value_mode' => 'numeric|in:' . implode(",", VoucherParameter::VALUE_MODE),
            'value' => 'numeric|in:' . implode(",", VoucherParameter::VALUE) . '|required_if:value_mode,' . VoucherParameter::VALUE_MODE['$'],
            'value_quantity' => 'numeric',
            'voucher_front_image' => 'mimes:' . implode(",", $allowedImageExtensionArray) . '|max:20000',
            'voucher_back_image' => 'mimes:' . implode(",", $allowedImageExtensionArray) . '|max:20000',
        ];

        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        //fron image code start
        $data = $request->all();
        if (!empty($request->file('voucher_front_image'))) {
            $file = $request->file('voucher_front_image');
            $name = $file->getClientOriginalName();
            $name = md5($name) . '.' . $file->getClientOriginalExtension();
            $path = '/voucherparameters/' . $name;
            Storage::put($path, File::get($file->getRealPath()));
            $frontImageUrl = Storage::disk('local')->url($path);
            $data['voucher_front_image'] = $frontImageUrl;
        }

        if (!empty($request->file('voucher_back_image'))) {

            $backImage = $request->file('voucher_back_image');
            $backImageName = $backImage->getClientOriginalName();
            $backImageName = md5($backImageName) . '.' . $backImage->getClientOriginalExtension();
            $backImagePath = '/voucherparameters/' . $backImageName;
            Storage::put($backImagePath, File::get($backImage->getRealPath()));
            $backImageUrl = Storage::disk('local')->url($backImagePath);
            $data['voucher_back_image'] = $backImageUrl;
        }

        $voucherParameter = $this->voucherRepo->addVoucherParameter($organization, $data);
        return api_response($voucherParameter);
    }

    /**
     * Generate Voucher From Voucher Parameters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateVoucher(Request $request)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'promo_id' => 'required|exists:voucher_parameters,promo_id',
            'customer_name' => 'string|required',
            'customer_email' => 'required|email',
        ];

        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /* @var $voucherParameter  VoucherParameter */
        $voucherParameter = $organization->voucherParameters()->where('voucher_parameters.promo_id', $request->get('promo_id'))->first();

        if (empty($voucherParameter)) {
            return api_error(['promo_id' => 'Invalid voucher parameter promo id']);
        }

        if(!empty($voucherParameter->min_value) && !empty($request->get('variable_value'))){
            if($voucherParameter->min_value > $request->get('variable_value')){
                return api_error(['error' => 'Value cannot be less then '.$voucherParameter->min_value]);
            }
        }

        if(!empty($voucherParameter->max_value) && !empty($request->get('variable_value'))){
            if($voucherParameter->max_value < $request->get('variable_value')){
                return api_error(['error' => 'Value cannot be greater then '.$voucherParameter->min_value]);
            }
        }

        if ($voucherParameter->limited == VoucherParameter::LIMITED_QUANTITY['Yes']) {
            $voucherLimitedQuantity = $voucherParameter->limited_quantity;
            $generatedVoucherCount = $voucherParameter->vouchers()->count();
            if ($voucherLimitedQuantity <= $generatedVoucherCount) {
                return api_error(['Voucher generation limit exceeded.']);
            }
        }
        $data = $request->all();
        $voucher = $this->voucherRepo->generateVoucher($organization, $voucherParameter, $data);

        return api_response($voucher);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function voucherCheck(Request $request)
    {

        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'voucher_code' => 'required|exists:vouchers,voucher_code',
        ];
        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $voucher = $organization->vouchers()->where('voucher_code', $request->get('voucher_code'))
            ->with([
                'organization' => function ($query) {
                    $query->select('id', 'name');
                },
                'voucherParameter' => function ($q) {
                    $q->select('id', 'promo_id');
                }
            ])->first();

        if (empty($voucher)) {
            return api_error(['voucher_code' => 'Invalid Organisation, not valid at this organization']);
        }

        //voucher is already validated
        if ($voucher->status == Voucher::VOUCHER_STATUS['Validated']) {
            return api_response($voucher,null,'Voucher is already validated');
        }

        //voucher is expired.
        if ($voucher->status == Voucher::VOUCHER_STATUS['Expired']) {
            return api_response($voucher,null,'Voucher has been Expired');
        }

        return api_response($voucher); //return response if voucher is validated.
    }

    public function voucherValidation(Request $request)
    {
        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'voucher_code' => 'required|exists:vouchers,voucher_code',
            'amount_to_redeem' => 'numeric',
            'voucher_check' => 'required|in:1,2'
        ];

        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region If we want to check the voucher before validation.
        if ($request->get('voucher_check') == IStatus::ACTIVE) {
            $voucher = $organization->vouchers()->where('voucher_code', $request->get('voucher_code'))
                ->with([
                    'organization' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'voucherParameter' => function ($q) {
                        $q->select('id', 'promo_id');
                    }
                ])->first();

            if (empty($voucher)) {
                return api_error(['voucher_code' => 'Invalid Organisation, not valid at this organization']);
            }

            //voucher is already validated
            if ($voucher->status == Voucher::VOUCHER_STATUS['Validated']) {
                return api_response($voucher,null,'Voucher is already validated');
            }

            //voucher is expired.
            if ($voucher->status == Voucher::VOUCHER_STATUS['Expired']) {
//            return api_error(['voucher_code' => 'Voucher has Expired']);
                return api_response($voucher,null,'Voucher has been Expired');
            }
        }
        //endregion

        /**
         * @var  $voucher Voucher
         */
        $voucher = $organization->vouchers()->where('voucher_code', $request->get('voucher_code'))->first();
        if (!$voucher) {
            return api_error(['voucher_code' => 'Invalid Organisation, not valid at this organization']);
        }


        $amountToValidate = $request->get('amount_to_redeem');

        $this->voucherRepo->validateVoucher($voucher, $amountToValidate);

        return api_response($voucher, null, 'Voucher Validated');
    }

    /**
     * Will return the list of active parameters for current organization.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVoucherParameterList(Request $request)
    {
        /* @var $organization  Organization */
        $organization = $request->get(Organization::NAME);
        $voucherParameterList = $organization->voucherParameters()->where([
            'status' => IStatus::ACTIVE,
        ])
//            ->select(['voucher_parameters.id', 'voucher_parameters.voucher_name', 'promo_id', 'availability', 'voucher_front_image', 'voucher_back_image', 'voucher_parameters.value'])
            ->get();
        return api_response($voucherParameterList);
    }

    public function getVoucherLogs(Request $request)
    {

        /* @var  $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'voucher_code' => 'required|exists:vouchers,voucher_code'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /* @var $voucher Voucher */
        $voucher = $organization->vouchers()->where('voucher_code', $request->get('voucher_code'))->first();

        if (empty($voucher)) {
            return api_error(null, ['voucher_code' => 'Invalid voucher code']);
        }

        $logs = $voucher->logs()->with(['organization' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        return api_response($logs);
    }

    /**
     * @param Request $request
     * @param $promoCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVoucherList(Request $request, $promoCode)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);

        /* @var  $voucherParameter VoucherParameter */
        $voucherParameter = $organization->voucherParameters()->where('voucher_parameters.promo_id', $promoCode)->first();

        if (empty($voucherParameter)) {
            return api_error(['error' => 'Invalid promo code']);
        }

        $vouchers = $voucherParameter->vouchers;

        return api_response($vouchers);

    }

    /**
     * @param Request $request
     * @param $voucherCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVoucherDetails(Request $request, $voucherCode)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);

        /* @var  $voucher VoucherParameter */
        $voucher = $organization->vouchers()->where('vouchers.voucher_code', $voucherCode)->with([
            'organization' => function($query){
                $query->select('id','name');
            }
        ])->first();

        if (empty($voucher)) {
            return api_error(['error' => 'Invalid voucher code']);
        }

        return api_response($voucher);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getBirthdayVouchers(Request $request)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);
        $birthdayVoucherList = $organization->voucherParameters()->where('voucher_type', VoucherParameter::VOUCHER_TYPE['Birthday'])->pluck('voucher_name','voucher_parameters.id');
        return api_response($birthdayVoucherList);
    }

    public function saveVoucherImage(Request $request)
    {


         /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'voucher_id' => 'required|exists:vouchers,id',
            'voucher_image' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /**
         * @var $voucher Voucher
         */
        $voucher = $organization->vouchers()->where([
            'id' => $request->get('voucher_id')
        ])->first();

        if(!$voucher){
            return api_error(['error' => 'Voucher not found']);
        }

        $file = $request->file('voucher_image');
        $name = $voucher->voucher_code.'-'.time() . '.' . 'png';
        $path = '/'.$organization->id.'-vouchers/' . $name;
        Storage::put($path, File::get($file->getRealPath()));
        $url = Storage::disk('local')->url($path);

        $voucher->front_image = $url;
        $voucher->save();

        return api_response($voucher);
    }


    public function sendVoucherAsEmail(Request $request)
    {
         /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'voucher_id' => 'required|exists:vouchers,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /**
         * @var $voucher Voucher
         */
        $voucher = $organization->vouchers()->where([
            'id' => $request->get('voucher_id')
        ])->first();

        if ( !$voucher) {
            return api_error(['error' => 'Voucher not found']);
        }

        $member = Member::whereEmail($voucher -> customer_email ) -> first();

        if ( !$member) {
            return api_error(['error' => 'Member not found']);
        }

        try{

            if(!empty($voucher) && !empty($voucher->customer_email) && !empty($voucher->front_image)){
                Mail::send('email.empty',['memberName' => 'Faisal'],function (\Illuminate\Mail\Message $message) use ($organization, $member,$voucher) {
                    $message
                        ->from(config('emailsettings.ORGANIZATION_SIGNUP_EMAIL'),config('emailsettings.ORGANIZATION_SIGNUP_NAME'))
                        ->subject('Voucher')
                        ->to($voucher->customer_email)
                        ->attach($voucher->front_image)
                        ->embedData([
                            'personalizations' => [
                                [
                                    'dynamic_template_data' => [
                                        'memberFullName' =>  $member->first_name . ' ' . $member->last_name,
                                        'organizationName' => $organization->name
                                    ]
                                ]
                            ],
                            'template_id' => \Config::get('emailsettings.SEND_VOUCHER_EMAIL_TEMPLATE'),
                        ], 'sendgrid/x-smtpapi');
                });
                return api_response($voucher,null,'Email has been sent to the member');
            }else{
                return api_response(null,null,'Voucher image not ready yet');
            }

        }catch (\Swift_TransportException $exception){
            \Log::info($exception->getMessage().'= = email: '. $voucher->customer_email);
            return api_error(['error' => 'Unable to send voucher to the member']);
        }

    }
}