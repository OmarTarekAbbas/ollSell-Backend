<?php

namespace Modules\MasterCatalog\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Http\Resources\Event\EventListResource;
use Modules\MasterCatalog\Repositories\EventRepository;
//todo change
class EventService extends BasicService
{
    protected $repo;



    /**
     * This is a constructor function that initializes the repository, category service, and target
     * market service.
     * 
     * param EventRepository repository The  parameter is an instance of the
     * EventRepository class, which is responsible for handling database operations related to
     * products. It likely contains methods for retrieving, creating, updating, and deleting product
     * records from the database.

     */
    public function __construct(EventRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function finds records based on specified conditions and returns them with optional
     * pagination and ordering.
     * 
     * param Request request  is an instance of the Request class in Laravel. It contains the
     * HTTP request information such as the request method, headers, and input data. In this function,
     * it is used to retrieve any search or filter criteria that may be passed in the request.
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query. These conditions will be added to the WHERE clause of the SQL query.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned.
     * param perPage The number of records to be displayed per page in case of pagination.
     * param orderBy An array that specifies the order in which the results should be sorted. The keys
     * of the array represent the columns to sort by, and the values represent the direction of the
     * sort (either "asc" for ascending or "desc" for descending). For example, ["name" => "asc", "
     * param recursiveRel The  parameter is an array that specifies the related models
     * that should be eager loaded when retrieving the data. This is useful for reducing the number of
     * database queries needed to retrieve related data. For example, if a model has a relationship
     * with another model, specifying that relationship in the 
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     * It can be a string or an array of column names. If not specified, it will retrieve all columns.
     * 
     * return the result of calling the `findBy` method on the `` object with the arguments
     * passed to the function.
     */
    public function findBy(Request $request, $moreConditionForFirstLevel = [], $pagination = false, $perPage = 10, $orderBy = [], $recursiveRel = [], $get = '')
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $orderBy, $moreConditionForFirstLevel, $recursiveRel, $get);
    }

    /**
     * This is a PHP function that retrieves data from a repository based on search and status filters,
     * with pagination and recursive relationships.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information such as query parameters, form data, and headers.
     * 
     * return the result of a query that retrieves data from a repository based on the parameters
     * passed in the `` object. The query includes additional conditions based on the values of
     * `->search` and `->status`, and also includes pagination and sorting options. The
     * result is returned as an array of data.
     */
    public function index(Request $request)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel  = [];

        if (isset($request->search) && $request->search != null) {
            $moreConditionForFirstLevel  += ['orWhere' => ['id' => [$request->search], 'title' => ['LIKE', '%' . $request->search . '%']]];
        }

        if(isset($request->status) && $request->status == 'all') {
            $request->merge(['status' => null]);
        }

        return $this->repo->findBy($request, pagination: true, moreConditionForFirstLevel: $moreConditionForFirstLevel, perPage: $tableLength, orderBy: ['column' => 'id', 'order' => 'desc']);
    }

    public function show($id)
    {
        return $this->repo->findOne($id);
    }


    /**
     * It deletes the supplier's account
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function delete($id = null)
    {
        $data = $this->repo->delete($id);
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * It returns a collection of ProductListResource
     * 
     * param Request request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     * 
     * return A collection of ProductListResource
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return EventListResource::collection($this->repo->list($request, $pagination, $perPage));
    }

   /**
     * It returns a new EventListResource object, which is a collection of
     * EventListResource objects
     * 
     * param id The id of the master catalog list you want to retrieve
     * 
     * return A new instance of the EventListResource class.
     */
    public function listOne($id)
    {
        $data =  $this->repo->findOne($id);
        if ($data) {
            return new EventListResource($this->repo->findOne($id));
        }
        return null;
    }
}
