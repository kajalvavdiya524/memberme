<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 12/23/2017
 * Time: 4:33 PM
 */

namespace App\Helpers;


use App\base\IStatus;
use App\KioskVoucherParameter;
use App\Organization;
use App\Voucher;
use App\VoucherParameter;
use Illuminate\Support\Collection;

class DropdownHelper
{
    public static function memberTitleList()
    {
        return [
            'Mr' => 'Mr',
            'Mrs' => 'Mrs',
            'Miss' => 'Miss',
            'Ms' => 'Ms',
            'Master' => 'Master',
            'Dr' => 'Dr',
            'Prof' => 'Prof',
            'Sir' => 'Sir',
        ];
    }

    public static function memberStatusList()
    {
        return [
            IStatus::ACTIVE => 'Active',
            IStatus::PENDING_NEW => 'Pending New',
            IStatus::SUSPENDED => 'Suspended',
            IStatus::RESIGNED => 'Resigned',
            IStatus::ON_HOLD => 'On Hold',
            IStatus::OVER_DUE => 'Overdue',
            IStatus::EXPIRED => 'Expired',
        ];
    }

    /**
     * @param $status
 * @return int
     */
    public static function getMemberStatusIdByName($status)
    {
        switch ($status){
            case 'Pending New':
                return IStatus::PENDING_NEW;
                break;
            case 'Active':
                return IStatus::ACTIVE ;
                break;
            case 'Overdue':
                return IStatus::OVER_DUE ;
                break;
            case 'Expired':
                return IStatus::EXPIRED ;
                break;
            case 'On Hold':
                return IStatus::ON_HOLD ;
                break;
            case 'Suspended':
                return IStatus::SUSPENDED ;
                break;
            default:
                return IStatus::PENDING_NEW;
        }
    }

    /**
     * @param Organization $organization
     * @return array | Collection
     */
    public static function getGroupList(Organization $organization, $shouldRemoveStatusGroups = true)
    {
        $query = $organization->groups();
        if($shouldRemoveStatusGroups){
            $query = $query->where('is_status_group','=',0);
        }
        $groupList = $query->pluck('name','id');
        return ($groupList)?:[];
    }

    public static function paymentFrequencyList()
    {
        return [
            'Weekly' => 'Weekly',
            'Fortnighty' => 'Fortnighty',
            'Monthly' => 'Monthly',
            'Bi Monthly' => 'Bi Monthly',
            'Quaterly' => 'Quaterly',
            '6 Monthly' => '6 Monthly',
            'Yearly' => 'Yearly',
        ];
    }

    public static function getVoucherParameterTypes()
    {
        $voucherParameterTypes = VoucherParameter::VOUCHER_TYPE;
        $result = [];
        foreach ($voucherParameterTypes as $key => $voucherParameterType) {
            $result[$voucherParameterType] = $key;
        }
        return $result;
    }

    public static function getVoucherParameterExpires()
    {
        $voucherParameterExpires = VoucherParameter::EXPIRES;
        $result = [];
        foreach ($voucherParameterExpires as $key => $voucherParameterExpire) {
            $result[$voucherParameterExpire] = $key;
        }
        return $result;
    }

    public static function getVoucherParameterExpiry()
    {
        $voucherParameterExpiry = VoucherParameter::EXPIRY;
        $result = [];
        foreach ($voucherParameterExpiry as $key => $voucherParameterExpiry) {
            $result[$voucherParameterExpiry] = $key;
        }
        return $result;
    }

    public static function getVoucherParameterLimitedQuantity()
    {
        $voucherParameterLimitedQuantity = VoucherParameter::LIMITED_QUANTITY;
        $result = [];
        foreach ($voucherParameterLimitedQuantity as $key => $voucherParameterLimitedQuantity) {
            $result[$voucherParameterLimitedQuantity] = $key;
        }
        return $result;
    }

    public static function getVoucherParameterValue()
    {
        $voucherParameterValue = VoucherParameter::VALUE;
        $result = [];
        foreach ($voucherParameterValue as $key => $voucherParameterValue) {
            $result[$voucherParameterValue] = $key;
        }
        return $result;
    }

    public static function getVoucherParameterValueMode()
    {
        $voucherParameterValueMode = VoucherParameter::VALUE_MODE;
        $result = [];
        foreach ($voucherParameterValueMode as $key => $voucherParameterValueMode) {
            $result[$voucherParameterValueMode] = $key;
        }
        return $result;
    }

    public static function getKioskVoucherParameterDuration()
    {
        $duration = KioskVoucherParameter::DURATION;
        $result = [];
        foreach ($duration as $key => $item) {
            $result[$item] = $key;
        }
        return $result;
    }

    public static function getKioskVoucherParameterFrequency()
    {
        $frequency = KioskVoucherParameter::FREQUENCY;
        $result = [];
        foreach ($frequency as $key => $item) {
            $result[$item] = $key;
        }
        return $result;
    }

    public static function getEthnicityList()
    {
        return [
            'Not Specified', 'New Zealand European', 'Māori', 'Australian', 'American (USA)', 'Afghani', 'African', 'Arab', 'Argentinian', 'Asian', 'Assyrian', 'Austrian', 'Bangladeshi', 'Brazilian', 'British', 'Burmese', 'Cambodian', 'Cambodian Chinese', 'Canadian', 'Caribbean', 'Chilean', 'Chinese', 'Colombian', 'Cook Islands Maori', 'Croatian', 'Czech', 'Danish', 'Dutch', 'Egyptian', 'English', 'Ethiopian', 'Eurasian', 'European', 'Fijian', 'Fijian Indian', 'Filipino', 'French', 'German', 'Greek', 'Hong Kong Chinese', 'Hungarian', 'Indian', 'Indigenous American', 'Indonesian', 'Iranian/Persian', 'Iraqi', 'Irish', 'Israeli/Jewish', 'Italian', 'Japanese', 'Kiribati', 'Korean', 'Laotian', 'Latin American', 'Lebanese', 'Malay', 'Malaysian Chinese', 'Mexican', 'Middle Eastern', 'Nepalese', 'Niuean', 'Norwegian', 'Other South African', 'Other Zimbawean', 'Pakistani', 'Papua New Guinean', 'Polish', 'Portuguese', 'Romanian', 'Russian', 'Samoan', 'Scottish', 'Serbian', 'Sinhalese', 'Somali', 'South African Indian', 'South African European', 'Southeast Asian', 'Spanish', 'Sri Lankan Tamil', 'Sri Lankan', 'Swedish', 'Swiss', 'Tahitian', 'Taiwanese', 'Thai', 'Tokelauan', 'Tongan', 'Turkish', 'Tuvaluan', 'Ukrainian', 'Vietnamese', 'Welsh', 'Zimbabwean European'
        ];
    }
}