<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CoreData\Entities\DropshipperSegmentation;

class DropshipperSegmentationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * * DS Size	From	To	   Avg	stage 
    *TINY	1	    100 	51	  مبتدئ
    *SMALL	101	    300	    201 	هاوٍ
    *MEDIUM	301 	500	    401	   متقدم 
    *BIG	501	    1500	1001	فائق
    *XBIG	1501	3000	2251	محترف
    *VIP    3000+		3000	أسطوري
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => ['ar' => "مبتدئ", 'en' => "TINY"],
                'from'=>1,
                'to'=>100
            ],
            [
                'name' => ['ar' => "هاوٍ", 'en' => "SMALL"],
                'from'=>101,
                'to'=>300
            ],
            [
                'name' => ['ar' => "متقدم", 'en' => "MEDIUM"],
                'from'=>301,
                'to'=>500
            ],
            [
                'name' => ['ar' => "فائق", 'en' => "BIG"],
                'from'=>501,
                'to'=>1500
            ],
            [
                'name' => ['ar' => "محترف", 'en' => "XBIG"],
                'from'=>1501,
                'to'=>3000
            ]
            ,
            [
                'name' => ['ar' => "أسطوري", 'en' => "VIP"],
                'from'=>3001,
                'to'=>99999999
            ]
        ];
        foreach ($data as $value) {
            $data = DropshipperSegmentation::create(['from' => $value['from'],'to' => $value['to']]);
            foreach (language() as $lang) {
                if (isset($value['name'][$lang->code])) {
                    $data->translation()->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
                }
            }
        }
    }
}
