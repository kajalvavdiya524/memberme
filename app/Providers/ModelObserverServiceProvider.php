<?php

namespace App\Providers;

use App\ChangeLog;
use App\Member;
use App\MemberOther;
use App\MemberViewLog;
use App\Observers\MemberChangeLogObserver;
use App\Observers\MemberObserver;
use App\Observers\MemberOtherObserver;
use App\Observers\MemberViewLogObserver;
use Illuminate\Support\ServiceProvider;

class ModelObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Member::observe(MemberObserver::class);
        MemberViewLog::observe(MemberViewLogObserver::class);
        ChangeLog::observe(MemberChangeLogObserver::class);
        MemberOther::observe(MemberOtherObserver::class);
    }
}
