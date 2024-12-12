<?php

namespace Modules\Wms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wms\Services\WMSService;
use Illuminate\Contracts\Support\Renderable;
//todo change
class WmsController extends Controller
{
    private $wmsService;

    public function __construct(WMSService $wmsService)
    {
        $this->wmsService = $wmsService;
    }

    public function fetchProducts(Request $request)
    {
        $perPage = $request->input('per_page', 3);
        $page = $request->input('page', 1);

        $products = $this->wmsService->fetchProducts($perPage, $page);
        return response()->json($products);
    }

    public function fetchMasterCatalog()
    {
        $masterCatalog = $this->wmsService->fetchMasterCatalog();
        return response()->json($masterCatalog);
    }

    public function fetchProductBySKU($skuCode)
    {
        $product = $this->wmsService->fetchProductBySKU($skuCode);
        return response()->json($product);
    }

    public function createProduct(Request $request)
    {
        $data = $request->all();
        $response = $this->wmsService->createProduct($data);
        return response()->json($response);
    }

    public function updateProduct(Request $request, $skuCode)
    {
        $data = $request->all();
        $response = $this->wmsService->updateProduct($skuCode, $data);
        return response()->json($response);
    }

    public function fetchHubInventory()
    {
        $inventory = $this->wmsService->fetchHubInventory();
        return response()->json($inventory);
    }

    public function createOrder(Request $request)
    {
        $data = $request->all();
        $response = $this->wmsService->createOrder($data);
        return response()->json($response);
    }
}
