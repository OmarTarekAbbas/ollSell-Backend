<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Modules\Order\Entities\PendingOrder;
use  Modules\Order\Entities\PendingOrderItem;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Actions\PendingOrder\SubmitOrderAction;

class PendingToOrderController extends BasicController
{


    public function __construct()
    {
        $this->middleware('auth:dropshipper');
    }


    public function pendingTransfersOrder(Request $request)
    {

        if (!empty($request->input('pending_ids'))) {

            $pendingOrders = PendingOrder::whereIn('id', $request->input('pending_ids'))->get();

            if (count($pendingOrders)) {
                (new SubmitOrderAction(
                    pendingOrders: $pendingOrders
                ))->execute();

                $listPendingOrders = PendingOrder::whereIn('id', $request->input('pending_ids'))->get();

                foreach ($listPendingOrders as $listPendingOrder) {
                    if ($listPendingOrder->invalid == 1) {
                        return $this->unKnowError(trans('orders.Please resolve the issue before sending it to the orders.'));
                    }
                }
                return $this->createResponse([
                    'url' => null,
                ], trans('orders.Success send orders.'));
            } else {
                return $this->unKnowError(trans('orders.Please enters ids Available in the system'));
            }
        }
        return $this->unKnowError(trans('orders.Please send pending ids.'));
    }


    public function pendingTransfersOrderAll(Request $request)
    {
        $pendingOrders = PendingOrder::where('dropshipper_id', Auth::guard('dropshipper')->user()->id)->get();

        if (count($pendingOrders)) {
            (new SubmitOrderAction(
                pendingOrders: $pendingOrders
            ))->execute();

            return $this->createResponse([
                'url' => null,
            ], trans('orders.Success send orders.'));
        } else {

            return $this->apiValidation(trans('orders.He doesn t have  pending orders'));
        }
    }

    public function pendingDeleteAll(Request $request)
    {
        $pendingOrders = PendingOrder::where('dropshipper_id', Auth::guard('dropshipper')->user()->id)->get();
        foreach ($pendingOrders as $row) {
            PendingOrder::where('id', $row->id)->delete();
            PendingOrderItem::where('pending_order_id', $row->id)->delete();
        }
        return $this->createResponse([], trans('orders.Success delete pending orders.'),);
    }

    public function pendingDeleteArray(Request $request)
    {
        if (!empty($request->input('pending_ids'))) {
            $pendingOrders = PendingOrder::whereIn('id', $request->input('pending_ids'))->get();
            foreach ($pendingOrders as $row) {
                PendingOrder::where('id', $row->id)->delete();
                PendingOrderItem::where('pending_order_id', $row->id)->delete();
            }
            return $this->createResponse([], trans('orders.Success delete pending orders.'),);
        }
        return $this->unKnowError(trans('orders.Please send pending ids.'));
    }
}
