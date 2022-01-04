<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IStatus;
use App\MemberCoffeeCard;
use App\MemberCoffeeCardReward;
use App\Organization;
use App\repositories\CoffeeCardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScannerController extends Controller
{
    /** @var  CoffeeCardRepository */
    private $coffeeCardRepository;

    public function __construct(CoffeeCardRepository $coffeeCardRepository)
    {
        $this->coffeeCardRepository = $coffeeCardRepository;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validationRules = [
            'id' => 'required',
            'password' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $org_id = $request->get('id');
        $password = $request->get('password');

        /** @var Organization $organization */
        $organization = Organization::where([
            'id' => $org_id,
            'password' => $password,
        ])->select(['name', 'scanner_token'])->first();

        if (empty($organization)) {
            return api_error(['error' => ['Invalid Credentials'] ]);
        }

        return api_response($organization, null, 'Successfully login into web scanner', IResponseCode::SUCCESS, IStatus::INACTIVE);
    }

    /**
     * This will return the response for each qr code. either the member coffee card or reward code.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCardDetails(Request $request)
    {

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'qr_code' => 'required'
        ];


        \Log::info('GetCardDetails code:'.$request->get('qr_code'));

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $card = $this->coffeeCardRepository->getCardFromQr($request->get('qr_code') , $organization);

        if ($card) {
            return api_response($card);
        }

        return api_error(['error' => ['Invalid Member Coffee Card Code'] ]);
    }

    public function addStamp(Request $request)
    {

        /** @var Organization $organization */
        $organization =  $request->get(Organization::NAME);
        $validationRules = [
            'member_coffee_card_code' => 'required|exists:member_coffee_cards,code',
            'stamps_to_add' => 'required|numeric',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        \Log::info('GetCardDetails code:'.$request->get('member_coffee_card_code'));
        /** @var MemberCoffeeCard $memberCoffeeCard */
        $memberCoffeeCard = MemberCoffeeCard::whereCode($request->get('member_coffee_card_code'))
            ->Where('status' , MemberCoffeeCard::STATUS['ACTIVE'])->first();

        if(empty($memberCoffeeCard)){
            return api_error(['error' => [ 'Invalid member coffee card code.'] ]);
        }

        $coffeeCard = $memberCoffeeCard->coffeeCard;
        $coffeeCardOrganization = $coffeeCard->organizations()->where('organizations.id', $organization->id)->first();
        if(empty($coffeeCardOrganization)){
            return api_error(['error' => ['Card is not associated with your organization']]);
        }

        if($request->get('stamps_to_add') > $memberCoffeeCard->stamp_required )
        {
            return api_error(['error' => [ 'You cannot add more then ' . $memberCoffeeCard->stamp_required . ' stamp(s) for this card.'] ]);
        }

        /** @var MemberCoffeeCard $memberUpdatedCard */
        $result = $this->coffeeCardRepository->addStampOnMemberCoffeeCard($memberCoffeeCard, $request->all(), $organization->id);
        $memberUpdatedCard = array_get($result,'member_coffee_card');
        return api_response($memberUpdatedCard,null,array_get($result,'message'));
    }

    /**
     * Get Reward Details for the merchant application. mem-01-04
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRewardDetails($code) {
        $reward =  MemberCoffeeCardReward::whereCode($code)->first();
        if(empty($reward)){
            return api_error(['error' => 'Invalid reward code']);
        }

        if ( $reward->status == MemberCoffeeCardReward::STATUS['REDEEMED'])
        {
            return api_error(['error' => [ 'Sorry this reward has already been validated @ '. date('d/m/y h:i',strtotime($reward->redeem_date_time))] ]);
        }


        if ( $reward->status == MemberCoffeeCardReward::STATUS['EXPIRED'])
        {
            return api_error(['error' => [ 'Sorry this reward has been expired.'] ]);
        }

        return api_response($reward);

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function redeemMemberReward(Request $request)
    {

        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'reward_code' => 'required|exists:member_coffee_card_rewards,code',
        ];

        $validator = Validator($request->all(), $validationRules);

        /** @var MemberCoffeeCardReward $reward */
        $reward = MemberCoffeeCardReward::where('code', $request->get('reward_code'))
            ->where('status' , MemberCoffeeCardReward::STATUS['ACTIVE'])->first();

        if(empty($reward)){
            return api_error(['error' =>  [ 'Invalid reward code' ] ]);
        }

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $reward = $this->coffeeCardRepository->redeemMemberReward($reward, $organization->id);

        return api_response($reward,null,'Reward ' . $reward->name .' have been successfully redeemed.');
    }
}
