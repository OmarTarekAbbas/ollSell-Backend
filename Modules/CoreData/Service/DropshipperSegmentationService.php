<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Actions\DropshipperSegmentation\DropshipperSegmentationAction;
use Modules\CoreData\Repositories\DropshipperSegmentationRepository;

class DropshipperSegmentationService extends BasicService
{
    protected $repo,$countryService, $cityService;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(DropshipperSegmentationRepository $repository)
    {
        $this->repo = $repository;
 
    }

    /**
     * The index function executes the IndexStateAction class with the given request.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the application.
     * 
     * return The code is returning the result of executing the `execute` method of the
     * `IndexStateAction` class with the `` parameter.
     */
    public function index(Request $request)
    {
        //todo change
        return App(DropshipperSegmentationAction::class)->execute($request);
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
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '',$orderBy=[])
    {
        return $this->repo->findBy($request, $pagination,  $perPage,get: $get,orderBy:$orderBy);
    }
}
