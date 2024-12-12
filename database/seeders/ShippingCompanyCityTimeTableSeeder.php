<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Logistics\Entities\ShippingCompanyCityTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class ShippingCompanyCityTimeTableSeeder extends Seeder
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
        $data = json_decode(File::get(base_path('ShippingCompanyCityTime.json')));
 
      
    //    $rows = explode("\n", $csv_text); // Split text into lines

    // $data = array();

    //         foreach ($rows as $row) {
    //             $data[] = str_getcsv($row); // Parse each line into an array
    //         }
    // $shipping_companys = ShippingCompanyCityTime::get(); 
    // foreach($shipping_companys as $row){
    //    $trans= DB::table('translations')
    //         ->where('category_type','like','%City%')
    //         ->Where('value', 'like','%'.$row->city_value.'%')->first();
    //         if($trans){
    //        $row->city_id=$trans->category_id;
    //         $row->save();
    //         }
        
    // }


        foreach ($data as  $value) {
       

            if(@$value->AR){
            //    ShippingCompanyCityTime::create(['number_hours' =>$value[1] ,'shipping_company_id'=>1,'city_id'=>1,'city_value'=>trim($value[0]),'price'=>25]); 

                      $city=DB::table('translations')
                     ->where('category_type','like','%City%')
                     ->Where('value', trim($value->AR))->first();
                if($city){
                    ShippingCompanyCityTime::create(['number_hours' =>$value->RUH,'shipping_company_id'=>1,'city_id'=>$city->category_id,'price'=>25]); 

                  }

            }

   
        }
    }
}
