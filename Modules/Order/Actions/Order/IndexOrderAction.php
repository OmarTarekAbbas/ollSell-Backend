<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Repositories\OrderRepository;

class IndexOrderAction
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
    public function execute($request)
    {
        $text = $request['search']['value'] ?? "";
        $parts = explode(",", $text);
        $result = [];
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        try
        {
            foreach($parts as $part)
            {
                // Split each pair into key and value
                [$key, $value] = explode("_", $part);
                // Remove any whitespace from the key
                $key = trim($key);
                // Add the key-value pair to the result array
                $result[$key] = $value;
            }
            if($result['fromSubTotal'] && $result['toSubTotal'])
            {
                $moreConditionForFirstLevel += ['whereBetween' => ['subTotal' => [$result['fromSubTotal'], $result['toSubTotal']]]];
            }else
            {
                if($result['fromSubTotal']) $moreConditionForFirstLevel += ['where' => ['subTotal' => ['>=', $result['fromSubTotal']]]];
                if($result['toSubTotal']) $moreConditionForFirstLevel += ['where' => ['subTotal' => ['<=', $result['toSubTotal']]]];
            }
            if($result['fromGrandTotla'] && $result['toGrandTotla'])
            {
                $moreConditionForFirstLevel += ['whereBetween' => ['grandTotal' => [$result['fromGrandTotla'], $result['toGrandTotla']]]];
            }else
            {
                if($result['fromGrandTotla']) $moreConditionForFirstLevel += ['where' => ['grandTotal' => ['>=', $result['fromGrandTotla']]]];
                if($result['toGrandTotla']) $moreConditionForFirstLevel += ['where' => ['grandTotal' => ['<=', $result['toGrandTotla']]]];
            }
            if($result['fromDate'] && $result['toDate'])
            {
                // Add 1 day to the "to" date to include records on that day
                $toDate = date('Y-m-d', strtotime($result['toDate'] . ' +1 day'));
                $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [$result['fromDate'], $toDate]]];
            }else
            {
                if($result['fromDate']) $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', $result['fromDate']]]];
                if($result['toDate'])
                {
                    // Add 1 day to the "to" date to include records on that day
                    $toDate = date('Y-m-d', strtotime($result['toDate'] . ' +1 day'));
                    $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', $toDate]]];
                }
            }
            if($result['fromDeliveryDate'] && $result['toDeliveryDate'])
            {
                // Add 1 day to the "to" delivery date to include records on that day
                $toDeliveryDate = date('Y-m-d', strtotime($result['toDeliveryDate'] . ' +1 day'));
                $moreConditionForFirstLevel += ['whereBetween' => ['deliveryDate' => [$result['fromDeliveryDate'], $toDeliveryDate]]];
            }else
            {
                if($result['fromDeliveryDate']) $moreConditionForFirstLevel += ['where' => ['deliveryDate' => ['>=', $result['fromDeliveryDate']]]];
                if($result['toDeliveryDate'])
                {
                    // Add 1 day to the "to" delivery date to include records on that day
                    $toDeliveryDate = date('Y-m-d', strtotime($result['toDeliveryDate'] . ' +1 day'));
                    $moreConditionForFirstLevel += ['where' => ['deliveryDate' => ['<=', $toDeliveryDate]]];
                }
            }

            if ($result['statusId'] && is_numeric($result['statusId'])) {
                if ($result['statusId'] == 2) {

                    $statusId = [OrderEnum::PENDING_STATUS, OrderEnum::NEW_STATUS];
                    $moreConditionForFirstLevel += ['whereIn' => ['status_id' => $statusId]];
                }else
                {
                    $moreConditionForFirstLevel += ['where' => ['status_id' => [$result['statusId']]]];
                }
            }
            if($result['paymentMethod'] && is_numeric($result['paymentMethod']))
            {
                $moreConditionForFirstLevel += ['where' => ['paymentMethod' => [$result['paymentMethod']]]];
            }
            if($result['search'] && strlen($result['search']) < 100)
            {
                $searchValue = $result['search'];
                if(is_numeric($searchValue))
                {
                    if(strlen($searchValue) > 6)
                    {
                        $moreConditionForFirstLevel += ['where' => ['customerPhone' => [$searchValue]]];
                    }else
                    {
                        $moreConditionForFirstLevel += ['where' => ['id' => [$searchValue]]];
                    }
                }else
                {
                    $moreConditionForFirstLevel += ['where' => ['customerName' => ['LIKE', '%' . $searchValue . '%']]];
                    $recursiveRel += ['dropshipper' => ['type' => 'orWhereHas', 'orWhere' => [
                        'email' => ['orWhere' => $searchValue],
                        'store_name' => ['orWhere' => $searchValue],
                        'first_name' => ['orWhere' => $searchValue],
                        'second_name' => ['orWhere' => $searchValue],
                        'email' => ['orWhere' => $searchValue],
                        'phone' => ['orWhere' => $searchValue],
                        'bankAccountName' => ['orWhere' => $searchValue],
                    ]]];
                }
            }
        }catch(\Throwable $th)
        {
        }
        if(isset($request['is_report']) && $request['is_report'])
        {
            if($request['fromDate'] && $request['toDate'])
            {
                $moreConditionForFirstLevel += [ 'whereBetween' => ['created_at' => [Carbon::parse($request['fromDate'])
                ->startOfDay(), Carbon::parse($request['toDate'])->endOfDay()]]];
                $recursiveRel = [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'recursive' => [
                            'product' => [
                                'type' => 'whereHas',
                            ]
                        ],
                    ]
                ];

                if(!(isset($request['product_id']) && !empty($request['product_id'])))
                {
                    $recursiveRel['orderItems']['recursive']['product'][]['whereBetween'] = ['created_at' => [Carbon::parse($request['fromDate'])
                        ->startOfDay(), Carbon::parse($request['toDate'])->endOfDay()]];

                }
            }elseif($request['fromDate'])
            {
                $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request['fromDate'])->endOfDay()]]];
                $recursiveRel = [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'recursive' => [
                            'product' => [
                                'type' => 'whereHas',
                            ]
                        ],
                    ]
                ];
                if(!(isset($request['product_id']) && !empty($request['product_id'])))
                {
                    $recursiveRel['orderItems']['recursive']['product'][]['where'] =  ['created_at' => ['>=', Carbon::parse($request['fromDate'])->startOfDay()]];
                }
            }elseif($request['toDate'])
            {
                $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request['toDate'])->endOfDay()]]];
                $recursiveRel = [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'recursive' => [
                            'product' => [
                                'type' => 'whereHas',
                            ]
                        ],
                    ]
                ];
                if(!(isset($request['product_id']) && !empty($request['product_id'])))
                {
                    $recursiveRel['orderItems']['recursive']['product']['where'] =  ['created_at' => ['<=', Carbon::parse($request['toDate'])->endOfDay()]];
                }
            }
            if(isset($request['supplier_id']) && !empty($request['supplier_id']))
            {
                $recursiveRel['orderItems']['recursive']['product']['whereIn'][] = ['supplier_id' => $request['supplier_id']];
            }
            if(isset($request['product_id']) && !empty($request['product_id']))
            {
                $recursiveRel['orderItems']['where'] = ['product_id' => $request['product_id']];
            }
            if(isset($request['warehouse_id']) && !empty($request['warehouse_id']))
            {
                $recursiveRel['orderItems']['recursive']['product']['whereIn'][] = ['warehouse_id' => $request['warehouse_id']];
            }
            if(isset($request['dropshipper_id']) && !empty($request['dropshipper_id']))
            {
                $moreConditionForFirstLevel += ['whereIn' => ['dropshipper_id' => $request['dropshipper_id']]];
            }
        }
        return $this->repo->all($request->all(), withRelations: ['dropshipper', 'status' => ['translation']],
            moreConditionForFirstLevel: $moreConditionForFirstLevel, isDatatable: true,
            orderBy: ['column' => 'id', 'order' => 'desc'], recursiveRel: $recursiveRel);
    }
}
