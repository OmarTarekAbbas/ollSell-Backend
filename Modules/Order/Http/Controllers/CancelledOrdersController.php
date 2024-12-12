<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Service\OrderService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Http\Resources\Order\Admin\OrderResource;

class CancelledOrdersController extends BasicController
{
    protected $service;
    protected $attemptsLogService;

    public function __construct(OrderService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_order')->only('index');
        $this->service = $Service;
    }

    public function index(Request $request)
    {
        // $orders = $this->service->index($request);
        $canUpdateOrder = user()->can('update_order');
        $canViewAll = user()->can('view_all_order');

        $haveBothMajorPermissions = $canUpdateOrder && $canViewAll;

        return $this->getDashboardView('order::cancel.index', [
            'canUpdateOrder' => $canUpdateOrder,
            'canViewAll' => $canViewAll,
            'haveBothMajorPermissions' => $haveBothMajorPermissions,
        ]);
    }

    public function orders(Request $request)
    {
        $orders = $this->service->enhancedList(request: $request, pagination: true, perPage: request('perPage') ?? 10);

        $ordersArray = $orders->toArray();

        return response()->json([
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $ordersArray['current_page'],
                'from' => $ordersArray['from'],
                'last_page' => $ordersArray['last_page'],
                'per_page' => $ordersArray['per_page'],
                'to' => $ordersArray['to'],
                'total' => $ordersArray['total'],
            ],
        ]);
    }
}
