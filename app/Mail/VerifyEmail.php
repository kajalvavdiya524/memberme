<?php

namespace App\Mail;

use App\Organization;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $org;
    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user,Organization $organization)
    {
        $this->user = $user;
        $this->org = $organization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.sendView')->from('registrations@validate.co.nz','registrations@validate.co.nz')
            ->subject('Memberme Verification');
    }
}
