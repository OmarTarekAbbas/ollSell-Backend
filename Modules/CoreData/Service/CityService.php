<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Repositories\CityRepository;
use Modules\CoreData\Actions\City\IndexCityAction;
use Modules\CoreData\Http\Resources\City\CityResource;

class CityService extends BasicService
{
    protected $repo, $countryService;

    /**
     * This is a constructor function that initializes the CityRepository and CountryService objects.
     *
     * param CityRepository repository The  parameter is an instance of the CityRepository
     * class, which is used to interact with the database and perform CRUD operations on the city
     * table.
     * param CountryService countryService The  parameter is an instance of the
     * CountryService class, which is a service that provides functionality related to countries. It is
     * likely used within the constructor or other methods of the class to perform operations related
     * to countries, such as retrieving country data or performing calculations based on country
     * information.
     */
    public function __construct(CityRepository $repository, CountryService $countryService)
    {
        $this->repo = $repository;
        $this->countryService = $countryService;
    }

    /**
     * The index function executes the IndexCityAction class with the given request.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request.
     *
     * return The code is returning the result of executing the `execute` method of the
     * `IndexCityAction` class, passing in the `` object as an argument.
     */
    public function index(Request $request)
    {
        return (new IndexCityAction(
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
     * return the result of calling the `findBy` method on the `` object with the parameters
     * ``, ``, ``, and ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination, $perPage, get: $get);
    }

    /**
     * This PHP function returns a collection of CityResource objects based on the provided parameters.
     *
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as headers, parameters, and cookies.
     *
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     *
     * param perPage The number of items to be displayed per page in the paginated list. In this case,
     * it is set to 10.
     *
     * return A collection of CityResource objects returned by calling the "list" method of the
     * repository with the provided parameters.
     */
    public function list(Request $request, $pagination = false, $perPage = 10,$recursiveRel=[])
    {
        return CityResource::collection($this->repo->list($request, $pagination, $perPage, $recursiveRel));
    }

    /**
     * This PHP function returns a list of active countries using a country service.
     *
     * return The function `countryList()` is returning the list of countries with active status. The
     * data is obtained by calling the `list()` method of the `countryService` object with a request
     * object containing the status parameter set to the value returned by the `activeType()` function.
     */
    public function countryList()
    {
        return $this->countryService->fullList(new Request(['status' => activeType()['as']]));
    }

    public function search(Request $request)
    {
        return $this->repo->findBy($request, get: 'first');
    }
}
