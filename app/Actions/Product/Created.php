<?php

namespace App\Actions\Product;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Actions\BaseAction;
use Modules\Store\Entities\DropshipperEcommerce;
//todo move to integration module salla
/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "product.created"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/ProductsWebhookResponse
 */
class Created extends BaseAction
{
    public function handle()
    {
        $dropshipper_id= DropshipperEcommerce::where('store_id',$this->request->merchant)
        ->where('store_type','salla')->first()->dropshipper_id;
        if($dropshipper_id){
            $dropshipper=Dropshipper::find($dropshipper_id);
            Auth::guard('dropshipper')->setUser($dropshipper);
        }
    }
}
