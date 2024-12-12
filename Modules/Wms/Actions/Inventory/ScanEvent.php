<?php

namespace Modules\Wms\Actions\Inventory;

use App\Services\AymakanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ActionsWMS\BaseAction;
use Modules\MasterCatalog\Actions\Bundle\EditQuantityAction;
use Modules\MasterCatalog\Actions\Product\CheckQuantityAction;
use Modules\MasterCatalog\Actions\ProductLog\LogQuantityAction;
use Modules\MasterCatalog\Entities\Product;

/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "product.created"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/ProductsWebhookResponse
 */
class ScanEvent extends BaseAction
{
    public function execute($id)
    {
        $product = Product::find($id);
        $aymakan = new  AymakanService();
        $response = $aymakan->fetchSKU($product->sku);
        if(isset($response))
        {
            $statusCode = $response->getStatusCode();
            // Get the response body 
            $body = $response->getBody()->getContents();
            $responseData = json_decode($body, true);
            if($statusCode == 200)
            {
                $available_quantity = $responseData['data']['available_quantity'] ?? 0;
                if($available_quantity != $product->quantity)
                {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update([
                            'quantity' => $available_quantity
                        ]);
                    $product = Product::find($id);
                    if($product->bundle->count())
                    {
                        App(EditQuantityAction::class)->execute($product);
                    }
                    if($product->quantity != 0 && $available_quantity > 0)
                    {
                        App(CheckQuantityAction::class)->SyncStockPendingOrder($product->id);
                    }
                    DB::table('product_variants')
                        ->where('product_id', $product->id)
                        ->update([
                            'quantity' => $available_quantity
                        ]);

                    App(LogQuantityAction::class)->execute(new Request(['product_id' => $product->id, 'quantity' => $available_quantity, 'type' => 'user', 'user_id' => user()->id ?? 0]));
                }
            }
        }
    }
}
