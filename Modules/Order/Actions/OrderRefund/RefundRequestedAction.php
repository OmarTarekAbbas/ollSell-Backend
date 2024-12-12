<?php

namespace Modules\Order\Actions\OrderRefund;

use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderRefund;
use Modules\Order\Repositories\OrderRefundRepository;
use Modules\Order\Service\OrderStatusRefundService;

class RefundRequestedAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRefundRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an orderRefund by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the orderRefund data.
     * 
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     * 
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($request, $id)
    {
        $orderRefund = OrderRefund::find($id);

        if ($request->action == 'refundApproved') {
            $orderRefund->status_id = OrderEnum::REFUND_PROGRESSING_STATUS;
        } else {
            $orderRefund->status_id = OrderEnum::REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS;
            $orderRefund->reason =  $request->reason ?? null;
        }

        if ($orderRefund->save()) {
            
            return app()->make(OrderStatusRefundService::class)->store($orderRefund);
        }
    }
}
