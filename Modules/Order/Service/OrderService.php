<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Acl\Service\DropshipperService;
use Modules\Acl\Service\UserService;
use Modules\Finance\Actions\Transaction\PayByWalleteAutomaticAction;
use Modules\Finance\Service\TransactionService;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Order\Actions\Order\CancelledIntegrationOrderAction;
use Modules\Order\Actions\Order\TrackOrderAction;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\OrderStatusAymakan;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use MagedAhmad\Aymakan\Facades\Aymakan;
use Modules\Basic\Service\BasicService;
use Modules\Report\Service\ReportService;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Actions\Order\AdminUpdateOrderAction;
use Modules\Order\Actions\Order\ApprovedOrderAction;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Actions\Order\ListOrderAction;
use Modules\Order\Actions\Order\IndexOrderAction;
use Modules\Order\Actions\Order\CancelOrderAction;
use Modules\Order\Actions\Order\CheckDuplicatedOrderAction;
use Modules\Order\Actions\Order\CityByAymakanOrderAction;
use Modules\Order\Actions\Order\CreateOrderAction;
use Modules\Order\Actions\Order\EnhancedOrderListAction;
use Modules\Order\Actions\Order\GetExportedOrdersAction;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;
use Modules\Order\Actions\Order\UpdateOrderAction;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Order\Actions\Order\PayWalletOrderAction;
use Modules\Order\Actions\Order\RefusedOrderAction;
use Modules\Order\Actions\Order\UpdateAymakanStatus;
use Modules\Order\Http\Resources\Order\Admin\OrderResource;
use Modules\Order\Actions\Order\WebhooksShippingOrderAction;
use Modules\Order\Actions\Order\RefundReplacementOrderAction;
use Modules\Order\Actions\Order\ExportEnhancedOrderListAction;
use Modules\Order\Actions\Order\WebhooksShippingDashboardOrderAction;
use Modules\Order\Actions\Order\WebhooksShippingTestHendOrderAction;
use Modules\Order\Repositories\PendingOrderRepository;

class OrderService extends BasicService
{
    protected $repo, $statusService, $orderRefundService, $refundMessageService, $listOrderAction, $pendingOrderRepository, $userService,$productService,$dropshipperService;
    public $transactionService;

    /**
     * The function is a constructor that initializes the class with dependencies.
     *
     * param OrderRepository repository The `` parameter is an instance of the
     * `OrderRepository` class.
     * param StatusService statusService The `statusService` parameter is an instance of the
     * `StatusService` class.
     * param OrderRefundService orderRefundService The `orderRefundService` parameter is an instance
     * of the `OrderRefundService`
     * param RefundMessageService refundMessageService The `refundMessageService` parameter is an
     * instance of the `RefundMessageService`
     */
    public function __construct(
        OrderRepository $repository,
        StatusService $statusService,
        OrderRefundService $orderRefundService,
        RefundMessageService $refundMessageService,
        ListOrderAction $listOrderAction,
        PendingOrderRepository $pendingOrderRepository,
        TransactionService $transactionService,
        UserService $userService,
        ProductService $productService,
        DropshipperService $dropshipperService
    ) {
        $this->repo = $repository;
        $this->statusService = $statusService;
        $this->orderRefundService = $orderRefundService;
        $this->refundMessageService = $refundMessageService;
        $this->listOrderAction = $listOrderAction;
        $this->pendingOrderRepository = $pendingOrderRepository;
        $this->transactionService = $transactionService;
        $this->userService = $userService;
        $this->productService = $productService;
        $this->dropshipperService = $dropshipperService;
    }

