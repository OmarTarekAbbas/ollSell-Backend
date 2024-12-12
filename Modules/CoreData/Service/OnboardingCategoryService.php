<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Repositories\OnboardingCategoryRepository;
use Modules\CoreData\Actions\OnboardingCategory\IndexOnboardingCategoryAction;
use Modules\CoreData\Http\Resources\OnboardingCategory\OnboardingCategoryResource;

class OnboardingCategoryService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(OnboardingCategoryRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * The index function executes the IndexCategoryAction class with the given request.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server.
     * 
     * return The code is returning the result of executing the `execute` method of the
     * `IndexCategoryAction` class with the `` parameter.
     */
    public function index(Request $request)
    {
        //todo change
        return App(IndexOnboardingCategoryAction::class)->execute($request);
    }

    /**
     * This PHP function returns a collection of CategoryResource objects based on a list of
     * parameters.
     *
     * param Request request The HTTP request object that contains information about the current
     * request being made.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the 
     * 
     * param recursiveRel The  parameter is an array that specifies the relationships
     * that should be recursively
     *
     * return a collection of CategoryResource objects. The collection is obtained by calling the
     * "list" method of the repository object with the
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        return OnboardingCategoryResource::collection($this->repo->list($request->merge(['status' => activeType()['as']]), [], $recursiveRel, $pagination, $perPage));
    }

    /**
     * This function finds records based on specified parameters and returns them.
     *
     * param Request request This parameter is an instance of the Request class, which is used to
     * retrieve data from the HTTP request.
     * 
     * param pagination A boolean value indicating whether or not to paginate the results. If set to
     * true, the results will be paginated based on the  parameter.
     * 
     * param perPage The number of records to be displayed per page in case of pagination.
     * 
     * param pluck An array of columns to retrieve from the database. If provided, only the specified
     * columns will be returned in the result set.
     * 
     * param get The "get" parameter is used to specify the columns that should be retrieved from the
     * database. It can be an array of column names or a string of comma-separated column names.
     * 
     * param moreConditionForFirstLevel moreConditionForFirstLevel is an optional parameter that
     * allows you to add additional conditions to the first level of the query. 
     * 
     * param recursiveRel The recursiveRel parameter is an array that specifies the relationships that
     * should be recursively loaded when retrieving the data. 
     * 
     * param withRelations withRelations is an array of relationships that should be eager loaded when
     * retrieving the data.
     * 
     * param latest The "latest" parameter is used to specify the column to order the results by in
     * descending order. 
     * 
     * param limit The limit parameter is used to limit the number of results returned by the query.
     *
     * return the result of calling the `findBy` method on the repository object with the provided
     * arguments.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination, $perPage, $orderBy = [], $moreConditionForFirstLevel = [], $recursiveRel = [], $get);
    }


    public function show($id)
    {
        return new OnboardingCategoryResource($this->repo->findOne($id));
    }
}
