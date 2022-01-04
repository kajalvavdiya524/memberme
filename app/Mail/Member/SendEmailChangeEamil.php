<?php

namespace App\Mail\Member;

use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailChangeEamil extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $token;


    /**
     * Create a new message instance.
     *
     * @param Member $member
     *
     * @param $token
     */
    public function __construct(Member $member, $token)
    {
        $this->member = $member;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),'Team@memberme')->subject('Member change email verification')->view('email.member.sendEmailChangeEmail');
    }
}
