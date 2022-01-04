<?php

namespace App\Mail;

use App\Member;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MemberAddNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Organization
     */
    public $organization;
    /**
     * @var Member
     */
    public $member;
    /**
     * Create a new message instance.
     *
     * @param Organization $organization
     * @param Member $member
     */
    public function __construct(Organization $organization, Member $member)
    {
        $this->organization = $organization;
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),'Registrations')->subject('Member Invite')->view('email.memberAddNotification');
    }
}
