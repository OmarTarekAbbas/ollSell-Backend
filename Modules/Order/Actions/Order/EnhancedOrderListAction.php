<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\OrderEnum;
use Modules\Acl\Entities\Dropshipper;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Repositories\OrderRepository;

class EnhancedOrderListAction
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
    public function execute(Request $request, $pagination, $perPage)
    {
        $moreConditionForFirstLevel = [];
        $user = user();
        if($user instanceof Dropshipper)
        {
            $dropshipper = ['dropshipper_id' => $user->id];
            $request->merge($dropshipper);
            $moreConditionForFirstLevel = ['whereCustom' => [
                'orWhere' => [
                    ['dropshipper_id' => ['=', $user->id]]
                ]
            ]];
        }else
        {
            if(!$user->can('view_all_order'))
            {
                $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                    'whereCustom' => [
                        'where' => [
                            ['operator_id' => ['=', $user->id]],
                            ['operator_id' => ['=', null]],
                        ],
                    ],
                ]);
            }
        }
        $sortBy = $request->get('sort_by');
        $sortOrder = $request->get('sort_order');
        $orderBy = ['column' => 'id', 'order' => 'desc'];
        if($request['orderByCount'])
        {
            $orderBy = ['multiple' => 1];
            foreach($sortBy as $index => $column)
            {
                $orderBy['orderBy'][] = ['column' => $column, 'order' => $sortOrder[$index]];
            }
        }else
        {
            $sortBy = $request->get('sort_by', 'id');
            $sortOrder = $request->get('sort_order', 'desc');
        }
        if($request->validated_by)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['validated_by' => ['=', $request->validated_by]],
            ]);
        }
        if($request->payment_method)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['paymentMethod' => ['=', $request->payment_method]],
            ]);
        }
        if($request->confirmationStatus !== null)
        {
            if($request->confirmationStatus)
            {
                // Confirmed Orders selected
                $moreConditionForFirstLevel += [
                    'whereNotIn' => ['status_id' => [
                        OrderEnum::NEW_STATUS,
                        OrderEnum::PENDING_STATUS,
                        OrderEnum::PAY_PENDING_STATUS,
                    ]],
                ];
            }else
            {
                // Unconfirmed Orders selected
                $moreConditionForFirstLevel += [
                    'whereIn' => ['status_id' => [
                        OrderEnum::NEW_STATUS,
                        OrderEnum::PENDING_STATUS,
                        OrderEnum::PAY_PENDING_STATUS,
                    ]],
                ];
            }
        }
        if($request->status_id && $request->status_id == OrderStatusEnum::PENDING_STATUS)
        {
            $moreConditionForFirstLevel += ['whereNull' => ['validated']];
        }
        if($request->status_id == 'validated')
        {
            $moreConditionForFirstLevel += ['whereNotNull' => ['validated']];
            $moreConditionForFirstLevel += ['whereNull' => ['tracking_number']];
            $request->merge(['status_id' => OrderStatusEnum::PENDING_STATUS]);
        }
        if($request->has('ids') && $request->ids != '')
        {
            $ids = explode(',', $request->ids);
            $moreConditionForFirstLevel += ['whereIn' => ['id' => $ids]];
        }
        if($request->subTotalFrom && $request->subTotalTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'whereBetween' => ['subTotal' => [$request->subTotalFrom, $request->subTotalTo]],
            ]);
        }elseif($request->subTotalFrom)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['subTotal' => ['>', $request->subTotalFrom]],
            ]);
        }elseif($request->subTotalTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['subTotal' => ['<', $request->subTotalTo]],
            ]);
        }
        if($request->grandTotalFrom && $request->grandTotalTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'whereBetween' => ['grandTotal' => [$request->grandTotalFrom, $request->grandTotalTo]],
            ]);
        }elseif($request->grandTotalFrom)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['grandTotal' => ['>', $request->grandTotalFrom]],
            ]);
        }elseif($request->grandTotalTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['grandTotal' => ['<', $request->grandTotalTo]],
            ]);
        }
        if($request->orderDateFrom && $request->orderDateTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'whereBetween' => ['created_at' => [
                    Carbon::parse($request->orderDateFrom)->startOfDay(),
                    Carbon::parse($request->orderDateTo)->endOfDay(),
                ]],
            ]);
        }elseif($request->orderDateFrom)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['created_at' => ['>', Carbon::parse($request->orderDateFrom)->startOfDay()]],
            ]);
        }elseif($request->orderDateTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['created_at' => ['<', Carbon::parse($request->orderDateTo)->endOfDay()]],
            ]);
        }
        if($request->deliveryDateFrom && $request->deliveryDateTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'whereBetween' => ['deliveryDate' => [
                    Carbon::parse($request->deliveryDateFrom)->startOfDay(),
                    Carbon::parse($request->deliveryDateTo)->endOfDay(),
                ]],
            ]);
        }elseif($request->deliveryDateFrom)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['deliveryDate' => ['>', Carbon::parse($request->deliveryDateFrom)->startOfDay()]],
            ]);
        }elseif($request->deliveryDateTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['deliveryDate' => ['<', Carbon::parse($request->deliveryDateTo)->endOfDay()]],
            ]);
        }
        if($request->assignedDateFrom && $request->assignedDateTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'whereBetween' => ['assigned_at' => [
                    Carbon::parse($request->assignedDateFrom)->startOfDay(),
                    Carbon::parse($request->assignedDateTo)->endOfDay(),
                ]],
            ]);
        }elseif($request->assignedDateFrom)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['assigned_at' => ['>', Carbon::parse($request->assignedDateFrom)->startOfDay()]],
            ]);
        }elseif($request->assignedDateTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['assigned_at' => ['<', Carbon::parse($request->assignedDateTo)->endOfDay()]],
            ]);
        }
        if($request->validatedAtFrom && $request->validatedAtTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'whereBetween' => ['validated' => [
                    Carbon::parse($request->validatedAtFrom)->startOfDay(),
                    Carbon::parse($request->validatedAtTo)->endOfDay(),
                ]],
            ]);
        }elseif($request->validatedAtFrom)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['validated' => ['>', Carbon::parse($request->validatedAtFrom)->startOfDay()]],
            ]);
        }elseif($request->validatedAtTo)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['validated' => ['<', Carbon::parse($request->validatedAtTo)->endOfDay()]],
            ]);
        }
        if($request->subStatusId)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' => ['sub_status_id' => ['=', $request->subStatusId]],
            ]);
        }
        if($request->is_duplicated)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['is_duplicated' => ['=', 1]]]
            );
        }
        if($request->isFailedPay)
        {
            $moreConditionForFirstLevel = array_merge_recursive($moreConditionForFirstLevel, [
                'where' =>
                    ['status_click_payment' => ['!=', ClickPayEnum::Pay]],
            ]);
        }
        if($request->assignedToCurrentUser)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['operator_id' => ['=', user()->id]]]
            );
        }
        if($request->unassignedOrders)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['operator_id' => ['=', null]]]
            );
        }
        if($request->ollopsFailedOrders)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['ollops_confirmation_status' => ['=', 'not_validated']]]
            );
        }
        if($request->ollopsConfirmationStatus)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['ollops_confirmation_status' => ['=', $request->ollopsConfirmationStatus]]]
            );
        }
        if($request->ollopsAttempts)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['ollops_attempts' => ['=', $request->ollopsAttempts]]]
            );
        }
        if($request->ollopsCancelledOrders)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['ollops_confirmation_status' => ['=', 'cancelled']]]
            );
        }
        // this still needs to be refactored
        if($request->operator)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['operator_id' => ['=', $request->operator]]]
            );
        }
        // remark_id
        if($request->remark_id)
        {
            $moreConditionForFirstLevel = array_merge_recursive(
                $moreConditionForFirstLevel,
                ['where' => ['remark_id' => ['=', $request->remark_id]]]
            );
        }
        if($request->search)
        {
            if(is_numeric($request->search))
            {
                if(strlen($request->search) > 8)
                {
                    $moreConditionForFirstLevel = array_merge_recursive(
                        $moreConditionForFirstLevel,
                        ['where' => ['customerPhone' => ['like', '%' . $request->search . '%']]]
                    );
                }else
                {
                    $moreConditionForFirstLevel = array_merge_recursive(
                        $moreConditionForFirstLevel,
                        ['where' => ['id' => [$request->search]]]
                    );
                }
            }else
            {
                $moreConditionForFirstLevel = array_merge_recursive(
                    $moreConditionForFirstLevel,
                    ['where' => ['customerName' => ['LIKE', '%' . $request->search . '%']]]
                );
                $moreConditionForFirstLevel = array_merge_recursive(
                    $moreConditionForFirstLevel,
                    ['orWhere' => ['tracking_number' => ['=', $request->search]]]
                );
                $moreConditionForFirstLevel = array_merge_recursive(
                    $moreConditionForFirstLevel,
                    ['orWhere' => ['token' => ['=', $request->search]]]
                );
            }
        }
        return $this->repo->list(
            $request,
            $pagination,
            $perPage,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            orderBy: $orderBy
        );
    }
}
