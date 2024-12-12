<?php

namespace Modules\MasterCatalog\Actions\Product;

use Illuminate\Http\Request;
use App\Services\AymakanService;
use Illuminate\Support\Facades\DB;
use Modules\MasterCatalog\Entities\Product;

class ButtonScanWMSOrderAction
{
    private $aymakanService;

    /**
     * Create a new instance of the class with dependencies injected.
     *
     * @param AymakanService $aymakanService
     */
    public function __construct(AymakanService $aymakanService)
    {
        $this->aymakanService = $aymakanService;
    }

    /**
     * Executes the process to scan and sync products from WMS into the system.
     *
     * @param Request $request
     * @return bool
     */
    public function execute(Request $request): bool
    {
        $all = json_decode($this->aymakanService->fetchAllSKU(1)->getBody()->getContents(), true)['meta']['last_page'] ?? 10;
        for ($page = 1; $page <= $all; $page++) {
            $responseData = $this->fetchSkuData($page);

            if ($responseData['data']) {
                foreach ($responseData['data'] as $row) {
                    $this->syncProduct($row);
                }
            }
        }

        return true;
    }

    /**
     * Fetch SKU data from the Aymakan service.
     *
     * @param int $page
     * @return array|null
     */
    private function fetchSkuData(int $page): ?array
    {
        $response = $this->aymakanService->fetchAllSKU($page);

        if ($response && $response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return null;
    }

    /**
     * Sync individual product with SKU from WMS to the local database.
     *
     * @param array $skuData
     * @return void
     */
    private function syncProduct(array $skuData): void
    {
        $product = Product::withTrashed()->where('sku', $skuData['sku_code'])->first();

        if (!$product) {
            $this->createNewProduct($skuData);
        } else {
            if ($product->trashed()) {
                Product::withTrashed()->where('sku', $skuData['sku_code'])->restore();
                Product::where('sku', $skuData['sku_code'])->update(['is_wms' => 1, 'isApproved' => 0, 'status' => 0]);
            } else {
                Product::where('sku', $skuData['sku_code'])->update(['is_wms' => 1]);
            }
        }
    }

    /**
     * Create a new product in the database with fetched SKU details.
     *
     * @param array $skuData
     * @return void
     */
    private function createNewProduct(array $skuData): void
    {
        $responseSKU = $this->aymakanService->fetchSKU($skuData['sku_code']);

        if ($responseSKU && $responseSKU->getStatusCode() === 200) {
            $responseDataSKU = json_decode($responseSKU->getBody()->getContents(), true);

            DB::table('products')->insert([
                'sku' => $skuData['sku_code'],
                'quantity' => $skuData['available_quantity'] ?? 0,
                'is_wms' => 1,
                'status' => 0,
                'isApproved' => 0,
                'created_at' => $responseDataSKU['data']['created_at'] ?? now(),
                'cost_price' => $responseDataSKU['data']['cost'] ?? 0,
            ]);

            $newProduct = Product::where('sku', $skuData['sku_code'])->first();

            foreach (language() as $lang) {
                $newProduct->translation()->create([
                    'key' => 'name',
                    'value' => $skuData['sku_name'] ?? $responseDataSKU['data']['name'],
                    'language_id' => $lang->id
                ]);
            }
        }
    }
}
