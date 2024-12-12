<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Repositories\TargetMarketRepository;
use Modules\CoreData\Actions\TargetMarket\IndexTargetMarketAction;
use Modules\CoreData\Http\Resources\TargetMarket\TargetMarketListResource;

class TargetMarketService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(TargetMarketRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * The index function executes the IndexTargetMarketAction class with the given request.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. 
     * 
     * return The code is returning the result of executing the `execute` method of the
     * `IndexTargetMarketAction` class, passing in the `` object as an argument.
     */
    public function index(Request $request)
    {
        return (new IndexTargetMarketAction(
            request: $request
         ))->execute();
    }

    /**
     * This function returns the results of a query based on the given request parameters and
     * pagination settings.
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
     * database. It is used to limit the amount of data returned from the database and improve
     * performance. If `` is not specified, all columns will be retrieved.
     * 
     * return the result of calling the `findBy` method on the `` object with the parameters
     * ``, ``, ``, and ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $get);
    }

    /**
     * This function returns a collection of target market lists based on the given request and
     * pagination parameters.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as the request method, headers, and parameters. 
     * 
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * 
     * param perPage The number of items to be displayed per page in the paginated list. In this case,
     * the default value is 10.
     * 
     * return a collection of TargetMarketListResource resources obtained from calling the `list`
     * method of the repository with the provided parameters. The `` parameter is optional
     * and defaults to `false`, while the `` parameter is also optional and defaults to `10`.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return TargetMarketListResource::collection($this->repo->list($request, $pagination, $perPage));
    }
}
