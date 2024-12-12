<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\StatusService;

/**
 * @group Order status management
 *
 * APIs for managing order statuses
 */
class StatusController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that injects an instance of the StatusService class into the
     * current class.
     * 
     * param StatusService Service The parameter "Service" is an instance of the "StatusService"
     * class. It is being injected into the constructor of another class, which means that the class
     * that is receiving this parameter can use the methods and properties of the "StatusService"
     * class. This is an example of dependency injection, which
     */
    public function __construct(StatusService $Service)
    {
        $this->service = $Service;
    }

    /**
     * List Order Statuses
     * 
     * The List Order Statuses endpoint allows users to retrieve a list of order statuses available within the system. 
     * This endpoint provides users with information about the different statuses that an order can have.
     *
     * This endpoint retrieves the list of order statuses available within the system. 
     * The API will respond with the order status information, including the status name, 
     * description, and any other relevant details.
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }
}
