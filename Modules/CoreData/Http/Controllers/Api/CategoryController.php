<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\CategoryService;
use Modules\MasterCatalog\Service\ProductService;

/**
 * @group Category management
 *
 * APIs for managing categories
 */
class CategoryController extends BasicController
{
    private $service, $productService;

    /**
     * This is a constructor function that initializes a CategoryService object.
     *
     * param CategoryService Service The parameter "Service" is an instance of the CategoryService
     * class, which is being injected into the constructor of the current class. This is a common
     * practice in dependency injection, where the dependencies of a class are passed in as constructor
     * parameters rather than being instantiated within the class itself. This allows for better
     */
    public function __construct(CategoryService $Service, ProductService $productService)
    {
        $this->service = $Service;
        $this->productService = $productService;
    }

    /**
     * List Categories
     *
     * The List Categories endpoint allows users to retrieve a list of categories available within the system.
     * This endpoint provides users with an overview of the available categories for products or services.
     *
     * This endpoint retrieves the list of categories available within the system.
     * The API will respond with the category information, including the category name, description, and any other relevant details.
     *
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    /**
     * Get Product
     *
     * The Get Product endpoint allows users to retrieve detailed information about a specific product
     * within the system. This endpoint provides users with comprehensive details about a particular
     * product based on its unique identifier.
     *
     * This endpoint retrieves the detailed information about a specific product based on its ID.
     * The API will respond with the product details, including the product name, description, price,
     * and any other relevant information.
     *
     */
    public function show($id)
    {
        $request = request();
        $request->merge(['category' => (int)$id, 'isApproved' => 1, 'orderBy' => ['column' => 'quantity', 'order' => 'desc']]);
        return $this->apiResponse($this->productService->list($request, $this->pagination(), $this->perPage()));
    }
    //todo change
    public function single($id)
    {
        $category = $this->service->show($id);
        return $this->apiResponse($category);
    }
}