    /**
     * This function is used to find records based on certain conditions,
     *
     * param Request request The `` parameter is an instance of the `Request` class, which is
     * typically used to retrieve data from the HTTP request.
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query.
     * param orderBy The "orderBy" parameter is used to specify the sorting order of the results.
     * param pagination The "pagination" parameter determines whether the results should be paginated
     * or not.
     * param perPage The "perPage" parameter determines the number of records to be displayed per page
     * when using pagination.
     * param get The "get" parameter is used to specify the columns that you want to retrieve from the
     * database.
     * param withRelations An array of relations to eager load with the query. These relations will be
     * loaded along with the main model to reduce the number of database queries.
     * param latest The "latest" parameter is used to specify the column to order the results by in
     * descending order.
     * param limit The "limit" parameter is used to specify the maximum number of records to retrieve
     * from the database.
     *
     * return the result of calling the `findBy` method on the `` object.
     */
    public function findBy(
        Request $request,
        $moreConditionForFirstLevel = [],
        $orderBy = [],
        $pagination = false,
        $perPage = 10,
        $get = '',
        $withRelations = [],
        $latest = '',
        $limit = null,
        $recursiveRel = []
    ) {
        return $this->repo->findBy(
            $request,
            $moreConditionForFirstLevel,
            $orderBy,
            $pagination,
            $perPage,
            $get,
            $withRelations,
            latest: $latest,
            limit: $limit,
            recursiveRel: $recursiveRel
        );
    }

    /**
     * The store function executes the CreateOrderAction class with the given request.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request.
     *
     * return The `store` function is returning the result of executing the `execute` method of the
     * `CreateOrderAction` class with the `` parameter.
     */
    public function store(Request $request, $now = false, $onlinePayment = false)
    {
        // check if order is duplicated
        $duplicatedOrders = (new CheckDuplicatedOrderAction(
            request: $request
        ))->execute();
        $request->merge([...$duplicatedOrders]);
        $order = App(CreateOrderAction::class)->execute($request);
        if ($now == false && Auth::guard('dropshipper')->check()) {
            user()->carts()->delete();
        }
        
        if (!$onlinePayment && $order->dropshipper->DropshipperOptionCheck('automatic_pay_from_profit_at_wallet') && $order->paymentMethod == PaymentEnum::WALLET_METHOD_ID) {
            $check = app(PayByWalleteAutomaticAction::class)->execute($order);
            if ($check) {
                $this->payWallet($order);
            }
        }
        return $order;
    }

    public function storeImport($request)
    {
        return $this->repo->save($request, $id = null);
    }

    /**
     * The store function executes the storeFromSaas class with the given request.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request.
     *
     * return The `store` function is returning the result of executing the `execute` method of the
     * `storeFromSaas` class with the `` parameter.
     */
    public function storeFromSaas(Request $request)
    {
        return App(CreateOrderAction::class)->execute($request);
    }

    /**
     * The function updates an order using the UpdateOrderAction class.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains all the data and information about the current HTTP request.
     * param id The  parameter is an optional parameter that represents the ID of the order that
     * needs to be updated.
     *
     * return The code is returning the result of executing the `execute` method of the
     * `UpdateOrderAction` class with the given `` and `` parameters.
     */
    public function update(Request $request, $id = null)
    {
        return App(UpdateOrderAction::class)->execute($request, $id);
    }

    public function adminUpdate(Request $request, $order)
    {
        return (new AdminUpdateOrderAction(
            order: $order,
            request: $request
        ))->execute();
    }


    /**
     * The function cancels an order by executing the CancelOrderAction class with the given request
     * and order ID.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains all the data and information about the current HTTP request.
     *
     * param id The  parameter is the identifier of the order that needs to be cancelled.
     *
     * return The code is returning the result of executing the `execute` method of the
     * `CancelOrderAction` class with the provided `` and `` parameters.
     */
    public function cancel(Request $request, $id)
    {
        return App(CancelOrderAction::class)->execute($request, $id);
    }

