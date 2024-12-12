<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\OnboardingCategoryService;

/**
 * @group Category management
 *
 * APIs for managing categories
 */
class OnboardingCategoryController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a CategoryService object.
     *
     * param CategoryService Service The parameter "Service" is an instance of the CategoryService
     * class, which is being injected into the constructor of the current class. This is a common
     * practice in dependency injection, where the dependencies of a class are passed in as constructor
     * parameters rather than being instantiated within the class itself. This allows for better
     */
    public function __construct(OnboardingCategoryService $Service)
    {
        $this->service = $Service;
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
}
