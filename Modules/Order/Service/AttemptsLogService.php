<?php

namespace Modules\Order\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Http\Resources\Status\StatusResource;
use Modules\Order\Entities\AttemptsLog;
use Modules\Order\Repositories\AttemptsLogRepository;

class AttemptsLogService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(AttemptsLogRepository $repository)
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
        return StatusResource::collection($this->repo->list($request, $moreConditionForFirstLevel, $orderBy, $pagination,  $perPage, $get, $withRelations));
    }

    /**
     * This function retrieves data from a repository based on search and status filters, and returns it
     * with pagination.
     *
     * param request This parameter is likely an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request made to the application. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body.
     *
     * return the result of a database query using the `findBy` method of a repository object. The
     * query is filtered based on the values of the `` object, as well as additional conditions
     * specified in the `` array. The result is paginated and sorted
     * according to the `` variable and the `` parameter. The ``
     */
    public function indexDashboard(Request $request, $pagination = false, $perPage = 10)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = [];
        // Parse both date and time for 'fromDate' and 'toDate'
        if ($request->fromDate && $request->toDate) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [
                Carbon::parse($request->fromDate),
                Carbon::parse($request->toDate)
            ]]];
        } elseif ($request->fromDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)]]];
        } elseif ($request->toDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)]]];
        }

        // Determine sort order based on user selection
        $sortBy = $request->get('sort_by', 'created_at_desc'); // Default sort by created_at descending
        $sortColumn = 'created_at';
        $sortOrder = 'desc';

        // Adjust sort column and order based on the selected option
        switch ($sortBy) {
            case 'created_at_asc':
                $sortColumn = 'created_at';
                $sortOrder = 'asc';
                break;
            case 'created_at_desc':
                $sortColumn = 'created_at';
                $sortOrder = 'desc';
                break;
            case 'id_asc':
                $sortColumn = 'order_id';
                $sortOrder = 'asc';
                break;
            case 'id_desc':
                $sortColumn = 'order_id';
                $sortOrder = 'desc';
                break;
        }

        return $this->repo->findBy(
            request: $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: $pagination,
            perPage: $perPage,
            orderBy: ['column' => $sortColumn, 'order' => $sortOrder]
        );
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

    /**
     * The store function executes the Create class to create a new order item.
     *
     * param order The parameter "order" is the data representing an order that needs to be stored. It
     * could be an array, object, or any other data structure that contains the necessary information
     * for creating an order item.
     *
     * @return the result of executing the `Create` class with the `` parameter.
     */
    public function store($order)
    {
        $request = request();
        $date = null;


        if ($attempt = AttemptsLog::where('order_id', $order->id)->first()) {
            $date = $attempt->created_at;
        } else {
            $date = now();
        }

        $request->merge([
            'order_id' => $order->id,
            'status_id' => $order->status_id,
            'sub_status_id' => $order->sub_status_id,
            'remark_id' => $order->remark_id,
            'attempts_count' => $order->attempts_count,
            'validated_at' => $order->validated,
            'first_validation' => $date,
            'last_edit_order' => $order->updated_at,
            'notes' => (collect($order->notes)->count() > 0) ? collect($order->notes)->last()->content : null,
        ]);

        return $this->repo->save($request);
    }
}
