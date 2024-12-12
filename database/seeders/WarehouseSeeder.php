<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = DB::table('suppliers')->select('id')->get();

        $products = [];
        for ($i = 1; $i < 10; $i++) {
            $products[] = [
                'name' => 'Warehouse - ' . $i,
                'country_id' => $i,
                'city_id' => $i,
                'address' => 'address - ' . $i,
                'lat' => '30.007414',
                'long' => '31.491318',
                'supplier_id' => $suppliers->random()->id,
            ];
        }

        DB::table('warehouses')->insert($products);
    }
}
