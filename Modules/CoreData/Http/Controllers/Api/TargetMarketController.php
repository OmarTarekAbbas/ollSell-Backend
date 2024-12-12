<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\TargetMarketService;

/**
 * @group Target market management
 *
 * APIs for managing target markets
 */
class TargetMarketController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a TargetMarketService object.
     * 
     * param TargetMarketService Service The parameter `` is an instance of the
     * `TargetMarketService` class that is being injected into the constructor of another class. This
     * is an example of dependency injection, where the `TargetMarketService` class is a dependency of
     * the class that is being constructed. By injecting the dependency through the
     */
    public function __construct(TargetMarketService $Service)
    {
        $this->service = $Service;
    }

    /**
     * List Target Markets
     * 
     * The List Target Markets endpoint allows users to retrieve a list of target markets available within the system. 
     * This endpoint provides users with information about the different target markets supported by the platform.
     * 
     * This endpoint retrieves the list of target markets available within the system. 
     * The API will respond with the target market information, including the market name, 
     * description, and any other relevant details.
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }
}
