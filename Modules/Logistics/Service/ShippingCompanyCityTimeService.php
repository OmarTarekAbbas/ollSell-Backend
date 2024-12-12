<?php

namespace Modules\Logistics\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Logistics\Repositories\ShippingCompanyCityTimeRepository;
use Modules\Logistics\Actions\ShippingCompanyCityTime\IndexShippingCompanyCityTimeAction;
use Modules\Logistics\Http\Resources\ShippingCompanyCityTime\ShippingCompanyCityTimeResource;

class ShippingCompanyCityTimeService extends BasicService
{
    protected $repo,$shipping_company_service;


    /**
     * This is a constructor function that initializes the ShippingCompanyCityTimeRepository and CountryService objects.
     * 
     * param ShippingCompanyCityTimeRepository repository The  parameter is an instance of the ShippingCompanyCityTimeRepository
     * class, which is used to interact with the database and perform CRUD operations on the city
     * table.
     * param CountryService shipping_company_service The  parameter is an instance of the
     * CountryService class, which is a service that provides functionality related to countries. It is
     * likely used within the constructor or other methods of the class to perform operations related
     * to countries, such as retrieving country data or performing calculations based on country
     * information.
     */
    public function __construct(ShippingCompanyCityTimeRepository $repository, ShippingCompanyService $shipping_company_service)
    {
        $this->repo = $repository;
        $this->shipping_company_service = $shipping_company_service;
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
        //todo change
        return App(IndexShippingCompanyCityTimeAction::class)->execute($request);
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
        return $this->repo->findBy($request, $pagination,  $perPage, get: $get);
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
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return ShippingCompanyCityTimeResource::collection($this->repo->list($request, $pagination, $perPage));
    }

    /**
     * This PHP function returns a list of active countries using a country service.
     * 
     * return The function `countryList()` is returning the list of countries with active status. The
     * data is obtained by calling the `list()` method of the `countryService` object with a request
     * object containing the status parameter set to the value returned by the `activeType()` function.
     */
    public function shippingCompanyList()
    {
        return $this->shipping_company_service->fullList(new Request(['status' => activeType()['as']]));
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
