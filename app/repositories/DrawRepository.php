<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 1/11/2019
 * Time: 4:59 PM
 */

namespace App\repositories;


use App\Draw;
use App\Organization;
use Carbon\Carbon;

class DrawRepository
{
    /**
     * DrawRepository constructor.
     */
    public function __construct()
    {

    }


    /**
     * Add draws with all param provided, and with prizes.
     *
     * @param Organization $organization
     * @param array $data
     * @return Draw
     * @throws \Exception
     */
    public function addDraw(Organization $organization, array $data)
    {
        if(!empty(array_get($data,'id'))){
            $draw = $organization->draws()->whereId(array_get($data,'id'))->first();
        }

        if(empty($draw)){
            $draw = new Draw();
            $draw->organization_id = $organization->id;
        }


        $draw->fill($data);

        $durationStart = $this->formatDurationDate(array_get($data,'duration_start'));
        $durationFinish = $this->formatDurationDate(array_get($data,'duration_finish'));

        $draw->duration_start = new Carbon($durationStart);
        $draw->duration_finish = new Carbon($durationFinish);
        $draw->draw_days = array_get($data,'draw_days');
        $draw->save();

        /* Adding Prizes. */
        $prizes = array_get($data,'prizes',[]);
        foreach ($prizes as $prize) {
            $this->addPrize($draw,$prize);
        }

        $draw = Draw::whereId($draw->id)->with([
            'prizes'
        ])->first();

        return $draw;
    }

    public function formatDurationDate($durationDate)
    {
        $durationDate = preg_replace('/:/', '-', $durationDate,2);
        if(!empty($durationDate)){
            $durationDate = $durationDate. ':00';
        }
        return $durationDate;
    }

    /**
     * Add prize to the draw function. add one price at a time.
     *
     * @param Draw $draw
     * @param array $data
     */
    public function addPrize(Draw $draw, array $data)
    {
        $prize = $draw->addPrize($data['name']);
        return $prize;
    }
}