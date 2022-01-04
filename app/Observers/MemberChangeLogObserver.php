<?php

namespace App\Observers;

use App\ChangeLog;
use App\Helpers\ApiHelper;
use App\User;
use Carbon\Carbon;

class MemberChangeLogObserver
{
    /**
     * Handle the MemberViewLog "creating" event
     * @param ChangeLog $changeLog
     */
    public function creating(ChangeLog $changeLog){
        $user = null;
        try{
            $user = ApiHelper::getApiUser();
        }catch (\Exception $exception){}
        if($user){
            $changeLog->user_id = $user->id;
        }else{
            $changeLog->user_id = 1;
        }
        $changeLog->changed_date_time = Carbon::now();

    }
}
