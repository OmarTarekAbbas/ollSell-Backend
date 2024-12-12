<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Http\Resources\Category\CategoryProductResource;
use Modules\CoreData\Repositories\CategoryRepository;
use Modules\CoreData\Actions\Category\IndexCategoryAction;
use Modules\CoreData\Actions\Category\StoreSuggestedAction;
use Modules\CoreData\Http\Resources\Category\CategoryResource;
use Modules\CoreData\Actions\Category\FindOrCreateCategoryAction;
use Modules\CoreData\Actions\Category\ListCategoriesSupplierAction;
use Modules\CoreData\Actions\Category\RejectCategoriesSupplierAction;
use Modules\CoreData\Actions\Notification\SendNotificationForSupplierAction;

class CategoryService extends BasicService
{
    protected CategoryRepository $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(CategoryRepository $repository)
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
        return (new IndexCategoryAction(
           request: $request
        ))->execute();
    }

    /** 
     * The function "listCategoriesSupplier" executes the "ListCategoriesSupplierAction" class with the
     * given request.
     * 
     * param Request request The  parameter is an instance of the Request class, which is used
     * to handle HTTP requests in Laravel.
     * 
     * return the result of executing the `ListCategoriesSupplierAction` class with the given
     * `` parameter.
     */
    public function listCategoriesSupplier(Request $request)
    {
        return (new ListCategoriesSupplierAction(
            request: $request
         ))->execute();
    }

    /**
     * The function "rejectCategoriesSupplier" executes the "execute" method of the
     * "RejectCategoriesSupplierAction" class with the given request and ID parameters.
     * 
     * param request The  parameter is typically an instance of the Illuminate\Http\Request
     * class, which represents an incoming HTTP request. 
     * 
     * param id The "id" parameter is the identifier of the supplier. It is used to specify which
     * supplier's categories should be rejected.
     * 
     * return the result of executing the `execute` method of the `RejectCategoriesSupplierAction`
     * class with the given `` and `` parameters.
     */
    public function rejectCategoriesSupplier($request, $id)
    {
        $data =(new RejectCategoriesSupplierAction(
            request: $request,
            id:$id
         ))->execute();
         App(SendNotificationForSupplierAction::class)->execute($data['title'], $data['content'], $data['supplier_id'], $data['urlType'], $data['urlId'], $data['color']);

   
    }

    /**
     * The function "storeSuggested" calls the "execute" method of the "StoreSuggestedAction" class
     * with the given request and id parameters.
     * 
     * param request The  parameter is an instance of the Illuminate\Http\Request class. It
     * represents the HTTP request made to the server.
     * 
     * param id The "id" parameter is the identifier of the suggested item that you want to store. It
     * is used to uniquely identify the suggested item in the database.
     * 
     * return the result of the `execute` method of the `StoreSuggestedAction` class, which is being
     * called with the `` and `` parameters.
     */
    public function storeSuggested($request, $id)
    {
        return (new StoreSuggestedAction(
            request: $request,
            id:$id
         ))->execute();
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
        return CategoryResource::collection($this->repo->list($request->merge(['status' => activeType()['as'], 'isApproved' => 1]), [], $recursiveRel, $pagination, $perPage));
    }
    public function listWithProduct(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        return CategoryProductResource::collection($this->repo->list($request->merge(['status' => activeType()['as'], 'isApproved' => 1]), [], $recursiveRel, $pagination, $perPage));
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
        return new CategoryResource($this->repo->findOne($id));
    }

    /**
     * The function "findOrCreate" takes a name as input and returns the result of executing the
     * "FindOrCreateCategoryAction" class with the given name.
     * 
     * param name The name parameter is the name of the category that you want to find or create.
     * 
     * return the result of executing the `execute` method of the `FindOrCreateCategoryAction` class
     * with the `` parameter.
     */
    public function findOrCreate($name)
    {
        return (new FindOrCreateCategoryAction(
            name: $name
         ))->execute();

    }
}