    /**
     * It returns a collection of OrderResource
     *
     * param Request request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * return A collection of OrderResource
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return App(ListOrderAction::class)->execute($request, $pagination, $perPage);
    }

    public function enhancedList(Request $request, $pagination = false, $perPage = 10)
    {
        return App(EnhancedOrderListAction::class)->execute($request, $pagination, $perPage);
    }

    public function exportEnhancedList($request, $pagination = true, $perPage = 10)
    {
        return App(ExportEnhancedOrderListAction::class)->execute($request, $pagination, $perPage);
    }

    /**
     * The show function returns the result of finding a record with
     *
     * param id The parameter "id" is the identifier of the item that you want to retrieve from the
     * repository.
     *
     * return the result of the findOne() method from the repository class for the given . If the
     * result is not found, it will return null.
     */
    public function show($id)
    {
        return $this->repo->findOne($id) ?? null;
    }

    /**
     * This is a PHP function that takes in a request and pagination parameters, and applies various
     * conditions to filter and search for data before returning a list of results.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information such as input data, headers, and cookies.
     * param pagination A boolean value indicating whether to enable pagination or not. If set to
     * true, the results will be paginated based on the  parameter.
     * param perPage The number of records to be displayed per page in the paginated result.
     *
     * return the result of calling the `list` method on the `->repo` object with the provided
     * arguments and the `` array as an additional named argument.
     */
    public function index(Request $request)
    {
        return App(IndexOrderAction::class)->execute($request);
    }

    public function export()
    {
        return (new GetExportedOrdersAction())->execute();
    }

    /**
     * The function "approved" executes the "execute" method of the "ApprovedOrderAction" class with
     * the given request and id.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the application.
     *
     * param id The  parameter is the identifier of the order that needs to be approved. It is used
     * to identify the specific order that needs to be processed.
     *
     * return the result of executing the `execute` method of the `ApprovedOrderAction` class with the
     * given `` and `` parameters.
     */
    public function approved(Request $request, $id)
    {
        return App(ApprovedOrderAction::class)->execute($request, $id);
    }

    public function startShipping($request)
    {
        $order = $this->repo->findOne($request->id);
        App(CreateShipmentOrderAction::class)->execute($order);
        return $order->refresh();
    }

    /**
     * This PHP function updates the status_id of an order to "refused" and saves the changes.
     *
     * param Request request an instance of the Request class, which contains the HTTP request data
     * sent by the client.
     * param id  is an optional parameter that represents the ID of the order that needs to be
     * refused. If it is not provided, a new order will be created with the refused status_id.
     *
     * return a boolean value - `true` if the data is saved successfully, and `false` if it is not.
     */
    public function refused(Request $request, $id)
    {
        return App(RefusedOrderAction::class)->execute($request, $id);
    }

    /**
     * This PHP function retrieves a list of statuses from a database table.
     *
     * return The function `statusList()` is returning a collection of all the rows from the `status_id`
     * table in the database.
     */
    public function statusList($request)
    {
        return $this->statusService->findBy($request);
    }

    /**
     * This PHP function returns the payment method text based on the provided payment method ID.
     *
     * param paymentMethod The ID of a payment method.
     *
     * return the payment method text associated with the given payment method ID. It is using the
     * PaymentMethodList class to retrieve the list of payment methods and then filtering it to find
     * the payment method with the given ID. The first matching payment method is returned.
     */
    public function setPaymentMethodText($paymentMethod)
    {
        return PaymentMethodList::list()->where('id', $paymentMethod)->first();
    }

    /**
     * This PHP function tracks a shipment using the provided ID.
     *
     * param Request request  is an instance of the Request class, which is used to handle HTTP
     * requests in Laravel.
     *
     * param id  is a parameter that is passed to the function and is set to null by default. It is
     * used to identify the specific data that needs to be tracked. If a valid  is provided, the
     * function will retrieve the data associated with that id and track its shipment.
     *
     * return If `` exists, the function will return the data obtained from the `trackShipment`
     * method. Otherwise, it will return `false`.
     */
    public function track(Request $request, $id = null)
    {
        return App(TrackOrderAction::class)->execute($request, $id);
    }

