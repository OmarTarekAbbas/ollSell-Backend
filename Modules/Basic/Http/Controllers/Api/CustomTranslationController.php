<?php

namespace Modules\Basic\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Basic\Service\CustomTranslationService;

class CustomTranslationController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes an object with a CustomTranslationService
     * dependency.
     * 
     * param CustomTranslationService Service The parameter "Service" is an instance of the
     * "CustomTranslationService" class that is being injected into the constructor of another class.
     * This is a common practice in object-oriented programming and is known as dependency injection.
     * By injecting the service as a dependency, the class can use its methods and properties without
     */
    public function __construct(CustomTranslationService $Service)
    {
        $this->service = $Service;
    }

    /**
     * This function returns an API response with a list of items based on the given request,
     * pagination, and number of items per page.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as headers, parameters, and data. It is used to retrieve data from the
     * client-side and pass it to the server-side for processing.
     * 
     * return an API response with the result of calling the `list` method of the service class,
     * passing in the `` object, `->pagination()` and `->perPage()` as arguments. The
     * `apiResponse` method is used to format the response with a custom translation message of "Done".
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request,$this->pagination(),$this->perPage()),getCustomTranslation('Done'));
    }
}
