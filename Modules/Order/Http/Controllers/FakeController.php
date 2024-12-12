<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Entities\Fake;
use Modules\Order\Entities\Order;
use Modules\Order\Service\FakeService;


/**
 * OrderController handles the management of orders, including listing, creating, updating,
 * and displaying order details, as well as managing related entities like invoices and statuses.
 */
class FakeController extends BasicController
{
    protected $service;
    /**
     * OrderController constructor initializes required services and sets middleware for authentication
     * and permissions for various order-related actions.
     *
     */
    public function __construct(FakeService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->service = $Service;
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function index(Request $request)
    {
        $data = $this->service->findBy($request, pagination: true, perPage: $this->perPage());
        return $this->getDashboardView('order::fake.index', ['data' => $data]);
    }

    public function scan(Request $request)
    {
        Fake::truncate();
        $orders = Order::groupBy('customerPhone')->pluck('customerPhone');

        foreach ($orders as $order) {
            app('Modules\Order\Actions\Order\FakeNumberOrderAction')->execute($order);
        }
        return redirect()->back();
    }
}