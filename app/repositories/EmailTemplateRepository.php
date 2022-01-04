<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 12/2/2019
 * Time: 1:56 PM
 */

namespace App\repositories;


use App\EmailTemplate;
use App\Exceptions\ApiException;
use App\Exceptions\Sendgrid\CustomSendgridException;
use App\Organization;
use App\Services\Sendgrid\SendgridService;
use Carbon\Carbon;
use Log;
use PharIo\Manifest\Email;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;

class EmailTemplateRepository
{
    /** @var  $sendgridService SendgridService */
    public $sendgridService;

    public function __construct()
    {
        $this->sendgridService = new SendgridService();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param array $data
     * @throws ApiException
     */
    public function sendEmail(EmailTemplate $emailTemplate, $data = [])
    {
        $organization = $emailTemplate->organization;
        $this->sendgridService->setup($organization);
        /** @var Mail $mail */
        foreach ($data as $parameters) {
            $parametersToSend = [
                'first_name' => array_get($parameters, 'first_name',' '),
                'last_name' => array_get($parameters, 'last_name',' '),
                'full_name' => array_get($parameters, 'full_name',' '),
                'member_no' => array_get($parameters, 'member_id',' '),
                'email' => array_get($parameters, 'email',' '),
                'subscription' => array_get(array_get($parameters, 'subscription',[]),'title',' '),
                'expiry_date' => !empty(array_get($parameters, 'renewal',''))? date('d/m/Y',strtotime(array_get($parameters, 'renewal'))) : ' ',
                'contact_landline' => array_get($parameters, 'phone',' '),
                'contact_mobile' => array_get($parameters, 'contact_no',' '),
                'date_of_birth' => !empty(array_get($parameters, 'date_of_birth',''))? date('d/m/Y',strtotime(array_get($parameters, 'date_of_birth'))) : ' ',
                'subscription_cost' => '$' . number_format((float)array_get( array_get($parameters,'subscription',[]), 'subscription_fee',''), 2, '.', ''),
                'points' => array_get(array_get($parameters, 'others', []), 'points',' '),
                'physical_address' => array_get(array_get($parameters, 'physical_address', []), 'address1',' ')  .' '. array_get(array_get(array_get($parameters, 'physical_address', []), 'country',[]),'name',' '),
                'postal_address' => array_get(array_get($parameters, 'postal_address', []), 'address1',' ') .' '. array_get(array_get(array_get($parameters, 'postal_address', []), 'country',[]),'name',' '),
            ];
//            print_r($parametersToSend);exit;
            try {
                $this->sendEmailBySendgrid($emailTemplate->template_id, $parametersToSend, $emailTemplate->send_from);
            } catch (TypeException $exception) {
                \Log::error($exception->getMessage());
            } catch (CustomSendgridException $e) {
//                    if($e->getCode() == CustomSendgridException::CODES['INVALID_TEMPLATE']){
//                        $emailTemplate->invalid = true;
//                        $emailTemplate->invalid_reason = 'Invalid Template Id';
//                        $emailTemplate->save();
//                    }
                \Log::error($e->getMessage());
            }
        }
    }


    /**
     * @param $emailTemplateId integer Email Template id
     * @param array $parameters Parameters Must have email and first_name
     * @param $from
     * @throws CustomSendgridException
     * @throws TypeException
     */
    public function sendEmailBySendgrid($emailTemplateId, $parameters, $from)
    {
        $mail = $this->sendgridService
            ->setTemplateId(
                $this->sendgridService->setupMail(
                    array_get($parameters, 'email'),
                    $parameters,
                    $from
                ),
                $emailTemplateId
            );
        try {
            $this->sendgridService->send($mail);
        } catch (CustomSendgridException $exception) {
            throw new CustomSendgridException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = EmailTemplate::find($id);
        if ($emailTemplate) {
            $emailTemplate->groups()->detach();
            $emailTemplate->subscriptions()->detach();
            $emailTemplate->delete();
        }
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function getMemberEmailListAndSendEmail()
    {
        setTimezone(); //setting Timezone according to organization
        \DB::enableQueryLog();

        $emailTemplates = EmailTemplate::where('send_email_time', (new Carbon())->hour)
            ->where('email_type', EmailTemplate::EMAIL_TYPE['SCHEDULED'])
            ->get();
        $memberEmailList = [];
        foreach ($emailTemplates as $emailTemplate) {
            $column = $this->getColumnName($emailTemplate->send_email_date);
            $dateToMatch = $this->getDateToMatch($emailTemplate);
            if ($column) {
                $emailList = $this->getEmailList($emailTemplate, $column, $dateToMatch);

                $this->sendEmail($emailTemplate, $emailList);
            }
        }
        return $memberEmailList;
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param $column
     * @param Carbon $dateToMatch
     * @return array
     */
    public function getEmailList(EmailTemplate $emailTemplate, $column, Carbon $dateToMatch)
    {
        $emailList = [];
        if ($emailTemplate->email_group == EmailTemplate::EMAIL_GROUP['SUBSCRIPTION']) {
            $subscriptions = $emailTemplate->subscriptions()->get();
            foreach ($subscriptions as $subscription) {
                if ($column == 'date_of_birth') {
                    $memberEmails = $subscription->members()
                        ->with([
                            'postalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'physicalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'others' => function ($q) {
                                $q->select('member_others.member_id', 'points');
                            },
                            'subscription' => function ($q) {
                                $q->select('subscriptions.id', 'subscriptions.subscription_fee', 'subscriptions.title');
                            }
                        ])
                        ->whereNotNull('email')
                        ->whereDay($column, $dateToMatch->day)
                        ->whereMonth($column, $dateToMatch->month)
                        ->select([
                            'members.id',
                            'email',
                            'first_name',
                            'last_name',
                            'date_of_birth',
                            'phone',
                            'contact_no',
                            'renewal',
                            'members.subscription_id',
                            'full_name',
                            'members.member_id',
                            'members.physical_address_id',
                            'members.postal_address_id'
                        ])
                        ->get();
                } else {
                    $memberEmails = $subscription->members()
                        ->with([
                            'postalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'physicalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'others' => function ($q) {
                                $q->select('member_others.member_id', 'points');
                            },

                            'subscription' => function ($q) {
                                $q->select('subscriptions.id', 'subscriptions.subscription_fee', 'subscriptions.title');
                            }
                        ])
                        ->whereNotNull('email')
                        ->whereDate('members.' . $column, $dateToMatch->toDateString())
                        ->select([
                            'members.id',
                            'email',
                            'first_name',
                            'last_name',
                            'date_of_birth',
                            'phone',
                            'contact_no',
                            'renewal',
                            'members.subscription_id',
                            'full_name',
                            'members.member_id',
                            'members.physical_address_id',
                            'members.postal_address_id'
                        ])
                        ->get();
                }
                $emailList = array_merge($emailList, $memberEmails->toArray());
            }
        } else if ($emailTemplate->email_group == EmailTemplate::EMAIL_GROUP['GROUPS']) {
            $groups = $emailTemplate->groups()->get();
            foreach ($groups as $group) {

                if ($column == 'date_of_birth') {
                    $memberEmails = $group->members()
                        ->with([
                            'postalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'physicalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'others' => function ($q) {
                                $q->select('member_others.member_id', 'points');
                            },

                            'subscription' => function ($q) {
                                $q->select('subscriptions.id', 'subscriptions.subscription_fee', 'subscriptions.title');
                            }
                        ])
                        ->whereNotNull('email')
                        ->whereDay($column, $dateToMatch->day)
                        ->whereMonth($column, $dateToMatch->month)
                        ->select([
                            'members.id',
                            'email',
                            'first_name',
                            'last_name',
                            'date_of_birth',
                            'phone',
                            'contact_no',
                            'renewal',
                            'members.subscription_id',
                            'full_name',
                            'members.member_id',
                            'members.physical_address_id',
                            'members.postal_address_id'
                        ])
                        ->get();
                } else {
                    $memberEmails = $group->members()
                        ->with([
                            'postalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'physicalAddress' => function ($q) {
                                $q->select('addresses.id', 'country_id', 'address1');
                                $q->with(['country' => function ($q) {
                                    $q->select('countries.id', 'countries.name');
                                }]);
                            }, 'others' => function ($q) {
                                $q->select('member_others.member_id', 'points');
                            },
                            'subscription' => function ($q) {
                                $q->select('subscriptions.id', 'subscriptions.subscription_fee', 'subscriptions.title');
                            }
                        ])
                        ->whereDate('members.' . $column, $dateToMatch->toDateString())
                        ->select([
                            'members.id',
                            'email',
                            'first_name',
                            'last_name',
                            'date_of_birth',
                            'phone',
                            'contact_no',
                            'renewal',
                            'members.subscription_id',
                            'full_name',
                            'members.member_id',
                            'members.physical_address_id',
                            'members.postal_address_id'
                        ])
                        ->get();
                }
                $emailList = array_merge($emailList, $memberEmails->toArray());
            }
        }
        return $emailList;
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return Carbon
     */
    public function getDateToMatch(EmailTemplate $emailTemplate)
    {
        $today = new Carbon();
        switch ($emailTemplate->before_or_after) {
            case EmailTemplate::BEFORE_OR_AFTER['BEFORE']:
                $dateToMatch = $today->addDays($emailTemplate->days);
                break;
            case EmailTemplate::BEFORE_OR_AFTER['AFTER']:
                $dateToMatch = $today->subDays($emailTemplate->days);
                break;

            case EmailTemplate::BEFORE_OR_AFTER['DAY_OF']:
                $dateToMatch = $today;
                break;

            default:
                $dateToMatch = $today;
        }
        return $dateToMatch;
    }

    /**
     * @param $sendEmailDate
     * @return string|null
     */
    public function getColumnName($sendEmailDate)
    {
        if ($sendEmailDate == EmailTemplate::SEND_EMAIL_DATE['MEMBER_CREATED_AT']) {
            $columnName = 'joining_date';
        } else if ($sendEmailDate == EmailTemplate::SEND_EMAIL_DATE['MEMBER_DATE_OF_BIRTH']) {
            $columnName = 'date_of_birth';
        } else if ($sendEmailDate == EmailTemplate::SEND_EMAIL_DATE['MEMBER_EXPIRY_DATE']) {
            $columnName = 'renewal';
        } else {
            $columnName = null;
        }

        return $columnName;
    }

    /**
     * @param Organization $organization
     * @param $templateId
     * @return bool | string
     * @throws ApiException
     */
    public function verifyTemplateId(Organization $organization, $templateId)
    {
        $this->sendgridService->setup($organization);
        $sendgridTemplate = $this->sendgridService->checkTemplateId($templateId);
        if ($sendgridTemplate) {
            return $sendgridTemplate['name'];
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getUserResetTemplate()
    {
        return EmailTemplate::whereOrganizationId(\Config::get('global.MEMBERME_ID'))->userResetPassword()->first();
    }
}