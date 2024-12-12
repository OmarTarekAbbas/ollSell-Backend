<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Supplier\Service\OrderService;
use Illuminate\Contracts\Support\Renderable;
use Modules\CoreData\CodeCountry\ListCodeCountry;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Supplier\Exports\OrderExport;
//todo change
class OrderController extends BasicController
{
    protected $service;

    public function __construct(OrderService $Service)
    {
        $this->service = $Service;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
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
     
     return   $this->getDashboardView('supplier::order.index', compact('orders', 'request', 'status'));
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
     * @return a view, either the logistics table view or the logistics index view, depending on
     * whether the request is an AJAX request or not. The view is passed the variables ``,
     * ``, and ``.
     */
    public function logistics(Request $request)
    {
        
        $status = $this->service->statusList($request);
        //todo change
        $payments = PaymentMethodList::list();
        if ($request->ajax()) {
            $orders = $this->service->index($request);
            return response()->json($orders);
        }
        return $this->getDashboardView('supplier::logistics.index', compact('request', 'status', 'payments'));
    }


    public function editOrder(Request $request, Order $order)
    {
        $countries = $this->service->countryList();
        //todo change
        $codes = ListCodeCountry::list();

        return  $this->getDashboardView('supplier::order.edit', compact('order', 'countries', 'codes'));
    }


    /**
     * Show the specified resource.
     * param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        $data->paymentMethodData = $this->service->setPaymentMethodText($data->paymentMethod);
        $data->orderSuppliers = $this->service->orderSuppliers($id);
        $shipments = null;

        if ($data->tracking_number) {
            try {
                $shipments = $this->service->trackShipment($data)['data']['shipments'];
            } catch (\Exception $e) {
                $shipments = null;
            }
        }
        return  $this->getDashboardView('supplier::order.show', compact('data', 'shipments'));
    }

    /**
     * Show the form for editing the specified resource.
     * param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return $this->getDashboardView('order::edit');
    }

    /**
     * Update the specified resource in storage.
     * param Request $request
     * param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $this->service->SupplierUpdate($request, $id);

        return redirect(url('supplier/order/logistics'))->with('success', 'orderItem has updated successfully');
    }

    /**
     * The function `updateCheckBoxByReady` in PHP updates a checkbox based on the request data and
     * returns 'done'.
     * 
     * @param Request request The `updateCheckBoxByReady` function takes a Request object as a
     * parameter. This Request object likely contains data that is being used to update a checkbox
     * status. The function calls a method `updateCheckBoxByReady` on a service class, passing the
     * Request object as an argument. Finally, the function
     * 
     * @return the string 'done'.
     */
    public function updateCheckBoxByReady(Request $request)
    {
        $this->service->updateCheckBoxByReady($request);
        return 'done';
    }

    /**
     * The function `export` downloads an Excel file containing order data using Laravel's Excel
     * package.
     * 
     * @return The `export()` function is returning a download response for an Excel file generated
     * using the `OrderExport` class. The file will be named 'exportOrders.xlsx'.
     */
    public function export()
    {
        return Excel::download(new OrderExport, 'exportSupperOrders.xlsx');
    }
}
