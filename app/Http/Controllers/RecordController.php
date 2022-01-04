<?php

namespace App\Http\Controllers;

use App\base\IRecordType;
use App\base\IResponseCode;
use App\Country;
use App\Helpers\ApiHelper;
use App\Organization;
use App\Plan;
use App\Record;
use App\repositories\RecordRepository;
use Illuminate\Http\Request;

class RecordController extends Controller
{

    /* @var $recordRepo RecordRepository*/
    public $recordRepo;

    public function __construct(RecordRepository $recordRepository)
    {
        $this->recordRepo = $recordRepository;
    }

    /**
     *  @api {post} /admin/records/edit-industry [[val-06-02]] Edit Industry
     * @apiVersion 0.1.0
     * @apiName [[val-06-02]] Edit Industry
     * @apiParam {number} id Id of industry
     * @apiParam {string} name name of industry
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Setting
     * @apiPermission Secured (Super Admin, Administrator)
     * @apiDescription Edit Industry
     *
     *
     * @return mixed
     */
    public function editIndustry(Request $request)
    {
        $validationRules = [
            'name' => 'required',
            'id' => 'required|exists:records,id',
        ];

        $validator = Validator($request->all(),$validationRules);
        if($validator->fails()){
            return response(ApiHelper::apiResponse(null,$validator->errors()));
        }

        $record = Record::where([
            'id' => $request->get('id'),
            'record_type_id' => IRecordType::INDUSTRY,
        ])->first();
        $validator->after(function ($validator) use($record) {
            if(empty($record)){
                $validator->getMessageBag()->add('id','Invalid Industry Id');
            }
        });

        if (!$validator->fails()) {
            $data = $request->all();
            $data['record_type_id'] = IRecordType::INDUSTRY;
            $record = $this->recordRepo->update($data);
            return response(ApiHelper::apiResponse($record,null,'Industry Updated Successfully'));
        } else {
            return api_error($validator->errors());
        }
    }

    public function editPosMember(Request $request)
    {
        $validationRules = [
            'name' => 'required',
            'id' => 'required|exists:records,id',
        ];

        $validator = Validator($request->all(),$validationRules);
        if($validator->fails()){
            return api_response($validator->errors());

        }
        $record = Record::where([
            'id' => $request->get('id'),
            'record_type_id' => IRecordType::POS_MEMBERS,
        ])->first();
        $validator->after(function ($validator) use($record) {
            if(empty($record)){
                $validator->getMessageBag()->add('id','Invalid Pos Member Id');
            }
        });

        if (!$validator->fails()) {
            $data = $request->all();
            $data['record_type_id'] = IRecordType::POS_MEMBERS;
            $record = $this->recordRepo->update($data);
            return response(ApiHelper::apiResponse($record,null,'Pos Member Updated Successfully'));
        } else {
            return api_error($validator->errors());
        }

    }

    /**
     *  @api {post} /admin/records/create-industry [[val-06-01]] Create Industry
     * @apiVersion 0.1.0
     * @apiName [[val-06-01]] Create Industry
     * @apiParam {string} name name of industry
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Setting
     * @apiPermission Secured (Super Admin, Administrator)
     * @apiDescription Create Industry
     *
     *
     * @return mixed
     */
    public function createIndustry(Request $request)
    {
        $validationRules = [
            'name' => 'required',
        ];
        $validator = Validator($request->all(),$validationRules);
        if($validator->fails()){
           return response(ApiHelper::apiResponse(null,$validator->errors()));
        }
        $data = $request->all();
        $data['record_type_id'] = IRecordType::INDUSTRY;
        $record = $this->recordRepo->create($data);
        return response(ApiHelper::apiResponse($record,null,'Industry Created Successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPosMember(Request $request)
    {
        $validationRules = [
            'name' => 'required',
        ];
        $validator = Validator($request->all(),$validationRules);
        if($validator->fails()){
            return api_response($validator->errors());
        }
        $data = $request->all();
        $data['record_type_id'] = IRecordType::POS_MEMBERS;
        $record = $this->recordRepo->create($data);
        return api_response($record,null,'Pos Member Created Successfully');
    }

    /**
     *
     *
     * @return \Illuminate\Http\JsonResponse
     *@api {get} /records/get-industry-list [[val-06-03]] Get Industry List
     * @apiVersion 0.1.0
     * @apiName [[val-06-03]] Get Industry List
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Setting
     * @apiPermission Public
     * @apiDescription Get Industry List
     *
     */
    public function getAllIndustries(){
       $data = $this->recordRepo->getAllIndustries();
       return api_response($data,null,'Industries List Loaded Successfully',IResponseCode::SUCCESS);
    }

    public function getAllPosMembers(){
        $data = $this->recordRepo->getAllPosMembers();
        return api_response($data,null,'All Pos Members');
    }

    public function deleteIndustry(Request $request)
    {
        $validationRules = [
            'id' => 'required|exists:records,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $record = Record::where([
                'id' => $request->get('id'),
                'record_type_id' => IRecordType::INDUSTRY,
            ])->first();
            $validator->after(function ($validator) use ($record) {
                if (empty($record)) {
                    $validator->getMessageBag()->add('id', 'Invalid Industry Id');
                }
            });
            if (!$validator->fails()) {
                $record->delete();
                return api_response(null,null,'Industry Deleted Successfully');
            } else {
                return api_error($validator->errors());
            }
        } else {
            return api_error($validator->errors());
        }
    }
    public function deletePosMember(Request $request)
    {
        $validationRules = [
            'id' => 'required|exists:records,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $record = Record::where([
                'id' => $request->get('id'),
                'record_type_id' => IRecordType::POS_MEMBERS,
            ])->first();
            $validator->after(function ($validator) use ($record) {
                if (empty($record)) {
                    $validator->getMessageBag()->add('id', 'Invalid Pos Member Id');
                }
            });
            if (!$validator->fails()) {
                $record->delete();
                return api_response(null,null,'Pos Member Deleted Successfully');
            } else {
                return api_error($validator->errors());
            }
        } else {
            return api_error($validator->errors());
        }
    }

    public function getContryList()
    {
        $countryCodeList = Country::select('name','id','country_code')->get();
        return response($countryCodeList);
    }

    /**
     * Saperate Api call list for mobile application.
     * This is a simple record call so that it has been taken in this controller. otherwise all member app calls are responding from membercontroller
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContryCodeList()
    {
        $countryCodeList = Country::get()->pluck('country_short_name','country_code');
        return api_response($countryCodeList);
    }


    public function getPlanList()
    {
        $planList = Plan::where('name', '!=','Testing')->get();
        return api_response($planList);
    }

    public function getUntillClientNames(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        return api_response($this->recordRepo->getAllPosClientCardName($organization->id));
    }
}
