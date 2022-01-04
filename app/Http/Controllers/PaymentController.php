<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IStatus;
use App\Helpers\ApiHelper;
use App\Mail\Member\SendTransactionReciept;
use App\Member;
use App\Organization;
use App\OrganizationSetting;
use App\Payment;
use App\Receipt;
use App\repositories\MemberRepository;
use App\repositories\PaymentRepository;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mail;

class PaymentController extends Controller
{
    /* @var $paymentRepo PaymentRepository */
    public $paymentRepo;

    public function __construct()
    {
        $this->paymentRepo = new PaymentRepository();
    }

    public function getMemberPaymentList(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $payments = $organization->payments()->whereItemType(Payment::ITEM_TYPE['MEMBER'])->where('status', '!=', IStatus::INACTIVE)
            ->with(['organization' => function ($query) {
                $query->select('id');
                $query->with(['details' => function ($query) {
                    $query->select('organization_id', 'tax_factor');
                }]);
            }])
            ->get();

        return api_response($payments);
    }

    public function create(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'amount' => 'numeric',
            'organization_id' => 'required|exists:organizations,id',
            'card' => 'numeric',
            'email' => 'numeric',
            'member_id' => 'required|exists:members,id',
            'subscription_id' => 'required|exists:subscriptions,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $member = $organization->members()->find(array_get($request->input(), 'member_id'));
            $validator->after(function ($validator) use ($member) {
                if (!$member) {
                    $validator->getMessageBag()->add('member_id', 'Invalid member');
                }
            });

            if (!$validator->fails()) {
                $payment = $this->paymentRepo->addMemberPayment($request->input());
                return api_response([$payment]);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function update(Request $request)
    {
        $validationRules = [
            'id' => 'required|exists:payments,id',
            'organization_id' => 'required|exists:organizations,id',
        ];

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 3) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']), IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);
        //region Validating and updating the fields
        $paymentField = null;
        $paymentFieldValue = null;
        $validKey = false;

        if (!$validator->fails()) {

            //region Validating Field Name
            foreach ($fields as $key => $value) {
                if ($key != 'organization_id' && $key != 'id') {
                    if (in_array($key, Payment::AUTHORISED_FIELDS)) {
                        $paymentField = $key;
                        $paymentFieldValue = $value;
                        $validKey = true;
                    } else {
                        $validator->getMessageBag()->add($key, 'Invalid Field Name');
                        return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                    }
                }
            }
            //endregion

            if ($validKey) {
                $payment = Payment::where(['id' => $request->id, 'organization_id' => $request->organization_id])->first();
                if ($payment) {
                    if ($paymentField == 'discount') {
                        $total = $payment->total + $payment->discount;
                        $payment->total = $total - $paymentFieldValue;
                    }
                    $payment->$paymentField = $paymentFieldValue;
                    $payment->save();
                    $payment->refresh();
                    return api_response($payment);
                } else {
                    $validator->getMessageBag()->add('organization_id', 'This payment is not assosiated with this Organization.');
                    return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                }
            }
        } else {
            return api_error($validator->errors());
        }
    }

    public function delete(Request $request)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);


        $validationRules = [
            'id' => 'required|exists:payments,id',
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $payment = $organization->payments()->find($request->get('id'));
            $validator->after(function ($validator) use ($payment) {
                if (!$payment) {
                    $validator->getMessageBag()->add('id', 'Invalid payment id');
                }
            });

            if (!$validator->fails()) {
                $payment->delete();
                return api_response([], null, 'Payment Successfully deleted');
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }

    }

    public function archiveAllPayments(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation hereu
            });

            if (!$validator->fails()) {

                $organization->payments()->update([
                    'status' => IStatus::INACTIVE
                ]);

                return api_response([], null, 'Reports Archived');
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function createTransaction(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'required|numeric|exists:payments,id',
            'total' => 'numeric',
            'send_email' => 'numeric',
            'payment_type' => 'required|exists:payment_types,name',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $payments = $organization->payments()->whereIn('id', $request->get('payment_ids'))->get();
            $validator->after(function ($validator) use ($payments) {
                if (empty($payments)) {
                    $validator->getMessageBag()->add('payment_ids', 'No Payment Found');
                }
            });
            $result = $this->paymentRepo->createTransaction($organization, $request->all(), $payments);
            return api_response($result);
        } else {
            return api_error($validator->errors());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function createPartPayment(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'required|numeric|exists:payments,id',
            'total' => 'numeric',
            'send_email' => 'numeric',
            'payment_type' => 'required|exists:payment_types,name',
            'part_pays' => 'array',
            'part_pays.*.payment_type_name' => 'exists:payment_types,name',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        if(!empty($request->get('part_pays')) && count($request->get('part_pays')) > 0 ){
            $totalPartPays = 0 ;
            foreach ($request->get('part_pays') as $item) {
                $totalPartPays += array_get($item,'amount');
            }

            if($totalPartPays > $request->get('total')){
                return api_error(['error' => 'Amount is more than the balance and not allow the payment to process']);
            }
        }
        $payments = $organization->payments()->whereIn('id', $request->get('payment_ids'))->get();
        if (empty($payments)) {
            return api_error(['payment_ids' => 'No Payment Found']);
        }

        //this will add a transaction , add a receipt,
        $result = $this->paymentRepo->createTransaction($organization, $request->all(),$payments,true);
        return api_response($result);
    }

}
