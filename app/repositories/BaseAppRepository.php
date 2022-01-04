<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 6/24/2019
 * Time: 2:29 AM
 */
namespace App\repositories;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseAppRepository
 * @package App\repositories
 */
class BaseAppRepository
{
    //if any thing shouldn't be updated from the request, add that to notFillable
    const notFillable = ['id','created_at','updated_at'];

    /**
     * @param Model $model
     * @param $id
     * @return mixed
     */
    public function find(Model $model, $id)
    {
        return $model->where('id',$id)->first();
    }

    /**
     * @param $model
     * @param $data
     * @return Model
     */
    public function fill($model, $data ) {
        $attributes = \Schema::getColumnListing($model->getTable());
        foreach ($attributes as $attribute) {
            if(in_array($attribute, Self::notFillable)){ continue; }
            $model->$attribute = array_get($data,$attribute, $model->$attribute);
        }
        return $model;
    }

    //bidal ti-phoid test.
}