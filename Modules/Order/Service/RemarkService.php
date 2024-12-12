<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Http\Resources\Remark\RemarkResource;
use Modules\Order\Repositories\RemarkRepository;

class RemarkService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(RemarkRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function returns a collection of StatusResource objects based on certain parameters passed
     * to it.
     *
     * param Request request An instance of the Request class, which contains the HTTP request
     * information such as headers, parameters, and cookies.
     *
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query.
     *
     * param orderBy An array that specifies the order in which the results should be sorted. It can
     * contain one or more key-value pairs, where the key
     *
     * param pagination A boolean value that determines whether or not to enable pagination for the
     * results. If set to true, the results will be paginated based on the  parameter.
     *
     * param perPage The number of items to be displayed per page in case of pagination.
     * param get The "get" parameter is used to specify which columns to retrieve from the
     *
     * param withRelations withRelations is an array that contains the names of the related models
     * that should be eager loaded with the main model.
     *
     * @return A collection of StatusResource objects returned by calling the "list" method of a
     * repository object with the provided parameters.
     */
    public function list(Request $request, $moreConditionForFirstLevel = [], $orderBy = [], $pagination = false, $perPage = 10, $get = '', $withRelations = [])
    {
        return RemarkResource::collection($this->repo->list($request, $moreConditionForFirstLevel, $orderBy, $pagination,  $perPage, $get, $withRelations));
    }

    /**
     * This function finds records based on specified conditions and returns them with optional
     * pagination and related data.
     *
     * param Request request This parameter is an instance of the Request class in Laravel. It
     * contains all the data that was sent with the HTTP request.
     *
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query. These conditions will be added to the WHERE clause of the SQL query.
     *
     * param orderBy An array that specifies the order in which the results should be sorted. The keys
     * of the array represent the columns to sort by.
     *
     * param pagination A boolean value that determines whether or not to enable pagination for the
     * query results.
     *
     * param perPage The number of records to be displayed per page in case of pagination.
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     *
     * param withRelations withRelations is an optional parameter that allows you to specify any
     * related models that should be eager loaded with the main model being queried. This can help to
     * reduce the number of database queries needed to retrieve related data, improving performance.
     * The parameter should be an array of relationship names.
     *
     * @return The function `findBy` is returning the result of calling the `findBy` method on the
     * `` object with the provided arguments.
     */
    public function findBy(Request $request, $moreConditionForFirstLevel = [], $orderBy = [], $pagination = false, $perPage = 10, $get = '', $withRelations = [])
    {
        return $this->repo->findBy($request, $moreConditionForFirstLevel, $orderBy, $pagination,  $perPage, $get, $withRelations);
    }
}
