<?php

namespace App\Actions\Product;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Actions\BaseAction;
use Modules\Store\Entities\DropshipperEcommerce;
//todo move to integration module salla
/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "product.deleted"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/ProductsWebhookResponse
 */
class Deleted extends BaseAction
{
    public function handle()
    {
        $dropshipper_id= DropshipperEcommerce::where('store_id',$this->request->merchant)
        ->where('store_type','salla')->first()->dropshipper_id;
        if($dropshipper_id){
            $dropshipper=Dropshipper::find($dropshipper_id);
            Auth::guard('dropshipper')->setUser($dropshipper);
             $product_id=$this->request->data['id'];
             DB::table('dropshipper_mapping_products')->where('model_type', 'salla')->
             where('model_id',  $product_id)->where('dropshipper_id',  $dropshipper_id)->delete();
             DB::table('dropshipper_mapping_products_options')->where('model_type', 'salla')->
             where('model_id',  $product_id)->where('dropshipper_id',  $dropshipper_id)->delete();
             DB::table('dropshipper_mapping_products_options')->where('model_type', 'salla')->
             where('model_id',  $product_id)->where('dropshipper_id',  $dropshipper_id)->delete();

        }
    }
}
