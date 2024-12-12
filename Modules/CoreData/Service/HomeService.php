<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Service\ProductService;

class HomeService extends BasicService
{
    /**
     * This function returns a list of products for the home page.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as the request method, headers, and parameters.
     * 
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be divided into pages based on the  parameter.
     * 
     * param perPage The number of items to be displayed per page in the pagination.
     * 
     * param recursiveRel The  parameter is an optional array that specifies the
     * relationships to be recursively loaded when retrieving the products.
     * 
     * return the result of calling the `listHome` method of the `ProductService` class with the
     * `` parameter passed to it.
     */
    public function listHome(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        //todo change
        return app()->make(ProductService::class)->listHome($request);
    }
}
