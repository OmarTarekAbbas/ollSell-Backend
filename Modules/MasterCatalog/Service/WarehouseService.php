<?php

namespace Modules\MasterCatalog\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Service\CityService;
use Modules\CoreData\Service\CountryService;
use Modules\Supplier\Repositories\WarehouseRepository;

//todo change
class WarehouseService extends BasicService
{
    protected $repo, $countryService, $cityService;

    /**
     * This is a constructor function that initializes the repository, category service, and target
     * market service.
     *
     * param WarehouseRepository repository The  parameter is an instance of the
     * WarehouseRepository class, which is responsible for handling database operations related to
     * products. It likely contains methods for retrieving, creating, updating, and deleting product
     * records from the database.
     */
    public function __construct(WarehouseRepository $repository, CountryService $countryService,
        CityService $cityService)
    {
        $this->repo = $repository;
        $this->countryService = $countryService;
        $this->cityService = $cityService;
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
     * @return the result of calling the `findBy` method on the `` object with the arguments
     * passed to the function.
     */
    public function findBy(Request $request, $moreConditionForFirstLevel = [], $pagination = false, $perPage = 10,
        $orderBy = [], $recursiveRel = [], $get = '')
    {
        return $this->repo->findBy($request, $pagination, $perPage, $orderBy, $moreConditionForFirstLevel,
            $recursiveRel, $get);
    }

    /**
     * This is a PHP function that retrieves data from a repository based on search and status filters,
     * with pagination and recursive relationships.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information such as query parameters, form data, and headers.
     *
     * @return the result of a query that retrieves data from a repository based on the parameters
     * passed in the `` object. The query includes additional conditions based on the values of
     * `->search` and `->status`, and also includes pagination and sorting options. The
     * result is returned as an array of data.
     */
    public function index(Request $request)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'name' => ['LIKE', '%' . $request->search . '%'], 'address' => ['LIKE', '%' . $request->search . '%']]];
        }
        return $this->repo->findBy(new Request(['is_internal' => 0]), pagination: true,
            moreConditionForFirstLevel: $moreConditionForFirstLevel, perPage: $tableLength,
            orderBy: ['column' => 'id', 'order' => 'desc']);
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
     * @return The data is being returned.
     */
    public function delete($id = null)
    {
        $data = $this->repo->delete($id);
        if($data)
        {
            return $data;
        }
        return false;
    }

    /**
     * The function returns a warehouse object that has the "is_internal" property set to 1.
     *
     * @return the result of the findBy method called on the warehouseService object. The findBy method
     * is being passed a Request object with the parameter 'is_internal' set to 1.
     */
    public function getWarehouseIsInternal()
    {
        return $this->repo->findBy(new Request(['is_internal' => 1]), get: 'first');
    }

    /**
     * The function retrieves admin users from the repository based on certain conditions and returns
     * the results with pagination.
     *
     * param  request The `` parameter is an instance of the `Illuminate\Http\Request`
     * class. It represents the current HTTP request made to the server and contains information such
     * as the request method, URL, headers, and any submitted form data.
     *
     * @return  result of a database query. It is using the `findBy` method of the `->repo`
     * object to retrieve records from the database. The query is filtered based on the conditions
     * specified in the `Request` object. The results are paginated and sorted in descending order by
     * the `id` column.
     */
    public function indexAdmin(Request $request)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = $recursiveRel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += [
                'whereCustom' => [
                    'orWhere' => [
                        ['id' => $request->search], ['name' => ['LIKE', '%' . $request->search . '%']], ['address' => ['LIKE', '%' . $request->search . '%']]
                    ]],
            ];
        }
        if(isset($request->is_report) && $request->is_report)
        {
            if($request->fromDate && $request->toDate)
            {
                $recursiveRel = [
                    'product' => [
                        'type' => 'whereHas',
                        'whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                            ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]
                    ]
                ];
            }elseif($request->fromDate)
            {
                $recursiveRel = [
                    'product' => [
                        'type' => 'whereHas',
                        'where' => ['created_at' => ['>', Carbon::parse($request->fromDate)->startOfDay()]]
                    ]
                ];
            }elseif($request->toDate)
            {
                $recursiveRel = [
                    'product' => [
                        'type' => 'whereHas',
                        'where' => ['created_at' => ['<', Carbon::parse($request->toDate)->endOfDay()]]
                    ]
                ];
            }
            if(isset($request->dropshipper_id) && !empty($request->dropshipper_id))
            {
                $recursiveRel['product']['recursive'] = [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'recursive' => [
                            'order' => [
                                'type' => 'whereHas',
                                'whereIn' => ['dropshipper_id' => $request->dropshipper_id]
                            ]
                        ]]
                ];
            }
            if(isset($request->supplier_id) && !empty($request->supplier_id))
            {
                $recursiveRel['product']['whereIn'] = ['supplier_id' => $request->supplier_id];
            }
            if(isset($request->product_id) && !empty($request->product_id))
            {
                $recursiveRel = [
                    'product' => [
                        'type' => 'whereHas',
                        'where' => ['id' => $request->product_id]
                    ]
                ];
            }
            if(isset($request->warehouse_id) && !empty($request->warehouse_id))
            {
                $moreConditionForFirstLevel += ['whereIn' => ['id' => $request->warehouse_id]];
            }
        }
        if(!isset($request->is_report))
        {
            $request->merge(['is_internal' => 0]);
        }
        return $this->repo->findBy($request, pagination: true,
            moreConditionForFirstLevel: $moreConditionForFirstLevel, perPage: $tableLength,
            orderBy: ['column' => 'id', 'order' => 'desc'], recursiveRel: $recursiveRel);
    }

    public function countryList(Request $request)
    {
        return $this->countryService->list(request());
    }

    public function cityList(Request $request)
    {
        return $this->cityService->findBy($request);
    }

    public function list(Request $request)
    {
        $moreConditionForFirstLevel = [];
        if(isset($request->term) && $request->term != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->term], 'name' => ['LIKE', '%' . $request->term . '%'], 'address' => ['LIKE', '%' . $request->term . '%']]];
        }
        return $this->repo->list(new Request(), moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: false);
    }
}
