<?php

namespace Modules\Order\Http\Controllers;

use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Service\FollowUpService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Http\Requests\FollowUp\SaveFollowUpRequest;

class FollowUpController extends BasicController
{
    protected $service;

    public function __construct(FollowUpService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $ordersWithFollowUp = $this->service->getOrdersWithFollowup();

        return $this->getDashboardView('order::followUp.index', compact('ordersWithFollowUp'));
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order->follow_order) {

            if ($order->status_id == OrderEnum::COMPLETED_STATUS) {
                return back()->with(['message' => 'Order already completed']);
            }

            $order->update([
                'follow_order' => now()
            ]);
        }

        $order->paymentMethodData = PaymentMethodList::list()->where('id', $order->paymentMethod)->first();

        return $this->getDashboardView('order::followUp.show', compact('order'));
    }

    public function getFollowUps($id)
    {
        $followUps = $this->service->getFollowUps($id);

        return response()->json($followUps);
    }

    public function save(SaveFollowUpRequest $request, $id)
    {
        $followUp = $this->service->saveFollowUp($request->all(), $id);

        return response()->json(['message' => 'Follow-up activity created successfully', 'follow_up' => $followUp], 201);
    }
}
