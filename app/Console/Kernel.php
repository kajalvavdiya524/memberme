<?php

namespace App\Console;

use App\base\IStatus;
use App\Console\Commands\CheckFinancial;
use App\Console\Commands\EmailTemplates\SendEmail;
use App\EmailTemplate;
use App\Member;
use App\Organization;
use App\repositories\MemberRepository;
use App\User;
use function foo\func;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CheckFinancial::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
//        $schedule->command
//        ('check:financial');
//        $schedule->call(function () {
//        \App\Member::select('renewal','id','subscription_id')->chunk(100,function ($members) {
//            foreach ($members as $member) {
//                $renewal = $member->renewal;
//                if (!empty($renewal) && (new \Carbon\Carbon($renewal)) <= \Carbon\Carbon::now()) {
//                    $member->financial = \App\base\IStatus::INACTIVE;
//                    $member->save();
//                } else {
//                    if( empty($member->subscription_id) ){
//                        $member->financial = \App\base\IStatus::INACTIVE;
//                        $member->save();
//                    }else{
//                        $member->financial = \App\base\IStatus::ACTIVE;
//                        $member->save();
//                    }
//                }
//            }
//        });
//            })->daily();

        \App\Organization::chunk(10,function ($organizations)  use($schedule) {
            foreach ($organizations as $organization) {
                /* @var  $organization Organization*/

                //region Setting up organization timezone if not set.
                if(!empty($organization->timezone->timezone)){
                    try{
                        $timezone = $organization->timezone->timezone;
                    }catch (\Exception $exception){
                        $timezone = 'Pacific/Auckland';
                    }
                }else{
                    $timezone = 'Pacific/Auckland';
                }
                //endregion

                $schedule->call(function() use ($organization) {
                    $organization->members()->notResigned()->chunk(100,function($members)  use ($organization) {
                        foreach ($members as $member) {

                            $memberRepository = new MemberRepository();
                            $renewal = $member->renewal;

                            if (!empty($renewal) && (new \Carbon\Carbon($renewal)) <= \Carbon\Carbon::now()) {

                                //region Changing Status to overdue and financial to no.
                                $member->financial = \App\base\IStatus::INACTIVE;
                                $member->due = \App\base\IStatus::ACTIVE;
                                if($member->status == IStatus::ACTIVE || $member->status == IStatus::PENDING_NEW)
                                {
                                    $memberRepository->changeStatus($member,IStatus::OVER_DUE);
                                }
                                $member->save();
//                                endregion
                            } else {

                                if( empty($member->subscription_id) ){
                                    //region Changing financial to No
                                    $member->financial = \App\base\IStatus::INACTIVE;
                                    $member->save();
                                    //endregion
                                }else{
                                    //region Changing financial to yes and status to active if member have in-time subscription
                                    $member->financial = \App\base\IStatus::ACTIVE;
                                    try {
                                        $memberRepository->changeStatus($member, IStatus::ACTIVE);
                                    } catch (\Exception $e) {
                                        \Log::info('cJ :Can not change status.'. 'id: '.$member->id.' Status: ACTiVE');
                                    }

                                    $member->save();
                                    //endregion
                                }
                            }
                        }
                    });
                })
                    ->dailyAt('00:00')->timezone($timezone);
//                ->everyMinute();
            }
        });

        \App\Organization::chunk(10,function ($organizations)  use($schedule) {
            foreach ($organizations as $organization) {
                /* @var  $organization Organization*/

                //region Setting TimeZone for Organization.
                if(!empty($organization->timezone->timezone)){
                    try{
                        $timezone = $organization->timezone->timezone;
                    }catch (\Exception $exception){
                        $timezone = 'Pacific/Auckland';
                    }
                }else{
                    $timezone = 'Pacific/Auckland';
                }
                //endregion

                $schedule->call(function() use ($organization) {
                    $organization->members()->notResigned()->chunk(100,function($members){ //excluding resigned members.
                        foreach ($members as $member) {
                            /* @var  $member Member */
                            $renewal = $member->renewal;
                            $subscription = $member->subscription()->select('id','due_duration')->first();

                            if (!empty($renewal) && !empty($subscription)) {

                                //region Calculation of renewal duration in days
                                $carbonRenewal = new \Carbon\Carbon($renewal);
                                $carbonCurrent = Carbon::now();
                                $interval = Carbon::parse($carbonRenewal)->diff($carbonCurrent);

//                                $interval = $carbonCurrent->diffAsCarbonInterval($carbonRenewal);
//                                $intervalDays = $interval->d;
//                                $intervalDays =  $intervalDays + ( $interval->m * 30 )  + ($interval->y * 365);
//                                ($intervalDays <= (!empty($dueDuration)?$dueDuration:30) && $interval->invert == 0) ||
                                $intervalDays = $interval->days;
                                //endregion

                                $dueDuration = array_get($subscription,'due_duration');

                                // if a member's subscription is going to end , set the due to yes. due duration will check if the subscription is going to end.
                                if( ($intervalDays <= (!empty($dueDuration)?$dueDuration:30) && $interval->invert == 1) || $renewal < $carbonCurrent  ){
                                    //region Setting Due to Yes
                                    $member->due = \App\base\IStatus::ACTIVE;
                                    $member->save();
                                    //endregion
                                }else{
                                    //region Setting due to No.
                                    $member->due = \App\base\IStatus::INACTIVE;
                                    $member->save();
                                    //endregion
                                }
                            }else{
                                if( empty($member->subscription_id) ){
                                    //region if member don't have the subscription set Due to yes
                                    $member->due = \App\base\IStatus::ACTIVE;
                                    $member->save();
                                    //endregion
                                }else{
                                    //region If member have subscription and he is not in the due duration, set the due to NO
                                    $member->due = \App\base\IStatus::INACTIVE;
                                    $member->save();
                                    //endregion
                                }
                            }
                        }
                    });
                })
                    ->dailyAt('00:00')->timezone($timezone);
//                ->everyMinute();
            }
        });

         $schedule->command(SendEmail::class)
                  ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
