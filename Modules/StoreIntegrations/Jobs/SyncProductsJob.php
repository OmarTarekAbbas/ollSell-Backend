<?php

namespace Modules\StoreIntegrations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Acl\Entities\Dropshipper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\MasterCatalog\Entities\Product;
use Modules\StoreIntegrations\Http\Services\SallaProductSyncService;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;
    protected $dropshipper;
    protected $sallaService;

    public function __construct(array $products, Dropshipper $dropshipper)
    {
        $this->products = $products;
        $this->dropshipper = $dropshipper;
    }

    public function handle(SallaProductSyncService $sallaService)
    {
        $accessToken = $this->dropshipper->sallaToken->access_token;

        // remove spaces from the access token
        $accessToken = str_replace(' ', '', $accessToken);

        foreach ($this->products as $product) {
            $product = Product::find($product['id']);
            $productData = $this->formatProductData($product);

            $existingProduct = DB::table('dropshipper_mapping_products')
                ->where('dropshipper_id', $this->dropshipper->id)
                ->where('model_type', 'salla')
                ->where('product_id', $product->id)
                ->first();

            try {
                $response = $existingProduct
                    ? $sallaService->createOrUpdateProduct($productData, $accessToken, $existingProduct->model_id)
                    : $sallaService->createOrUpdateProduct($productData, $accessToken);

                $responseBody = null;

                // Check if $response is an array or an object
                if (is_array($response)) {
                } elseif (is_object($response)) {
                    // If it is an object (likely a Guzzle response), get the response body
                    $responseBody = json_decode($response->getBody()->getContents(), true);
                }

                // Check if response indicates success
                $success = $response['success'] ?? ($responseBody['success'] ?? false);

                if ($success ?? false) {
                    $sallaProductData = $response['data']; // Get the product data from the response

                    $this->saveProductMapping($product, $sallaProductData, $existingProduct);

                    // Ensure $sallaProductData['skus'] exists before processing SKUs
                    if (!empty($sallaProductData['skus'])) {
                        $sallaService->mapProductVariations($sallaProductData, $product);
                    }
                } else {
                    Log::channel('salla')->error('Salla API Error', ['response' => $responseBody]);
                }
            } catch (\Exception $e) {
                if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                    // Use __toString() to get the full error message
                    $responseBody = (string) $e->getResponse()->getBody();
                    Log::channel('salla')->error('Error during request', [
                        'message' => $e->getMessage(),
                        'response' => $responseBody
                    ]);
                } else {
                    Log::channel('salla')->error('Error during request', [
                        'message' => $e->getMessage()
                    ]);
                }
                return;
            }
        }
    }

    private function getLogo($logo)
    {
        if (in_array($logo->type, [mediaType()['dm']])) {
            $path = pathType()['up'];
        } elseif (in_array($logo->type, [mediaType()['am'], mediaType()['lm']])) {
            $path = pathType()['ip'];
        } else {
            $path = pathType()['ip'];
        }
        return [
            'id' => $logo->id,
            'file' => getFile($logo->file, $path, getFileNameServer($logo)) ??  asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
        ];
    }


    protected function formatProductData($product)
    {
        $images = [];
        $options = [];

        // Format product images
        if (!empty($product->logo)) {
            foreach ($product->logo as $index => $image) {
                $imageData = $this->getLogo($image);
                $images[$index] = [
                    'original' => $imageData['file'],
                    'thumbnail' => $imageData['file'],
                    'alt' => 'Product image',
                    'default' => $index == 0 ? true : false,
                    'sort' => $index + 1,
                ];
            }
        }

        // Format product options
        if (!empty($product->options)) {
            foreach ($product->options as $optionName => $optionValues) {
                $values = [];
                foreach ($optionValues as $value) {
                    $values[] = [
                        'name' => $value,
                        'price' => 120,  // Default or dynamic price if applicable
                        'quantity' => 10 // Default or dynamic quantity if applicable
                    ];
                }
                $options[] = [
                    'name' => $optionName,
                    'values' => $values,
                    'display_type' => 'text',
                ];
            }
        }

        $sellingPrice = $this->getSellingPrice($product);

        return [
            'name' => $product->name->value ?? 'Unnamed Product',
            'price' => $sellingPrice ?? $product->cost_price,
            // 'sale_price' => $sellingPrice ?? $product->cost_price,
            'cost_price' => $product->cost_price ?? 1.0,
            'status' => $product->status == 1 ? 'sale' : 'out',
            'product_type' => $product->type ?? 'product',
            'quantity' => $product->quantity ?? 1,
            'description' => $product->description->value ?? 'No description available.',
            'sku' => $product->sku ?? 'default_sku',
            'weight' => $product->weight ?? 1.0,
            'weight_type' => 'kg',
            'require_shipping' => true,
            'images' => $images,
            'options' => $options,
        ];
    }

    protected function getSellingPrice($product)
    {
        $product = DB::table('dropshipper_mapping_products')
                ->where('dropshipper_id', $this->dropshipper->id)
                ->where('model_type', 'salla')
                ->where('product_id', $product->id)
                ->first();
        
        return $product ? $product->selling_price : null;
    }

    protected function saveProductMapping($product, $sallaProductData, $existingProduct = null)
    {
        if ($existingProduct) {
            DB::table('dropshipper_mapping_products')
                ->where('id', $existingProduct->id)
                ->update(['move' => 1]);
        } else {
            DB::table('dropshipper_mapping_products')->insert([
                'model_type'    => 'salla',
                'model_id'      => $sallaProductData['id'],
                'product_id'    => $product->id,
                'dropshipper_id' => $this->dropshipper->id,
                'move'          => 1,
            ]);
        }
    }
}
