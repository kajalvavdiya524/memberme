<?php

namespace App\Http\Controllers;

use App\Organization;
use App\repositories\MemberRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    /** @var $memberRepository MemberRepository */
    public $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function addContact(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'first_name' => 'required',
            'contact_no' => 'required',
            'last_name' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var array preparing data for addMemberFunction
         * Must be included.
         * first_name
         * last_name
         * organization_id
         * $data
         */
        $data = $request->all();
        $data['organization_id'] = $organization->id;

        $contact = $this->memberRepository->addMember($data, false, false, false, true);
        $contact = $organization->contacts()->where('members.id', $contact->id)->with(
            [
                'physicalAddress',
                'postalAddress',
                'notes'
            ])->first();
        return api_response($contact);
    }

    /**
     * Data table listing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getList(Request $request, $orgID)
    {
        set_time_limit(300);

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        // contacts query for Yajra Datatable response.
        $contacts = $this->memberRepository->getContactList($organization, $request->all());

        return api_response(DataTables::of($contacts)->make(true));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function convertToMember(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);


        $validationRules = [
            'id' => 'required|exists:members,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $member = $this->memberRepository->convertContactToMember($organization, $request->all());

        return api_response($member,null,'Contact is converted to member successfully');
    }
}
