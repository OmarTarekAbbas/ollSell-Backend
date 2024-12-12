<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Http\Resources\Order\PendingOrderImportResource;
use Modules\Order\Repositories\PendingOrderRepository;

class PendingOrderImportService extends BasicService
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
    public function __construct(
        PendingOrderRepository $repository
    ) {
        $this->repo = $repository;
    }

    /**
     * This function is used to find records based on certain conditions,
     *
     * param Request request The `` parameter is an instance of the `Request` class, which is
     * typically used to retrieve data from the HTTP request.
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query.
     * param orderBy The "orderBy" parameter is used to specify the sorting order of the results.
     * param pagination The "pagination" parameter determines whether the results should be paginated
     * or not.
     * param perPage The "perPage" parameter determines the number of records to be displayed per page
     * when using pagination.
     * param get The "get" parameter is used to specify the columns that you want to retrieve from the
     * database.
     * param withRelations An array of relations to eager load with the query. These relations will be
     * loaded along with the main model to reduce the number of database queries.
     * param latest The "latest" parameter is used to specify the column to order the results by in
     * descending order.
     * param limit The "limit" parameter is used to specify the maximum number of records to retrieve
     * from the database.
     *
     * return the result of calling the `findBy` method on the `` object.
     */
    public function findBy(
        Request $request,
        $moreConditionForFirstLevel = [],
        $orderBy = [],
        $pagination = false,
        $perPage = 10,
        $get = '',
        $withRelations = [],
        $latest = '',
        $limit = null,
        $recursiveRel = []
    ) {
        return $this->repo->findBy(
            $request,
            $moreConditionForFirstLevel,
            $orderBy,
            $pagination,
            $perPage,
            $get,
            $withRelations,
            latest: $latest,
            limit: $limit,
            recursiveRel: $recursiveRel
        );
    }


    /**
     * It returns a collection of OrderResource
     *
     * param Request request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * return A collection of OrderResource
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        $moreConditionForFirstLevel = [];

        if ($request->search) {

            if (is_numeric($request->search)) {

                if (strlen($request->search) > 6) {

                    $moreConditionForFirstLevel += ['where' => ['customerPhone' => [$request->search]]];
                } else {

                    $moreConditionForFirstLevel += ['where' => ['id' => [$request->search]]];
                }
            } else {

                $moreConditionForFirstLevel += ['where' => ['customerName' => ['LIKE', '%' . $request->search . '%']]];
            }
        }

        $request->merge(['dropshipper_id' => user()->id]);

        if ($request->city_id) {
            $request->merge(['customerCity' => $request->city_id]);
        }

        $data = $this->repo->list($request, $pagination, $perPage);

        return PendingOrderImportResource::collection($data);
    }

    /**
     * This function stores an import request by saving it to a repository.
     *
     * param request  is a variable that contains the data sent through an HTTP request.
     */
    public function storePendingOrdersImport($request)
    {
        return $this->repo->save($request, $id = null);
    }

    public function update(Request $request, $id = null)
    {
        return $this->repo->save($request, $id);
    }
}
