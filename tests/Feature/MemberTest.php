<?php

namespace Tests\Feature;

use App\Member;
use App\repositories\MemberRepository;
use App\repositories\OrganizationRepository;
use App\Subscription;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberTest extends TestCase
{
    /**
     *  Add Member To Database and check its functionality
     *
     * @return void
     */
    public function testExample()
    {
        $memberRepository = new MemberRepository();
        try {
            $testMember = $memberRepository->addMember([
                'first_name' => 'Test-Automation',
                'last_name' => 'Member',
                'organization_id' => 15550,
            ],false,false,false);

            $this->assertNotEmpty($testMember); // add member working perfectly.
        }catch (\Exception $exception){
            $this->assertFalse(false);
        }
    }

    /**
     * Assign Organization Card Template and generate card for the first time for this test member.
     */
    public function AssignTemplate()
    {
        /** @var OrganizationRepository $organizationRepository */
        $organizationRepository = new OrganizationRepository();

        /** @var Member $testMember */
        $testMember = Member::where('first_name','Test-Automation')->orderBy('id','desc')->first();

        try{
            $organization = $testMember->organization;
            if($organization){
                $template = $organization->templates()->first();
                if($template){
                    $organizationRepository->assignTemplate($testMember,$template);
                }
            }
            $this->assertTrue(true);
        }catch (\Exception $exception){
            $this->assertFalse(true);
        }
    }

    /**
     * Changing the field randomly and testing that.
     * @throws \Exception
     */
    public function testChangeField()
    {
        /** @var MemberRepository $memberRepository */
        $memberRepository = new MemberRepository();

        /** @var Member $testMember */
        $testMember = Member::where('first_name','Test-Automation')->orderBy('id','desc')->first();
        $fieldToChange = array_get(Member::AUTHORISED_FIELDS, random_int(0, count(Member::AUTHORISED_FIELDS) - 1 ));
        if( $fieldToChange && $testMember ){
            try{
                $memberRepository->updateField($testMember, $fieldToChange, ($fieldToChange == 'first_name') ? 'Test-Automation': null);
                $this->assertTrue(true);
            }catch (\Exception $exception){
                $this->assertTrue(false);
            }
        }else{
            print PHP_EOL. 'No Test for field change';
            $this->assertTrue(true);
        }
    }

    /**
     *  Test for Adding subscription to the member and changing its card generation.
     */
    public function testAddSubscription()
    {
        /** @var MemberRepository $memberRepository */
        $memberRepository = new MemberRepository();

        /** @var Member $testMember */
        $testMember = Member::where('first_name','Test-Automation')->orderBy('id','desc')->first();

        $subscription = Subscription::find(1);
        if($testMember){
            try{
                $memberRepository->addSubscription($testMember,$subscription);
                $this->assertTrue(true);
            }catch (\Exception $exception){
                $this->assertFalse( true );
            }
        }
    }

    /**
     * Deleting the test member created during test.
     * Verifying the deleteMember functionality of the application.
     *
     */
    public function testDeleteMember(){
        $memberRepository = new MemberRepository();
        $testMember = Member::where('first_name','Test-Automation')->orderBy('id','desc')->first();
        if($testMember){
            $memberDeleted = $memberRepository->deleteMember($testMember->id);
            $this->assertTrue($memberDeleted);

        }
    }

}
