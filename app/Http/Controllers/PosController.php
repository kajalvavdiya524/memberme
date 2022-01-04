<?php

namespace App\Http\Controllers;

use App\Member;
use App\Organization;
use App\repositories\MemberRepository;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /** @var MemberRepository $memberRepository */
    public $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }
    public function login(Request $request)
    {

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'password' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $organization = Organization::where([
            'id' => $request->get('organization_id'),
            'password' => $request->get('password')
        ])
            ->select(['id','api_token','name'])->first();

        if($organization){
            $organization->makeVisible('api_token');
            return api_response($organization);
        }
        return api_error(['error' => 'Invalid credentials']);
    }

    public function updateMemberPoints(Request $request)
    {
        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'client_id' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $member = Member::where('untill_id' , $request->get('client_id'))->first();

        if (!empty($member)) {
            $memberService = new MemberRepository();
            $memberInstance = Member::find($member->id);
            $member->points = number_format($member->points, 2);
            try{
                $memberPoints = $memberService->getUpdatedMemberPoints($organization, $memberInstance);
            }catch (\Exception $exception){

            }

            $message = 'Points updated successfully';
            if (!empty($memberPoints)) {
                $memberOther = $memberInstance->others;
                if (!empty($memberOther)) {
                    $memberOther->points = $memberPoints;
                    $memberOther->save();
                }
                $message = 'Points update failed, try again.';
            }
        }

        return api_response(null,null,$message);

    }
}
