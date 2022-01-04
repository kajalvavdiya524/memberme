<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrganizationDetail
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $bio
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property string|null $office_phone
 * @property int $industry
 * @property string $account_no
 * @property string|null $logo
 * @property string|null $cover
 * @property string|null $physical_address_id
 * @property string|null $postal_address_id
 * @property string|null $gst_number
 * @property string|null $starting_member
 * @property string|null $starting_receipt
 * @property string|null $next_member
 * @property string|null $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Address|null $physicalAddress
 * @property-read \App\Address|null $postalAddress
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereGstNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereNextMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereOfficePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail wherePhysicalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail wherePostalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereStartingMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereStartingReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereUpdatedAt($value)
 * @property float|null $tax_rate
 * @property int|null $pos_vendor
 * @property float|null $tax_factor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Member[] $members
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail wherePosVendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereTaxFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereTaxRate($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail query()
 * @property-read int|null $members_count
 */

class OrganizationDetail extends Model
{
    protected $fillable = ['organization_id'];
    public function physicalAddress()
    {
        return $this->belongsTo('App\Address','physical_address_id','id');
    }
    public function postalAddress()
    {
        return $this->belongsTo('App\Address','postal_address_id','id');
    }

    public function members()
    {
        return $this->hasMany(Member::class,'organization_id','organization_id');
    }

    public function setTaxFactorAttribute($value)
    {
        if(!empty($this->tax_rate)){
            $this->attributes['tax_factor'] = $this->tax_rate / (100 + $this->tax_rate);
//            $this->attributes['tax_factor'] = 200.2223333;
        }else{
            $this->attributes['tax_factor'] = null;
        }
    }

    public function setTaxRateAttribute($value)
    {
        $this->attributes['tax_rate'] = $value;
        $this->setTaxFactorAttribute($value);
    }

}
