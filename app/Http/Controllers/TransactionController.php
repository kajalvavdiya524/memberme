<?php

namespace App\Http\Controllers;

use App\Mail\Member\SendTransactionReciept;
use App\Organization;
use App\Receipt;
use App\repositories\PaymentRepository;
use App\Transaction;
use Illuminate\Http\Request;
use Mail;

class TransactionController extends Controller
{
    /** @var  PaymentRepository */
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getAllTransactions(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $transactions = $organization->transactions;

        return api_response($transactions);
    }

    public function sendEmail(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'transaction_id' => 'required|exists:transactions,id',
            'receipt_id' => 'required|exists:receipts,id',
            'organization_id' => 'required|exists:organizations,id',
//            'receipt_img' => 'file',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $receipt = $organization->receipts()->where('id', $request->get('receipt_id'))->first();
            $transaction = $organization->transactions()->where('id', $request->get('transaction_id'))->first();
            $payerMember = (!empty($transaction->payer)) ? $transaction->payer : null;
            $recieptImageArray = [];
            $receiptImageAttachment = $request->file('receipt_img');
            $recieptImageArray[] = $receiptImageAttachment;
            $validator->after(function ($validator) use ($receipt, $transaction, $payerMember, $recieptImageArray) {
                if (empty($transaction)) {
                    $validator->getMessageBag()->add('transaction_id', 'Transaction not found against this transction_id');
                }
                if (empty($payerMember)) {
                    $validator->getMessageBag()->add('payer', 'Payer Member not found against this transction');
                }
                if (empty($receipt)) {
                    $validator->getMessageBag()->add('receipt_id', 'We are sorry! We can not found the receipt');
                }
                if (empty($recieptImageArray)) {
                    $validator->getMessageBag()->add('receipt_img', 'Invalid Image To Send Customer. Please contact support');
                }
            });

            if (!$validator->fails()) {
                try {
                    if (empty(array_get($payerMember, 'email'))) {
                        return api_error(['error' => 'Payer member does not have email']);
                    }

//                    $this->paymentRepository->sendReciptEmail(array_get($payerMember, 'email'),$receipt,$transaction, $recieptImageArray,$organization,$payerMember,$request->file('receipt_img'));
                    Mail::to(array_get($payerMember, 'email'))->send(new SendTransactionReciept($receipt, $transaction, $recieptImageArray));
                    return api_response([], null, 'Receipt has been sent by email');
                } catch (\Exception $exception) {
                    return api_error(['error' => $exception->getMessage()]);
                }

            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }
}
