<?php

namespace App\Mail\Member;

use App\Receipt;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTransactionReciept extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Receipt
     */
    public $receipt;

    /**
     * @var Transaction
     */
    public $transaction;

    /**
     * @var mixed
     */
    public $payments;

    /**
     * @var
     */
    public $subscription;

    /**
     * @var mixed
     */
    public $payer;

    public $organization;

    public $address;

    public $officePhone;

    public $organizationDetails;

    public $discount;

    public $tax;

    public $receiptImageAttachment;
    /**
     * Create a new message instance.
     *
     * @param Receipt $receipt
     * @param Transaction $transaction
     */
    public function __construct(Receipt $receipt, Transaction $transaction,array $receiptImageAttachment)
    {
        $this->receipt = $receipt;
        $this->transaction = $transaction;
        $this->receiptImageAttachment = $receiptImageAttachment;
        if($transaction->organization){
            $this->organization = $transaction->organization;
        }

        if(!empty($this->organization)){
            $organizationDetails = $this->organization->details;
            if(!empty($organizationDetails )){
                $this->organizationDetails = $organizationDetails;
                $this->address = $organizationDetails->physicalAddress;
                $this->officePhone = $organizationDetails->office_phone;
            }
        }
        if($transaction->payer){
            $this->payer = $transaction->payer;
        }
        if($transaction->payer->subscription){
            $this->subscription = $transaction->payer->subscription;
        }

        if($transaction->payments){
            $this->payments = $transaction->payments;
            $this->discount = $transaction->payments()->sum('discount');
            $total = $transaction->payments()->sum('total');

            $this->tax = $total/15;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->receiptImageAttachment) && is_array($this->receiptImageAttachment)) {
            foreach ($this->receiptImageAttachment as $attachment) {
                $this->attach($attachment, ['as' => $this->receipt->receipt_no.'.png']);
            }
        }

        return $this->from(config('mail.from.address'),'Payments')->subject('Payment Receipt')->view('email.receiptEmail');
    }
}