    /**
     * This function returns a list of webhooks using the Aymakan API.
     *
     * param Request request  is an object of the Request class in Laravel framework. It
     * contains the data and information about the current HTTP request made to the server.
     *
     * return The function `webhooksList` is returning the result of the `getWebHook` method of the
     * `Aymakan` class.
     */
    public function webhooksList(Request $request)
    {
        return Aymakan::getWebHook();
    }

    /**
     * The function "cityByAymakan" in PHP takes a request object as a parameter and returns the result
     * of calling the "cityByAymakan" method of the Aymakan class with the request object.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to handle HTTP requests in Laravel.
     * return the result of calling the `cityByAymakan` method of the `Aymakan` class, passing in the
     * `` object as an argument.
     */
    public function cityByAymakan(Request $request)
    {
        return App(CityByAymakanOrderAction::class)->execute($request);
    }

    /**
     * The function `updateAymakanStatus` calls the `execute` method of the `UpdateAymakanStatus` class
     * with the provided request.
     *
     * @param Request request The `updateAymakanStatus` function is a Laravel controller method that
     * takes a `Request` object as a parameter. The `Request` object contains the data sent by the
     * client in the HTTP request.
     * @return The `updateAymakanStatus` function is returning the result of executing the `execute`
     *             method of the `UpdateAymakanStatus` class with the provided `` object.
     */
    public function updateAymakanStatus(Request $request)
    {
        return App(UpdateAymakanStatus::class)->execute($request);
    }

    /**
     * This function updates the status_id of an order based on the data received from a webhook request
     * and saves it to the database.
     *
     * param Request request  is an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request.
     *
     * return a boolean value - true or false.
     */
    public function webhooksShipping(Request $request)
    {
        return App(WebhooksShippingOrderAction::class)->execute($request);
    }

    public function webhooksShippingTestHend(Request $request)
    {
        return App(WebhooksShippingTestHendOrderAction::class)->execute($request);
    }

    /**
     * This function updates the status of an order to "delivered" and saves the delivery date.
     *
     * param Request request an instance of the Illuminate\Http\Request class, which contains the HTTP
     * request information.
     *
     * return a boolean value (true or false) depending on whether the data was successfully saved or
     * not.
     */
    public function webhooksShippingDashboard(Request $request)
    {
        return App(WebhooksShippingDashboardOrderAction::class)->execute($request);
    }

    /**
     * This PHP function creates a webhook for Aymakan shipping updates.
     *
     * param Request request  is an instance of the Request class, which is used to handle
     * HTTP requests in Laravel.
     *
     * return the result of calling the `updateWebHook` method of the `Aymakan` class, passing in an
     * array with a `webhook_url` and an `id` as parameters.
     */
    public function updateWebhooks(Request $request)
    {
        $requestAymakan = [
            'webhook_url' => url('/api/order/webhooks/shipping'),
            'id' => env('AYMAKAN_WEBHOOK_ID'),
        ];
        return Aymakan::updateWebHook($requestAymakan);
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
        return $this->orderRefundService->refundOrderItem($request);
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
        return $this->orderRefundService->totalOrder($request);
    }

    /**
     * The function returns a collection of OrderRefundResource objects based on the provided request.
     *
     * param request The  parameter is an object that contains the data and information needed
     * to process the refund order.
     *
     * return a collection of OrderRefundResource objects.
     */
    public function listRefundOrder($request)
    {
        return $this->orderRefundService->list($request);
    }

    /**
     * The function "actionRefundRequested" calls the "actionRefundRequested" method of the
     * "orderRefundService" object and returns its result.
     *
     * param request The  parameter is an object that contains the data and information
     * related to the refund request.
     *
     * return the result of the method call
     * `->orderRefundService->actionRefundRequested()`.
     */
    public function actionRefundRequested($request, $id)
    {
        return $this->orderRefundService->actionRefundRequested($request, $id);
    }

