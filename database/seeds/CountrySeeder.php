         <?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('/database/seeds/') . 'Burstsmscodes.csv';
        $file = fopen($path, "r");
        $all_data = array();
        $count = 0;
        while (($data = fgetcsv($file, 504, ",")) !== FALSE) {
            if ($count == 0) {
                $count++;
                continue;
            }

            $name           = array_get($data, 0);
            $countryCode    = array_get($data, 1);
            $shortname      = array_get($data, 2);

            $countryCode = ltrim($countryCode,'+');
            /**
             * @var $country \App\Country
             */

            $country = \App\Country::where('name', $name)->first();

            if(empty($country)){
                $country = new \App\Country();
            }
            $country->name = $name;
            $country->country_short_name = $shortname;
            $country->country_code = $countryCode;
            $country->save();
        }
        fclose($file);
    }
}
