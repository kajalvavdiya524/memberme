<?php

namespace App\Mail;

use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    /* @var $org Organization*/
    public $org;

    /**
     * Create a new message instance.
     *
     * @param Organization $organization
     */
    public function __construct(Organization $organization)
    {
        $this->org = $organization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.adminNotificationEmail')->from('registrations@validate.co.nz','MemberMe')
            ->subject('Organization Created');
    }
}
