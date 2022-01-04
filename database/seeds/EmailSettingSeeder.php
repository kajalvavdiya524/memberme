<?php

namespace Database\Seeders;

use App\EmailTemplate;
use App\SendgridSetting;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use PharIo\Manifest\Email;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sendgridSetting = new SendgridSetting();
        $sendgridSetting->organization_id = \Config::get('global.MEMBERME_ID');
        $sendgridSetting->api_key = \Config::get('global.SENDGRID_API_KEY');
        $sendgridSetting->key_added_datetime = Carbon::now();
        $sendgridSetting->save();

        //resetpasswod for user email template
        $emailTemplate = new EmailTemplate();
        $emailTemplate->template_id = \Config::get('global.SENDGRID_USER_PASSWORD_EMAIL_TEMPLATE_ID');
        $emailTemplate->email_type = EmailTemplate::EMAIL_TYPE['TRANSACTIONAL'];
        $emailTemplate->email_name = 'User Password Reset Email';
        $emailTemplate->event = EmailTemplate::EVENT['USER_RESET_PASSWORD'];
        $emailTemplate->organization_id = \Config::get('global.MEMBERME_ID');
        $emailTemplate->save();

        //resetpasswod for member email template
        $emailTemplate = new EmailTemplate();
        $emailTemplate->template_id = \Config::get('global.SENDGRID_MEMBER_PASSWORD_EMAIL_TEMPLATE_ID');
        $emailTemplate->email_type = EmailTemplate::EMAIL_TYPE['TRANSACTIONAL'];
        $emailTemplate->email_name = 'Member Password Reset Email';
        $emailTemplate->event = EmailTemplate::EVENT['MEMBER_RESET_PASSWORD'];
        $emailTemplate->organization_id = \Config::get('global.MEMBERME_ID');
        $emailTemplate->save();

    }
}
