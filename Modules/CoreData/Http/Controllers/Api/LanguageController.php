<?php

namespace Modules\CoreData\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Service\LanguageService;

/**
 * @group Language management
 *
 * APIs for managing languages
 */
class LanguageController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a LanguageService object.
     * 
     * param LanguageService Service The parameter "Service" is an instance of the "LanguageService"
     * class that is being injected into the constructor of the current class. This is a common
     * practice in object-oriented programming and is known as dependency injection. By injecting the
     * "LanguageService" class into the constructor, the current class can use
     */
    public function __construct(LanguageService $Service)
    {
        $this->service = $Service;
    }

    /**
     * List Languages
     * 
     * The List Languages endpoint allows users to retrieve a list of languages available within the system. 
     * This endpoint provides users with information about the languages supported by the platform.
     * 
     * This endpoint retrieves the list of languages available within the system. 
     * The API will respond with the language information, 
     * including the language name, code, and any other relevant details.`
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }
}
