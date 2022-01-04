<?php

namespace App\Http\Controllers\Kiosk;

use App\KioskBackground;
use App\Organization;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class BackgroundController extends Controller
{
    public function create(Request $request)
    {
        $allowedImageExtensionArray = ['jpeg', 'bmp', 'png','JPG'];
        $validationRules = [
            'background' => 'mimes:' . implode(",", $allowedImageExtensionArray),
            'id' => 'exists:kiosk_backgrounds,id',
            'label' => 'string',
            'orientation' => 'between:1,2'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $file = $request->file('background');
        if($file){
            $name = $file->getClientOriginalName();
            $name = md5($name).'.'.$file->getClientOriginalExtension();
            $path = '/kiosk-backgrounds/'.$name;
            Storage::put($path,File::get($file->getRealPath()));
            $url =  Storage::disk('local')->url($path);
        }

        if(isset($request->id) && !empty($request->id)){
            $kioskBackground = KioskBackground::find($request->id);
        }else{
            $kioskBackground = new KioskBackground();
        }

        $kioskBackground->label = $request->get('label');
        $kioskBackground->url = !empty($url)?$url:$kioskBackground->url;
        $kioskBackground->orientation = $request->get('orientation');

        $kioskBackground->organization_id = \Config::get('global.MEMBERME_ID'); // this will update and create the default system kiosk background for site setting > kiosk

        $kioskBackground->save();
        $kioskBackground->refresh();

        return api_response($kioskBackground);
    }

    /**
     * Returning all the kiosk backgrounds to be updated by the super admin.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $allBackgrounds = KioskBackground::whereOrganizationId(\Config::get('global.MEMBERME_ID'))->limit(KioskBackground::DEFAULT_BACKGROUND_COUNT)->orderBy('id','desc')->get();
        return api_response($allBackgrounds);
    }

    /**
     * Returning the kiosk background specific uploaded by the organization user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSpecificList(Request $request)
    {
        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $allBackgrounds = $organization->specificBackgrounds()->limit(KioskBackground::ORGANIZATION_BACKGROUND_COUNT)->orderBy('id','desc')->get();
        return api_response($allBackgrounds);
    }

    /**
     * Upload the new image if no id, and update the background image if there is a background id provided.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadOrUpdateSpecificBackground(Request $request)
    {
        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);
        $allowedImageExtensionArray = ['jpeg', 'bmp', 'png', 'JPG'];

        $validationRules = [
            'id' => 'exists:kiosk_backgrounds,id',
            'image-to-upload' => 'required|mimes:' . implode(",", $allowedImageExtensionArray) . '|max:4000',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $file = $request->file('image-to-upload');
        if($file){
            $name = $file->getClientOriginalName();
            $name = md5($name).'.'.$file->getClientOriginalExtension();
            $path = '/kiosk-backgrounds-'.$organization->id.'/'.$name;
            Storage::put($path,File::get($file->getRealPath()));
            $url =  Storage::disk('local')->url($path);
        }

        //finding the old record or creating the new background record.
        if(isset($request->id) && !empty($request->id)){
            $kioskBackground = KioskBackground::find($request->id);
        }else{
            $kioskBackground = new KioskBackground();
        }

        $kioskBackground->label = $request->get('label');
        $kioskBackground->url = !empty($url)?$url:$kioskBackground->url;
        $kioskBackground->orientation = $request->get('orientation',1);
        $kioskBackground->organization_id = $organization->id;  //will assign the organization id to the background.
        $kioskBackground->save();
        $kioskBackground->refresh();

        return api_response($kioskBackground);

    }
}