    /**
     * The function "actionRefundReplacement" calls the "actionRefundReplacement" method of the
     * "orderRefundService" object with the given request and id parameters.
     *
     * param request The  parameter is typically an instance of the Request class, which
     * contains the data and information about the current HTTP request.
     *
     * param id The ID of the order for which the refund and replacement action is being performed.
     *
     * return the result of the `actionRefundReplacement` method from the `orderRefundService` object.
     */
    public function actionRefundReplacement($request, $id)
    {
        return App(RefundReplacementOrderAction::class)->execute($request, $id);
    }

    /**
     * The function "refundIndex" returns the result of calling the "index" method of the
     * "orderRefundService" object with the provided parameters.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request made to the server.
     *
     * param pagination The pagination parameter determines whether or not to enable pagination for
     * the results. If set to true, the results will be paginated.
     *
     * param perPage The "perPage" parameter determines the number of items to be displayed per page
     * in the pagination. In this case, it is set to 10
     *
     * return the result of the `index` method of the `orderRefundService` object.
     */
    public function refundIndex(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->orderRefundService->index($request, $pagination, $perPage);
    }

    /**
     * The function "showRefund" retrieves refund data for a given ID and returns it, or returns null
     * if no data is found.
     *
     * param id The parameter "id" is the identifier of the refund that you want to retrieve and
     * display.
     *
     * return the data retrieved from the `show` method of the `orderRefundService` if it exists,
     * otherwise it returns `null`.
     */
    public function showRefund($id)
    {
        return $this->orderRefundService->show($id) ?? null;
    }

    /**
     * The function "payWallet" updates the status of an order to "NEW_STATUS" and then calls a service
     * to update the wallet balance.
     *
     * param order The parameter `` is an instance of the `Order` class. It represents an order
     * that needs to be processed and paid for.
     */
    public function payWallet($order)
    {
        return App(PayWalletOrderAction::class)->execute($order);
    }

    /**
     * The function "sendMessages" sends a request to the refundMessageService to store a message with
     * the given request and id.
     *
     * param request The `` parameter is typically an object or an array that contains the
     * data needed to send the message.
     *
     * param id The id parameter is used to identify the specific message or refund that the function
     * is referring to.
     *
     * return the result of the `store` method of the `refundMessageService` object.
     */
    public function sendMessages($request, $id)
    {
        return $this->refundMessageService->sendMessages($request, $id);
    }

    /**
     * The function "listMessages" returns a list of refund messages based on the provided request.
     *
     * param request The parameter `` is an object that contains the necessary information for
     * listing messages.
     *
     * return the result of the `list()` method of the `` object, which is being
     * called with the `` parameter.
     */
    public function listMessages($request)
    {
        return $this->refundMessageService->list($request);
    }

    /**
     * This PHP function tracks a shipment using a tracking number provided in the input data.
     *
     * param data It is a variable that contains the input data for the function. It is likely an
     * object that has a property called "tracking_number"
     *
     * return the result of calling the `trackShipment` method of the `Aymakan` class with the
     * `` parameter. The specific return value depends on the implementation of the
     * `trackShipment` method.
     */
    public function trackShipment($data)
    {
        return Aymakan::trackShipment(['tracking' => $data->tracking_number]);
    }

