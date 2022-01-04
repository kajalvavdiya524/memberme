<?php

use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $path = base_path('/database/seeds/').'timezonelist.csv';
        $file = fopen($path, "r");
        $all_data = array(); $count = 0;
        while ( ($data = fgetcsv($file, 502, ",")) !==FALSE) {
            if($count == 0 ){
                $count++;
                continue;
            }
            $other = array_get($data,0);
            $territory = array_get($data,1);
            $timezone = array_get($data,2);

            $newTimezone = new \App\Timezone();
            $newTimezone -> other = $other;
            $newTimezone -> territory = $territory;
            $newTimezone -> timezone = $timezone;
            $newTimezone ->save();
        }
        fclose($file);

    }
}
