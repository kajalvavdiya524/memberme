<?php

namespace App\Observers;

use App\Helpers\ApiHelper;
use App\MemberViewLog;

class MemberViewLogObserver
{
    /**
     * Handle the MemberViewLog "creating" event
     * @param MemberViewLog $memberViewLog
     */
    public function creating(MemberViewLog $memberViewLog){
        $user = ApiHelper::getApiUser();
        if($user){
            $memberViewLog->user_id = $user->id;
        }
    }
    /**
     * Handle the MemberViewLog "created" event.
     *
     * @param  \App\MemberViewLog  $memberViewLog
     * @return void
     */
    public function created(MemberViewLog $memberViewLog)
    {
        
    }

    /**
     * Handle the MemberViewLog "updated" event.
     *
     * @param  \App\MemberViewLog  $memberViewLog
     * @return void
     */
    public function updated(MemberViewLog $memberViewLog)
    {
        //
    }

    /**
     * Handle the MemberViewLog "deleted" event.
     *
     * @param  \App\MemberViewLog  $memberViewLog
     * @return void
     */
    public function deleted(MemberViewLog $memberViewLog)
    {
        //
    }

    /**
     * Handle the MemberViewLog "restored" event.
     *
     * @param  \App\MemberViewLog  $memberViewLog
     * @return void
     */
    public function restored(MemberViewLog $memberViewLog)
    {
        //
    }

    /**
     * Handle the MemberViewLog "force deleted" event.
     *
     * @param  \App\MemberViewLog  $memberViewLog
     * @return void
     */
    public function forceDeleted(MemberViewLog $memberViewLog)
    {
        //
    }
}
