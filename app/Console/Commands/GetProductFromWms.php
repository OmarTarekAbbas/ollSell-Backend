<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AymakanService;
use Illuminate\Support\Facades\DB;
use Modules\MasterCatalog\Entities\Product;

class GetProductFromWms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-product-from-wms';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Product From Wms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $aymakan = new  AymakanService();
        for($i = 1; $i < 10; $i++)
        {
        $response = $aymakan->fetchAllSKU($i);
        if(isset($response))
        {
            $body = $response->getBody()->getContents();
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($body, true);
            if($statusCode == 200 && $responseData['data'])
            {
                foreach($responseData['data'] as $row)
                {
                    $this->info(' successfully!' . $row['sku_code']);
                    $product = DB::table('products')
                        ->where('sku', $row['sku_code'])
                        ->first();
                    if(!$product)
                    {
                        $responseSKU = $aymakan->fetchSKU($row['sku_code']);
                        if(isset($responseSKU))
                        {
                            $bodySKU = $responseSKU->getBody()->getContents();
                            $statusCodeSKU = $responseSKU->getStatusCode();
                            $responseDataSKU = json_decode($bodySKU, true);
                            if($statusCodeSKU == 200)
                            {

                                    $this->info('create!' . $row['sku_code']);
                                    $newProduct = DB::table('products')->insert([
                                        'sku' => $row['sku_code'],
                                        'quantity' => $row['available_quantity'] ?? 0,
                                        'is_wms' => 1,
                                        'status' => 0,
                                        'isApproved' => 0,
                                        'created_at' => $responseDataSKU['data']['created_at'] ?? now(),
                                        'cost_price' => $responseDataSKU['data']['cost'] ?? 0,
                                    ]);
                                    $newProducts = Product::where('sku',$row['sku_code'])->first();
                                    foreach (language() as $lang)
                                    {
                                        $newProducts->translation()
                                            ->create(['key' => 'name', 'value' => $row['sku_name'] ?? $responseDataSKU['data']['name'], 'language_id' => $lang->id]);
                                    }

                            }
                        }
                    }else{
                       Product::where('sku',$row['sku_code'])->update(['is_wms' => 1]);
                    }
                }
            }
            //   $products = DB::table('products')->where('status',1)->get();

        }
        }
    }
}