    public function updateOrderStatus($request)
    {
        return DB::transaction(function () use ($request) {
            // Retrieve data from the request
            $status_id = $request->input('status_id');
            $sub_status_id = $request->input('sub_status_id');
            $remark_id = $request->input('remark_id');
            // find core data
            $order = Order::find($request->input('currentOrder'));
            $dropshipper = $order->dropshipper;
            // completed status
            if ($order->status_id !== $status_id && $status_id == OrderEnum::COMPLETED_STATUS) {
                $order->deliveryDate = now()->format('Y-m-d');
            }
            // cancelled status
            if ($order->status_id !== $status_id && $status_id == OrderEnum::CANCELED_STATUS) {
                $order->cancelDate = Carbon::now();
                if ($order->status->id != OrderEnum::PAY_PENDING_STATUS) {
                    if ((int)$order->paymentMethod === PaymentEnum::WALLET_METHOD_ID) {
                        $walletBalance = $dropshipper->walletBalance + $order->grandTotal;
                        $dropshipper->update([
                            'walletBalance' => $walletBalance,
                        ]);
                    }
                }
            }
            // rejected orders
            if ($order->status_id !== $status_id && $status_id == OrderEnum::REJECTED_STATUS) {
                if ((int)$order->paymentMethod === PaymentEnum::WALLET_METHOD_ID) {
                    $walletBalance = $dropshipper->walletBalance + $order->grandTotal;
                    $dropshipper->update([
                        'walletBalance' => $walletBalance
                    ]);
                }
            }
            // shipping order
            if ($order->status_id != $status_id && $status_id == OrderEnum::PREPARING_STATUS) {
                // START SHIPPING
                App(CreateShipmentOrderAction::class)->execute($order);
            }
            // Update order status, sub-status, and remark
            $order->status_id = $status_id;
            $order->sub_status_id = $sub_status_id;
            $order->remark_id = $remark_id;
            // Save changes to the order
            $order->save();
            return new OrderResource($order->refresh());
        });
    }

    public function reportList($request)
    {
        $checkDate = isset($request->orderDateFrom);
        if ($checkDate) {
            $newRequest = new Request(['fromDate' => $request->orderDateFrom, 'toDate' => $request->orderDateTo]);
            $currentPeriod = App(ReportService::class)->getPeriodBestOnPeriodType('custom', $newRequest);
            $lastPeriod = App(ReportService::class)->getLastPeriodBestOnPeriodType('custom', $newRequest);
            $request->merge(['orderDateFrom' => $lastPeriod['from'], 'orderDateTo' => $currentPeriod['to']]);
        }
        $order = $this->listOrderAction->execute($request, collection: true);
        $percentageChangeTotal = $percentageChangeCompleted = $percentageChangeReject = $percentageChangeValidated = 0;
        if ($checkDate) {
            $currentOrder = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
            $lastOrder = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
            $totalCurrentOrder = $currentOrder->count();
            $totalLastOrder = $lastOrder->count();
            if ($totalLastOrder) {
                $percentageChangeTotal = App(ReportService::class)->percentageChange(
                    $totalCurrentOrder,
                    $totalLastOrder
                );
            }
            $completedCurrentOrder = $currentOrder->where('status_id', OrderEnum::COMPLETED_STATUS)->count();
            $completedLastOrder = $lastOrder->where('status_id', OrderEnum::COMPLETED_STATUS)->count();
            if ($completedLastOrder) {
                $percentageChangeCompleted = App(ReportService::class)->percentageChange(
                    $completedCurrentOrder,
                    $completedLastOrder
                );
            }
            $rejectCurrentOrder = $currentOrder->where('status_id', OrderEnum::REJECTED_STATUS)->count();
            $rejectLastOrder = $lastOrder->where('status_id', OrderEnum::REJECTED_STATUS)->count();
            if ($rejectLastOrder) {
                $percentageChangeReject = App(ReportService::class)->percentageChange(
                    $rejectCurrentOrder,
                    $rejectLastOrder
                );
            }
            $validatedCurrentOrder = $currentOrder->whereIn(
                'status_id',
                [OrderEnum::SHIPPING_STATUS, OrderEnum::PREPARING_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::READY_STATUS, OrderEnum::PENDING_INVENTORY_STATUS]
            )
                ->count();
            $validatedLastOrder = $lastOrder->whereIn(
                'status_id',
                [OrderEnum::SHIPPING_STATUS, OrderEnum::PREPARING_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::READY_STATUS, OrderEnum::PENDING_INVENTORY_STATUS]
            )
                ->count();
            if ($validatedLastOrder) {
                $percentageChangeValidated = App(ReportService::class)->percentageChange(
                    $validatedCurrentOrder,
                    $validatedLastOrder
                );
            }
        } else {
            $totalCurrentOrder = $order->count();
            $completedCurrentOrder = $order->where('status_id', OrderEnum::COMPLETED_STATUS)->count();
            $rejectCurrentOrder = $order->where('status_id', OrderEnum::REJECTED_STATUS)->count();
            $validatedCurrentOrder = $order->whereIn(
                'status_id',
                [OrderEnum::SHIPPING_STATUS, OrderEnum::PREPARING_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::READY_STATUS, OrderEnum::PENDING_INVENTORY_STATUS]
            )
                ->count();
        }
        return [
            ['totalCurrentOrder' => $totalCurrentOrder, 'percentageChangeTotal' => $percentageChangeTotal],
            ['completedCurrentOrder' => $completedCurrentOrder, 'percentageChangeCompleted' => $percentageChangeCompleted],
            ['rejectCurrentOrder' => $rejectCurrentOrder, 'percentageChangeReject' => $percentageChangeReject],
            ['validatedCurrentOrder' => $validatedCurrentOrder, 'percentageChangeValidated' => $percentageChangeValidated]
        ];
    }

