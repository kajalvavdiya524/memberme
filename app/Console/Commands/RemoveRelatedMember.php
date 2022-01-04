<?php

namespace App\Console\Commands;

use App\Member;
use App\MemberOther;
use Illuminate\Console\Command;

class RemoveRelatedMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        MemberOther::whereNotNull('proposer_member_id')->chunk(200,function($memberOthers){
            foreach ($memberOthers as $memberOther) {
                /** @var $memeberOther MemberOther */
                $member = Member::whereMemberId($memberOther->proposer_member_id)->where('organization_id' , 15636)->first();
                if(empty($member) && $memberOther->member->organization_id == 15636){
                    $memberOther->proposer_member_id = null;
                    $memberOther->save();
                }
            }

        });

        MemberOther::whereNotNull('secondary_member_id')->chunk(200,function($memberOthers){
            foreach ($memberOthers as $memberOther) {
                /** @var  MemberOther  $memeberOther */
                $member = Member::whereMemberId($memberOther->secondary_member_id)->where('organization_id' , 15636)->first();
                if(empty($member) && $memberOther->member->organization_id == 15636){
                    $memberOther->secondary_member_id = null;
                    $memberOther->save();
                }
            }
        });

    }
}
