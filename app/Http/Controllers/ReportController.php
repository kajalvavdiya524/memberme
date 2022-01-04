<?php

namespace App\Http\Controllers;

use App\base\IStatus;
use App\Member;
use App\MemberOther;
use App\Organization;
use App\Payment;
use Carbon\Carbon;
use Config;
use DB;
use Storage;
use function foo\func;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function memberReport(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('post_max_size', '500M');
        set_time_limit(0);

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'field_names' => 'array',
            'field_names.*.field_name' => 'required|string',
            'field_names.*.operator' => 'required|string',
        ];

        $validator = Validator($request->all(), $validationRules);

        $authorizedMemberFileds = Member::AUTHORISED_FIELDS;    //members table authorised fields
        $authorisedMemberOtherFields = MemberOther::AuthorizedFields();     //member_others table authorised fields
        $authorisedAddressFields = Member::ADDRESS_FIELDS;  //address table authorised fields

        $authorisedFields = array_merge($authorizedMemberFileds, $authorisedMemberOtherFields);      //all authorised fieslds
        $authorisedFields = array_merge($authorisedFields, $authorisedAddressFields);   //all authorise


        if (!$validator->fails()) {

            $validator->after(function ($validator) use ($request, $authorisedFields) {
                $fieldNames = $request->get('field_names');
                if (is_array($fieldNames)) {
                    foreach ($fieldNames as $fieldName) { //checking if field is invalid
                        if (!in_array($fieldName['field_name'], $authorisedFields)) {
                            $validator->getMessageBag()->add($fieldName['field_name'], 'Invalid Field Name');
                        }
                    }
                }

            });

            if (!$validator->fails()) {
                $searchFromMember = [];
                $searchFromMemberOther = [];
                $searchFromAddress = [];
                $fieldNames = $request->get('field_names');
                if (is_array($fieldNames)) {
                    foreach ($request->get('field_names') as $fieldName) {
                        if (in_array($fieldName['field_name'], $authorizedMemberFileds)) {
                            $searchFromMember [] = $fieldName;
                        }
                        if (in_array($fieldName['field_name'], $authorisedMemberOtherFields)) {
                            $searchFromMemberOther [] = $fieldName;
                        }

                        if (in_array($fieldName['field_name'], $authorisedAddressFields)) {
                            $searchFromAddress [] = $fieldName;
                        }

                    }
                }
                $resultSet = $organization->members();
                foreach ($searchFromMember as $item) {
                    $operator = array_get($item, 'operator');
                    $value = array_get($item, 'value');
                    $columnName = array_get($item, 'field_name');
                    if ($operator == 'contains') {
                        $operator = 'like';
                        $value = '%' . array_get($item, 'value') . '%';
                    }

                    if ($operator == 'is_yes') {
                        $operator = '=';
                        $value = 1;
                    }
                    if ($operator == 'no') {
                        $operator = '=';
                        $value = 2;
                    }

                    if ($operator == 'begin_with') {
                        $operator = 'like';
                        $value = array_get($item, 'value') . '%';
                    }

                    if ($operator == 'ends_in') {
                        $operator = 'like';
                        $value = '%' . array_get($item, 'value');
                    }

                    if ($operator == 'not like') {
                        $value = array_get($item, 'value') . '%';
                    }

                    $resultSet->where($columnName, $operator, $value);
                }

                foreach ($searchFromMemberOther as $item) {
                    $resultSet->whereHas('others', function ($query) use ($item) {
                        $operator = array_get($item, 'operator');
                        $value = array_get($item, 'value');
                        $columnName = array_get($item, 'field_name');

                        if ($operator == 'contains') {
                            $operator = 'like';
                            $value = '%' . array_get($item, 'value') . '%';
                        }

                        if ($operator == 'begin_with') {
                            $operator = 'like';
                            $value = array_get($item, 'value') . '%';
                        }


                        if ($operator == 'is_yes') {
                            $operator = '=';
                            $value = 1;
                        }
                        if ($operator == 'no') {
                            $operator = '=';
                            $value = 2;
                        }
                        if ($operator == 'ends_in') {
                            $operator = 'like';
                            $value = '%' . array_get($item, 'value');
                        }

                        if ($operator == 'not like') {
                            $value = array_get($item, 'value') . '%';
                        }

                        $query->where($columnName, $operator, $value);
                    });
                }
                foreach ($searchFromAddress as $item) {
                    $resultSet->whereHas('physicalAddress', function ($query) use ($item) {
                        $operator = array_get($item, 'operator');
                        $value = array_get($item, 'value');
                        $columnName = array_get($item, 'field_name');
                        if ($columnName == 'address') {
                            $columnName = 'address1';
                        }

                        if ($operator == 'contains') {
                            $operator = 'like';
                            $value = '%' . array_get($item, 'value') . '%';
                        }

                        if ($operator == 'begin_with') {
                            $operator = 'like';
                            $value = array_get($item, 'value') . '%';
                        }


                        if ($operator == 'is_yes') {
                            $operator = '=';
                            $value = 1;
                        }
                        if ($operator == 'no') {
                            $operator = '=';
                            $value = 2;
                        }

                        if ($operator == 'ends_in') {
                            $operator = 'like';
                            $value = '%' . array_get($item, 'value');
                        }

                        if ($operator == 'not like') {
                            $value = array_get($item, 'value') . '%';
                        }

                        $query->where($columnName, $operator, $value);
                    });

                    $resultSet->orWhereHas('postalAddress', function ($query) use ($item) {
                        $operator = array_get($item, 'operator');
                        $value = array_get($item, 'value');
                        $columnName = array_get($item, 'field_name');

                        if ($operator == 'contains') {
                            $operator = 'like';
                            $value = '%' . array_get($item, 'value') . '%';
                        }

                        if ($operator == 'begin_with') {
                            $operator = 'like';
                            $value = array_get($item, 'value') . '%';
                        }


                        if ($operator == 'is_yes') {
                            $operator = '=';
                            $value = 1;
                        }
                        if ($operator == 'no') {
                            $operator = '=';
                            $value = 2;
                        }

                        if ($operator == 'ends_in') {
                            $operator = 'like';
                            $value = '%' . array_get($item, 'value');
                        }

                        if ($operator == 'not like') {
                            $value = array_get($item, 'value') . '%';
                        }

                        $query->where($columnName, $operator, $value);
                    });

                }

                try {
                    $members = $resultSet->get();
                } catch (\Exception $exception) {
                    return api_error(['error' => 'Invalid Operators or Values']);
                }
                return api_response($members);
            } else {
                return api_error($validator->errors());
            }
        } else {
            return api_error($validator->errors());
        }
    }

    public function reportOperators(Request $request)
    {
        $authorisedOperator = Member::Authorised_operators;
        return api_response($authorisedOperator);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function memberStatusReport(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        ini_set('memory_limit', '1024M');
        ini_set('post_max_size', '500M');
        set_time_limit(0);

        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'subscription' => 'exists:subscriptions,id',
            'status' => 'array'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {

            });

            if (!$validator->fails()) {
                $resultSet = $organization->members();
                if (!empty($request->get('status'))) {
                    $resultSet->whereIn('members.status', $request->get('status'));
                } else {
                    $resultSet->where('status', IStatus::ACTIVE);
                }
                if (!empty($request->get('subscription'))) {
                    $resultSet->where('subscription_id', $request->get('subscription'));
                }

                if (!empty($request->get('group_by'))) {
                    $startDate = Carbon::now();
                    $endDate = Carbon::now();
                    switch ($request->get('group_by')) {
                        case 'today':
                            $startDate = $startDate->subDays(1);
                            break;
                        case 'this_month':
                            $startDate = $startDate->subMonth(1);
                            break;
                        case 'last_15_days':
                            $startDate = $startDate->subDays(15);
                            break;
                        case 'this_year':
                            $startDate = $startDate->subYear(1);
                            break;
                    }
//                    $resultSet->whereBetween('created_at',[$startDate,$endDate]);
                }
                $members = $resultSet->with([
                    'groups' => function ($query) {
                        $query->where('is_status_group', '!=', IStatus::ACTIVE);
                    },

                    'others',

                    'physicalAddress' => function ($query) {
                        $query->with('country');
                    },
                    'postalAddress' => function ($query) {
                        $query->with('country');
                    }, 'organization' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'subscription' => function ($query) {
                        $query->select('id', 'title');
                    },
                ])->get();

                foreach ($members as $member) {
                    $member->setAppends([]);
                }

                return api_response($members);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function memberStatusReportUrl(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        ini_set('memory_limit', '1024M');
        ini_set('post_max_size', '500M');
        set_time_limit(0);

        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'subscription' => 'exists:subscriptions,id',
            'status' => 'array'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {

            });

            if (!$validator->fails()) {
                $resultSet = $organization->members();
                if (!empty($request->get('status'))) {
                    $resultSet->whereIn('members.status', $request->get('status'));
                } else {
                    $resultSet->where('status', IStatus::ACTIVE);
                }
                if (!empty($request->get('subscription'))) {
                    $resultSet->where('subscription_id', $request->get('subscription'));
                }

                if (!empty($request->get('group_by'))) {
                    $startDate = Carbon::now();
                    $endDate = Carbon::now();
                    switch ($request->get('group_by')) {
                        case 'today':
                            $startDate = $startDate->subDays(1);
                            break;
                        case 'this_month':
                            $startDate = $startDate->subMonth(1);
                            break;
                        case 'last_15_days':
                            $startDate = $startDate->subDays(15);
                            break;
                        case 'this_year':
                            $startDate = $startDate->subYear(1);
                            break;
                    }
//                    $resultSet->whereBetween('created_at',[$startDate,$endDate]);
                }
                $members = $resultSet->with([
                    'groups' => function ($query) {
                        $query->where('is_status_group', '!=', IStatus::ACTIVE);
                    },

                    'others',

                    'physicalAddress' => function ($query) {
                        $query->with('country');
                    },
                    'postalAddress' => function ($query) {
                        $query->with('country');
                    }, 'organization' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'subscription' => function ($query) {
                        $query->select('id', 'title');
                    },
                ])->get();

                foreach ($members as $member) {
                    $member->setAppends([]);
                }

//                $newJsonString = json_encode($members, JSON_PRETTY_PRINT);
                $fileName = 'reports/' . $organization->name . '.json';
                Storage::drive('local')->put($fileName, $members);
                $url = Storage::drive('local')->url($fileName);

                return api_response($url);

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
     */
    public function newMemberReport(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = $organization->members();
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('members.created_at', [$startDate, $endDate]);
        }
        $members = $query->select(
            'organization_id',
            'id',
            'member_id',
            'contact_no',
            'physical_address_id',
            'date_of_birth',
            'joining_date',
            'renewal',
            'gender',
            'first_name',
            'last_name',
            'status',
            'subscription_id',
            'created_at',
            'subscription',

        )->with(
            [
                'others' => function ($query) {
                    $query->select(
                        'id',
                        'member_id',
                        'secondary_member_id',
                        'proposer_member_id',
                        'transferred_from',
                        );
                    $query->with(['member' => function ($q) {
                        $q->select('id', 'first_name');
                    }]);
                },
                'physicalAddress',
                'organization' => function ($q){
                    $q->select('id');
                }
            ])->get();

        return api_response($members);
    }

    public function memberBirthdaysReport(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];


        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $months = $request->get('months');
        $query = $organization->members();
        if (!empty($startDate) && !empty($endDate)) {
            $startDay = date('d', strtotime($startDate));
            $endDay = date('d', strtotime($endDate));

//            dd($startDay, $endDay);
//            $query->whereBetween('date_of_birth', [$startDate, $endDate]);
            $query->whereBetween(DB::raw('MONTH(date_of_birth)'), [date('m', strtotime($startDate)), date('m', strtotime($endDate))]);
            $query->whereBetween(DB::raw('DAY(date_of_birth)'), [$startDay, 31]);
            $query->whereBetween(DB::raw('DAY(date_of_birth)'), [01, $endDay]);
        }
        if (!empty($months)) {
            foreach ($months as $month) {
                $query->whereIn(DB::raw('MONTH(date_of_birth)'), $months);
            }
        }

        $members = $query->select('id', 'member_id', 'first_name', 'last_name', 'date_of_birth')
            ->orderBy('date_of_birth', 'asc')
            ->get();

        return api_response($members);
    }

    public function memberRenewalReport(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];


        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $months = $request->get('months');
        $query = $organization->members()->notResigned();
        if (!empty($startDate) && !empty($endDate)) {
            $startDay = date('d', strtotime($startDate));
            $endDay = date('d', strtotime($endDate));

            $query->whereBetween(DB::raw('MONTH(renewal)'), [date('m', strtotime($startDate)), date('m', strtotime($endDate))]);
            $query->whereBetween(DB::raw('DAY(renewal)'), [$startDay, 31]);
            $query->whereBetween(DB::raw('DAY(renewal)'), [01, $endDay]);
        }
        if (!empty($months)) {
            foreach ($months as $month) {
                $query->whereIn(DB::raw('MONTH(renewal)'), $months);
            }
        }

        $members = $query->select('members.id', 'member_id', 'members.email', 'first_name', 'last_name', 'subscription_id', 'payment_method', 'payment_frequency', 'postal_address_id')
            ->addSelect(DB::raw('date(renewal)   as renewal'))
            ->with(['subscription' => function ($query) {
                $query->select('id', 'title', 'subscription_fee');
            }, 'postalAddress'])
            ->orderBy('renewal', 'asc')
            ->get();

        return api_response($members);
    }

    public function memberPaymentReport(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $months = $request->get('months');
        $query = $organization->payments()
            ->whereNotNull('transaction_id')
            ->whereRaw('(Select count(id) from transactions where transactions.id = payments.transaction_id) > 0')
            ->with(['transaction' => function ($query) {
                $query->whereNotNull('receipt_id');
                $query->select('transactions.id', 'receipt_id');
            }])
            ->where('item_type', Payment::ITEM_TYPE['MEMBER'])
            ->select('item_id', 'transaction_id', 'total', 'first_name', 'last_name', 'payments.created_at', 'member_id')
            ->orderBy('payments.id', 'desc')
            ->join('members', 'members.id', '=', 'item_id');


        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('payments.created_at', [$startDate, $endDate]);
        }

        $result = $query->get();

        return api_response($result);
    }

    public function memberGroupReport(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        $groupIds = $request->get('group_ids');
        $noGroup = false;

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $query = $organization->groups()
            ->where(function ($query) {
                $query->where('groups.is_status_group', 0);
            })
            ->whereHas('members')->with(['members' => function ($query) use ($request) {
                $query->select('members.id', 'first_name', 'last_name', 'members.member_id', 'members.status');

                if (!empty($request->get('statuses'))) {
                    $query->whereIn('members.status', $request->get('statuses'));
                }
            }]);

        if (!empty($groupIds)) {
            if ($groupIds == 'No_Name') {
                $noGroup = true;
            } else {
                $query->whereIn('groups.id', $groupIds);
            }
        }
        if ($noGroup) {
            $result = $organization->members()->whereDoesntHave('groups')->select('first_name', 'last_name', 'members.member_id', 'id', 'members.status')->get();
        } else {
            $result = $query->get();
        }
        return api_response($result);
    }


    public function memberCardPrintingReport(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $months = $request->get('months');
        $query = $organization->payments()
            ->whereNotNull('transaction_id')
            ->whereRaw('(Select count(id) from transactions where transactions.id = payments.transaction_id) > 0')
            ->with(['transaction' => function ($query) {
                $query->whereNotNull('receipt_id');
                $query->select('transactions.id', 'receipt_id');
            }])
            ->where('item_type', Payment::ITEM_TYPE['MEMBER'])
            ->select('item_id', 'payments.transaction_id', 'members.renewal', 'subscriptions.title as member_subscription', 'first_name', 'last_name', 'payments.created_at as payment_created_at', 'member_id')
            ->orderBy('payments.id', 'desc')
            ->join('members', 'members.id', '=', 'item_id')
            ->join('subscriptions', 'subscriptions.id', '=', 'members.subscription_id');


        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('payments.created_at', [$startDate, $endDate]);
        }

        $result = $query->get();

        return api_response($result);
    }

}
