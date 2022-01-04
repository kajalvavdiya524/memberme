<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 5/27/2019
 * Time: 1:05 PM
 */

namespace App\Observers;


use App\Member;
use App\repositories\MemberRepository;

class MemberObserver
{
    /** @var $memberRepository MemberRepository */
    public $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }
    /**
     * @param Member $member
     */
    public function saving(Member $member)
    {
        $middleName = (!empty($member->middle_name))? " $member->middle_name" :  "";
        $member->full_name = $member->first_name . $middleName .  " ". $member->last_name;    }

    /**
     * @param Member $member
     */
    public function updating(Member $member)
    {
        $dirtyFields = $member->getDirty();

        foreach ($dirtyFields as $key => $dirtyField){
            if(!in_array($key ,Member::NON_LOGS_FIELDS)){
                $this->memberRepository->addMemberChangeLog(
                    $member,
                    $key,
                    $member->getOriginal($key),
                    $member->$key
                );
            }
        }
        $middleName = (!empty($member->middle_name))? " $member->middle_name" :  "";
        $member->full_name = $member->first_name . $middleName .  " ". $member->last_name;
    }
}