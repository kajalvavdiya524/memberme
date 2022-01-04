<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Group;
use App\Organization;
use App\repositories\EmailTemplateRepository;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EmailTemplateController extends Controller
{

    /** @var  $emailTemplateRepo EmailTemplateRepository */
    public $emailTemplateRepo;

    public function __construct()
    {
        $this->emailTemplateRepo = new EmailTemplateRepository();
    }

    public function sendEmail(Request $request)
    {
        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'email_template_id' => 'required|exists:email_templates,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate =  $organization->emailTemplates()->where('id',$request->get('email_template_id'))->first();
        if(empty($emailTemplate)){
            return api_error(['error' => 'Unable to found email template']);
        }

        $subscriptions = $emailTemplate->subscriptions;
        $groups = $emailTemplate->groups;
        /** @var Collection $membersToSendEmail */
        $membersToSendEmail = new Collection();
        foreach ($subscriptions as $subscription) {
            /** @var $subscription Subscription */
            $members = $subscription->members()->whereNotNull('email')->get();

            $membersToSendEmail = $membersToSendEmail->concat($members);
        }

        foreach ($groups as $group) {
            /** @var $group Group */
            $members = $group->members()->whereNotNull('email')->get();
            $membersToSendEmail = $membersToSendEmail->concat($members);
        }

        $membersToSendEmail = $membersToSendEmail->unique('email');
        $parameters = [];
        foreach ($membersToSendEmail as $member) {
            $subscription = $member->subscription()->select('subscriptions.title')->first();
            $parameters[] =[
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'full_name' => $member->full_name,
                'member_no' => $member->member_id,
                'email' => $member->email,
                'subscription' => (!empty($subscription))? $subscription->title : ''
            ];
        }
        $this->emailTemplateRepo->sendEmail($emailTemplate,$parameters);
        return api_response([], null, 'Email has been sent to all corresponding members.');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEmailTemplate(Request $request , $id )
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $emailTemplate = $organization->emailTemplates()->where(['email_templates.id' => $id])->first();
        if($emailTemplate) {
            $this->emailTemplateRepo->delete($id);
        }else{
            return api_error(['error' => 'Email Template not found']);
        }
        return api_response([], [], 'Email Template deleted Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function verifyTemplateId(Request $request)
    {
        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);
        $validationRules = [
            'template_id' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $sendgridTemplateName = $this->emailTemplateRepo->verifyTemplateId($organization, $request->get('template_id'));

        if($sendgridTemplateName){
            return api_response($sendgridTemplateName);
        }else{
            return api_error(['error' => 'Invalid Template Id']);
        }
    }
}
