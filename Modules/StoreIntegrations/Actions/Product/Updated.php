<?php

namespace Modules\StoreIntegrations\Actions\Product;

use Illuminate\Support\Facades\DB;
use Modules\Basic\Actions\BaseAction;
use Modules\Store\Entities\DropshipperEcommerce;

class Updated extends BaseAction
{
    public function handle()
    {
        $record = DropshipperEcommerce::where('store_id', $this->request->merchant)
            ->where('store_type', 'salla')->first();
        
        if(!$record) {
            return;
        }

        $dropshipper_id = $record->dropshipper_id ?? null;

        if (!$dropshipper_id) {
            return;
        }

        $product = DB::table('dropshipper_mapping_products')
            ->where('dropshipper_id', $dropshipper_id)
            ->where('model_type', 'salla')
            ->where('model_id', $this->request->data['id'])
            ->first();

        if(!$product) {
            return;
        }

        DB::table('dropshipper_mapping_products')
            ->where('dropshipper_id', $dropshipper_id)
            ->where('model_type', 'salla')
            ->where('model_id', $this->request->data['id'])
            ->update(['selling_price' => $this->request->data['price']['amount']]);

        return;
    }
}
