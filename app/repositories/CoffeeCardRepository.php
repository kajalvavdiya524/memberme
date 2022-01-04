<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 6/24/2019
 * Time: 2:28 AM
 */

namespace App\repositories;

use App\Address;
use App\base\AddressType;
use App\base\IRecordType;
use App\base\IStatus;
use App\CoffeeCard;
use App\CoffeeCardReward;
use App\Exceptions\ApiException;
use App\Member;
use App\MemberCoffeeCard;
use App\MemberCoffeeCardLog;
use App\MemberCoffeeCardReward;
use App\MemberProfile;
use App\Organization;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Parent_;
use Storage;
use function foo\func;

/**
 * Class CoffeeCardRepository
 * @package App\repositories
 */
class CoffeeCardRepository extends BaseAppRepository
{

    /**
     * This function will create the complete coffee card and will add rewards as the reward of this coffee card.
     * all logics related to the creation of coffee card is here.
     *
     * @param $data array
     * @param Organization $organization
     * @return CoffeeCard|\Illuminate\Database\Eloquent\Model
     */
    public function createCoffeeCard($data = [], Organization $organization)
    {
        /* @var $coffeeCard \App\CoffeeCard */
        if (isset($data['id']) && !empty($data['id'])) {
//            $coffeeCard = CoffeeCard::find($data['id']);  //getting coffee Card
        }
        if (empty($coffeeCard)) {
            $coffeeCard = new CoffeeCard();
        }

        /** @var CoffeeCard $coffeeCard */
        $coffeeCard = $this->fill($coffeeCard, $data);  //creating coffee card from the data input
        $coffeeCard->save();        //saving coffee card without attaching to any organization

//        $this->generateCoffeeCardImage($coffeeCard);
        /* The line below have been commented because only admin will create the coffee card and then manually assign to the organizations.*/
        $organization->coffeeCards()->save($coffeeCard);    //attaching coffee card to the organization which have created this.
        //region attaching reward to the coffee card if exists.
        /** @var bool $haveRewardInInput */
        $haveRewardInInput = $this->haveRewardInInput($data);
        if ($haveRewardInInput) {
            /** @var CoffeeCardReward[] $reward */
            $rewards = $this->addRewardToCoffeeCard($coffeeCard, $data);
        }
        //endregion

        return $coffeeCard;

    }