    public function webhooksHendShipping(Request $request)
    {
        $getContent = $request->getContent();
        $getDataAymakanArray = json_decode($getContent, true); // Set second argument as TRUE
        $getId = $getDataAymakanArray['reference'];
        $reference = explode("-", $getId);
        $getDataAymakanStatus = strtolower($getDataAymakanArray['description']);
        $order = Order::where('tracking_number', $getDataAymakanArray['tracking'])->first();
        if (!$order) {
            return false;
        }
        if ($getDataAymakanArray['status'] === 'AY-0005') {
            $status_id = OrderEnum::COMPLETED_STATUS;
            $orderStatus = OrderStatus::where('order_id', $order->id)->latest()->first();
            $request->merge([
                'status_id' => $status_id,
                'deliveryDate' => Carbon::parse($orderStatus->created_at)->format('Y-m-d'),
            ]);
        } elseif (in_array($getDataAymakanArray['status_code'], [
            'AY-0026',
            'AY-0009',
            'AY-0030',
            'AY-0069',
            'AY-0004',
            'AY-0056',
            'AY-0003',
            'AY-0080',
            'AY-0086',
            'AY-0034',
            'AY-0082',
            'AY-0096',
            'AY-0076',
            'AY-0079'
        ])) {
            $request->merge([
                'status_id' => OrderEnum::SHIPPING_STATUS,
            ]);
        } elseif ($getDataAymakanArray['status'] === 'AY-0008') {
            $request->merge([
                'status_id' => OrderEnum::REJECTED_STATUS,
            ]);
        }
        $data = $this->repo->save($request, $order->id);
        $OrderStatusAymakan = new OrderStatusAymakan();
        $getOrderId = $getDataAymakanArray['reference'];
        $orderId = explode("-", $getOrderId);
        $OrderStatusAymakan->status = $getDataAymakanArray['status'];
        $OrderStatusAymakan->description = $getDataAymakanArray['description'];
        $OrderStatusAymakan->tracking = $getDataAymakanArray['tracking'];
        $OrderStatusAymakan->reference = $getDataAymakanArray['reference'];
        $OrderStatusAymakan->order_id = $orderId[0];
        $OrderStatusAymakan->save();
        return true;
    }

    public function PaymentProfit(Request $request)
    {
        return $this->transactionService->paymentProfit($request);
    }

    public function userList(Request $request)
    {
        return $this->userService->userOrderList($request);
    }

    public function cancelOrder($request)
    {
        $order = $this->repo->find($request->currentOrder);
        if ($order->wms_status->count() && $order->wms_status->last()->status != 'shipped') {
            return App(CancelledIntegrationOrderAction::class)->execute($order);
        }
        return false;
    }

    public function getProducts($request)
    {
       return $this->productService->search($request);
    }

    public function getDropshipper($request)
    {
        return $this->dropshipperService->list($request);
    }
}
