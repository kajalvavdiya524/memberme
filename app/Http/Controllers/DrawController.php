<?php

namespace App\Http\Controllers;

use App\base\IStatus;
use App\Draw;
use App\DrawEntry;
use App\Organization;
use App\repositories\DrawRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DrawController extends Controller
{
    protected $drawRepo;

    /**
     * DrawController constructor.
     * @param DrawRepository $drawRepository
     */
    public function __construct(DrawRepository $drawRepository)
    {
        $this->drawRepo = $drawRepository;
    }

    /**
     * Display a listing of the draws.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);

        $draws = $organization->draws()->whereStatus(IStatus::ACTIVE)->orderBy('draws.id' , 'desc');

        return api_response(\DataTables::eloquent($draws)->orderColumn('id' ,'desc')->make(true));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string',
            'frequency' => 'required|in:' . implode(",", Draw::FREQUENCY),
            'frequency_limit' => 'in:' . implode(",", Draw::FREQUENCY_LIMIT),
            'frequency_limit_quantity' => 'required_if:frequency_limit,' . IStatus::ACTIVE,
            'frequency_limit_quantity_period' => 'required_if:frequency_limit,' . IStatus::ACTIVE . '|in:' . implode(',', Draw::FREQUENCY_LIMIT_PERIOD),
            'entry_limit' => 'in:' . implode(",", Draw::ENTRY_LIMIT),
            'entry_limit_quantity' => 'required_if:entry_limit,' . Draw::ENTRY_LIMIT['YES'],
            'print_entry' => 'in:' . implode(',', Draw::PRINT_ENTRY),
            'prizes' => 'array',
            'prizes.*.name' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        return api_response($this->drawRepo->addDraw($organization, $request->all()));
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * This function will return all the entries of the draw by draw id.
     *
     * @param Request $request
     * @param $drawId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getEntriesListByDrawId(Request $request, $drawId)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        if (!empty($organization)) {
            $entries = $organization->drawEntries()
                ->with([
                    'member' => function ($query) {
                        $query->select('id','member_id', 'full_name','status');
                    }
                ])->where('draw_id', $drawId)->get();

            //region Resetting appends
            foreach ($entries as $entry) {
                $entry->member->setAppends([]);
            }
            //endregion

            return api_response(\DataTables::of($entries)->make(true));
        }

        return api_error(['error' => 'Invalid organization']);
    }


    public function getDrawEntry(Request $request, $drawEntryId)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        if (!empty($organization)) {

            $drawEntry = $organization->drawEntries()->where('draw_entries.id',$drawEntryId)->with([
                'member' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])->first();

            return api_response($drawEntry);
        }

        return api_error(['error' => 'Invalid organization']);

    }

    public function deleteDraw(Request $request, $drawId)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $organization->draws()->where('draws.id',$drawId)->update(['status' => IStatus::INACTIVE]);
//        $organization->drawEntries()->where('draw_entries.draw_id',$drawId)->delete();
        return api_response(null,null,'Draw have been deleted.');
    }
}
