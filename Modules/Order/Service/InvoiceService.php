<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Repositories\InvoiceRepository;

class InvoiceService extends BasicService
{
    protected $repo;

    /**
     * The function is a constructor that initializes the class with dependencies.
     *
     * param OrderRepository repository The `` parameter is an instance of the
     * `OrderRepository` class.
     * param StatusService statusService The `statusService` parameter is an instance of the
     * `StatusService` class.
     * param OrderRefundService orderRefundService The `orderRefundService` parameter is an instance
     * of the `OrderRefundService`
     * param RefundMessageService refundMessageService The `refundMessageService` parameter is an
     * instance of the `RefundMessageService`
     */
    public function __construct(InvoiceRepository $repository)
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
        $moreConditionForFirstLevel = [];

        if (isset($request->search) && $request->search != null) {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'name' => ['LIKE', '%' . $request->search . '%']]];
        }

        $request->merge(['dropshipper_id' => user()->id]);

        return $this->repo->findBy($request, $pagination, $perPage, $orderBy, $moreConditionForFirstLevel, $recursiveRel, $get);
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
        $moreConditionForFirstLevel = [];

        return $this->repo->findBy($request, pagination: true, moreConditionForFirstLevel: $moreConditionForFirstLevel, perPage: $tableLength, orderBy: ['column' => 'id', 'order' => 'desc']);
    }

    /**
     * This function saves data to the database.
     *
     * param Request request an instance of the Request class, which contains the data submitted in
     * the HTTP request
     */
    public function store($order)
    {
        $request = request();
        $request->merge([
            'order_id' => $order->id,
            'dropshipper_id' => $order->dropshipper_id,
            'dropshipper_branch_id' => $order->dropshipper_branch_id,
            'invoice_number' => random_int(1000, 9999) . $order->id,
            'paymentMethod' => $order->paymentMethod,
            'costPrice' => $order->costPrice,
            'subTotal' => $order->subTotal,
            'grandTotal' => $order->grandTotal,
            'totalVat' => $order->totalVat,
            'net_profit' => $order->net_profit,
            'pdf_link' => url('/api/invoice/download/' . $order->id),
        ]);
        $data = $this->repo->save($request, $id = null);
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * The show function retrieves and returns a single record from the repository based on the provided
     * ID.
     *
     * @param id The `show` function takes an `` parameter as input. This function is responsible for
     * fetching and returning a single record from the repository based on the provided ``.
     * @return The `show` function is returning the result of calling the `findOne` method on the `repo`
     *             object with the `` parameter passed to the `show` function.
     */
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
     * It returns a list of all the active records in the database
     *
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to be displayed per page.
     * param moreConditionForFirstLevel This is an array of conditions that you want to add to the
     * first level of the query.
     */
    public function list(Request $request, $pagination = true, $perPage = 20, $moreConditionForFirstLevel = [], $recursiveRel = [])
    {
        return $this->repo->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel);
    }
}
