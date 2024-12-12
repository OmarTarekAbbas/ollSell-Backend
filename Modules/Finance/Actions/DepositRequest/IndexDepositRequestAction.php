<?php

namespace Modules\Finance\Actions\DepositRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Finance\Repositories\DepositRequestRepository;

class IndexDepositRequestAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(DepositRequestRepository $repository)
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
    public function execute(Request $request, $pagination, $perPage)
    {
        $moreConditionForFirstLevel = [];

        if ($request->orderDateFrom && $request->orderDateTo) {

            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->orderDateFrom)->startOfDay(),  Carbon::parse($request->orderDateTo)->endOfDay()]]];
        }

        if ($request->search) {

            if (is_numeric($request->search)) {

                if (strlen($request->search) > 6) {

                    $moreConditionForFirstLevel += ['where' => ['customerPhone' => [$request->search]]];
                } else {
                    $moreConditionForFirstLevel += ['where' => ['id' => [$request->search]]];
                }
            } else {

                $moreConditionForFirstLevel += ['where' => ['amount' => [$request->search]]];
            }
        }

        return $this->repo->list($request, $pagination, $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel);
    }
}
