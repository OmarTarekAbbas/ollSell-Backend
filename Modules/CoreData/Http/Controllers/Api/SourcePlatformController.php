<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\CodeCountry\ListCodePlatform;

/**
 * @group Country management
 *
 * APIs for managing countries
 */
class SourcePlatformController extends BasicController
{


    /**
     * This is a constructor function that injects an instance of the CountryService class into the
     * current class.
     * 
     * param CountryService Service The parameter "Service" is an instance of the "CountryService"
     * class that is being injected into the constructor of another class. This is a common practice in
     * object-oriented programming and is known as dependency injection. By injecting the
     * "CountryService" class into the constructor, the class can access the methods
     */


    /**
     * List Countries
     * 
     * The List Countries endpoint allows users to retrieve a list of countries available within the system. 
     * This endpoint provides users with information about the countries supported by the platform.
     * 
     * This endpoint retrieves the list of countries available within the system. The API will respond with the country information, 
     * including the country name, ISO code, and any other relevant details.
     * 
     */
    public function list(Request $request)
    {
        return $this->apiResponse(ListCodePlatform::list());
    }
}
