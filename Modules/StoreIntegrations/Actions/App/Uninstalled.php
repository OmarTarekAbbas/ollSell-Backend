<?php

namespace Modules\StoreIntegrations\Actions\App;

use Illuminate\Support\Facades\DB;
use Modules\Basic\Actions\BaseAction;
use Modules\Store\Entities\DropshipperEcommerce;

class Uninstalled extends BaseAction
{
    public function handle()
    {
        DropshipperEcommerce::where('store_id',$this->request->merchant)
            ->where('store_type','salla')->delete();

        DB::table('salla_tokens')
            ->where('merchant_id',$this->request->merchant)->delete();
    }
}