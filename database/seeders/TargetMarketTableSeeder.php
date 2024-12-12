<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CoreData\Entities\TargetMarket;

class TargetMarketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = [
            array('id' => '1', 'name_ar' => 'الإمارات العربية المتحدة', 'name_fr' => 'Emirats Arabes Unis', 'name_en' => 'United Arab Emirates', 'code' => 'ae'),
            array('id' => '2', 'name_ar' => 'مصر', 'name_fr' => 'Egypte', 'name_en' => 'Egypt', 'code' => 'eg'),
            array('id' => '3', 'name_ar' => 'المملكة العربية السعودية', 'name_fr' => 'Arabie Saoudite', 'name_en' => 'Saudi Arabia', 'code' => 'sa'),
        ];

        foreach ($country as $value) {
            $data = TargetMarket::create(['order' => $value['id'],'code' => $value['code']]);
            foreach (language() as $lang) {
                if (isset($value['name_'.$lang->code])) {
                    $data->translation()->create(['key' => 'name', 'value' => $value['name_'.$lang->code], 'language_id' => $lang->id]);
                }
            }
        }
    }
}
