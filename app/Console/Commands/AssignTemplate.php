<?php

namespace App\Console\Commands;

use App\Member;
use App\Organization;
use App\OrganizationCardTemplate;
use App\repositories\OrganizationRepository;
use Illuminate\Console\Command;
use function foo\func;

class AssignTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:template {organization_id} {--template-no=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will assign first template to description Organization.';

    /** @var string $pass */
    private $pass = 'me';

    /** @var int Organization id */
    private $id;

    /** @var $organization Organization */
    private $organization;

    /** @var $organizationRepo OrganizationRepository */
    private $organizationRepo;

    /** @var OrganizationCardTemplate $cardTemplate */
    private $cardTemplate;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrganizationRepository $organizationRepository)
    {
        parent::__construct();
        $this->organizationRepo = $organizationRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->afterInit();     //all necessary initializations after validation of parameters by laravel

        $this->isValid();       //validate Request, custom validation.

        $this->assignTemplate();
    }

    public function assignTemplate()
    {
        $this->organization->members()
            ->chunk(10, function ($members) {
                foreach ($members as $member) {
                    if(!$this->organizationRepo->assignTemplate($member, $this->cardTemplate)){
                        \Log::info('Unable To Assign Template' . ($this->cardTemplate)? $this->cardTemplate->title: '' . ' to '. $member->full_name);
                    }
                }
            });
    }

    /**
     * Do all initial initialization as the constructor will run even the required params are not provided.
     */
    public function afterInit()
    {
        $this->id = $this->argument('organization_id');
    }

    /**
     * Verify the Request Params.
     * If there is any invalid response. Send back as error.
     */
    public function isValid()
    {
        $password = $this->secret('What is the password?');
        if ($password != $this->pass) {
            $this->error('Insufficient access.');
            exit();
        }

        $this->organization = Organization::find($this->id);
        if (!$this->organization) {
            $this->error('Invalid Organization id.');
            exit();
        }

        if (!empty($this->option('template-no'))) {
            $this->cardTemplate = $this->organization->templates()
                ->where('label','Template '. $this->option('template-no'))
                ->first();
            if (!$this->cardTemplate) {
                $confirm = $this->confirm(' The Template Id is invalid. Should we assign the first template id?');
                if ($confirm) {
                    $this->info('Assigning First Template');
                    $this->cardTemplate = $this->organization->templates()
                        ->where('label','Template 1')
                        ->first();
                } else {
                    exit();
                }
            }
        } else {
            $this->cardTemplate = $this->organization->templates()
                ->where('label','Template 1')
                ->first();
        }
        if(!$this->cardTemplate){
            $this->error('Unable to find Card Template for this organization');
            exit();
        }
    }
}
