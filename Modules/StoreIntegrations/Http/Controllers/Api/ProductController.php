<?php

namespace Modules\StoreIntegrations\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Acl\Entities\Dropshipper;
use Modules\MasterCatalog\Entities\Product;
use Modules\StoreIntegrations\Jobs\SyncProductsJob;
use Modules\Store\Service\DropshipperMappingProductService;
use Modules\MasterCatalog\Http\Resources\Product\MappedProductResource;

class ProductController
{
    private $mapProductService;

    public function __construct(DropshipperMappingProductService $mapProductService)
    {
        $this->mapProductService = $mapProductService;
    }

    public function list(Request $request)
    {
        $request->merge(['dropshipper_id' => user()->id]);

        $products = $this->mapProductService->getMappedProducts($request);

        return response()->json(MappedProductResource::collection($products), 200);
    }

    public function updateSellingPrice(Request $request)
    {
        $product = $this->mapProductService->updateSellingPrice($request, $request->product_id);
        $dropshipper = Dropshipper::findOrFail(user()->id);
        $product = Product::find($product->product_id);

        SyncProductsJob::dispatch([$product], $dropshipper);

        return response()->json(new MappedProductResource($product) , 200);
    }
}
