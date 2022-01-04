<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 3/14/2018
 * Time: 10:20 PM
 */

namespace App\repositories;


use App\base\IStatus;
use App\EmailTemplate;
use App\Exceptions\ApiException;
use App\Mail\Member\SendTransactionReciept;
use App\Member;
use App\Organization;
use App\Payment;
use App\PaymentType;
use App\Receipt;
use App\Subscription;
use App\Transaction;
use App\TransactionPartpay;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Mail;
use SendGrid\Mail\Attachment;
use SimpleSoftwareIO\QrCode\DataTypes\Email;

class PaymentRepository
{
    /* @var $memberService MemberRepository */
    public $memberService;

    public function __construct()
    {
        $this->memberService = new MemberRepository();
    }

    /**
     * @param Organization $organization
     * @param $id
     * @param $itemType
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findById(Organization $organization, $id, $itemType = Payment::ITEM_TYPE['MEMBER'])
    {
        return $organization->payments()->where([
            'id' => $id,
            'item_type' => $itemType
        ])->first();
    }

    public function addMemberPayment($data = [])
    {
        $member = Member::find(array_get($data, 'member_id'));
        $payments = [];

        $allParents = $this->memberService->getParents($member);
        $children = $this->memberService->getChildren($member);

        foreach ($allParents as $parentMember) {
            $payment = New Payment;
            $payment->organization_id = array_get($data, 'organization_id');
            $payment->item_id = array_get($parentMember, 'id');

            $subscriptionJoiningFee = $this->getSubscriptionJoiningFee($parentMember);
            if ($subscriptionJoiningFee) {
                $payment->is_first_payment = IStatus::ACTIVE;
            }
            $calculatedAmount = $this->calculatePaymentAmount($parentMember);

            $payment->amount = $calculatedAmount + $subscriptionJoiningFee;

            if ($subscriptionJoiningFee == 0) {
                $payment->is_first_payment = IStatus::INACTIVE;
            } else {
                $payment->is_first_payment = IStatus::ACTIVE;
            }
            $payment->card = array_get($data, 'card');
            $payment->email = array_get($data, 'email');
            $payment->subscription_id = $parentMember->subscription_id;
            $payment->discount = array_get($data, 'discount');

            /*if (!empty($member)) {
                $subtotal = array_get($member, 'joining_fee') + array_get($member, 'subscription_fee');
                $payment->total = $subtotal - array_get($data, 'discount');
            } else {
                $payment->total = array_get($data, 'total');
            }*/

            $payment->total = $calculatedAmount + $subscriptionJoiningFee;
            $payment->sub_total = $calculatedAmount;
            $payment->item_type = Payment::ITEM_TYPE['MEMBER'];
            $payment->save();

            $payment = Payment::where(['id' => $payment->id])->with(['organization' => function ($query) {
                $query->select('id');
                $query->with(['details' => function ($query) {
                    $query->select('organization_id', 'tax_factor');
                }]);
            }])->first();

