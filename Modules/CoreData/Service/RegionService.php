<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Actions\State\IndexStateAction;
use Modules\CoreData\Repositories\StateRepository;
use Modules\CoreData\Http\Resources\State\StateListResource;

class RegionService extends BasicService
{
    protected $repo,$countryService, $cityService;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(StateRepository $repository, CountryService $countryService, CityService $cityService)
    {
        $this->repo = $repository;
        $this->countryService = $countryService;
        $this->cityService = $cityService;
    }

    /**
     * The index function executes the IndexStateAction class with the given request.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the application.
     * 
     * @return The code is returning the result of executing the `execute` method of the
     * `IndexStateAction` class with the `` parameter.
     */
    public function index(Request $request)
    {
        return (new IndexStateAction(
            request: $request
         ))->execute();
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and
     * filtering.
     * 
     * param Request request This is an instance of the Request class in Laravel, which contains all
     * the data that was sent with the HTTP request.
     * 
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * 
     * param perPage The number of results to be displayed per page in case of pagination.
     * 
     * param get The `` parameter is a string that specifies the columns to retrieve from the
     * database. 
     * 
     * @return the result of calling the `findBy` method on the `` object with the parameters
     * ``, ``, ``, and ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $get);
    }

    /**
     * This PHP function returns a collection of state list resources based on the provided request,
     * pagination, and perPage parameters.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as headers, parameters, and cookies. 
     * 
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned without pagination.
     * param perPage The number of items to be displayed per page in the paginated list. In this case,
     * the default value is 10.
     * 
     * @return a collection of StateListResource objects obtained from calling the "list" method of the
     * repository with the provided parameters. The  parameter is a boolean that determines
     * whether or not to paginate the results, and the  parameter is an integer that sets the
     * number of items per page if pagination is enabled.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return StateListResource::collection($this->repo->list($request, $pagination, $perPage));
    }

    /**
     * The function returns a list of active countries using a country service.
     * 
     * @return The `countryList()` function is returning a list of countries with active status. The
     * list is obtained by calling the `list()` method of the `` object, which is passed
     * a new `Request` object with the `status` parameter set to the value returned by the
     * `activeType()` function.
     */
    public function countryList()
    {
        return $this->countryService->list(new Request(['status' => activeType()['as']]));
    }

    /**
     * The function returns a list of active countries using a city service.
     * 
     * @return The `cityList()` function is returning a list of countries with active status. The
     * list is obtained by calling the `list()` method of the `` object, which is passed
     * a new `Request` object with the `status` parameter set to the value returned by the
     * `activeType()` function.
     */
    public function cityList()
    {
        return $this->cityService->list(new Request(['status' => activeType()['as']]));
    }
}
