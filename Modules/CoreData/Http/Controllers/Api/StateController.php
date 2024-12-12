<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\StateService;

/**
 * @group State management
 *
 * APIs for managing states
 */
class StateController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that injects an instance of the StateService class into the
     * current class.
     * 
     * param StateService Service The parameter "Service" is an instance of the "StateService" class.
     * It is being injected into the constructor of another class, which means that the class that is
     * receiving this parameter can use the methods and properties of the "StateService" class. This is
     * an example of dependency injection, which
     */
    public function __construct(StateService $Service)
    {
        $this->service = $Service;
    }

    /**
     * List States
     * 
     * The List States endpoint allows users to retrieve a list of states available within the system. 
     * This endpoint provides users with information about the different states or provinces 
     * supported by the platform.
     * 
     * This endpoint retrieves the list of states available within the system. 
     * The API will respond with the state information, including the state name, code, 
     * and any other relevant details.
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()), '');
    }
}
