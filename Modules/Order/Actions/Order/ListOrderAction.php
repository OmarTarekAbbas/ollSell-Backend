<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Http\Resources\Order\OrderResource;

class ListOrderAction
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
    public function execute(Request $request, $pagination = false, $perPage = 12, $collection = false)
    {
        $moreConditionForFirstLevel = [];

        if ($request->subTotalFrom && $request->subTotalTo) {

            $moreConditionForFirstLevel += ['whereBetween' => ['subTotal' => [$request->subTotalFrom, $request->subTotalTo]]];
        } elseif ($request->subTotalFrom) {

            $moreConditionForFirstLevel += ['where' => ['subTotal' => ['>', $request->subTotalFrom]]];
        } elseif ($request->subTotalTo) {

            $moreConditionForFirstLevel += ['where' => ['subTotal' => ['<', $request->subTotalTo]]];
        }

        if ($request->grandTotalFrom && $request->grandTotalTo) {

            $moreConditionForFirstLevel += ['whereBetween' => ['grandTotal' => [$request->grandTotalFrom, $request->grandTotalTo]]];
        } elseif ($request->grandTotalFrom) {

            $moreConditionForFirstLevel += ['where' => ['grandTotal' => ['>', $request->grandTotalFrom]]];
        } elseif ($request->grandTotalTo) {

            $moreConditionForFirstLevel += ['where' => ['grandTotal' => ['<', $request->grandTotalTo]]];
        }

        if ($request->orderDateFrom && $request->orderDateTo) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->orderDateFrom)->startOfDay(),  Carbon::parse($request->orderDateTo)->endOfDay()]]];
        } elseif ($request->orderDateFrom) {

            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>', Carbon::parse($request->orderDateFrom)->startOfDay()]]];
        } elseif ($request->orderDateTo) {

            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<', Carbon::parse($request->orderDateTo)->endOfDay()]]];
        }

        if ($request->deliveryDateFrom && $request->deliveryDateTo) {

            $moreConditionForFirstLevel += ['whereBetween' => ['deliveryDate' => [Carbon::parse($request->deliveryDateFrom)->startOfDay(), Carbon::parse($request->deliveryDateTo)->endOfDay()]]];
        } elseif ($request->deliveryDateFrom) {

            $moreConditionForFirstLevel += ['where' => ['deliveryDate' => ['>', Carbon::parse($request->deliveryDateFrom)->startOfDay()]]];
        } elseif ($request->deliveryDateTo) {

            $moreConditionForFirstLevel += ['where' => ['deliveryDate' => ['<', Carbon::parse($request->deliveryDateTo)->endOfDay()]]];
        }

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
        
        $data = $this->repo->list($request, $pagination, $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel);
        if ($collection) {
            return  $data;
        }
        return OrderResource::collection($data);
    }
}
