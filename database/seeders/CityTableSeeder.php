<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Modules\CoreData\Entities\City;
use Modules\CoreData\Entities\CityAlias;
use Modules\CoreData\Entities\Country;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      /*  $city = [
            array(8, 'Fujairah', '04', 2),
        array(9, 'Abu Dhabi', '01', 2),
        array(10, 'Dubai', '03', 2),
        array(11, 'Ras Al Khaimah', '05', 2),
        array(12, 'Umm Al Quwain', '07', 2),
        array(13, 'Sharjah', '06', 2),
        array(14, 'Ajman', '02', 2),
        array(926, 'Ash Sharqiyah', '14', 59),
        array(927, 'Al Gharbiyah', '05', 59),
        array(928, 'Ad Daqahliyah', '01', 59),
        array(929, 'Al Jizah', '08', 59),
        array(930, 'Al Minya', '10', 59),
        array(931, 'Kafr ash Shaykh', '21', 59),
        array(932, 'Al Buhayrah', '03', 59),
        array(933, 'Qina', '23', 59),
        array(934, 'Al Qahirah', '11', 59),
        array(935, 'Al Iskandariyah', '06', 59),
        array(936, 'Al Fayyum', '04', 59),
        array(937, 'Asyut', '17', 59),
        array(938, 'Al Minufiyah', '09', 59),
        array(939, 'Bani Suwayf', '18', 59),
        array(940, 'Al Qalyubiyah', '12', 59),
        array(941, 'Aswan', '16', 59),
        array(942, 'Shamal Sina', '27', 59),
        array(943, 'Suhaj', '24', 59),
        array(944, 'Janub Sina', '26', 59),
        array(945, 'Al Bahr al Ahmar', '02', 59),
        array(946, 'Al Ismailiyah', '07', 59),
        array(947, 'Dumyat', '20', 59),
        array(948, 'Matruh', '22', 59),
        array(949, 'As Suways', '15', 59),
        array(950, 'Al Wadi al Jadid', '13', 59),
        array(951, 'Bur Said', '19', 59),
        array(3082, 'Makkah', '14', 178),
        array(3083, 'Ar Riyad', '10', 178),
        array(3084, 'Hail', '13', 178),
        array(3085, 'Al Hudud ash Shamaliyah', '15', 178),
        array(3086, 'Jizan', '17', 178),
        array(3087, 'Ash Sharqiyah', '06', 178),
        array(3088, 'Al Madinah', '05', 178),
        array(3089, 'Al Qasim', '08', 178),
        array(3090, 'Al Bahah', '02', 178),
        array(3091, 'Tabuk', '19', 178),
        array(3092, 'Al Jawf', '20', 178),
        array(3093, '', '00', 178)
        ];

        foreach ($city as $value) {
            $country = Country::find($value[3]);
            if ($country) {
                $data = City::create(['id'=>$value[0],'country_id' => $value[3]]);
                foreach (language() as $lang) {
                    if (isset($value[1])) {
                        $data->translation()->create(['key' => 'name', 'value' => $value[1], 'language_id' => $lang->id]);
                    }
                }
            }
        }*/
        $cities = City::all();
        foreach($cities as $city)
        {
            foreach(languageAll() as $lang)
            {
               $c = CityAlias::where('alias','like',$city->nameValue($lang))->count();
                if(!$c)
                {
                    $newRequest =  new Request(['city_id'=>$city->id,'alias'=>$city->nameValue($lang)]);
                  $CityAlias = new CityAlias;
                  $CityAlias->Create($newRequest->all());

                }
            }
        }
    }
}
