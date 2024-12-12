<?php

namespace Modules\MasterCatalog\Http\Controllers\Api;

use ZipArchive;
use Illuminate\Http\Request;
use Modules\MasterCatalog\Service\BundleService;
use Modules\Basic\Http\Controllers\BasicController;

/**
 * @group Product management
 *
 * APIs for managing products
 */
class BundleController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that requires authentication for a dropshipper and initializes a
     * ProductService object.
     *
     * param ProductService Service The `` parameter is an instance of the `ProductService`
     * class, which is likely a service class responsible for handling business logic related to
     * products in the application. The constructor is injecting this service into the controller,
     * allowing the controller to use its methods and functionality.
     */
    public function __construct(BundleService $Service)
    {
        $this->middleware('auth:dropshipper');
        $this->service = $Service;
    }

    /**
     * List Products
     *
     * The List Products endpoint allows users to retrieve a list of products available within the system.
     * This endpoint provides users with information about the products offered by the platform,
     * filtered by the user's target market.
     *
     * This endpoint retrieves the list of products available within the system,
     * filtered by the user's target market. The API will respond with the product
     * information, including the product name, description, price, and any other relevant details.
     *
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list());
    }

    public function show($id)
    {
        $bundle = $this->service->show($id);
        if($bundle)
        {
            $can = true;
            $ids = $bundle->bundle_dropshippers->pluck('dropshipper_id');
            if($ids->count())
            {
                if(!in_array(user()->id, $ids->toArray()))
                {
                    $can = false;
                }
            }
            if($bundle->status && $can)
            {
                return $this->apiResponse($bundle);
            }
        }
        return $this->apiResponse([], 'Not Approved', 404);
    }
}
