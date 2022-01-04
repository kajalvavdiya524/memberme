<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 1/19/2019
 * Time: 6:02 PM
 */

namespace App\repositories;


use App\Advertising;
use App\AdvertisingImage;
use App\Organization;
use File;
use Storage;

class AdvertisingRepository
{
    public function createAdvertising(Organization $organization, $data = [])
    {
        /* @var $advertising Advertising */
        if(!empty(array_get($data,'id'))) {
            $advertising = $organization->advertising()->where(['advertisings.id' =>  array_get($data, 'id')])->first();
        }

        if(!empty(array_get($data,'template_no'))){
            $advertising = $organization->advertising()->where(['advertisings.template_no' =>  array_get($data, 'template_no')])->first();
        }

        if(empty($advertising)){
            $advertising = new  Advertising();
            $advertising->organization_id = $organization->id;
            $advertising->template_no = array_get($data,'template_no');
        }

        $advertising->delay = array_get($data,'delay');
        $advertising->name = array_get($data,'name');
        $advertising->template_label = array_get($data,'template_label');
        $advertising->save();

//        $advertising->advertisingImages()->delete();

        foreach (array_get($data,'advertising_image',[]) as $item) {
            /* @var $advertisingImage AdvertisingImage */
            $advertisingImage = new AdvertisingImage();
            $advertisingImage->animation = array_get($item,'animation');
            $advertisingImage->duration = array_get($item,'duration');
            $advertisingImage->sequence = array_get($item,'sequence');

            if (!empty(array_get($item,'image'))) {
                $uploadedAdvertisingImage = array_get($item,'image');
                $advertisingImageName = $uploadedAdvertisingImage->getClientOriginalName();
                $advertisingImage->name = $advertisingImageName;
                $advertisingImageName = md5($advertisingImageName) . '.' . $uploadedAdvertisingImage->getClientOriginalExtension();
                $advertisingImagePath = '/' . $organization->id . '-advertising-images/' . $advertisingImageName;
                Storage::put($advertisingImagePath, File::get($uploadedAdvertisingImage->getRealPath()));
                $advertisingImageUrl = Storage::disk('local')->url($advertisingImagePath);
                $advertisingImage->url = $advertisingImageUrl;
            }

            try {
                if (!empty(array_get($item,'sound'))) {
                    $advertisingSound = array_get($item,'sound');
                    $advertisingSoundName = $advertisingSound->getClientOriginalName();
                    $advertisingImage->sound_name = $advertisingSoundName;
                    $advertisingSoundName = md5($advertisingSoundName) . '.' . $advertisingSound->getClientOriginalExtension();
                    $advertisingSoundPath = '/' . $organization->id . '-advertising-sounds/' . $advertisingSoundName;
                    Storage::put($advertisingSoundPath, File::get($advertisingSound->getPathname()));
                    $advertisingSoundUrl = Storage::disk('local')->url($advertisingSoundPath);
                    $advertisingImage->sound = $advertisingSoundUrl;
                }
            } catch (\Exception $e) {
                \Log::info('Unable to upload sound for '. $organization->id. ' at '. time());
            }

            $advertisingImage->advertising_id = $advertising->id;
            $advertisingImage->save();
        }
        return Advertising::where('id',$advertising->id)->with('advertisingImages')->first();
    }
}