<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Service\DropshipperService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\CodeCountry\ListCodeCountry;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Remark;
use Modules\CoreData\Entities\Status;
use Modules\CoreData\Service\CityService;
use Modules\CoreData\Service\CountryService;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Order\Entities\Invoice;
use Modules\Order\Entities\SubStatus;
use Modules\Order\Http\Requests\Order\EditAdminRequest;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Order\Service\InvoiceService;
use Modules\Order\Service\OrderService;

/**
 * OrderController handles the management of orders, including listing, creating, updating,
 * and displaying order details, as well as managing related entities like invoices and statuses.
 */
class OrderController extends BasicController
{
    protected $service;

    protected $productService;

    protected $dropshipperService;

    protected $countryService;

    protected $invoiceService;

    /**
     * OrderController constructor initializes required services and sets middleware for authentication
     * and permissions for various order-related actions.
     *
     * @param OrderService $Service
     * @param ProductService $productService
     * @param DropshipperService $dropshipperService
     * @param CountryService $countryService
     * @param InvoiceService $invoiceService
     */
    public function __construct(OrderService $Service, ProductService $productService, DropshipperService $dropshipperService, CountryService $countryService, InvoiceService $invoiceService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_order')->only('index');
        $this->middleware('permission:extract_order')->only('extract');
        $this->service = $Service;
        $this->productService = $productService;
        $this->dropshipperService = $dropshipperService;
        $this->countryService = $countryService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function index(Request $request)
    {

        $request->merge(["status" => "new"]);
        $orders = $this->service->index($request, true);
        $status = $this->service->statusList($request);

        if ($request->ajax()) {
            return $this->getDashboardView(
                'order::order.table',
                compact('orders', 'request')
            );
        }

        return $this->getDashboardView('order::order.index', compact('orders', 'request', 'status'));
    }

    /**
     * The logistics function retrieves orders and their status for display in a view, with the option
     * for an AJAX request.
     *
     * param Request request  is an instance of the Request class, which contains all the data
     * that was sent with the HTTP request. It can be used to retrieve input data, files, cookies,
     * headers, and more. In this function, it is used to retrieve data from the HTTP request and pass
     * it to other methods
     *
     * return a view, either the logistics table view or the logistics index view, depending on
     * whether the request is an AJAX request or not. The view is passed the variables ``,
     * ``, and ``.
     */
    public function logistics(Request $request)
    {
        $status = OrderStatusEnum::getAllStatuses();
        $payments = PaymentMethodList::list();
        if ($request->ajax()) {
            $orders = $this->service->index($request);

            foreach ($orders['data'] as $order) {
                $attempts = $order->followUps()->where('activity_type', '!=', 'Initiated')->count();
                $firstValidationAttempt = $order->followUps()->where('activity_type', '!=', 'Initiated')->first();
                $lastValidationAttempt = $order->followUps()->where('activity_type', '!=', 'Initiated')->latest()->first();

                $order['number_of_attempts'] = $attempts;
                $order['validated'] = $order->followUps()->where('activity_type', 'Validated')->exists();
                $order['first_validation_attempt_timestamp'] = $firstValidationAttempt ? $firstValidationAttempt->created_at->format('Y-m-d H:i:s') : null;
                $order['last_validation_attempt_timestamp'] = $lastValidationAttempt ? $lastValidationAttempt->created_at->format('Y-m-d H:i:s') : null;
            }

            return response()->json($orders);
        }

        return $this->getDashboardView('order::logistics.index', compact('request', 'status', 'payments'));
    }

    /**
     * This function returns a view of approved orders based on the request input.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain data from the URL, form data, headers,
     * cookies, and more. In this case, it is being passed to the approved() method of a service class
     * to retrieve a list
     *
     * return a view named 'order::order.index' with a variable named 'orders' that contains the
     * result of calling the 'approved' method of the 'service' property with the 'request' parameter.
     */
    public function approved(Request $request, $id)
    {
        $data = $this->service->approved($request, $id);

        if ($data) {
            return redirect(url('/order/'.$id));
        }

        return redirect(url('/order/'.$id))->with('message', 'Something went wrong with the shipping, contact admin.');
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param Request $request
     * @param Order $order
     * @return Renderable
     */
    public function editOrder(Request $request, Order $order)
    {
        $countries = App(CityService::class)->countryList();
        $codes = ListCodeCountry::list();

        return $this->getDashboardView('order::order.edit', compact('order', 'countries', 'codes'));
    }

    /**
     * Initiate the shipping process for a given order.
     *
     * @param Request $request
     */
    public function startShipping(Request $request)
    {
        $order = $this->service->startShipping($request);

        return response()->json([
            'tracking_number' => $order->tracking_number,
            'pdf_label' => $order->pdf_label,
        ]);
    }

    /**
     * This function returns a view of refused orders based on the request input.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain information such as form data, query
     * parameters, headers, and more. In this code snippet,  is being passed as a parameter to
     * the refused() method of a service class
     *
     * return a view named 'order::order.index' with the variable  passed to it.
     */
    public function refused(Request $request, $id)
    {
        $data = $this->service->refused($request, $id);
        if ($data) {
            return redirect(url('/order/'.$id));
        }

        return $this->unKnowError();
    }

    /**
     * Store a newly created resource in storage.
     * param Request $request
     * return Renderable
     */
    public function store(Request $request)
    {
        $request->merge(['source_platform' => 'admin']);
        $this->service->store($request);

        return redirect(url('/order/list'))->with('message', 'A new product has been added');
    }

    /**
     * Show the specified resource.
     * param int $id
     * return Renderable
     */
    public function showOrder($id, Request $request)
    {

        $data = $this->service->show($id);
        if(!user()->can('view_all_order'))
        {
            if($data->operator_id != user()->id && $data->operator_id != null)
            {
                return redirect(route('order.listing.index'))->with('message', 'You are not authorized to view this order');
            }
        }
        $data->paymentMethodData = $this->service->setPaymentMethodText($data->paymentMethod);
        $invoice = Invoice::where('order_id', $id)->first();
        $shipments = null;

        if ($data->tracking_number) {
            try {
                $shipments = $this->service->trackShipment($data)['data']['shipments'];
            } catch (\Exception $e) {
                $shipments = null;
            }
        }
        if ($request->ajax()) {
            return view(checkView('order::order.showAjax'), compact('data', 'shipments', 'invoice'));
        }

        return $this->getDashboardView('order::order.show', compact('data', 'shipments', 'invoice'));
    }

    /**
     * Display the logs associated with a specific order.
     *
     * @param int $id
     * @return Renderable
     */
    public function showLogs($id)
    {
        $order = $this->service->show($id);

        $logs = $order->orderLogs->reverse()->map(function ($log) {
            // Get the attribute name and values
            $attribute = $log->attribute_changed;
            $oldValue = $log->old_value;
            $newValue = $log->new_value;

            // Initialize the log text
            $logText = '';

            // Generate the log text based on the attribute changed
            switch ($attribute) {
                case 'status_id':
                    $oldStatusName = Status::find($oldValue)?->name?->value ?? '-';
                    $newStatusName = Status::find($newValue)?->name?->value ?? '-';
                    $logText = "Status changed from $oldStatusName to $newStatusName";
                    break;
                case 'sub_status_id':
                    // Fetch the substatus names using the IDs
                    $oldSubStatusName = SubStatus::find($oldValue)?->name ?? '-';
                    $newSubStatusName = SubStatus::find($newValue)?->name ?? '-';
                    $logText = "Substatus changed from $oldSubStatusName to $newSubStatusName";
                    break;
                case 'remark_id':
                    // Fetch the remark text using the IDs
                    $oldRemarkText = Remark::find($oldValue)?->name ?? '-';
                    $newRemarkText = Remark::find($newValue)?->name ?? '-';
                    $logText = "Remark changed from \"$oldRemarkText\" to \"$newRemarkText\"";
                    break;
                default:
                    // Default text if attribute is not recognized
                    $logText = "Attribute $attribute changed from $oldValue to $newValue";
                    break;
            }

            // Add the log text to the log object
            $log->log_text = $logText;

            return $log;
        });

        return $this->getDashboardView('order::order.logs.show', compact('order', 'logs'));
    }

    /**
     * Update the specified resource in storage.
     * param Request $request
     * param int $id
     * return Renderable
     */
    public function update(EditAdminRequest $request, Order $order)
    {
        $this->service->adminUpdate($request, $order);

        return redirect(route('order.show', $order->id))->with('success', 'Order has updated successfully');
    }

    /**
     * This function calls a webhook using a request object and a service.
     *
     * param Request request  is an instance of the Request class, which is a class in the
     * Laravel framework used to represent an HTTP request. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body. In this code
     * snippet, the  parameter is passed
     *
     * return The `webhooksShipping` method of the `` object is being called with the ``
     * parameter, and the result of that method call is being returned.
     */
    public function webhooksShipping(Request $request)
    {
        $order = $this->service->webhooksShippingDashboard($request);
        if ($order) {
            return redirect(url('/order/list'));
        }

        return $this->unKnowError();
    }

    /**
     * The function sends messages using the provided request and ID, and returns the API response.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request made to the server. It contains
     * information such as the request method, headers, and any data sent in the request body.
     * param id The id parameter is used to identify a specific entity or resource. It is typically
     * used to retrieve or manipulate data related to that specific entity. In this case, it is being
     * passed as an argument to the sendMessages method of the service class.
     *
     * return the result of the `sendMessages` method of the `` object, wrapped in an API
     * response.
     */
    public function sendMessages(Request $request, $id)
    {
        $sendMessages = $this->service->sendMessages($request, $id);
        if ($sendMessages) {
            return redirect(url('/order/refund/'.$id));
        }

        return $this->unKnowError();
    }

    /**
     * The function "listMessages" returns an API response for the list of messages obtained from the
     * service.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and any data sent in the request body. In this case, it is being passed
     * to the listMessages method of the service
     *
     * return the result of the `apiResponse` method, which is being called with the result of the
     * `listMessages` method of the `` object, passing in the `` object as an argument.
     */
    public function listMessages(Request $request)
    {
        $listMessages = $this->service->listMessages($request);
        if ($listMessages) {
            return redirect(url('/order/list/messages'));
        }

        return $this->unKnowError();
    }

    /**
     * Change the status of an order based on the provided request data.
     *
     * @param Request $request
     */
    public function changeOrderStatus(Request $request)
    {
        $order = $this->service->updateOrderStatus($request);

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order]);
    }

    /**
     * Display a list of invoices associated with orders.
     *
     * @param Request $request
     * @return Renderable
     */
    public function invoiceList(Request $request)
    {
        $invoices = $this->invoiceService->index($request);

        return $this->getDashboardView('order::invoice.index', compact('invoices'));
    }
}

