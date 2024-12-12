<?php

namespace Modules\StoreIntegrations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Acl\Entities\Dropshipper;
use Modules\StoreIntegrations\Http\Resources\DropshipperEcommerceResource;
use Modules\StoreIntegrations\Jobs\SyncProductsJob;

class StoreIntegrationsController extends Controller
{
    
    /**
     * It takes a request and returns the response from the service
     *
     * param Request request The request object
     *
     * return The response from the service
     */
    public function sallaSyncProducts(Request $request)
    {
        
        $dropshipper = Dropshipper::findOrFail(user()->id);
        $products = $dropshipper->product()->get();

        SyncProductsJob::dispatch($products->toArray(), $dropshipper);

        return response()->json(['message' => 'Product sync initiated with Salla.'], 200);
    }

    /**
     * It takes a request and returns the response from the service
     *
     * param Request request The request object
     *
     * return The response from the service
     */
    public function getConnectedStores(Request $request)
    {
        $dropshipper = Dropshipper::findOrFail(user()->id);
        $stores = $dropshipper->dropshipperEcommerces()->get();

        return response()->json(['data' => DropshipperEcommerceResource::collection($stores)], 200);
    }
}
