<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Entities\OrderSchedule;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Entities\Order;

class OrderScheduleController extends BasicController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function save(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'scheduled_date' => ['required', 'date'],
        ]);

        // Save the scheduled date
        $orderSchedule = new OrderSchedule();
        $orderSchedule->user_id = user()->id;
        $orderSchedule->order_id = $id;
        $orderSchedule->scheduled_date = $request->scheduled_date;
        $orderSchedule->save();

        return response()->json([
            'scheduled_date' => $orderSchedule->scheduled_date,
        ]);
    }

    public function markAsSatisfied(Request $request, $id)
    {
        $order = Order::find($id);
        $unsatisfiedSchedule  = $order->firstUnsatisfiedSchedule;

        if ($unsatisfiedSchedule) {
            $unsatisfiedSchedule->update(['satisfied' => true]);

            return response()->json(['message' => 'Order schedule marked as satisfied'], 200);
        }

        return response()->json(['error' => 'No unsatisfied schedule found for this order'], 404);
    }

}
