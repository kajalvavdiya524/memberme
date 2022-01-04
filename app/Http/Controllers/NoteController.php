<?php

namespace App\Http\Controllers;

use App\base\IStatus;
use App\Helpers\ApiHelper;
use App\Member;
use App\Note;
use App\Organization;
use App\repositories\NoteRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    /**
     * @var $noteRepo NoteRepository
     */
    public $noteRepo;


    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepo = $noteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get('organization');
        $notes = $organization->notes()->orderBy('created_at')->get();
        return api_response($notes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        /* @var  $organization Organization*/
        $organization = $request->get(Organization::NAME);
        /* @var $user \App\User*/
        $user  = ApiHelper::getApiUser();
        $validationRules = [
            'member_id' => 'required|exists:members,id',
            'note' => 'required|string'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            /* @var $member Member  This will return member either the contact or member.*/
            $member = Member::where([
                'organization_id' => $organization->id,
                'id' => $request->member_id
            ])->first();

            $validator->after(function ($validator) use($member) {
                if(!$member){
                    $validator->getMessageBag()->add('member_id','Member not found');
                }
            });
            if (!$validator->fails()) {
                $note = $this->noteRepo->storeNote($organization,$user,$request->all());
                return api_response($note);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function updateMemberNote(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'member_id' => 'required|exists:members,id',
            'note' => 'required|string',
            'note_id' => 'required|exists:notes,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $organization = $request->get(Organization::NAME);
        if (empty($organization)) {
            return api_error(['error' => 'No Organization Selected']);
        }

        /* @var $user \App\User*/
        $user = ApiHelper::getApiUser();
        $note = $organization->notes()->where([
            'id' => $request->get('note_id'),
            'member_id' => $request->get('member_id'),
        ])
            ->first();

        if (empty($note)) {
            return api_error(['error' => 'Invalid Note']);
        }
        $note = $this->noteRepo->storeNote($organization,$user,$request->all());
        return api_response($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws \App\Exceptions\ApiException
     */
    public function destroy(Request $request,$id)
    {

        $organization = $request->get(Organization::NAME);
        if (empty($organization)) {
            return api_error(['error' => 'No Organization Selected']);
        }

        /* @var $user \App\User*/
        $user = ApiHelper::getApiUser();
        $note = $organization->notes()->where([
            'id' => $id,
            'user_id' => $user->id,
        ])
            ->first();

        if(empty($note)){
            return api_error(['error' => 'Invalid Note']);
        }
        $this->noteRepo->delete($organization,$id);
        return api_response(null,null,'Note Successfully Removed');
    }

}
