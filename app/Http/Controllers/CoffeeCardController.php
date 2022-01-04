<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IStatus;
use App\base\IUserType;
use App\CoffeeCard;
use App\CoffeeCardReward;
use App\Helpers\ApiHelper;
use App\MemberCoffeeCard;
use App\MemberCoffeeCardReward;
use App\Organization;
use App\repositories\CoffeeCardRepository;
use Illuminate\Http\Request;

class CoffeeCardController extends Controller
{

    /**
     * @var CoffeeCardRepository
     */
    public $coffeeCardRepository;

    public function __construct()
    {
        $this->coffeeCardRepository = new CoffeeCardRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
//            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $coffeeCards = $this->coffeeCardRepository->userCoffeeCardList($organization);
        $response = \DataTables::of($coffeeCards)->make(true);
        return api_response($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'card_name' => 'required',
            'rewards' => 'array',
            'rewards.*.expiry' => 'numeric|required|in:' . implode(",", CoffeeCardReward::EXPIRY),
            'rewards.*.expiry_mode' => 'string|required_if:rewards.*.expiry,1|in:' . implode(",", CoffeeCardReward::EXPIRY_MODE),
            'rewards.*.expiry_period_quantity' => 'numeric|required_if:rewards.*.expiry_mode,Period',
            'rewards.*.expiry_period_duration' => 'string|required_if:rewards.*.expiry_mode,Period|in:' . implode(",", CoffeeCardReward::EXPIRY_DURATION),
            'rewards.*.expiry_date' => 'required_if:rewards.*.expiry_mode,Date',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /**
         * @var $coffeeCard CoffeeCard
         */
        $coffeeCard = $this->coffeeCardRepository->createCoffeeCard($request->all(), $organization);
        $result = CoffeeCard::whereId($coffeeCard->id)->with(['rewards', 'organizations' => function ($q) {
            $q->select('organizations.id', 'name');
        }])->get();
        return api_response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $cardCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $cardCode)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        /** @var CoffeeCard $coffeeCard */
        if(ApiHelper::getApiUser()->hasRole(IUserType::SUPER_ADMIN)){
            $coffeeCard = CoffeeCard::whereCardCode($cardCode)->with('rewards')->first();
        }else{
            $coffeeCard = $organization->coffeeCards()->where('card_code', $cardCode)->with('rewards')->first();
        }

        if(empty($coffeeCard)){
            return api_error(['error' => ['Invaid coffee card code']]);
        }
        
        return api_response($coffeeCard);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function adminCoffeeCardList(Request $request)
    {
        /** @var CoffeeCard [] $coffeeCardList */
        $coffeeCardList = $this->coffeeCardRepository->adminCoffeeCardList();
        $response = \DataTables::of($coffeeCardList)->make(true);
        return api_response($response);
    }

    /**
     * Assignment of Coffee Card to The organization. | admin protected api call | val-04-22
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignOrganization(Request $request)
    {

        $validationRules = [
            'organization_to_assign_id' => 'required|exists:organizations,id',
            'coffee_card_id' => 'required|exists:coffee_cards,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var Organization $organizationToAssign */
        $organizationToAssign = Organization::find($request->get('organization_to_assign_id'));
        /** @var CoffeeCard $coffeeCardToAssign */
        $coffeeCardToAssign = CoffeeCard::find($request->get('coffee_card_id'));

        /** @var CoffeeCard $assignedCoffeeCard */
        $assignedCoffeeCard = $this->coffeeCardRepository->assignCoffeeCardToOrganization($organizationToAssign, $coffeeCardToAssign);

        $result = CoffeeCard::whereId($assignedCoffeeCard->id)->with(['rewards', 'organizations' => function ($q) {
            $q->select('organizations.id', 'name');
        }])->get();
        return api_response($result);
    }


    /**
     * Detachment of Coffee Card from the organization. | admin protected api call | val-04-23
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachOrganization(Request $request)
    {

        $validationRules = [
            'organization_to_assign_id' => 'required|exists:organizations,id',
            'coffee_card_id' => 'required|exists:coffee_cards,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var Organization $organizationToAssign */
        $organizationToAssign = Organization::find($request->get('organization_to_assign_id'));
        /** @var CoffeeCard $coffeeCardToAssign */
        $coffeeCardToAssign = CoffeeCard::find($request->get('coffee_card_id'));

        /** @var CoffeeCard $assignedCoffeeCard */
        $assignedCoffeeCard = $this->coffeeCardRepository->detachOrganizationFromCoffeeCard($organizationToAssign, $coffeeCardToAssign);

