<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrganizationCardTemplate
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $url
 * @property string|null $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $label
 * @property string|null $style
 * @property int|null $show_image
 * @property string|null $element_labels
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Member[] $members
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereElementLabels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereShowImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereUrl($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate query()
 * @property string|null $coordinates
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationCardTemplate whereCoordinates($value)
 * @property-read int|null $members_count
 */
class OrganizationCardTemplate extends Model
{
    const LABEL = [
        'TEMPLATE1' => 'template1',
        'TEMPLATE2' => 'template2',
        'TEMPLATE3' => 'template3',
    ];
    /**
     * Return the organization owning this template
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Return list of members related to this template
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(Member::class,'organization_card_template_id','id');
    }

    public function setStyleAttribute($value)
    {
        $this->attributes['style'] = serialize_data($value);
    }

    public function getStyleAttribute()
    {
        if($this->attributes['style']){
            return un_serialize_data($this->attributes['style']);
        }

        return null;
    }

    public function setElementLabelsAttribute($value)
    {
        $this->attributes['element_labels'] = serialize_data($value);
    }

    public function getElementLabelsAttribute()
    {
        if($this->attributes['element_labels']){
            return un_serialize_data($this->attributes['element_labels']);
        }
        return null;
    }

    public function setCoordinatesAttribute($value)
    {
        $this->attributes['coordinates'] = serialize_data($value);
    }

    public function getCoordinatesAttribute()
    {
        if($this->attributes['coordinates']){
            return un_serialize_data($this->attributes['coordinates']);
        }
        return null;
    }
}
