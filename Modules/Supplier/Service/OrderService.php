<?php

namespace Modules\Supplier\Service;

use Illuminate\Http\Request;
use MagedAhmad\Aymakan\Facades\Aymakan;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Service\CityService;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Actions\Order\ListOrderAction;
use Modules\Order\Actions\Order\UpdateOrderAction;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Supplier\Actions\Order\IndexOrderAction;
use Modules\Order\Actions\Order\SearchResultsOrderAction;
use Modules\Order\Entities\OrderItem;
use Modules\Supplier\Actions\OrderItem\SupplierUpdateOrderAction;
//todo change
class OrderService extends BasicService
{
    protected $repo, $statusService, $cityService;

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
    public function __construct(OrderRepository $repository, StatusService $statusService, CityService $cityService)
    {
        $this->repo = $repository;
        $this->statusService = $statusService;
        $this->cityService = $cityService;
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
     * @return the result of calling the `findBy` method on the `` object.
     */
    public function findBy(Request $request, $moreConditionForFirstLevel = [], $orderBy = [], $pagination = false,
        $perPage = 10, $get = '', $withRelations = [], $latest = '', $limit = 0)
    {
        return $this->repo->findBy($request, $moreConditionForFirstLevel, $orderBy, $pagination, $perPage, $get,
            $withRelations, latest: $latest, limit: $limit);
    }

    /**
     * The function updates an order using the UpdateOrderAction class.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains all the data and information about the current HTTP request.
     * param id The  parameter is an optional parameter that represents the ID of the order that
     * needs to be updated.
     *
     * @return The code is returning the result of executing the `execute` method of the
     * `UpdateOrderAction` class with the given `` and `` parameters.
     */
    public function update(Request $request, $id = null)
    {
        //todo change
        return App(UpdateOrderAction::class)->execute($request, $id);
    }

    public function SupplierUpdate(Request $request, $id)
    {
        //todo change
        return (new SupplierUpdateOrderAction(request: $request, id: $id))->execute();
    }

    /**
     * It returns a collection of OrderResource
     *
     * param Request request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * @return A collection of OrderResource
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        //todo change
        return App(ListOrderAction::class)->execute($request, $pagination, $perPage);
    }

    /**
     * The show function returns the result of finding a record with
     *
     * param id The parameter "id" is the identifier of the item that you want to retrieve from the
     * repository.
     *
     * @return the result of the findOne() method from the repository class for the given . If the
     * result is not found, it will return null.
     */
    public function show($id)
    {
        return ($this->repo->findOne($id)) ?? null;
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
     * @return the result of calling the `list` method on the `->repo` object with the provided
     * arguments and the `` array as an additional named argument.
     */
    public function index(Request $request)
    {
        //todo change
        $orders = App(IndexOrderAction::class)->execute($request);
        foreach(collect($orders['data']) as $key => $order)
        {
            $totalPrice = [];
            foreach($order->orderItems as $item)
            {
                if($item->supplier_id != auth()->id())
                {
                    continue;
                }
                $totalPrice[] = $item->totalPrice;
            }
            $totalPriceSupplier = collect($totalPrice)->sum();
            $order['subTotal'] = $totalPriceSupplier;
        }
        return $orders;
    }

    /**
     * The function `searchResults` takes in a request object and optional pagination parameters, and
     * performs a search based on various conditions,
     *
     * param Request request The  parameter is an instance of the Request class, which contains
     * the data sent by the client in the HTTP request.
     * param pagination A boolean value indicating whether pagination should be applied to the search
     * results.
     * param perPage The "perPage" parameter determines the number of search results to be displayed
     * per page. By default, it is set to 10, but you can change it to any desired value.
     *
     * @return the result of the `list` method from the `` object.
     */
    public function searchResults(Request $request, $pagination = false, $perPage = 10)
    {
        //todo change
        return App(SearchResultsOrderAction::class)->execute($request, $pagination, $perPage);
    }

    /**
     * This PHP function retrieves a list of statuses from a database table.
     *
     * @return The function `statusList()` is returning a collection of all the rows from the `status_id`
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
     * @return the payment method text associated with the given payment method ID. It is using the
     * PaymentMethodList class to retrieve the list of payment methods and then filtering it to find
     * the payment method with the given ID. The first matching payment method is returned.
     */
    public function setPaymentMethodText($paymentMethod)
    {
        //todo change
        return PaymentMethodList::list()->where('id', $paymentMethod)->first();
    }

    /**
     * This PHP function tracks a shipment using a tracking number provided in the input data.
     *
     * param data It is a variable that contains the input data for the function. It is likely an
     * object that has a property called "tracking_number"
     *
     * @return the result of calling the `trackShipment` method of the `Aymakan` class with the
     * `` parameter. The specific return value depends on the implementation of the
     * `trackShipment` method.
     */
    public function trackShipment($data)
    {
        //todo change
        return Aymakan::trackShipment(['tracking' => $data->tracking_number]);
    }

    public function orderSuppliers($orderId)
    {
        //todo change
        return OrderItem::where('order_id', $orderId)->where('supplier_id', auth()->id())->get();
    }

    public function updateCheckBoxByReady($request)
    {
        //todo change
        $orderItem = OrderItem::find($request->id);
        $orderItem->is_ready = ($request->check == "true") ? 1 : 0;
        $orderItem->save();
    }

    public function countryList()
    {
        return $this->cityService->countryList();
    }
}
