<?php

namespace Modules\MasterCatalog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Service\EventService;
//todo change
/**
 * @group Favorite management
 *
 * APIs for managing favorite list
 */
class EventController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that requires authentication for a dropshipper and initializes a
     * FavoriteService object.
     * 
     * param EventService Service The parameter `` is an instance of the `EventService`
     * class that is being injected into the constructor of the current class. This is a form of
     * dependency injection, where the `EventService` class is being used by the current class to
     * perform certain operations. The `EventService` class
     */
    public function __construct(EventService $Service)
    {
        $this->middleware('auth:dropshipper');
        $this->service = $Service;
    }

    /**
     * It takes a request, merges the request with the user's target market, and then returns the
     * response from the service
     * 
     * param Request request The request object
     * 
     * return The list of all the users in the database.
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    /**
     * It takes the id of a product and returns the product with the target market of the user
     * 
     * param id The id of the record you want to show
     * 
     * return The show method is returning the result of the service show method.
     */
    public function show($id)
    {
        if ($this->service->listOne($id)) {
            return $this->apiResponse($this->service->listOne($id));
        }
        return $this->notFoundResponse(trans('orders.notFound'));
    }

}
