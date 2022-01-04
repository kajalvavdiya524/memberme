<?php

namespace App\Mail;

use App\Member;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddMemberEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    public  $member;
    /**
     * @var
     */
    public  $organization;

    /**
     * @var
     */
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(Organization $organization,Member $member,$password)
    {
        $this->member = $member;
        $this->password = $password;
        $this->organization = $organization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),'Registrations')->subject('Member Invite')->view('email.memberLogin');
    }
}
