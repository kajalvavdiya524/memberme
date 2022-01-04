<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrganizationKioskTemplate
 *
 * @package App
 * @property $id
 * @property $created_at
 * @property $updated_at
 * @property $organization_id
 * @property $kiosk_background_id
 * @property $label
 * @property $template_no
 * @property $date_color
 * @property $logo
 * @property $text_one
 * @property $text_one_style
 * @property $text_two
 * @property $text_two_style
 * @property-read \App\KioskBackground $background
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereDateColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereKioskBackgroundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereTemplateNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereTextOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereTextOneStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereTextTwo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereTextTwoStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationKioskTemplate query()
 */
class OrganizationKioskTemplate extends Model
{
    protected $fillable = [
        'organization_id',
        'kiosk_background_id',
        'label',
        'template_no',
        'date_color',
        'logo',
        'text_one',
        'text_one_style',
        'text_two',
        'text_two_style',
    ];
    /**
     * Return Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Retrun the background of this template.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function background()
    {
        return $this->belongsTo(KioskBackground::class,'kiosk_background_id','id');
    }

    public function setTextOneStyleAttribute($value)
    {
        $this->attributes['text_one_style'] = serialize_data($value);
    }

    public function getTextOneStyleAttribute()
    {
        if($this->attributes['text_one_style']){
            return un_serialize_data($this->attributes['text_one_style']);
        }

        return null;
    }
    public function setTextTwoStyleAttribute($value)
    {
        $this->attributes['text_two_style'] = serialize_data($value);
    }

    public function getTextTwoStyleAttribute()
    {
        if($this->attributes['text_two_style']){
            return un_serialize_data($this->attributes['text_two_style']);
        }

        return null;
    }
}
