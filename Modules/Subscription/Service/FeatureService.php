<?php

namespace Modules\Subscription\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Subscription\Repositories\FeatureRepository;

class FeatureService extends BasicService
{
    protected FeatureRepository $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(FeatureRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This PHP function returns a collection of CategoryResource objects based on a list of
     * parameters.
     *
     * param Request request The HTTP request object that contains information about the current
     * request being made.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * param perPage The number of items to be displayed per page in the paginated list. In this case,
     * it is set to 10.
     * param recursiveRel The  parameter is an array that specifies the relationships
     * that should be recursively loaded when retrieving the categories. This means that not only the
     * categories will be retrieved, but also their related models that are specified in the
     *  array. This is useful when you need to retrieve nested relationships or when
     *
     * return a collection of CategoryResource objects. The collection is obtained by calling the
     * "list" method of the repository object with the provided parameters. The "list" method is
     * expected to return a list of category objects, which are then transformed into CategoryResource
     * objects using the "CategoryResource::collection" method.
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        return $this->repo->list($request, [], $recursiveRel, $pagination, $perPage);
    }

    /**
     * This function finds records based on specified parameters and returns them.
     *
     * param Request request This parameter is an instance of the Request class, which is used to
     * retrieve data from the HTTP request. It contains information about the current request, such as
     * the HTTP method, headers, and any data submitted in the request body.
     * param pagination A boolean value indicating whether or not to paginate the results. If set to
     * true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned.
     * param perPage The number of records to be displayed per page in case of pagination.
     * param pluck An array of columns to retrieve from the database. If provided, only the specified
     * columns will be returned in the result set.
     * param get The "get" parameter is used to specify the columns that should be retrieved from the
     * database. It can be an array of column names or a string of comma-separated column names. If not
     * specified, all columns will be retrieved.
     * param moreConditionForFirstLevel moreConditionForFirstLevel is an optional parameter that
     * allows you to add additional conditions to the first level of the query. This can be useful if
     * you need to filter the results further based on some criteria that are not directly related to
     * the model's attributes. For example, you might want to filter the
     * param recursiveRel The recursiveRel parameter is an array that specifies the relationships that
     * should be recursively loaded when retrieving the data. This is useful when you need to retrieve
     * data from related tables that are multiple levels deep. For example, if you have a User model
     * that has a relationship with a Post model, and the Post
     * param withRelations withRelations is an array of relationships that should be eager loaded when
     * retrieving the data. This can help to reduce the number of database queries needed to retrieve
     * related data. For example, if a model has a "comments" relationship, including "comments" in the
     * withRelations array will ensure that all comments
     * param latest The "latest" parameter is used to specify the column to order the results by in
     * descending order. This means that the most recent records will be returned first. It is used in
     * conjunction with the "limit" parameter to limit the number of results returned.
     * param limit The limit parameter is used to limit the number of results returned by the query.
     * If set to 0, it means there is no limit and all matching results will be returned. If set to a
     * positive integer, it means that only that number of results will be returned.
     *
     * return the result of calling the `findBy` method on the repository object with the provided
     * arguments.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $pluck = [], $get = '', $moreConditionForFirstLevel = [], $recursiveRel = [], $withRelations = [], $latest = '', $limit = 0)
    {
        return $this->repo->findBy($request, $pagination, $perPage, $pluck, $get, $moreConditionForFirstLevel, $recursiveRel, $withRelations, latest: $latest, limit: $limit);
    }
}
