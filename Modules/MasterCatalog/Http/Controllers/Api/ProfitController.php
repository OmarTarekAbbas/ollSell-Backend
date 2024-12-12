<?php

namespace Modules\MasterCatalog\Http\Controllers\Api;

use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Http\Requests\Profit\CreateRequest;
use Modules\MasterCatalog\Http\Requests\Profit\RemoveRequest;
use Modules\MasterCatalog\Service\ProductService;
use Modules\MasterCatalog\Service\ProfitService;

/**
 * @group Profit management
 *
 * APIs for managing dropshipper profit margin.
 */
class ProfitController extends BasicController
{
    private $service;
    private $productService;

    /**
     * This is a constructor function that sets the middleware for authentication and initializes a
     * ProfitService object.
     *
     * param ProfitService Service The parameter `` is an instance of the `ProfitService`
     * class that is being injected into the constructor of a class. This is a common practice in
     * object-oriented programming and is known as dependency injection. By injecting the
     * `ProfitService` instance into the constructor, the class can use the methods
     */
    public function __construct(ProfitService $Service, ProductService $productService)
    {//todo change
        $this->middleware('auth:dropshipper');
        $this->service = $Service;
        $this->productService = $productService;
    }

    /**
     * Update Profit Margin for Product
     *
     * The Update Profit Margin for Product endpoint allows users to modify the profit margin for a specific product.
     * This endpoint provides users with the ability to adjust the profit percentage for a particular product.
     *
     * This endpoint receives the necessary parameters to update the profit margin for a specific product.
     * The user needs to provide the product ID and the new profit margin as per the request parameters.
     * The profit margin value should be a positive number.
     *
     */
    public function update(CreateRequest $request)
    {
        if ($this->productService->showProduct($request->product_id)) {
            $profit = $this->service->store($request);
            if ($profit) {
                return $this->createResponse($profit, "The new profit margin for this product only is " . $request->profit);
            }
            return $this->unKnowError();
        }
        return $this->notFoundResponse('notFound');
    }

    public function remove(RemoveRequest $request)
    {
        if ($product = $this->productService->showProduct($request->product_id)) {
            $profit = $product->queryProfitProduct();

            if($profit) {
                $profit->delete();
            }

            $product->update([
                'isManual' => false
            ]);

            return $this->createResponse([], "Removed any custom profit for the product");
        }
        return $this->notFoundResponse('notFound');
    }
}
