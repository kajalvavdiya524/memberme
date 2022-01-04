<?php

namespace App\Mail;

use App\Organization;
use App\Role;
use App\VerifyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUser extends Mailable
{
    use Queueable, SerializesModels;

    public $verifyDetails;
    public $organization;
    public $role;

    /**
     * Create a new message instance.
     *
     * @param VerifyUser $verifyUser
     * @param Organization $organization
     * @param Role $role
     */
    public function __construct(VerifyUser $verifyUser,Organization $organization,Role $role)
    {
        $this->verifyDetails = $verifyUser;
        $this->organization = $organization;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),'Registrations')->subject('Organisation Invite')->view('email.verifyUser');
    }
}
