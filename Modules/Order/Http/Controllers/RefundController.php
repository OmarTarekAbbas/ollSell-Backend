<?php

namespace Modules\Order\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Service\OrderService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Order\Service\OrderRefundService;
use Modules\Basic\Http\Controllers\BasicController;

class RefundController extends BasicController
{
    protected $service;
    protected $orderRefundService;

    /**
     * The function is a constructor that initializes the OrderService and OrderRefundService objects
     * and sets middleware for authentication and admin access.
     * 
     * param OrderService Service The  parameter is an instance of the OrderService class. It
     * is used to perform operations related to orders, such as creating, updating, and deleting
     * orders.
     * param OrderRefundService orderRefundService The `` parameter is an instance
     * of the `OrderRefundService` class. It is used to handle operations related to order refunds,
     * such as processing refunds, updating refund status, etc.
     */
    public function __construct(OrderService $Service, OrderRefundService $orderRefundService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->service = $Service;
        $this->orderRefundService = $orderRefundService;
    }

    /**
     * The index function retrieves refund data and returns a view with the refunds for the order
     * dashboard.
     * 
     * param Request request The "request" parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the current request, such as the
     * request method, headers, and input data.
     * 
     * return the dashboard view 'order::refund.index' with the variable 'refunds' compacted.
     */
    public function index(Request $request)
    {
        $refunds = $this->service->refundIndex(request: $request, pagination: true);

        return $this->getDashboardView('order::refund.index', compact('refunds'));
    }

    /**
     * The function retrieves a refund using the provided ID and returns a view with the refund data.
     * 
     * param id The parameter "id" is the identifier of the refund that you want to retrieve and
     * display. It is used to fetch the refund details from the service.
     * 
     * return a view called 'order::refund.show' with the variable 'refund' passed to it.
     */
    public function show($id)
    {
        $refund = $this->service->showRefund($id);

        return $this->getDashboardView('order::refund.show', compact('refund'));
    }

    /**
     * This PHP function calls the "actionRefundRequested" method of a service class and then returns
     * back to the previous page.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and input data.
     * param id The  parameter is used to identify the specific item or resource that the action is
     * being performed on. It is typically used to retrieve the relevant data from the database or
     * perform any necessary operations related to that specific item.
     * 
     * return the result of the `back()` function.
     */
    public function action(Request $request, $id)
    {
        $this->service->actionRefundRequested($request, $id);

        return back();
    }

    /**
     * The function "startShipping" calls the "actionRefundReplacement" method of the "service" object
     * with the given request and ID parameters, and then returns back to the previous page.
     * 
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request.
     * param id The  parameter is the identifier of the item that needs to be shipped. It is used
     * to identify the specific item that needs to be processed for shipping.
     * 
     * return the user back to the previous page.
     */
    public function startShipping(Request $request, $id)
    {
        $this->service->actionRefundReplacement($request, $id);

        return back();
    }

    /**
     * The function refunds the balance for a specific order.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * contains the data sent by the client in the HTTP request.
     * param id The id parameter is used to identify the specific order for which the balance is being
     * refunded.
     * 
     * return the user back to the previous page.
     */
    public function refundBalance(Request $request, $id)
    {
        $this->orderRefundService->actionRefundBalance($request, $id);

        return back();
    }
}
