<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\CityService;

/**
 * @group City management
 *
 * APIs for managing cities
 */
class CityController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that injects an instance of the CityService class into the
     * current class.
     * 
     * param CityService Service The parameter "Service" is an instance of the "CityService" class. It
     * is being injected into the constructor of another class, which means that the class that is
     * receiving this parameter can use the methods and properties of the "CityService" class. This is
     * a common practice in object-oriented programming
     */
    public function __construct(CityService $Service)
    {
        $this->service = $Service;
    }

    /**
     * List Cities
     * 
     * The List Cities endpoint allows users to retrieve a list of cities available within the system. 
     * This endpoint provides users with information about the cities supported by the platform.
     * 
     * This endpoint retrieves the list of cities available within the system. The API will respond with the city information, 
     * including the city name, location, and any other relevant details.
     * 
     */
    public function list(Request $request)
    {
        $recursiveRel = [];

        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage(),$recursiveRel), '');
    }

    /**
     * List Shipping Cities
     * 
     * The List Cities endpoint allows users to retrieve a list of cities available within the system. 
     * This endpoint provides users with information about the cities supported by the platform.
     * 
     * This endpoint retrieves the list of cities available within the system. The API will respond with the city information, 
     * including the city name, location, and any other relevant details.
     * 
     */
    public function listShippingCities(Request $request)
    {
        $request->merge(['country_id' => 178]);
        
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()), '');
    }
}
