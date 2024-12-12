<?php

namespace Modules\Acl\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Acl\Entities\Supplier;
use Modules\Basic\Service\BasicService;
use Modules\Supplier\Entities\Warehouse;
use Modules\MasterCatalog\Entities\Product;
use Modules\Acl\Repositories\SupplierRepository;

class SupplierService extends BasicService
{
    protected $repo;

    /**
     * This is a constructor function that initializes the repository, category service, and target
     * market service.
     *
     * param SupplierRepository repository The  parameter is an instance of the
     * SupplierRepository class, which is responsible for handling database operations related to
     * products. It likely contains methods for retrieving, creating, updating, and deleting product
     * records from the database.
     */
    public function __construct(SupplierRepository $repository)
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
     * return the result of a query that retrieves data from a repository based on the parameters
     * passed in the `` object. The query includes additional conditions based on the values of
     * `->search` and `->status`, and also includes pagination and sorting options. The
     * result is returned as an array of data.
     */
    public function index(Request $request)
    { //todo change
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = $recursiveRel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'name' => ['LIKE', '%' . $request->search . '%']]];
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
                $recursiveRel['product']['recursive'] =
                    ['orderItems' => [
                        'type' => 'whereHas',
                        'recursive' => [
                            'order' => [
                                'type' => 'whereHas',
                                'whereIn' => ['dropshipper_id' => $request->dropshipper_id]
                            ]]]];
            }
            if(isset($request->supplier_id) && !empty($request->supplier_id))
            {
                $moreConditionForFirstLevel += ['whereIn' => ['id' => $request->supplier_id]];
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
                $recursiveRel['product']['whereIn'] = ['warehouse_id' => $request->warehouse_id];
            }
        }
        return $this->repo->findBy($request, pagination: true, moreConditionForFirstLevel: $moreConditionForFirstLevel,
            perPage: $tableLength, orderBy: ['column' => 'id', 'order' => 'desc'], recursiveRel: $recursiveRel);
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
        if($data)
        {
            return $data;
        }
        return false;
    }

    public function destroy(Request $request, $id)
    {
        if(request('newSupplierId'))
        {
            $newSupplierId = request('newSupplierId');
            // assign products & warehouses
            //todo change
            Product::where('supplier_id', $id)->update(['supplier_id' => $newSupplierId]);
            Warehouse::where('supplier_id', $id)->update(['supplier_id' => $newSupplierId]);
        }
        $data = Supplier::find($id)->delete();
        if($data)
        {
            return $data;
        }
        return false;
    }

    public function list(Request $request)
    {
        $moreConditionForFirstLevel = [];
        if(isset($request->term) && $request->term != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->term], 'name' => ['LIKE', '%' . $request->term . '%'], 'email' => ['LIKE', '%' . $request->term . '%']]];
        }
        return $this->repo->list(new Request(), moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: false);
    }
}
