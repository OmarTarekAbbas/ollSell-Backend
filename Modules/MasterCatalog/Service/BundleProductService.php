<?php

namespace Modules\MasterCatalog\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Repositories\BundleProductRepository;

class BundleProductService extends BasicService
{
    protected $repo;

    public function __construct(BundleProductRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Store bundle products based on the request data.
     * 
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // Check if bundle_products are present in the request
        if ($request->has('bundle_products')) {
            // Loop through each product and save it
            foreach ($request->bundle_products['product_id'] as $key => $productId) {
                $data = [
                    'product_id' => $productId,
                    'bundle_id' => $request->bundle_id, // Assuming bundle_id is passed in the request
                    //'count' => $request->bundle_products['count'][$key],
                ];
                $this->repo->save(new Request($data)); // Create a new request for each product
            }
        }
        return true; // Return true if products are stored successfully
    }
}
