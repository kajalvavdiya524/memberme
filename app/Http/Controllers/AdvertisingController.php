<?php

namespace App\Http\Controllers;

use App\Advertising;
use App\AdvertisingImage;
use App\base\IStatus;
use App\Organization;
use App\repositories\AdvertisingRepository;
use DB;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class AdvertisingController extends Controller
{
    /* @var $advertisingRepo AdvertisingRepository */
    public $advertisingRepo;

    public function __construct(AdvertisingRepository $advertisingRepository)
    {
        $this->advertisingRepo = $advertisingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* @var  $organization Organization */
        $organization = $request->get(Organization::NAME);
        $advertisingList = $organization->advertising()->where(['status' => IStatus::ACTIVE])->with('advertisingImages')->get();
        return api_response($advertisingList);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
//            'id' => 'exists:advertisings,id',
            'name' => 'required|string',
            'delay' => 'numeric',
            'duration' => 'numeric',
//            'image' => 'required',
//            'sound' => 'required'
        ];


        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $data = $request->all();

        try {
            if (!empty($request->file('sound'))) {
                $advertisingSound = $request->file('sound');
                $advertisingSoundName = $advertisingSound->getClientOriginalName();
                $advertisingSoundName = md5($advertisingSoundName) . '.' . $advertisingSound->getClientOriginalExtension();
                $advertisingSoundPath = '/' . $organization->id . '-advertising-sounds/' . $advertisingSoundName;
                Storage::put($advertisingSoundPath, File::get($advertisingSound->getPathname()));
                $advertisingSoundUrl = Storage::disk('local')->url($advertisingSoundPath);
                $data['sound'] = $advertisingSoundUrl;
            }
        } catch (\Exception $e) {
            \Log::info('Unable to upload sound for '. $organization->id. ' at '. time());
        }

        $advertising = $this->advertisingRepo->createAdvertising($organization, $data);
        return api_response($advertising);
    }


    /**
     * Return the ad information..
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        /* @var $organization Organization */
        $organization =  $request->get(Organization::NAME);
        if(empty($organization)){
            return api_error(['error' => 'Invalid Organization']);
        }

        $advertising =  $organization->advertising()->where('advertisings.id' ,$id)->with('advertisingImages')->first();

        return api_response($advertising);
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
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $organization->advertising()->where(['advertisings.id' => $id])->delete();
        return api_response(null, null, 'Advertising Deleted');
    }

    public function updateSequence(Request $request)
    {
        /* @var $organization Organization */

        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'advertising_id' => 'required|exists:advertisings,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /**
         * @var $advertising Advertising
         */
        $advertising = $organization->advertising()->where('advertisings.id',$request->get('advertising_id'))->first();

        if(empty($advertising)){
            return api_error(['error' => 'Invalid advertising']);
        }

        foreach ($request->get('image') as $image) {
            /* @var  $advertisingImage AdvertisingImage */
            $advertisingImage = $advertising
                ->advertisingImages()
                ->where('advertising_images.id',array_get($image,'id'))
                ->first();

            if(!empty($advertisingImage)) {
                $advertisingImage->sequence = array_get($image,'sequence');
                $advertisingImage->save();
            }
        }

        return api_response($organization->advertising()->where('advertisings.id',$advertising->id)->with('advertisingImages')->first());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAdvertisingImage(Request $request, $id)
    {
        $organization = $request->get(Organization::NAME);
        AdvertisingImage::whereIn('advertising_id',$organization->advertising()->pluck('id'))->where('id',$id)->delete();
        return api_response(null,null,'Advertising Image Deleted Successfully');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllAdvertisingImages(Request $request, $templateId)
    {
        $organization = $request->get(Organization::NAME);

        /* @var $advertising Advertising */
        $advertising = $organization->advertising()->where('advertisings.template_no',$templateId)->first();
        if($advertising){
            $advertising->advertisingImages()->delete();
        }
        return api_response(null,null,'Advertising Images Deleted Successfully');
    }

}