            $payments[] = $payment;
        }

        $payment = New Payment;
        $payment->organization_id = array_get($data, 'organization_id');
        $payment->item_id = array_get($data, 'member_id');

        $subscriptionJoiningFee = $this->getSubscriptionJoiningFee($member);

        if ($subscriptionJoiningFee == 0) {
            $payment->is_first_payment = IStatus::INACTIVE;
        } else {
            $payment->is_first_payment = IStatus::ACTIVE;
        }

        $calculatedAmount = $this->calculatePaymentAmount($member);
        $payment->amount = $calculatedAmount + $subscriptionJoiningFee;

        $payment->card = array_get($data, 'card');
        $payment->email = array_get($data, 'email');
        $payment->subscription_id = $member->subscription_id;
        $payment->discount = array_get($data, 'discount');
        $payment->sub_total = $calculatedAmount;
        /*if (!empty($member)) {
            $subtotal = array_get($member, 'joining_fee') + array_get($member, 'subscription_fee');
            $payment->total = $subtotal - array_get($data, 'discount');
        } else {
            $payment->total = array_get($data, 'total');
        }*/
        $payment->total = $calculatedAmount + $subscriptionJoiningFee;


        $payment->item_type = Payment::ITEM_TYPE['MEMBER'];
        $payment->save();

        $payment = Payment::where(['id' => $payment->id])->with(['organization' => function ($query) {
            $query->select('id');
            $query->with(['details' => function ($query) {
                $query->select('organization_id', 'tax_factor');
            }]);
        }])->first();

        $payments[] = $payment;

        foreach ($children as $childs) {
            foreach ($childs as $child) {
                $payment = New Payment;
                $payment->organization_id = array_get($data, 'organization_id');
                $payment->item_id = array_get($child, 'id');
                $subscriptionJoiningFee = $this->getSubscriptionJoiningFee($child);

                if ($subscriptionJoiningFee == 0) {
                    $payment->is_first_payment = IStatus::INACTIVE;
                } else {
                    $payment->is_first_payment = IStatus::ACTIVE;
                }

                $calculatedAmount = $this->calculatePaymentAmount($child);
                $payment->amount = $calculatedAmount + $subscriptionJoiningFee;

                $payment->sub_total = $calculatedAmount;
                $payment->card = array_get($data, 'card');
                $payment->email = array_get($data, 'email');
                $payment->subscription_id = $child->subscription_id;
                $payment->discount = array_get($data, 'discount');

                /*if (!empty($member)) {
                    $subtotal = array_get($member, 'joining_fee') + array_get($member, 'subscription_fee');
                    $payment->total = $subtotal - array_get($data, 'discount');
                } else {
                    $payment->total = array_get($data, 'total');
                }*/
                $payment->total = $calculatedAmount + $subscriptionJoiningFee;

                $payment->item_type = Payment::ITEM_TYPE['MEMBER'];
                $payment->save();

                $payment = Payment::where(['id' => $payment->id])->with(['organization' => function ($query) {
                    $query->select('id');
                    $query->with(['details' => function ($query) {
                        $query->select('organization_id', 'tax_factor');
                    }]);
                }])->first();
                $payments[] = $payment;
            }
        }

        return $payments;
    }

    /**
     * @param $m    emberId int Member_id
     * @param int $status
     *
     */
    public function changeStatus($memberId, $status = IStatus::ACTIVE)
    {
        $member = Member::find($memberId);
        if (!empty($member)) {
            $member->status = $status;
            $member->update();
        }
    }

    /**
     * @param Member $member
     * @param Subscription $subscription
     * @return Carbon|null|static
     */
    public function calculateRenewalDate(Member $member, Subscription $subscription)
    {
        $startDate = $member->subscription_start_date;

        //for that persons who are going to pay their bill before payment deadline.
        if (!empty($member->renewal)) {
            $alreadyCalculatedExpiry = new Carbon($member->renewal);
        }

        $startDate = new Carbon($startDate);
        $subscriptionQuantity = $subscription->expiry_quantity;

        /* @var $dateOfBirth Carbon */
        $dateOfBirth = $member->date_of_birth;

        if (empty($dateOfBirth)) {
//            \Log::info('Empty date of birth for member ' . $member->first_name . ' ' . $member->last_name);
        }

        $subscriptionTerm = $subscription->expiry_term;
        $renewalDate = null;
        $reRenewalDate = null; //for that persons who are gointg to pay their bill before payment deadline

        switch ($subscriptionTerm) {
            case 'Days':
                $daysToAdd = $subscriptionQuantity;
                $renewalDate = $startDate->addDays($daysToAdd);
                if (!empty($alreadyCalculatedExpiry) && $member->subscription_id == $subscription->id && $member->renewal > Carbon::now()) {
                    $reRenewalDate = $alreadyCalculatedExpiry->addDays($daysToAdd);
                    return $reRenewalDate;
                }
                break;
            case 'Weeks':
                $weeksToAdd = $subscriptionQuantity;
                $renewalDate = $startDate->addWeeks($weeksToAdd);
                if (!empty($alreadyCalculatedExpiry) && $member->subscription_id == $subscription->id && $member->renewal > Carbon::now()) {
                    $reRenewalDate = $alreadyCalculatedExpiry->addWeeks($weeksToAdd);
                    return $reRenewalDate;
                }
                break;
            case 'Months':
                $monthsToAdd = $subscriptionQuantity;
                $renewalDate = $startDate->addMonths($monthsToAdd);
                if (!empty($alreadyCalculatedExpiry) && $member->subscription_id == $subscription->id && $member->renewal > Carbon::now()) {
                    $reRenewalDate = $alreadyCalculatedExpiry->addMonths($monthsToAdd);
                    return $reRenewalDate;
                }
                break;
            case 'Year':
                if (!empty($alreadyCalculatedExpiry) && $member->subscription_id == $subscription->id) {
                    $reRenewalDate = $alreadyCalculatedExpiry->addYear($subscriptionQuantity);
                    return $reRenewalDate;
                }

                $currentTime = new Carbon();    // current date
                $startDate = null;  //start date will have the date to add year according to that 6 options
                $dateSubscriptionAssinged = $member->subscription_assign_date;
                $paymentDate = $member->subscription_start_date;
                $joinDate = $member->created_at;


                switch ($subscription->expiry_date_option) {
                    case Subscription::EXPIRY_DATE_OPTION['CURRENT_DATE']:
                        $startDate = $currentTime;
                        break;
                    case Subscription::EXPIRY_DATE_OPTION['SUBSCRIPTION_ASSIGNED']:
                        $startDate = new Carbon($dateSubscriptionAssinged);
                        $startDate = date($startDate->day . '-' . $startDate->month . '-Y ' . $startDate->hour . ':' . $startDate->minute . ':' . $startDate->second);
                        $startDate = new Carbon($startDate);

                        break;
                    case Subscription::EXPIRY_DATE_OPTION['PAYMENT_DATE']:
                        $startDate = new Carbon($paymentDate);
//                        $startDate = date($startDate->day.'-'.$startDate->month.'-Y h:i:s');
                        $startDate = date($startDate->day . '-' . $startDate->month . '-Y ' . $startDate->hour . ':' . $startDate->minute . ':' . $startDate->second);
                        $startDate = new Carbon($startDate);
                        break;
                    case Subscription::EXPIRY_DATE_OPTION['FIRST_OF_THE_MONTH']:
                        $startDate = $currentTime->firstOfMonth();
                        break;
                    case Subscription::EXPIRY_DATE_OPTION['JOIN_DATE']:
                        $startDate = new Carbon($joinDate);
//                        $startDate = date($startDate->day.'-'.$startDate->month.'-Y h:i:s');
                        $startDate = date($startDate->day . '-' . $startDate->month . '-Y ' . $startDate->hour . ':' . $startDate->minute . ':' . $startDate->second);

                        $startDate = new Carbon($startDate);
                        break;
                    case Subscription::EXPIRY_DATE_OPTION['LAST_DAY_OF_THE_MONTH']:
                        $startDate = $currentTime->lastOfMonth();
                        break;
                    default:
                        $startDate = new Carbon();
                }

                $renewalDate = $startDate->addYear($subscriptionQuantity);

                if ($subscription->pro_rata == IStatus::ACTIVE) {
                    $proRataDate = $subscription->pro_rata_date;
                    if ($proRataDate == "Birthday") {
                        $dateOfBirth = new Carbon($dateOfBirth);
                        $currentTime = new Carbon();
                        $addYear = false;

                        if (!empty($subscription->pro_rata_birthday) && $subscription->pro_rata_birthday == 'Last day of the Month') {
                            $dateOfBirth = $dateOfBirth->lastOfMonth();
                        }
                        if ($dateOfBirth->month < $currentTime->month) {
                            //if user's birthday has been passed
                            $addYear = true;
                        }

                        if ($dateOfBirth->month == $currentTime->month && $currentTime->day >= $dateOfBirth->day)
                            $addYear = true;

                        $newDate = date($dateOfBirth->day . '-' . $dateOfBirth->month . '-Y h:i:s');
                        $newCarbonDate = new Carbon($newDate);


                        if ($addYear) {
                            $newCarbonDate = $newCarbonDate->addYear($subscriptionQuantity);
                        }
                        $renewalDate = $newCarbonDate;
                    } else {
                        try {
                            $dateOfBirth = new Carbon($proRataDate);
                            $currentTime = new Carbon();
                            $addYear = false;
                            if ($dateOfBirth->month < $currentTime->month) { // if birthday month has been passed.
                                $addYear = true; //if user's birthday has been passed
                            }

                            // if birthday month isn't passed but birth day has been passed.
                            if ($dateOfBirth->month == $currentTime->month && $currentTime->day >= $dateOfBirth->day)
                                $addYear = true;

                            $newDate = date($dateOfBirth->day . '-' . $dateOfBirth->month . '-Y h:i:s');
                            $newCarbonDate = new Carbon($newDate);

                            if ($addYear) {
                                $newCarbonDate = $newCarbonDate->addYear($subscriptionQuantity);
                            }

                            $renewalDate = $newCarbonDate;
                        } catch (\Exception $exception) {
                            \Log::info($exception->getMessage() . $member->id);
                        }
                    }
                }

                break;
        }
        return $renewalDate;
    }

    /**
     * @param Member $member
     * @return int
     */
    public function getSubscriptionJoiningFee(Member $member)
    {
        $payment = Payment::where([
            'subscription_id' => $member->subscription_id,
            'item_type' => Payment::ITEM_TYPE['MEMBER'],
            'item_id' => $member->id,
            'is_first_payment' => IStatus::ACTIVE
        ])->first();

        if (empty($payment) && !$member->is_imported) {
            return $member->subscription()->first()->joining_fee;
        } else {
            return 0;
        }
    }

    /**
     * @param Member $member
     * @return float|int|null
     * @throws ApiException
     */
    public function calculatePaymentAmount(Member $member)
    {
        /* @var $subscription Subscription */
        $subscription = $member->subscription()->first();
        if (empty($subscription)) {
            throw  new ApiException(null, ['error' => 'Subscription not found against member #' . $member->id]);
        }

        $dateOfBirth = $member->date_of_birth;
        if (empty($dateOfBirth)) {
            \Log::info('Empty date of birth for member ' . $member->first_name . ' ' . $member->last_name);
        }

        $subscriptionQuantity = $subscription->expiry_quantity;
        $subscriptionTerm = $subscription->expiry_term;
        $subscriptionFee = $subscription->subscription_fee;

        switch ($subscriptionTerm) {
            case 'Days':
                break;
            case 'Weeks':
                break;
            case 'Months':
                break;
            case 'Year':
                if ($subscription->pro_rata == 1) { //if prorata is enabled need to calculate the amount as per prorata process.
                    $proRataDate = $subscription->pro_rata_date;

                    //region To decide either we need to calculate prorata amount or not.
                    $firstPaymentPaid = Payment::whereItemId($member->id)->whereSubscriptionId($subscription->id)->whereNotNull('transaction_id')->first();

                    $shouldCalculateProrataAmount = false;
                    if($member->subscription_id == $subscription->id && !empty($member->renewal) && $member->is_imported){
                        $shouldCalculateProrataAmount = false;
                    }else if (empty($firstPaymentPaid)) {
                        $shouldCalculateProrataAmount = true;
                    } else {
                        $nextPayment = Payment::whereItemId($member->id)->where('subscription_id', '!=', $subscription->id)->where('created_at', '>', $firstPaymentPaid->created_at)->first();
                        if (!empty($nextPayment) && $nextPayment->subscription->pro_rata == IStatus::INACTIVE) {
                            $shouldCalculateProrataAmount = true;
                        }
                    }
                    /* else if(
                        $firstPaymentPaid->is_first_payment == IStatus::ACTIVE &&
                        !empty($firstPaymentPaid->transaction_id)
                    ) {
                        $shouldCalculateProrataAmount = false;
                    }*/

                    //endregion

                    if ($shouldCalculateProrataAmount) {
                        $monthlyFee = number_format($subscriptionFee / 12, 2);
                        if ($proRataDate == "Birthday") {
                            $dateOfBirth = new Carbon($dateOfBirth);

                            if (!empty($subscription->pro_rata_birthday) && $subscription->pro_rata_birthday == 'Last day of the Month') {
                                $dateOfBirth = $dateOfBirth->lastOfMonth();
                            }

                            $monthOfDob = $dateOfBirth->month;  //Birthday month to check if birthday passed or not
                            $currentTime = new Carbon();

                            $currentMonth = $currentTime->month;
                            $addYear = false;

                            if ($monthOfDob < $currentMonth) {
                                $addYear = true;
                            }

                            if ($monthOfDob == $currentMonth && $currentTime->day >= $dateOfBirth->day) {
                                $addYear = true;
                            }

                            if ($addYear) {
                                $monthOfDob += 12;
                            }

                            $remainingMonthsInBirthday = $monthOfDob - $currentMonth;

                            if ($remainingMonthsInBirthday != 0) {
                                $subscriptionFee = $monthlyFee * $remainingMonthsInBirthday;
                            }
                        } else {
                            try {
                                $dateOfBirth = new Carbon($proRataDate);
                                $monthOfDob = $dateOfBirth->month;
                                $currentTime = new Carbon();
                                $currentMonth = $currentTime->month;


                                $addYear = false;
                                if ($dateOfBirth->month < $currentTime->month) {      //if user's birthday has been passed
                                    $addYear = true;
                                }

                                if ($dateOfBirth->month == $currentTime->month && $currentTime->day >= $dateOfBirth->day)
                                    $addYear = true;


                                if ($addYear) {
                                    $monthOfDob += 12;
                                }

                                $remainingMonthsInBirthday = $monthOfDob - $currentMonth;
                                if ($remainingMonthsInBirthday != 0) {
                                    $subscriptionFee = $monthlyFee * $remainingMonthsInBirthday;
                                }
                            } catch (\Exception $exception) {
                                \Log::info($exception->getMessage() . $member->id);
                            }
                        }
                    }
                    break;
                }
        }
        return $subscriptionFee;
    }

    /**
     * @param $email
     * @param Receipt $receipt
     * @param Transaction $transaction
     * @param $imagesArray
     * @param Organization $organization
     * @param Member $member
     * @param $attachment UploadedFile
     * @throws ApiException
     * @throws \SendGrid\Mail\TypeException
     */
    public function sendReciptEmail($email, Receipt $receipt, Transaction $transaction, $imagesArray, Organization $organization, Member $member, $attachment)
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $organization->emailTemplates()->memberPayment()->first();

        if (empty($emailTemplate->template_id)) {
            $emailTemplate = EmailTemplate::whereOrganizationId(15550)->memberPayment()->first();
        }

        if (!empty($email)) {

            /** @var Subscription $subscription */
            $subscription = $member->subscription()->first();
            $sendgridService = new \App\Services\Sendgrid\SendgridService();
            $sendgridService->setup($organization);

            $dynamicParameters = [
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'full_name' => $member->full_name,
                'member_no' => $member->member_id,
                'subscription' => (!empty($subscription)) ? $subscription->title : ''
            ];
            $email = $sendgridService->setupMail($email, $dynamicParameters);
            $email = $sendgridService->setTemplateId(
                $email
                , $emailTemplate->template_id
            );
            $content = file_get_contents($attachment->getRealPath());

            $emailAttachment = new Attachment($content, 'image/png', 'reciept.jpg', 'attachment');

            $email->addAttachment($emailAttachment);
            $sendgridService->send($email);
        }
    }

    /**
     * @param Organization $organization
     * @param array $data
     * @param array $payments
     * @param bool $partPay
     * @return Transaction[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws ApiException
     */
    public function createTransaction(Organization $organization, $data = [], $payments = [], $partPay = false)
    {
        $payerMemberId = isset($payments[0]) ? $payments[0]->item_id : null;
        $payerMember = Member::find($payments[0]->item_id);

        if (empty($payerMember)) {
            throw new ApiException(null, ['error' => 'Unable to find payer member']);
        }


        //adding transactions
        if (!empty(array_get($data, 'transaction_id'))) {
            $newTransaction = $organization->transactions()->where('transactions.id', array_get($data, 'transaction_id'))->first();
        } else {
            $newTransaction = new Transaction();
        }
        $newTransaction->organization_id = $organization->id;
        $total = $organization->payments()->whereIn('id', array_get($data, 'payment_ids'))->sum('total');
        if ($total) {
            $newTransaction->total = $total;
        } else {
            $newTransaction->total = array_get($data, 'total');
        }

        $newTransaction->expiry_date_time = Carbon::now()->addDay(3);
        $newTransaction->payment_type = array_get($data, 'payment_type');
        $newTransaction->payer_member_id = $payerMemberId;
        $newTransaction->status = IStatus::ACTIVE;
        $newTransaction->save();

        if ($partPay) {
            $paymentType = $organization->paymentTypes()->where('payment_types.id', array_get($data, 'payment_type_id'))->first();
            $this->addPartPayment($newTransaction, $paymentType, $data['part_pay_amount']);
//            $this->addPartPayments($organization, $newTransaction, $data);
        }
        //adding receipt
        if (!empty($organization->details->tax_factor))
            $gstTax = $newTransaction->total * $organization->details->tax_factor;
        else
            $gstTax = null;

        $newReceipt = new Receipt();
        $newReceipt->due_date = $newTransaction->expiry_date_time;
        $newReceipt->sub_total = $newTransaction->total;
        $newReceipt->gst = $gstTax;
        $newReceipt->organization_id = $organization->id;
        $newReceipt->total = $newTransaction->total;
        $newReceipt->payer_member_id = $payerMemberId;
        $newReceipt->send_email = IStatus::ACTIVE;

        $date = array_get($data, 'payment_transction_date');
        $dateToSet = null;
        if (!empty($date)) {
            $dashDate = str_replace('/', '-', $date);
            $time = date('h:i:s');
            $dashDate = $dashDate . '' . $time;
            $dateToSet = date('Y-m-d h:s:i', strtotime($dashDate));
        }

        if (!empty($dateToSet)) {
            $newReceipt->payment_date = $dateToSet;
        } else {
            $newReceipt->payment_date = Carbon::now();
        }
        $newReceipt->save();

        //updating necessary things
        $newTransaction->receipt_id = $newReceipt->id;
        $newTransaction->save();

        //region updating transaction_id in payments and setting them as inactive.
        $organization->payments()->whereIn('id', array_get($data, 'payment_ids'))->update(
            [
                'status' => IStatus::INACTIVE,
                'transaction_id' => $newTransaction->id,
                'created_at' => $newTransaction->created_at,
            ]);
        //endregion

        foreach ($payments as $payment) {
            /* @var $payment Payment */
            $member = Member::where(['id' => $payment->item_id, 'organization_id' => $organization->id])->first();
//              $subscriptionStartSetting = !empty($organization->organizationSettings->subscription_start_date)?$organization->organizationSettings->subscription_start_date:null;
//
//                    if(is_null($subscriptionStartSetting) || $subscriptionStartSetting == OrganizationSetting::SUBSCRIPTION_DROPDOWN_OPTION['PAYMENT_DATE']){
//                        $member->subscription_start_date = $newTransaction->created_at;
//                        $member->due = IStatus::INACTIVE;
//                        $member->financial = true;
//                        $member->update();
//                    }

            //region Updating members in all payments, setting subscription start date to current date, due to No and financial to Yes
            $member->update([
                'subscription_start_date' => Carbon::now(),
                'due' => IStatus::INACTIVE,
                'financial' => true,
            ]);
            //endregion

            $memberRepo = new MemberRepository();

            //region Changing member status which are included in this transaction to ACTIVe.
            $memberRepo->changeStatus($member, IStatus::ACTIVE);
            //endregion

            //region Setting renewal date for all members associatted with this transaction.
            $subscription = $payment->subscription;
            $renewalDate = $this->calculateRenewalDate($member, $subscription); //calculating renewal date
            $renewalDate = new Carbon($renewalDate);

            try {
                $member->renewal = $renewalDate->endOfDay();
            } catch (\Exception $exception) {
                $member->renewal = $renewalDate;
            }
            //endregion

            if ($member->template) {
                $member->member_id_card = $memberRepo->generateMemberCard($member->template, $member);
            }
            $member->save();
            try {
                $this->memberService->pushmemberToPos($member->organization, $member);
            } catch (\Exception $exception) {

            }
        }

        $newReceipt->refresh();

        //region Sending transactional email to payer email.
        if (!empty($payerMember->email) && array_get($data, 'send_email') == 1) {
            Mail::to(array_get($payerMember, 'email'))->send(new SendTransactionReciept($newReceipt, $newTransaction));
        }
        //endregion

        //region Making response: adding reciept, organization details, organization details and physical address.
        $result = Transaction::where('id', $newTransaction->id)->with([
            'receipt',
            'organization' => function ($query) {
                $query->select('organizations.id', 'organizations.name');
                $query->with(['details' => function ($detailsQuery) {
                    $detailsQuery->select(
                        'organization_details.organization_id',
                        'organization_details.physical_address_id',
                        'gst_number'
                    );
                    $detailsQuery->with(['physicalAddress' => function ($addressQuery) {
                        $addressQuery->select('addresses.*');
                    }]);
                }]);
            },
            'payments'
        ])->get();
        //endregion

        return $result;
    }

    /**
     * @param Transaction $transaction
     * @param $data
     */
    public function addPartPayments(Organization $organization, Transaction $transaction, $data)
    {
        foreach ($data['part_pays'] as $partPay) {
            $paymentType = $organization->paymentTypes()->where('payment_types.name', array_get($partPay, 'payment_type_name'))->first();
            /** @var TransactionPartpay $partPay */
            $this->addPartPayment($transaction, $paymentType, array_get($partPay, 'amount'));
        }
    }

    public function addPartPayment(Transaction $transaction, PaymentType $paymentType, $amount)
    {
        $partPay = new TransactionPartpay();
        $partPay->amount = array_get($partPay, 'amount');
        $partPay->owing_amount = $transaction->total - $partPay->amount;
        $partPay->payment_type_id = $paymentType->id;
        $partPay->transaction_id = $transaction->id;
        $partPay->save();

    }
}