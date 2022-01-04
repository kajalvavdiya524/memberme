<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 9/15/2018
 * Time: 11:47 AM
 */

namespace App\repositories;


use App\Exceptions\ApiException;
use App\Kiosk;
use App\KioskVoucherParameter;
use App\Organization;
use File;
use Storage;

class KioskRepository
{
    /**
     * @param $mac
     * @return mixed
     */
    public function validateMac($mac)
    {
        $isValidMac = filter_var($mac, FILTER_VALIDATE_MAC);
        return $isValidMac;
    }

    /**
     * Add mac into db if that isn't already available.
     *
     * @param $mac
     * @return Kiosk
     */
    public function findOrCreate($mac)
    {
        /* @var $kiosk Kiosk */
        $kiosk = Kiosk::with([
            'organization' => function ($query) {
                $query->select('organizations.id', 'organizations.name', 'organizations.api_token');
            }])->firstOrCreate([
            'mac' => $mac
        ]);
        if($kiosk->organization){
            $kiosk->organization->makeVisible('api_token');
        }

        return $kiosk;
    }

    /**
     * Adding and updating kiosk voucher parameter to the kiosk for auto voucher generation.
     *
     * @param Organization $organization
     * @param array $data
     * @return KioskVoucherParameter
     * @throws ApiException
     * @internal param Kiosk $kiosk
     */
    public function addOrUpdateKioskVoucherParameter(Organization $organization, $data = [])
    {
        /**
         * @var $kioskVoucherParameter KioskVoucherParameter
         */
        $kioskVoucherParameter = $organization->kioskVoucherParameter;

        if (empty($kioskVoucherParameter)) {

            if(empty($data['voucher_parameter_id'])){
                throw new ApiException(null,['error' => 'Please assign a voucher first']);
            }

            $kioskVoucherParameter = new KioskVoucherParameter();
        }

        if (!is_null(array_get($data, 'kiosk_print')))
            $kioskVoucherParameter->kiosk_print = array_get($data, 'kiosk_print', $kioskVoucherParameter->kiosk_print);
        if (!is_null(array_get($data, 'email_voucher')))
            $kioskVoucherParameter->email_voucher = array_get($data, 'email_voucher', $kioskVoucherParameter->email_voucher);
        if (!is_null(array_get($data, 'show_in_app')))
            $kioskVoucherParameter->show_in_app = array_get($data, 'show_in_app', $kioskVoucherParameter->show_in_app);
        if (!is_null(array_get($data, 'voucher_parameter_id')))
            $kioskVoucherParameter->voucher_parameter_id = array_get($data, 'voucher_parameter_id', $kioskVoucherParameter->voucher_parameter_id);


        try {
            if (!empty(array_get($data,'sound'))) {
                $kioskSound = array_get($data,'sound');
                $kioskSoundName = $kioskSound->getClientOriginalName();
                $kioskSoundName = md5($kioskSoundName) . '.' . $kioskSound->getClientOriginalExtension();
                $kioskSoundPath = '/' . $organization->id . '-kiosk-voucher-sounds/' . $kioskSoundName;
                Storage::put($kioskSoundPath, File::get($kioskSound->getPathname()));
                $kioskSoundUrl = Storage::disk('local')->url($kioskSoundPath);
                $kioskVoucherParameter->sound = $kioskSoundUrl;
            }
        } catch (\Exception $e) {
            \Log::info('Unable to upload sound for '. $organization->id. ' at '. time().PHP_EOL.$e->getMessage());
        }

        $kioskVoucherParameter->duration = array_get($data, 'duration',$kioskVoucherParameter->duration);
        $kioskVoucherParameter->days_after = array_get($data, 'days_after', $kioskVoucherParameter->days_after);
        $kioskVoucherParameter->days_before = array_get($data, 'days_before', $kioskVoucherParameter->days_before);
        $kioskVoucherParameter->frequency = array_get($data, 'frequency', $kioskVoucherParameter->frequency);
        $kioskVoucherParameter->display_message = array_get($data, 'display_message',$kioskVoucherParameter->display_message);
        $kioskVoucherParameter->voucher_message = array_get($data, 'voucher_message',$kioskVoucherParameter->voucher_message);
        $kioskVoucherParameter->lighting = array_get($data, 'lighting',$kioskVoucherParameter->lighting);
        $kioskVoucherParameter->organization_id = $organization->id;
        $kioskVoucherParameter->save();

        return $kioskVoucherParameter;
    }

    /**
     * @param Organization $organization
     * @return \App\VoucherParameter
     * @internal param Kiosk $kiosk
     */
    public function getActiveBirthdayVoucher(Organization $organization)
    {
        $kioskVoucherParameter = (!empty($organization->kioskVoucherParameter->voucherParameter)) ? $organization->kioskVoucherParameter->voucherParameter : null;
        return $kioskVoucherParameter;
    }
}