    /**
     * @param $coffeeCard
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fill($coffeeCard, $data = [])
    {
        /* @var $coffeeCard CoffeeCard */
        $coffeeCard = Parent::fill($coffeeCard, $data);
        $coffeeCard->status = IStatus::ACTIVE;
        $coffeeCard->card_code = $this->coffeeCardTemplateCode();   // generating code for coffee Card. xxxx-xxxx
        $coffeeCard->qr_code = $this->getQrCodeLink('coffee-card-QrCodes', $coffeeCard->card_code);   //creating qr_code for card_code.
        $oldBackgroundUrl = (!empty($coffeeCard->background)) ? $coffeeCard->background : null;
        $coffeeCard->background = (!empty($this->uploadCoffeeCardBackgroundImage($data))) ? $this->uploadCoffeeCardBackgroundImage($data) : $oldBackgroundUrl; //uploading and attaching the background image
        return $coffeeCard;
    }

    /**
     * Generation of coffee card template code and member coffee card code.
     * as the required code for both is same, so we will use the parameter to check it from the required table.
     *
     * @param bool $memberCoffeeCardCode
     * @return string
     */
    public function coffeeCardTemplateCode($memberCoffeeCardCode = false)
    {
        $code = Str::random(12);
        if ($memberCoffeeCardCode) {
            $count = MemberCoffeeCard::whereCode($code)->count();
        } else {
            $count = CoffeeCard::whereCardCode($code)->count();
        }
        if ($count <= 0) {
            return strtoupper($code);
        }
        $this->coffeeCardTemplateCode($memberCoffeeCardCode);
    }

    /**
     * @param $path
     * @param $code
     * @return string
     */
    public function getQrCodeLink($path, $code)
    {
        return qrCodeGenerate($path, $code);
    }

    /**
     *
     * @param CoffeeCard $coffeeCard
     * @param array $data
     * @return CoffeeCardReward[]
     */
    public function addRewardToCoffeeCard(CoffeeCard $coffeeCard, $data = array())
    {

        $inputRewards = array_get($data, 'rewards');
        $rewards = [];
        foreach ($inputRewards as $inputReward) {

            /** @var CoffeeCardReward $reward */
            $reward = new CoffeeCardReward();
            $reward->name = array_get($inputReward, 'name');

            if (!empty(array_get($inputReward, 'expiry'))) {
                if (array_get($inputReward, 'expiry') == IStatus::ACTIVE) {
                    $reward->expiry = array_get($inputReward, 'expiry');
                    $reward->expiry_mode = array_get($inputReward, 'expiry_mode');
                    $reward->reward_code = $this->generateRewardCode();
                    $reward->qr_code = $this->getQrCodeLink($coffeeCard->card_code . 'cc-reward-qr', $reward->reward_code);
                    if (array_get($inputReward, 'expiry_mode') == CoffeeCardReward::EXPIRY_MODE['DATE']) {
                        if (array_get($inputReward, 'expiry_date')) {
                            $expiryDate = str_replace('/', '-', array_get($data, 'expiry_date'));
                            $expiryToSet = new Carbon($expiryDate);
                            $reward->expiry_date = $expiryToSet->endOfDay();
                        }
                    } else if (array_get($inputReward, 'expiry_mode') == CoffeeCardReward::EXPIRY_MODE['PERIOD']) {
                        $reward->expiry_period_quantity = array_get($inputReward, 'expiry_period_quantity');
                        $reward->expiry_period_duration = array_get($inputReward, 'expiry_period_duration');
                    }
                }
            }

            $reward->message = array_get($inputReward, 'rewards_message');

            if (!empty(array_get($inputReward, 'image')))
                $reward->image = uploadFile(array_get($inputReward, 'image'), $coffeeCard->card_code . '-coffee-card-reward-image');

            $reward->coffee_card_id = $coffeeCard->id;
            $reward->save();
            $rewards[] = $reward;
        }
        return $rewards;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function haveRewardInInput($data = [])
    {
        if (!empty(array_get($data, 'rewards'))) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadCoffeeCardBackgroundImage($data = [])
    {
        if (!empty(array_get($data, 'background'))) {
            $imageToUpload = array_get($data, 'background');
            $name = $imageToUpload->getClientOriginalName();
            $name = md5($name) . '.' . $imageToUpload->getClientOriginalExtension();
            $path = '/coffee-card-background/' . $name;
            Storage::put($path, File::get($imageToUpload->getRealPath()));
            $url = Storage::disk('local')->url($path);
            return $url;
        } else {
            return null;
        }
    }

    /**
     * Generation of rewardcode.
     * @return string
     */
    public function generateRewardCode()
    {
        $code = Str::random(12);
        $count = CoffeeCardReward::whereRewardCode($code)->count();
        if ($count <= 0) {
            return strtoupper($code);
        }
        $this->generateRewardCode();
    }

    /**
     * Generate Member reward code.
     */
    public function generateMemberRewardCode()
    {
        $code = Str::random(10);
        $count = MemberCoffeeCardReward::whereCode($code)->count();
        if ($count <= 0) {
            return strtoupper($code);
        }
        $this->generateMemberRewardCode();  // recursive call for getting random unique reward code.
    }

    /**
     * Admin Only rights api call method. Return the list of all coffeecards with rewards and assigned organizations.
     * @return array
     */
    public function adminCoffeeCardList()
    {
        $coffeeCardList = CoffeeCard::select('id', 'card_name', 'card_code')->with([
            'rewards' => function ($query) {
                $query->select('coffee_card_rewards.id', 'coffee_card_id', 'name');
            },
            'organizations' => function ($query) {
                $query->select('organizations.id', 'name');
            },
        ])
//            ->addSelect(DB::raw('(Select count(id) from member_coffee_cards where coffee_card_id = coffee_cards.id) as memberCoffeeCardCount'))
//            ->addSelect(DB::raw('(Select COALESCE(sum(stamp_balance),0) from member_coffee_cards where coffee_card_id = coffee_cards.id) as earnedStampsCount'))
//            ->addSelect(DB::raw('(Select count(id) from member_coffee_card_rewards where member_coffee_card_id in (select id from member_coffee_cards where coffee_card_id = coffee_cards.id )) as earnedRewardsCount'));
            ->get();

        $result = [];
        foreach ($coffeeCardList   as $coffeeCard) {
            $coffeeCard['memberCoffeeCardCount'] = MemberCoffeeCard::whereCoffeeCardId($coffeeCard->id)->count();
            $coffeeCard['earnedStampsCount'] = MemberCoffeeCard::whereCoffeeCardId($coffeeCard->id)->sum('stamp_balance');
            $coffeeCard['earnedRewardsCount'] = $coffeeCard->memberCoffeeCardRewards()->count();
            $result[] = $coffeeCard;
        }
        return $result;

//        return $coffeeCardList;
    }


    public function userCoffeeCardList(Organization $organization)
    {
        $coffeeCardList = CoffeeCard::select('id', 'card_name', 'card_code')
            ->whereHas('organizations', function ($query) use ($organization) {
                $query->where('organizations.id', $organization->id);
            })
            ->with([
                'rewards' => function ($query) {
                    $query->select('coffee_card_rewards.id', 'coffee_card_id', 'name');
                },
                'organizations' => function ($query) {
                    $query->select('organizations.id', 'name');
                },
            ])
//            ->addSelect(DB::raw('(Select count(id) from member_coffee_cards where coffee_card_id = coffee_cards.id) as memberCoffeeCardCount'))
//            ->addSelect(DB::raw('(Select COALESCE(sum(stamp_balance),0) from member_coffee_cards where coffee_card_id = coffee_cards.id) as earnedStampsCount'))
//            ->addSelect(DB::raw('(Select count(id) from member_coffee_card_rewards where member_coffee_card_id in (select id from member_coffee_cards where coffee_card_id = coffee_cards.id )) as earnedRewardsCount'));
            ->get();
//
        $result = [];
        foreach ($coffeeCardList as $coffeeCard) {
            $coffeeCard['memberCoffeeCardCount'] = MemberCoffeeCard::whereCoffeeCardId($coffeeCard->id)->count();
            $coffeeCard['earnedStampsCount'] = MemberCoffeeCard::whereCoffeeCardId($coffeeCard->id)->sum('stamp_balance');
            $coffeeCard['earnedRewardsCount'] = $coffeeCard->memberCoffeeCardRewards()->count();
            $result[] = $coffeeCard;
        }
        return $result;
//
//        return $coffeeCardList;
    }

    /**
     *
     * @param Organization $organization
     * @param CoffeeCard $coffeeCard
     * @return CoffeeCard
     */
    public function assignCoffeeCardToOrganization(Organization $organization, CoffeeCard $coffeeCard)
    {
        /** @var boolean $alreadyAssigned */
        $alreadyAssigned = $organization->coffeeCards->contains($coffeeCard->id);   //if Organization is not already assigned this coffee card.
        if (!$alreadyAssigned) {
            $organization->coffeeCards()->save($coffeeCard);
        }
        return $coffeeCard;
    }

    /**
     * @param Organization $organization
     * @param CoffeeCard $coffeeCard
     * @return CoffeeCard
     */
    public function detachOrganizationFromCoffeeCard(Organization $organization, CoffeeCard $coffeeCard)
    {
        /** @var boolean $alreadyAssigned */
        $alreadyAssigned = $organization->coffeeCards->contains($coffeeCard->id);   //if Organization is not already assigned this coffee card.
        if ($alreadyAssigned) {
            $organization->coffeeCards()->detach($coffeeCard);
        }
        return $coffeeCard;
    }

    /**
     * @param MemberCoffeeCard $memberCoffeeCard
     * @param int $nextStampIndex
     * @param int $noOfStamps
     * @return string
     * @internal param CoffeeCard $coffeeCard
     */
    public function generateCoffeeCardImage(MemberCoffeeCard $memberCoffeeCard, $nextStampIndex = 0, $noOfStamps = 0)
    {
        $coffeeCard = $memberCoffeeCard->coffeeCard;
        if (!$nextStampIndex || !$noOfStamps || $noOfStamps > $memberCoffeeCard->stamp_required - $nextStampIndex + 1) {
            return null;
        }

        /** @var array $style */
        $style = $coffeeCard->style;
        $styleObject = (array)json_decode($style);
        $coffeeCardImageUrl = $memberCoffeeCard->coffee_card_image;
        $coffeeCardImageUrl = str_replace('https','http',$coffeeCardImageUrl);
        $image = Image::make($coffeeCardImageUrl)->resize(432, 275, function ($constraint) {
//        $constraint->aspectRatio();
//            $constraint->upsize();
        }); // image generation with default dimensions and with uploaded background.

        //region Stamp Placement
        $stampUrl = asset('stamp.png');
        $stampUrl = str_replace('https','http',$stampUrl);
        $stamp = Image::make($stampUrl)->resize(60, 60, function ($constraint) {
//            $constraint->aspectRatio();
            $constraint->upsize();
        });

//        for ( $i = 1 , $stampIndexCount = $nextStampIndex ; $i <= $noOfStamps ; $i++ , $stampIndexCount++ ) {
//
//        }

        $stampIndexCount = $nextStampIndex;
        $stampsHaveBeenAdded = 0;
        foreach ($styleObject as $index => $item) {
            if ($index == 'image') {
                continue;
            }
            if ($index == 'stamp' . $stampIndexCount) {
                $image->insert($stamp, 'top-left', (int)$item->x, (int)$item->y);  //writing Stamp to the canvas
                $stampsHaveBeenAdded++;
                if ($stampsHaveBeenAdded < $noOfStamps) {
                    $stampIndexCount++;
                }
            }
        }
        //endregion

        $image = $image->encode('jpg');
        $name = $memberCoffeeCard->code . md5(date('d-m-y h:s:i'));
        $path = '/member-coffee-cards/' . $name . '.jpg';
        Storage::put($path, $image);
        $url = Storage::disk('local')->url($path);

        if (!empty($url)) {
            Storage::disk('local')->delete('member-coffee-cards/' . basename($memberCoffeeCard->coffee_card_image));
        }

        return $url;
    }

    /**
     * @param CoffeeCard $coffeeCard
     * @param Member $member
     * @return MemberCoffeeCard
     * @throws ApiException
     */
    public function createMemberCoffeeCard(CoffeeCard $coffeeCard, Member $member)
    {
        //region Creation of member coffee Card
        /** @var MemberCoffeeCard $memberCoffeeCard */
        $memberCoffeeCard = new MemberCoffeeCard();
        $memberCoffeeCard->member_id = $member->id;
        $memberCoffeeCard->coffee_card_id = $coffeeCard->id;
        $memberCoffeeCard->code = $this->coffeeCardTemplateCode(true);   //todo Generate Member Coffee Card Code.
        $memberCoffeeCard->qr_code = $this->getQrCodeLink($memberCoffeeCard->code . 'cc-member-card-qr', $memberCoffeeCard->code);
        $memberCoffeeCard->stamp_balance = 0;
        $memberCoffeeCard->stamp_earned = 0;
        $memberCoffeeCard->stamp_required = $coffeeCard->number_of_stamps;
        $memberCoffeeCard->coffee_card_image = $coffeeCard->background;
        $memberCoffeeCard->status = IStatus::ACTIVE;
        $memberCoffeeCard->save();
        //endregion

        /** @var Organization [] $organizations */
        $organizations = $coffeeCard->organizations()->get();

        /** @var MemberProfile $memberProfile */
        $memberProfile = MemberProfile::whereEmail($member->email)->first();

        //region SafeSide Script if member don't have the profile.
        if (empty($memberProfile)) {
            $memberProfile = $member;
        }
        //endregion

        foreach ($organizations as $organization) {
            $isMember = $organization->members()->where('members.email', $member->email)->count();

            //checking for each organization asossiated with that coffee card, if member is already associated with the organization , do nothing.
            if ($isMember == 0) {
                //if member isn't already associated with the organization create member with pending status.
                //this will create a new member , with resolved member_id ( next_member_id in organization setting or next member Id according to formulae ),
                //if there is any auto assign subscription, assignment of that subscription with pending status.
                //creation of member profile associated with this newly created member.
                //adding of old member profile addressess in the newly created member.

                /** @var MemberRepository $memberRepository */
                $memberRepository = new MemberRepository();
                $data = [
                    'first_name' => $memberProfile->first_name,
                    'last_name' => $memberProfile->last_name,
                    'date_of_birth' => $memberProfile->date_of_birth,
                    'organization_id' => $organization->id,
                    'contact_no' => $memberProfile->contact_no,
                    'email' => $memberProfile->email
                ];
                /** @var Member $newMember */
                $newMember = $memberRepository->addMember($data, false, false);

                //region Adding Physical and postal Addresses
                $addressData = $memberProfile->physicalAddress;
                if (!empty($addressData)) {
                    $addressData = $addressData->toArray();
                    $addressData['member_id'] = $newMember->id;
                    $memberRepository->addAddress($addressData, AddressType::PHYSICAL_ADDRESS);
                }

                $addressData = $memberProfile->postalAddress;
                if (!empty($addressData)) {
                    $addressData = $addressData->toArray();
                    $addressData['member_id'] = $newMember->id;
                    $memberRepository->addAddress($addressData, AddressType::POSTAL_ADDRESS);
                }
                //endregion
            }
        }

        return $memberCoffeeCard;
    }

    /**
     * @param Member $member
     * @param CoffeeCardReward $coffeeCardReward
     * @param null $id
     * @return MemberCoffeeCardReward
     */
    public function createMemberReward(Member $member, CoffeeCardReward $coffeeCardReward, $memberCoffeeCardId, $organizationId, $id = null)
    {
        if (!empty($id)) {
            /** @var MemberCoffeeCardReward $memberReward */
            $memberReward = MemberCoffeeCardReward::find($id);
        }

        if (empty($memberReward)) {
            $memberReward = new MemberCoffeeCardReward();
        }
        $memberReward->name = $coffeeCardReward->name;
        $memberReward->member_id = $member->id;
        $memberReward->coffee_card_reward_id = $coffeeCardReward->id;
        $memberReward->reward_entry_date = Carbon::now();
        $memberReward->member_coffee_card_id = $memberCoffeeCardId;
        $memberReward->code = $this->generateMemberRewardCode();
        $memberReward->earned_at = $organizationId;
        $memberReward->qr_code = $this->getQrCodeLink('Member-cc-reward', $memberReward->code);
        if ($coffeeCardReward->expiry == CoffeeCardReward::EXPIRY['YES']) {
            if ($coffeeCardReward->expiry_mode == CoffeeCardReward::EXPIRY_MODE['PERIOD']) {
                /** @var Carbon $currentDate */
                $currentDate = new Carbon();
                $quantity = $coffeeCardReward->expiry_period_quantity;
                $duration = $coffeeCardReward->expiry_period_duration;
                switch ($duration) {
                    case CoffeeCardReward::EXPIRY_DURATION['DAY']:
                        $dateToSet = $currentDate->addDays($quantity);
                        break;

                    case CoffeeCardReward::EXPIRY_DURATION['WEEK']:
                        $dateToSet = $currentDate->addWeeks($quantity);
                        break;

                    case CoffeeCardReward::EXPIRY_DURATION['MONTH']:
                        $dateToSet = $currentDate->addMonths($quantity);
                        break;

                    case CoffeeCardReward::EXPIRY_DURATION['YEAR']:
                        $dateToSet = $currentDate->addMonths($quantity);
                        break;
                    default:
                        $dateToSet = Carbon::now();
                }
                $memberReward->reward_expiry_date = $dateToSet;
            } else {
                $memberReward->reward_expiry_date = $coffeeCardReward->expiry_date;
            }
        }
        $memberReward->save();

        return $memberReward;
    }

    /**
     * @param MemberCoffeeCard $memberCoffeeCard
     * @param array $data
     * @param $organizationId
     * @return array
     */
    public function addStampOnMemberCoffeeCard(MemberCoffeeCard $memberCoffeeCard, $data = [], $organizationId)
    {
        $requiredStamps = $memberCoffeeCard->stamp_required;
        $stampBalance = $memberCoffeeCard->stamp_balance + array_get($data, 'stamps_to_add');
        $lastStampEarned = $memberCoffeeCard->stamp_balance;
        $message = null;
        if ($stampBalance >= $requiredStamps) {
            $overloadedStamps = $stampBalance - $requiredStamps;
            $numberOfStampsForOldCard = array_get($data, 'stamps_to_add') - $overloadedStamps;

            //todo check stamp earned value for use case 8 balance adding 1
            $memberCoffeeCard->stamp_earned = $numberOfStampsForOldCard;
            $memberCoffeeCard->stamp_balance = $memberCoffeeCard->stamp_required;
            $memberCoffeeCard->status = MemberCoffeeCard::STATUS['REWARD_EARNED'];
            $url = $this->generateCoffeeCardImage($memberCoffeeCard, ($lastStampEarned + 1), $numberOfStampsForOldCard);
            if ($url) {
                $memberCoffeeCard->coffee_card_image = $url;
            }
            $memberCoffeeCard->save();
            $this->addMemberCoffeeCardLog($memberCoffeeCard, $organizationId);   //adding Member Coffee Card Log

            //region Creation of Member Coffee Card Reward.
            /** @var CoffeeCard $coffeeCard */
            $coffeeCard = $memberCoffeeCard->coffeeCard;
            if (!empty($coffeeCard)) {
                $coffeeCardRewards = $coffeeCard->rewards;
                foreach ($coffeeCardRewards as $coffeeCardReward) {
                    $message = 'Reward earned your be redeemed next purchase';
                    $this->createMemberReward($memberCoffeeCard->member, $coffeeCardReward, $memberCoffeeCard->id,$organizationId); // add member reward in reward list
                }
            }
            //endregion

            $newMemberCoffeeCard = $this->createMemberCoffeeCard($coffeeCard, $memberCoffeeCard->member);
            $newMemberCoffeeCard->stamp_earned = $overloadedStamps;
            $newMemberCoffeeCard->stamp_balance = $overloadedStamps;
            if ($overloadedStamps > 0) {
                $url = $this->generateCoffeeCardImage($newMemberCoffeeCard, 1, $overloadedStamps);
                if (!empty($url)) {
                    $newMemberCoffeeCard->coffee_card_image = $url;
                }
            }
            $newMemberCoffeeCard->save();

            if ($overloadedStamps > 0) {
                $this->addMemberCoffeeCardLog($newMemberCoffeeCard, $organizationId);   //adding Member Coffee Card Log
            }

            return ['member_coffee_card' => $newMemberCoffeeCard, 'message' => $message];
        } else {
            $memberCoffeeCard->stamp_balance = $stampBalance;
            $memberCoffeeCard->stamp_earned = array_get($data, 'stamps_to_add');

            $url = $this->generateCoffeeCardImage($memberCoffeeCard, ($lastStampEarned + 1), array_get($data, 'stamps_to_add'));
            if ($url) {
                $memberCoffeeCard->coffee_card_image = $url;
            }
            $memberCoffeeCard->save();
            $this->addMemberCoffeeCardLog($memberCoffeeCard, $organizationId);   //adding Member Coffee Card Log

            return ['member_coffee_card' => $memberCoffeeCard, 'message' => $message];
        }
    }

    /**
     * This function will return all the rewards related to a logged in member.
     * @param Member $member
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMemberRewards(Member $member)
    {
        $rewards = $member->coffeeCardRewards()
            ->join('coffee_card_rewards', 'coffee_card_rewards.id', '=', 'coffee_card_reward_id')
            ->where('member_coffee_card_rewards.status', IStatus::ACTIVE)
            ->with(['coffeeCardReward' => function ($q) {
                $q->select('id', 'coffee_card_id');
                $q->with(['coffeeCard' => function ($q) {
                    $q->select('id');
//                    $q->with(['organizations' => function($q){$q->select('organizations.id','name'); }]);
                }]);
            }])
            ->select(
                'member_coffee_card_rewards.id',
                'member_coffee_card_rewards.name',
                'member_coffee_card_rewards.code',
                'member_coffee_card_rewards.qr_code',
                'member_coffee_card_rewards.reward_expiry_date',
                'member_coffee_card_rewards.coffee_card_reward_id',
                'coffee_card_rewards.image'
            )
            ->get();

        for ($i = 0; $i < count($rewards); $i++) {
            $card = $rewards[$i];
            $card['organization'] = null;
            $coffeeCardReward = $rewards[$i]->coffeeCardReward;
            if ($coffeeCardReward) {
                $organizations = $coffeeCardReward->coffeeCard->organizations;
                $count = $organizations->count();
                if ($count == 0) {
                    $card['organization'] = 'No Organization';
                } else if ($count == 1) {
                    $card['organization'] = $organizations[0]->name;
                } else if ($count > 1) {
                    $card['organization'] = 'Multiple organization';
                }
            } else {
                $card['organization'] = 'No Organization';
            }
            $rewards[$i] = $card;
        }
        return $rewards;
    }

    /**
     * Redeem coffee card reward from member reward list.
     *
     * @param MemberCoffeeCardReward $memberCoffeeCardReward
     * @return MemberCoffeeCardReward
     * @internal param Member $member
     */
    public function redeemMemberReward(MemberCoffeeCardReward $memberCoffeeCardReward, $organizationId)
    {
        $memberCoffeeCardReward->status = MemberCoffeeCardReward::STATUS['REDEEMED'];
        $memberCoffeeCardReward->redeem_date_time = Carbon::now();
        $memberCoffeeCardReward->redeemed_at = $organizationId;
        $memberCoffeeCardReward->save();
        return $memberCoffeeCardReward;
    }

    /**
     * This function will return the coffee card list having sorting logic for mobile application.
     * @param Member $member
     * @return MemberCoffeeCard
     */
    public function getMemberCoffeeCardList(Member $member)
    {
        /** @var MemberCoffeeCard $memberCoffeeCards */
        $memberCoffeeCards = $member->memberCoffeeCard()
            ->select(DB::raw('( Select stamp_added_time from member_coffee_card_logs where member_coffee_card_logs.member_coffee_card_id = member_coffee_cards.id 
             Order by stamp_added_time desc limit 1) as date_to_order'))
            ->where('status', MemberCoffeeCard::STATUS['ACTIVE'])
            ->addSelect('member_coffee_cards.id', 'coffee_card_image', 'code', 'qr_code', 'coffee_card_id', 'member_coffee_cards.member_id')
            ->with(['coffeeCard' => function ($query) {
                $query->select('id', 'card_name');
            }])
            ->orderBy('date_to_order', 'desc')
            ->get();
        return $memberCoffeeCards;

    }

    /**
     * This function will add the log for member coffee card stamps.
     *
     * @param MemberCoffeeCard $memberCoffeeCard
     * @param $organizationId
     * @return MemberCoffeeCardLog
     */
    public function addMemberCoffeeCardLog(MemberCoffeeCard $memberCoffeeCard, $organizationId)
    {
        /** @var MemberCoffeeCardLog $memberCoffeeCardLog */
        $memberCoffeeCardLog = new MemberCoffeeCardLog();
        $memberCoffeeCardLog->stamp_added = $memberCoffeeCard->stamp_earned;
        $memberCoffeeCardLog->stamp_added_time = Carbon::now();
        $memberCoffeeCardLog->member_coffee_card_id = $memberCoffeeCard->id;
        $memberCoffeeCardLog->organization_id = $organizationId;
        $memberCoffeeCardLog->member_id = $memberCoffeeCard->member_id;
        $memberCoffeeCardLog->save();

        return $memberCoffeeCardLog;
    }


    /**
     * This repository method will get the member coffee card and return the logs of that.
     * @param MemberCoffeeCard $memberCoffeeCard
     * @return mixed
     */
    public function getMemberCoffeeCardLogs(MemberCoffeeCard $memberCoffeeCard)
    {
        return $memberCoffeeCard->memberCoffeeCardLog()->with(['organization' => function ($query) {
            $query->select('id', 'name');
        }])->get();
    }

    /**
     * This will check the qr code if qr code is for coffee card or member card reward.
     *
     * @param $cardCode
     * @param Organization $organization
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|null|object|static
     * @throws ApiException
     */
    public function getCardFromQr($cardCode, Organization $organization)
    {
        $codeStringCount = strlen($cardCode);
        $card = null;

        if ($codeStringCount == MemberCoffeeCard::CODE_LENGTH) {
            $card = $this->getMemberCoffeeCard($cardCode, $organization);
        } else if ($codeStringCount == MemberCoffeeCardReward::CODE_LENGTH) {
            $card = $this->getMemberReward($cardCode);
        } else {
            throw new ApiException(null, ['error' => ['Invalid Code']]);
        }
        return $card;
    }

    /**
     * @param $cardCode
     * @param Organization $organization
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @throws ApiException
     */
    public function getMemberCoffeeCard($cardCode, Organization $organization)
    {
        $card = MemberCoffeeCard::where('code', $cardCode)
            ->with([
                'memberCoffeeCardLog' => function ($query) {
                    $query->with(['organization' => function ($query) {
                        $query->select('id', 'name');
                    }]);
                },
                'coffeeCard' => function ($query) {
                    $query->select('id', 'card_name');
                }
            ])->first();

        if (empty($card)) {
            throw new ApiException(null, ['error' => ['Invalid card code']]);
        }

        $coffeeCard = $card->coffeeCard;
        $coffeeCardOrganization = $coffeeCard->organizations()->where('organizations.id', $organization->id)->first();
        if(empty($coffeeCardOrganization)){
            throw new ApiException(null, ['error' => ['Card is not associated with your organization']]);
        }

        $card['organization_name'] = $organization->name;
        $card['response_type'] = 'member_coffee_card';
        return $card;
    }

    /**
     * @param $cardCode
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|null|object|static
     * @throws ApiException
     */
    public function getMemberReward($cardCode)
    {
        $reward = MemberCoffeeCardReward::whereCode($cardCode)->with(['coffeeCardReward' => function ($query) {
            $query->select('id', 'image', 'coffee_card_id');
            $query->with(['coffeeCard' => function ($query) {
                $query->select('id');
                $query->with(['organizations' => function ($query) {
                    $query->select('organizations.id', 'name');
                }]);
            }]);
        }])->first();
        if (empty($reward)) {
            return api_error(['error' => 'Invalid reward code']);
        }

        if ($reward->status == MemberCoffeeCardReward::STATUS['REDEEMED']) {
            throw new ApiException(null, ['error' => ['Sorry this reward has already been validated @ ' . date('d/m/y h:i', strtotime($reward->redeem_date_time))]]);
        }

        if ($reward->status == MemberCoffeeCardReward::STATUS['EXPIRED']) {
            throw new ApiException(null, ['error' => ['Sorry this reward has been expired.']]);
        }

        $card = $reward;
        $card['organization'] = null;
        $coffeeCardReward = $reward->coffeeCardReward;
        $organizations = $coffeeCardReward->coffeeCard->organizations;
        $count = $organizations->count();
        if ($count == 0) {
            $card['organization'] = 'No Organization';
        } else if ($count == 1) {
            $card['organization'] = $organizations[0]->name;
        } else if ($count > 1) {
            $card['organization'] = 'Multi Merchant';
        }
        $card['response_type'] = 'member_reward';
        return $card;
    }

    /**
     * This function will return all organizations that are assigned to any of the coffee card for listing in  coffee card stats dashboard's dropdown.
     * @return Organization[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllAssignedOrganizations()
    {
        $organizations = Organization::whereHas('coffeeCards')->select(['id', 'name'])->get();
        return $organizations;
    }

    /**
     * This function will return all coffee cards according to the organization_ids passed in the request.
     * @param array $organizationIds
     * @return CoffeeCard[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCoffeeCardListForDropdown($organizationIds = [])
    {
        $coffeeCards = CoffeeCard::whereHas('organizations', function ($query) use ($organizationIds) {
            $query->whereIn('organizations.id', $organizationIds);
        })
            ->select(['coffee_cards.id', 'coffee_cards.card_name'])->get();

        return $coffeeCards;
    }

    public function coffeeCardStats($data = [])
    {
        $currentDate = Carbon::now();
        $yesterday = Carbon::now()->subDay(1);
        $firstDayOfWeek = $currentDate->startOfWeek(1);
        $currentDateForLast = Carbon::now();
        $lastDayOfWeek =  $currentDateForLast->endOfWeek(0);
        $firstDayOfLastWeek = Carbon::now()->subWeek(1)->startOfWeek(1);
        $lastDayOfLastWeek = Carbon::now()->subWeek(1)->endOfWeek(0);
        $organizationConstraint = 'organization_id in ('.implode(",",$data["organization_ids"]).')';
        $coffeeCardConstraint = 'coffee_card_id in ('.implode(",",$data["coffee_card_ids"]).')';
        $memberCoffeeCardJoin = 'left join member_coffee_cards on member_coffee_card_id = member_coffee_cards.id ';
        $firstDayOfLastMonth = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d');
        $endDayOfLastMonth = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d');
        $firstDayOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDayOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        $firstDayOfLastYear = Carbon::now()->subYear(1)->startOfYear()->format('Y-m-d');
        $endDayOfLastYear = Carbon::now()->subYear(1)->endOfYear()->format('Y-m-d');
        $firstDayOfYear = Carbon::now()->startOfYear()->format('Y-m-d');
        $endDayOfYear = Carbon::now()->endOfYear()->format('Y-m-d');

//        $timeQuery = ' CURRENT_DATE,
//	Date_sub( Date_Add( LAST_DAY( CURRENT_DATE ), INTERVAL 1 DAY ), INTERVAL 1 MONTH ) AS first_day_of_current_month,
//	LAST_DAY( CURRENT_DATE ) as last_day_of_current_month,
//	date_sub(LAST_DAY( CURRENT_DATE ) , interval 1 month) as last_day_of_last_month,
//	date_sub(Date_sub( Date_Add( LAST_DAY( CURRENT_DATE ), INTERVAL 1 DAY ), INTERVAL 1 MONTH ) , interval 1 month ) as first_day_of_last_month,
//	LAST_DAY(DATE_ADD(CURRENT_DATE, INTERVAL 12-MONTH(NOW()) MONTH)) as last_day_of_this_year,
//	date_sub(LAST_DAY(DATE_ADD(CURRENT_DATE, INTERVAL 12-MONTH(NOW()) MONTH)) , Interval 1 year) as last_day_of_last_year,
//	date_add(date_sub(LAST_DAY(DATE_ADD(CURRENT_DATE, INTERVAL 12-MONTH(NOW()) MONTH)) , Interval 1 year), interval 1 day) as first_day_of_this_year,
//	date_sub(date_add(date_sub(LAST_DAY(DATE_ADD(CURRENT_DATE, INTERVAL 12-MONTH(NOW()) MONTH)) , Interval 1 year), interval 1 day), interval 1 year ) as first_day_of_last_year,
//	';
        $stats = DB::select('SELECT
	( SELECT COALESCE(sum( stamp_added ),0) FROM member_coffee_card_logs '.$memberCoffeeCardJoin.'
	    WHERE date( stamp_added_time ) = "'.Carbon::now()->format('Y-m-d').'" 
	    AND '.$organizationConstraint.'
	    And '.$coffeeCardConstraint.'
	    )
	     AS today,
	( SELECT COALESCE(sum( stamp_added ),0) FROM member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) = "'.$yesterday->format('Y-m-d').'") AS yesterday,
	(SELECT COALESCE(sum( stamp_added ),0) FROM	member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) BETWEEN "'.$firstDayOfMonth.'" AND "'.$endDayOfMonth.'" AND '.$organizationConstraint.' And '.$coffeeCardConstraint.') AS this_month,
	(SELECT COALESCE(sum( stamp_added ),0) FROM	member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) BETWEEN "'.$firstDayOfLastMonth.'" AND "'.$endDayOfLastMonth.'" AND '.$organizationConstraint.' And '.$coffeeCardConstraint.') AS last_month,
	(SELECT COALESCE(sum( stamp_added ),0) FROM	member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) BETWEEN "'.$firstDayOfYear.'" AND "'.$endDayOfYear.'" AND '.$organizationConstraint.' And '.$coffeeCardConstraint.') AS this_year,
	(SELECT COALESCE(sum( stamp_added ),0) FROM	member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) BETWEEN "'.$firstDayOfLastYear.'" AND "'.$endDayOfLastYear.'" AND '.$organizationConstraint.' And '.$coffeeCardConstraint.') AS last_year,
	(SELECT COALESCE(sum( stamp_added ),0) FROM	member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) BETWEEN "'.$firstDayOfWeek->format('Y-m-d').'" AND "'.$lastDayOfWeek->format('Y-m-d').'" AND '.$organizationConstraint.' And '.$coffeeCardConstraint.') AS this_week,
	(SELECT COALESCE(sum( stamp_added ),0) FROM	member_coffee_card_logs '.$memberCoffeeCardJoin.' WHERE date( stamp_added_time ) BETWEEN "'.$firstDayOfLastWeek->format('Y-m-d').'" AND "'.$lastDayOfLastWeek->format('Y-m-d').'" AND '.$organizationConstraint.' And '.$coffeeCardConstraint.') AS last_week
FROM
	member_coffee_card_logs 
	LIMIT 1');

//        \Log::info(DB::getQueryLog());
        return $stats;
    }

    public function getRewardStats($data = [])
    {
        $currentDate = Carbon::now();
        $firstDayOfWeek = $currentDate->startOfWeek(1);
        $currentDateForLast = Carbon::now();
        $lastDayOfWeek =  $currentDateForLast->endOfWeek(0);
        $organizationEarnedConstraint = 'earned_at in ('.implode(",",$data["organization_ids"]).')';
        $organizationRedeemedConstraint = 'redeemed_at in ('.implode(",",$data["organization_ids"]).')';
        $coffeeCardConstraint = 'coffee_card_id in ('.implode(",",$data["coffee_card_ids"]).')';
        $memberCoffeeCardJoin = 'left join member_coffee_cards on member_coffee_card_id = member_coffee_cards.id ';
        $statusCheckForActive = 'member_coffee_card_rewards.status = '. MemberCoffeeCardReward::STATUS['ACTIVE'];
        $statusCheckForRedeem = 'member_coffee_card_rewards.status = '. MemberCoffeeCardReward::STATUS['REDEEMED'];
        $firstDayOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDayOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        $firstDayOfYear = Carbon::now()->startOfYear()->format('Y-m-d');
        $endDayOfYear = Carbon::now()->endOfYear()->format('Y-m-d');
        $stats = DB::select('SELECT
	( SELECT COALESCE(count( member_coffee_card_rewards.id ),0) FROM member_coffee_card_rewards '.$memberCoffeeCardJoin.'
	    WHERE date( reward_entry_date ) = "'.Carbon::now()->format('Y-m-d').'" 
	    AND '.$organizationEarnedConstraint.'
	    And '.$coffeeCardConstraint.' 
	    )
	     AS today_earned,
	( SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( redeem_date_time  ) = "'.Carbon::now()->format('Y-m-d').'"  AND '.$organizationRedeemedConstraint.') AS today_redeemed,
	(SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM	member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( reward_entry_date  ) BETWEEN "'.$firstDayOfMonth.'" AND "'.$endDayOfMonth.'" AND '.$organizationEarnedConstraint.' And '.$coffeeCardConstraint.' ) AS this_month_earned,
	(SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM	member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( redeem_date_time  ) BETWEEN "'.$firstDayOfMonth.'" AND "'.$endDayOfMonth.'" AND '.$organizationRedeemedConstraint.' And '.$coffeeCardConstraint.' ) AS this_month_redeemed,
	(SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM	member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( reward_entry_date  ) BETWEEN "'.$firstDayOfYear.'" AND "'.$endDayOfYear.'" AND '.$organizationEarnedConstraint.' And '.$coffeeCardConstraint.' ) AS this_year_earned,
	(SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM	member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( redeem_date_time  ) BETWEEN "'.$firstDayOfYear.'" AND "'.$endDayOfYear.'" AND '.$organizationRedeemedConstraint.' And '.$coffeeCardConstraint.' ) AS this_year_redeem,
	(SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM	member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( reward_entry_date  ) BETWEEN "'.$firstDayOfWeek->format('Y-m-d').'" AND "'.$lastDayOfWeek->format('Y-m-d').'" AND '.$organizationEarnedConstraint.' And '.$coffeeCardConstraint.' ) AS this_week_earned,
	(SELECT COALESCE(count( member_coffee_card_rewards.id ) ,0) FROM	member_coffee_card_rewards '.$memberCoffeeCardJoin.' WHERE date( redeem_date_time  ) BETWEEN "'.$firstDayOfWeek->format('Y-m-d').'" AND "'.$lastDayOfWeek->format('Y-m-d').'" AND '.$organizationRedeemedConstraint.' And '.$coffeeCardConstraint.' ) AS this_week_redeemed
     FROM member_coffee_card_rewards LIMIT 1');

        return $stats;
    }
}   