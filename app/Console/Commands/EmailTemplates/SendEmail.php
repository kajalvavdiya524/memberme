<?php

namespace App\Console\Commands\EmailTemplates;

use App\repositories\EmailTemplateRepository;
use Illuminate\Console\Command;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending scheduled emails to associated groups and subscriptions.';

    /** @var EmailTemplateRepository $emailTemplateRepository */
    private $emailTemplateRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->emailTemplateRepository = new EmailTemplateRepository();
    }

    /**
     * Execute the console command.
     *
     * @throws \App\Exceptions\ApiException
     * @throws \SendGrid\Mail\TypeException
     */
    public function handle()
    {
//        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
        $memberList = $this->emailTemplateRepository->getMemberEmailListAndSendEmail();
    }
}
