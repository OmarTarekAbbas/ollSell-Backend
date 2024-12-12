<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Repositories\CountryRepository;
use Modules\CoreData\Actions\Country\IndexCountryAction;
use Modules\CoreData\Http\Resources\Country\CountryListResource;

class CountryService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(CountryRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * The index function executes the IndexCountryAction class with the given request.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server.
     * 
     * return The `index` function is returning the result of executing the `execute` method of the
     * `IndexCountryAction` class with the `` parameter.
     */
    public function index(Request $request)
    {
        return (new IndexCountryAction(
            request: $request
         ))->execute();
    
    }

    /**
     * This PHP function returns a collection of country list resources with optional pagination and
     * recursive relationship filtering.
     * 
     * param Request request The HTTP request object that contains information about the current
     * request being made.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * param perPage The number of items to be displayed per page in the paginated result. In this
     * case, it is set to 10.
     * 
     * return a collection of resources of the CountryListResource class. The resources are obtained
     * by calling the "list" method of the repository object with the provided parameters, which
     * includes a recursive relationship with the "city" model and a "whereHas" condition. The
     *  and  parameters are used to control the pagination of the results.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return CountryListResource::collection($this->repo->list($request, $pagination, $perPage, recursiveRel: ['city' => ['type' => 'whereHas']]));
    }

    /**
     * The function returns a collection of country list resources based on the given request, with
     * optional pagination and a specified number of items per page.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server. 
     * 
     * param pagination The pagination parameter determines whether or not to enable pagination for
     * the list of countries. If set to true, the list will be paginated.
     * 
     * param perPage The "perPage" parameter determines the number of items to be displayed per page
     * in the paginated list.
     * 
     * return a collection of CountryListResource objects.
     */
    public function fullList(Request $request, $pagination = false, $perPage = 10)
    {
        return CountryListResource::collection($this->repo->list($request, $pagination, $perPage));
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and
     * filtering.
     * 
     * param Request request This parameter is of type Request and is used to pass in the HTTP
     * request object that contains the search parameters.
     * 
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * 
     * param perPage The number of items to be displayed per page in case of pagination.
     * 
     * param get  is a string parameter that specifies the columns to retrieve from the database.
     * 
     * return The `findBy` method of the current object's repository is being called with the
     * provided parameters, and the result of that method call is being returned.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $get);
    }
}
