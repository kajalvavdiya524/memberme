<?php

namespace App\Mail\Member;

use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendResetEmailForMembers extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    /**
     * Create a new message instance.
     *
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try{
            return $this->from(config('mail.from.address'),'Member Me')->subject('Reset password email')->view('email.member.sendResetEmailForMember');
        }catch (\Exception $exception){
            \Log::info($exception->getMessage());
        }
    }
}
