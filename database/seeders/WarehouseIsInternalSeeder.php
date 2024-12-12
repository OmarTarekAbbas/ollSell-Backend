<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseIsInternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = DB::table('suppliers')->select('id')->get();
        $data = [
            'name' => 'Fulfill by OllDrop',
            'country_id' => 178,
            'city_id' => 3083,
            'address' => 'address',
            'lat' => '30.007414',
            'long' => '31.491318',
            // 'supplier_id' => $suppliers->random()->id,
            'is_internal' => 1,
            'address' => 'Sulay',
            'district' => 'Sulay'
        ];
        DB::table('warehouses')->insert($data);
    }
}
