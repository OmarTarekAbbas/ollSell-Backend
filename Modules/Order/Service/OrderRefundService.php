<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Repositories\OrderRefundRepository;
use Modules\Order\Actions\OrderRefund\TotalOrderAction;
use Modules\Order\Http\Resources\Order\OrderRefundResource;
use Modules\Order\Actions\OrderRefund\RefundRequestedAction;
use Modules\Order\Actions\OrderRefund\RefundReplacementAction;
use Modules\Order\Actions\OrderRefund\CreateOrderRefundAction;
use Modules\Order\Actions\OrderRefund\RefundOrderBalanceAction;
use Modules\Order\Actions\OrderRefund\WebhooksShippingOrderRefundAction;
class OrderRefundService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(OrderRefundRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * The index function returns a list of items from the repository with optional pagination and a
     * specified number of items per page.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server. 
     * param pagination The pagination parameter determines whether or not to enable pagination for
     * the list of items. If set to true, the list will be paginated. If set to false, all items will
     * be returned without pagination.
     * param perPage The `` parameter is used to specify the number of items to be displayed
     * per page in the pagination. By default, it is set to 10, but you can change it to any desired
     * value.
     * 
     * return the result of calling the `list` method on the `` object, passing in the
     * ``, ``, and `` arguments.
     */
    public function index(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->repo->list($request, $pagination, $perPage);
    }

    /**
     * The function refunds order items by setting their "is_refund" flag to 1 and creating a new
     * order refund entry.
     *
     * param request The parameter `` is an object that contains the data sent in the HTTP
     * request. It typically includes information such as the request method, headers, and body.
     */
    public function refundOrderItem($request)
    {
        return App(CreateOrderRefundAction::class)->execute($request);
    }

    /**
     * The function "totalOrder" refunds all order items associated with a given order.
     *
     * param request The  parameter is an object that contains the request data sent to the
     * function. It is used to retrieve the order ID from the request.
     *
     * return a boolean value of true.
     */
    public function totalOrder($request)
    {
        return App(TotalOrderAction::class)->execute($request);
    }

    /**
     * The function returns a collection of OrderRefundResource objects based on the provided request.
     *
     * param request The  parameter is an object that contains the data and information needed
     * to process the refund order. It could include things like the user's authentication credentials,
     * the order ID, the reason for the refund, and any other relevant details.
     *
     * return a collection of OrderRefundResource objects.
     */
    public function list($request)
    {
        return OrderRefundResource::collection($this->repo->list($request));
    }

    /**
     * The function updates the status and reason of an order refund and stores it using the
     * OrderStatusRefundService.
     *
     * param request The `` parameter is an instance of the `Illuminate\Http\Request` class. It
     * represents the current HTTP request and contains all the data sent with the request,
     * param id The `id` parameter is the identifier of the order refund that is being requested. It is
     * used to retrieve the specific order refund from the database.
     *
     * return The method is returning the result of the `store` method called on the
     * `OrderStatusRefundService` class.
     */
    public function actionRefundRequested($request, $id)
    {
        return App(RefundRequestedAction::class)->execute($request, $id);
    }

    /**
     * The function updates the status of an order refund to "refund replacement" and stores the
     * updated status in the database.
     *
     * param request The `` parameter is typically an instance of the
     * `Illuminate\Http\Request` class, which represents an incoming HTTP request. It contains
     * information about the request such as the request method, headers, and input data.
     * param id The "id" parameter is the unique identifier of the order refund that needs to be
     * updated.
     *
     * return The code is returning the result of the `store` method of the `OrderStatusRefundService`
     * class.
     */
    public function actionRefundReplacement($request, $id)
    {
        return App(RefundReplacementAction::class)->execute($request, $id);
    }

    /**
     * The function `webhooksShipping` processes webhook data related to shipping and updates the
     * status of an order refund accordingly.
     *
     * param Request request The `` parameter is an instance of the `Illuminate\Http\Request`
     * class, which represents an HTTP request made to the application. It contains information about
     * the request, such as the request method, headers, and payload.
     *
     * return a boolean value. It returns true if the data is successfully saved, and false otherwise.
     */
    public function webhooksShippingOrderRefund($request, $getDataAymakanArray)
    {
        return App(WebhooksShippingOrderRefundAction::class)->execute($request, $getDataAymakanArray);
    }

    /**
     * The function "actionRefundBalance" updates the status of an order refund and updates the wallet
     * balance of a dropshipper.
     *
     * param request The `` parameter is typically an instance of the
     * `Illuminate\Http\Request` class, which represents an incoming HTTP request.
     * param id The "id" parameter is the identifier of the order refund that needs to be processed.
     * It is used to retrieve the specific order refund from the database.
     */
    public function actionRefundBalance($request, $id)
    {
        return App(RefundOrderBalanceAction::class)->execute($request, $id);
    }
}
