<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Logistics\Entities\ShippingCompany;

class ShippingCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        ShippingCompany::create([
            'phone' =>'01021904259',
            'name'=>'aya mkane',
            'email'=>'ahmed.abdelaal2222@gmail.com',
            'address'=>'albnate street',
            'price'=>'25.00',
            'order_fulfillment_start_time'=>'08:25:00',
            'order_fulfillment_end_time'=>'14:28:00',
    ]); 

   
    }
}
