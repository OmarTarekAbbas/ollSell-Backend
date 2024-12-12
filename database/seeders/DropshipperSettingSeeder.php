<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Acl\Entities\DropshipperSetting;

class DropshipperSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dropSettings = [
            'accept_payment_online_bulk',
            'convert_payment_online_to_wallet',
            'automatic_pay_from_profit_at_wallet',
        ];
        foreach ($dropSettings as $dropSetting) {
            $check = DropshipperSetting::where('name', $dropSetting)->first();
            if ($check) {
                continue;
            }
            DropshipperSetting::create(['name' => $dropSetting]);
        }
    }
}
