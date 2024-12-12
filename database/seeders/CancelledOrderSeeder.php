<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;

class CancelledOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/Cancel.csv"), "r");
        while(($data = fgetcsv($csvFile, 2000, ",")) !== false)
        {
            executionTime();
            $order = Order::find($data[0]);
            if($order && $order->status_id == OrderEnum::PENDING_STATUS)
            {
                $order->update(['status_id' =>OrderEnum::CANCELED_STATUS,'cancelled_at'=>now(),'sub_status_id'=>2]);
            }
        }
        fclose($csvFile);
    }
}
