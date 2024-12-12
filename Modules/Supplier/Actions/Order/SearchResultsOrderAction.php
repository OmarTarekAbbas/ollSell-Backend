<?php

namespace Modules\Supplier\Actions\Order;

//todo change
use Modules\Order\Repositories\OrderRepository;

class SearchResultsOrderAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     * 
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     * 
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($request, $pagination, $perPage)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];

        if ($request->subTotalFrom && $request->subTotalTo) {
            $moreConditionForFirstLevel += ['whereBetween' => ['subTotal' => [$request->subTotalFrom, $request->subTotalTo]]];
        }

        if ($request->grandTotalFrom && $request->grandTotalTo) {
            $moreConditionForFirstLevel += ['whereBetween' => ['grandTotal' => [$request->grandTotalFrom, $request->grandTotalTo]]];
        }

        if ($request->orderDateFrom && $request->orderDateTo) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [$request->orderDateFrom, $request->orderDateTo]]];
        }

        if ($request->deliveryDateFrom && $request->deliveryDateTo) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [$request->deliveryDateFrom, $request->deliveryDateTo]]];
        }

        if ($request->search) {

            if (is_numeric($request->search)) {
                $moreConditionForFirstLevel += ['where' => ['id' => [$request->search]]];
            } else {
                $recursiveRel += ['dropshipper' => ['type' => 'orWhereHas', 'where' => [
                    'email' => ['like' => $request->search . "%"]
                ]]];
            }
        }

        return $this->repo->list($request, $pagination, $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel);
    }
}
