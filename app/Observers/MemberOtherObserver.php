<?php

namespace App\Observers;

use App\Member;
use App\MemberOther;
use App\repositories\MemberRepository;

class MemberOtherObserver
{

    /** @var $memberRepository MemberRepository */
    public $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }


    public function updating(MemberOther $memberOther)
    {

        $dirtyFields = $memberOther->getDirty();

        foreach ($dirtyFields as $key => $dirtyField) {
            if(in_array($key,['proposer_number'])){

            }
            $this->memberRepository->addMemberChangeLog(
                $memberOther->member,
                $key,
                $memberOther->getOriginal($key),
                $memberOther->$key
            );
        }
    }
}
