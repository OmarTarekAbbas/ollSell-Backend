<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AymakanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MasterCatalog\Actions\Bundle\EditQuantityAction;
use Modules\MasterCatalog\Actions\Product\CheckQuantityAction;
use Modules\MasterCatalog\Actions\ProductLog\LogQuantityAction;
use Modules\MasterCatalog\Entities\Product;

class SynchronizeProductQuantities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:synchronize-product-quantities';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $aymakan = new  AymakanService();
        $products = DB::table('products')->where('is_wms', 1)->get();
        foreach($products as $row)
        {
            $response = $aymakan->fetchSKU($row->sku);
            if(isset($response))
            {
                $statusCode = $response->getStatusCode();
                // Get the response body
                $body = $response->getBody()->getContents();
                $responseData = json_decode($body, true);
                if($statusCode == 200)
                {
                    $available_quantity = $responseData['data']['available_quantity'] ?? 0;
                    if($available_quantity != $row->quantity)
                    {
                        DB::table('products')
                            ->where('id', $row->id)
                            ->update([
                                'quantity' => $available_quantity
                            ]);
                        $row = DB::table('products')->where('id', $row->id)->first();
                        if($row->quantity != 0 && $available_quantity > 0)
                        {
                            App(CheckQuantityAction::class)->SyncStockPendingOrder($row->id);
                        }
                        DB::table('product_variants')
                            ->where('product_id', $row->id)
                            ->update([
                                'quantity' => $available_quantity
                            ]);
                        $product = Product::find($row->id);
                        if($product->bundle->count())
                        {
                            App(EditQuantityAction::class)->execute($product);
                        }
                        App(LogQuantityAction::class)->execute(new Request(['product_id' => $row->id, 'quantity' => $available_quantity, 'type' => 'olldrop']));
                    }
                }
            }
        }
    }
}
