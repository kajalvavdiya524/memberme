<?php

namespace App\Mail\Member;

use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class SendMemberVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $organization;

    /**
     * Create a new message instance.
     * @param Member $member
     */
    public function __construct(Member $member)
    {
        $this->member = $member;

        if(empty($this->member->verify_token)){
            $this->member->verify_token = Str::random(60);
        }

        $this->organization = $member->organization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),'Registrations')->subject('Member Verification')->view('email.member.sendMemberVerificationEmail');
    }
}