        $result = CoffeeCard::whereId($assignedCoffeeCard->id)->with(['rewards', 'organizations' => function ($q) {
            $q->select('organizations.id', 'name');
        }])->get();
        return api_response($result);
    }

    public function checkMemberCoffeeCard(Request $request)
    {
        /** @var Organization $organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
//            'organization_id' => 'required|exists:organizations,id',
            'member_coffee_card_code' => 'required|exists:member_coffee_cards,code',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var MemberCoffeeCard $card */
        $card = MemberCoffeeCard::whereCode($request->get('member_coffee_card_code'))->first();

        return api_response(['stamps_earned' => $card->stamp_balance]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStamp(Request $request)
    {
        /** @var Organization $organization */
        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'member_coffee_card_code' => 'required|exists:member_coffee_cards,code',
            'stamps_to_add' => 'required|numeric',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var MemberCoffeeCard $memberCoffeeCard */
        $memberCoffeeCard = MemberCoffeeCard::whereCode($request->get('member_coffee_card_code'))
            ->Where('status', MemberCoffeeCard::STATUS['ACTIVE'])->first();

        if (empty($memberCoffeeCard)) {
            return api_error(['error' => 'Invalid member coffee card code.']);
        }


        $coffeeCard = $memberCoffeeCard->coffeeCard;
        $coffeeCardOrganization = $coffeeCard->organizations()->where('organizations.id', $organization->id)->first();

        if (empty($coffeeCardOrganization)) {
            return api_error(['error' => ['Card is not associated with your organization']]);
        }


        if ($request->get('stamps_to_add') > $memberCoffeeCard->stamp_required) {
            return api_error(['error' => 'You cannot add more then ' . $memberCoffeeCard->stamp_required . ' stamp(s) for this card.']);
        }

        /** @var MemberCoffeeCard $memberUpdatedCard */
        $result = $this->coffeeCardRepository->addStampOnMemberCoffeeCard($memberCoffeeCard, $request->all(), $organization->id);
        $memberUpdatedCard = array_get($result, 'member_coffee_card');
        return api_response($memberUpdatedCard, null, array_get($result, 'message'));
    }

    /**
     * Endpoint for redeem member coffee card reward.
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|static[]
     */
    public function redeemMemberCoffeeCard(Request $request)
    {
        /** @var Organization $organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_reward_code' => 'required|exists:member_coffee_card_rewards,code',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $memberCoffeeCardReward = MemberCoffeeCardReward::whereCode($request->get('member_reward_code'))
            ->whereHas('member',
                function ($query) use ($organization) {
                    $query->where('members.organization_id', '=', $organization->id);
                    $query->select('id');
                }
            )
            ->first();

        if (empty($memberCoffeeCardReward)) {
            return api_error(['error' => 'Invalid reward code.']);
        }


        if ($memberCoffeeCardReward->status == MemberCoffeeCardReward::STATUS['REDEEMED']) {
            return api_error(['error' => 'Already Redeemed.']);
        }

        if ($memberCoffeeCardReward->status == MemberCoffeeCardReward::STATUS['EXPIRED']) {
            return api_error(['error' => 'Reward have been expired.']);
        }

        $memberCoffeeCardReward = $this->coffeeCardRepository->redeemMemberReward($memberCoffeeCardReward, $organization->id);

        return api_response($memberCoffeeCardReward, null, 'Reward has been redeemed successfully');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function coffeeCardsStats(Request $request)
    {
        $validationRules = [
            'organization_ids' => 'required|array',
            'coffee_card_ids' => 'required|array',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $coffeeCardStats = $this->coffeeCardRepository->coffeeCardStats($request->all());
        return api_response($coffeeCardStats);
    }

    public function getRewardStats(Request $request)
    {
        $validationRules = [
            'organization_ids' => 'required|array',
            'coffee_card_ids' => 'required|array',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $rewardStats = $this->coffeeCardRepository->getRewardStats($request->all());
        return api_response($rewardStats);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getAllAssignedOrganization(Request $request)
    {
        $organizations = $this->coffeeCardRepository->getAllAssignedOrganizations();
        return api_response($organizations);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListForDropdown(Request $request)
    {
        $validationRules = [
            'organization_ids' => 'required|array',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        if (count($request->get('organization_ids')) > 1) {
            $user = ApiHelper::getApiUser();
            if (!$user->hasRole(IUserType::SUPER_ADMIN)) {
                return api_error(['error' => ['Access Denied']], IResponseCode::NOT_ENOUGH_PERMISSIONS);
            }
        }

        $coffeeCardList = $this->coffeeCardRepository->getCoffeeCardListForDropdown($request->get('organization_ids'));
        return api_response($coffeeCardList);
    }
}
