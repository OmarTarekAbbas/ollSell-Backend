<?php

namespace App\ActionsWMS\Inventory;

use Illuminate\Http\Request;
use App\ActionsWMS\BaseAction;
use Modules\MasterCatalog\Actions\Bundle\EditQuantityAction;
use Modules\MasterCatalog\Actions\Product\CheckQuantityAction;
use Modules\MasterCatalog\Actions\ProductLog\LogQuantityAction;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Entities\ProductVariant;

//todo move to integration module salla

/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "product.created"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/ProductsWebhookResponse
 */
class UpdateEvent extends BaseAction
{
    public function handle()
    {
        $product = Product::where('sku', $this->request['data'][0]['sku_code'])->first();
        if($product)
        {
            if($this->request['data'][0]['quantity'] != $product->quantity)
            {
                $product->update([
                    'quantity' => $this->request['data'][0]['quantity']
                ]);
                ProductVariant::where('sku', $this->request['data'][0]['sku_code'])
                    ->update([
                        'quantity' => $this->request['data'][0]['quantity']
                    ]);
                $product = Product::find($product->id);
                if($product->bundle->count())
                {
                    App(EditQuantityAction::class)->execute($product);
                }
                if($product->capasteOrder() && $product->quantity > 0)
                {
                    App(CheckQuantityAction::class)->SyncStockPendingOrder($product->id);
                }
                App(LogQuantityAction::class)->execute(new Request(['product_id' => $product->id, 'quantity' => $product->quantity, 'type' => 'wms']));
            }
            return true;
        }
        return false;
    }
}
