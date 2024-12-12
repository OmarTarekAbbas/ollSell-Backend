<?php

namespace Modules\Order\Http\Controllers\Api;

use Modules\Finance\Actions\Transaction\PayByWalleteAutomaticAction;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Integration\Models\ClickPaymentLog;
use PDF;
use Illuminate\Http\Request;
use Modules\Acl\Service\SMS;
use Modules\Basic\Http\Requests\DateRequest;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Service\OrderService;
use Illuminate\Support\Facades\Validator;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperBranch;
use Modules\Order\Exports\Order\OrderExport;
use Modules\Order\Http\Requests\Order\EditRequest;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Actions\ClickPayments\PaymentAction;
use Modules\Order\Entities\Invoice;
use Modules\Order\Http\Requests\Order\CreateRequest;
use Modules\Order\Http\Resources\Order\OrderResource;
use Illuminate\Support\Facades\Log as LaravelLog;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use ZipArchive;

class OrderController extends BasicController
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->middleware('auth:dropshipper')
            ->except(['import', 'download', 'webhooksShipping', 'downloadInvoice', 'confirmOnlinePayment', 'confirmOnlinePaymentYourcallback', 'confirmOnlinePaymentPayNow', 'extractOrderId', 'showPaymentStatus', 'updateAttempts', 'downloadMediaZip']);
        $this->service = $service;
    }

    /**
     * It takes a request, merges the request with the user's target market, and then returns the
     * response from the service
     *
     * param Request request The request object
     *
     * return The list of all the users in the database.
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    /**
     * It takes the id of a product and returns the product with the target market of the user
     *
     * param id The id of the record you want to show
     *
     * return The show method is returning the result of the service show method.
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        if ($data && $data->dropshipper_id == user()->id) {
            return $this->apiResponse(new OrderResource($data));
        }
        return $this->notFoundResponse(trans('orders.notFound'));
    }

    /**
     * It checks if the product exists in the favorites table, if it does, it returns an error message,
     * if it doesn't, it adds the product to the favorites table
     *
     * param CreateRequest request
     */
    public function store(CreateRequest $request)
    {
        try {
            // Add key in mobile endpoint to be stored
            if (!$request->source_platform) {
                $request->merge(['source_platform' => PlatformEnum::WEBSITE_PLATFORM]);
            }
            $request->merge(['created_platform' => PlatformEnum::WEBSITE_PLATFORM]);
            $order = $this->service->store($request);
            if ($order) {
                return $this->createResponse([
                    'url' => route('invoice.download', ['token' => $order->token]),
                    'order' => true,
                    'id' => $order->id,
                ], trans('orders.A new order has added successfully'));
            }
            return $this->unKnowError();
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    public function storeOrderNow(CreateRequest $request)
    {
        try {
            // Add key in mobile endpoint to be stored
            if (!$request->source_platform) {
                $request->merge(['source_platform' => PlatformEnum::WEBSITE_PLATFORM]);
            }
            $request->merge(['created_platform' => PlatformEnum::WEBSITE_PLATFORM]);
            $order = $this->service->store($request, true);
            if ($order) {
                return $this->createResponse([
                    'url' => route('invoice.download', ['token' => $order->token]),
                    'order' => true,
                    'id' => $order->id,
                ], trans('orders.A new order has added successfully'));
            }
            return $this->unKnowError();
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    /**
     * It updates the order and returns the updated order if it's updated successfully, otherwise it
     * returns an unknown error.
     *
     * param Request request The request object.
     * param id The id of the order you want to update.
     */
    public function update(EditRequest $request, $id)
    {
        if (!$this->service->show($id)) {
            return $this->notFoundResponse(trans('orders.notFound'));
        }
        try {
            $order = $this->service->update($request, $id);
            if ($order) {
                return $this->updateResponse(new OrderResource($order->refresh()), trans('orders.A updated order has successfully'));
            }
            return $this->unKnowError();
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    /**
     * Get Payment Method list
     *
     * param Request $request
     * return \Illuminate\Http\Response|string
     */
    public function paymentMethods(Request $request)
    {
        $payment = PaymentMethodList::list();
        return $this->createResponse($payment);
    }

    /**
     * The function is called export, it takes a request as a parameter, and it returns an Excel file
     * called orders.xlsx
     *
     * param Request request The request object.
     *
     * return The export function is returning an Excel file.
     */
    public function export(Request $request)
    {
        $orders = $this->service->enhancedList(request: $request);
        $orderExport = new OrderExport($orders);
        return Excel::download($orderExport, 'exportOrders.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * The function "getUploadedOrders" retrieves uploaded orders and returns a response with the
     * orders.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to handle HTTP requests in Laravel.
     *
     * return a response with an array of uploaded orders.
     */
    public function getUploadedOrders(Request $request)
    {
        $uploadedOrders = $this->service->getUploadedOrders();
        return $this->createResponse(['orders' => $uploadedOrders]);
    }

    /**
     * This PHP function imports an Excel file and checks if the file extension is valid before
     * importing.
     *
     * param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an incoming HTTP request.
     *
     * return either a success message if the file extension is valid and the Excel file is
     * successfully imported using the OrderImport class,
     */
    public function import(Request $request)
    {
        executionTime();
        $extensions = ["xls", "xlsx", "csv", "xlm", "xla", "xlc", "xlt", "xlw"];
        if (!empty($request->file('excelFile'))) {
            $fileExtension = $request->file('excelFile')->getClientOriginalExtension();
            if (in_array($fileExtension, $extensions)) {
                if (file_exists(public_path('/missings/orders/' . user()->id))) {
                    $files = public_path('/missings/orders/' . user()->id . '/orders_failed_rows.xlsx');
                    if (is_file($files)) {
                        unlink($files);
                    }
                }
                executionTime();
                Excel::import(new OrderImport($this->cityService, $this->service), $request->file('excelFile'));
                executionTime();
                $files = file_exists(public_path('/missings/orders/' . user()->id . '/orders_failed_rows.xlsx'));
                $fileProduct = FileOrder::latest()->first();
                if ($files) {
                    $url = url('order/files/download?id=' . user()->id);
                    return $this->createResponse([
                        'url' => $url,
                        'failed' => $fileProduct['countFail'],
                        'succeed' => $fileProduct['countSuccess'],
                    ], trans('orders.Uploaded done with errors.'));
                }
                return $this->createResponse([
                    'url' => null,
                    'failed' => $fileProduct['countFail'],
                    'succeed' => $fileProduct['countSuccess'],
                ], trans('orders.Success Upload Excel File.'),);
            } else {
                return $this->apiValidation(trans('orders.Please Upload Excel File.'));
            }
        }
        return $this->unKnowError(trans('orders.Please Upload Excel File.'));
    }

    /**
     * This function returns the result of a download service.
     *
     * return The `download()` method of the object's `service` property is being returned.
     */
    public function download()
    {
        return $this->service->download();
    }

    /**
     * Get Payment Method list
     *
     * param Request $request
     * return \Illuminate\Http\Response|string
     */
    public function cancel(Request $request, $id)
    {
        if (!$this->service->show($id)) {
            return $this->notFoundResponse(trans('orders.notFound'));
        }
        $order = $this->service->cancel($request, $id);
        if ($order) {
            return $this->updateResponse($order, trans('orders.Order has canceled successfully'));
        }
        return $this->unKnowError();
    }

    /**
     * This function tracks a request with a given ID using a service and returns a not found response
     * if the ID is not found.
     *
     * param Request request  is an instance of the Request class, which is used to retrieve
     * data from the HTTP request made to the server. It contains information such as the request
     * method, headers, and any data sent in the request body. In this function,  is passed as
     * a parameter to the track method of
     * param id  is a parameter that represents the unique identifier of the resource being
     * tracked. It is passed as an argument to the track() method along with the  object.
     *
     * return the result of calling the `track` method of the `` object with the ``
     * and `` parameters. If the `` parameter does not exist, it will return a "notFound"
     * response.
     */
    public function track(Request $request, $id)
    {
        // if (!$this->service->show($id)) {
        //     return $this->notFoundResponse('notFound');
        // }
        return $this->service->track($request, $id);
    }

    /**
     * This function returns a list of webhooks based on the request input.
     *
     * param Request request  is an instance of the Request class, which is a class in the
     * Laravel framework used to represent an HTTP request. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body. In this code
     * snippet, the  parameter is passed
     *
     * return The function `webhooksList` is returning the result of calling the `webhooksList` method
     * of the `` object with the `` parameter. The specific return value depends on the
     * implementation of the `webhooksList` method in the `` object.
     */
    public function webhooksList(Request $request)
    {
        return $this->service->webhooksList($request);
    }

    /**
     * The function "cityByAymakan" takes a request object as a parameter and returns the result of
     * calling the "cityByAymakan" method on the service object.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and any data sent in the request body. In this case, it is being passed
     * to the cityByAymakan method
     *
     * return the result of the method call `->service->cityByAymakan()`.
     */
    public function cityByAymakan(Request $request)
    {
        return $this->service->cityByAymakan($request);
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
        $this->service->webhooksShipping($request);
        return $this->apiResponse(data: [], message: "Shipment Updated Successfully!", code: 200);
        // $order = $this->service->webhooksShipping($request);
        // if ($order) {
        //     return $this->updateResponse($order);
        // }
        // return $this->unKnowError();
    }

    public function webhooksShippingTestHend(Request $request)
    {
        $this->service->webhooksShippingTestHend($request);
        return $this->apiResponse(data: [], message: "Shipment Updated Successfully!", code: 200);
        // $order = $this->service->webhooksShipping($request);
        // if ($order) {
        //     return $this->updateResponse($order);
        // }
        // return $this->unKnowError();
    }

    /**
     * This function updates webhooks using a request object in PHP.
     *
     * param Request request  is an instance of the Request class, which is a class in the
     * Laravel framework used to represent an HTTP request. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body. In this code
     * snippet, the  parameter is passed
     *
     * return the result of calling the `updateWebhooks` method of the `` object with the
     * `` parameter.
     */
    public function updateWebhooks(Request $request)
    {
        return $this->service->updateWebhooks($request);
    }

    /**
     * The function `updateAymakanStatus` in PHP updates the status of Aymakan based on the request
     * data.
     *
     * @param Request request The `updateAymakanStatus` function takes a `Request` object as a
     * parameter. This `Request` object likely contains data or information needed to update the status
     * of an "Aymakan". The function then calls a method `updateAymakanStatus` on a service class,
     * passing
     *
     * @return The `updateAymakanStatus` function is returning the result of calling the
     * `updateAymakanStatus` method on the `` object with the `` parameter.
     */
    public function updateAymakanStatus(Request $request)
    {
        return $this->service->updateAymakanStatus($request);
    }

    /**
     * The function "refundOrderItem" takes a request object as a parameter, calls a service method to
     * refund an order item, and returns the API response.
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and any data sent in the request body. In this case, it is being passed
     * to the refundOrderItem method of the
     *
     * return the result of the `refundOrderItem` method of the `` object, which is being
     * passed the `` object as an argument. The result is then being passed to the
     * `apiResponse` method, and the final result of that method is being returned.
     */
    public function refundOrderItem(Request $request)
    {
        $request->validate([
            'orderItem' => 'required',
            'reason' => 'required'
        ]);
        return $this->apiResponse(
            data: $this->service->refundOrderItem($request),
            message: trans('orders.Order Item Refund Request sent!')
        );
    }

    /**
     * The function "totalOrder" returns the API response for the total order.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and any data sent in the request body. In this case, it is being passed to the
     * totalOrder method of the service class
     *
     * return the result of the `totalOrder` method of the `` object, which is being passed
     * the `` object as an argument. The result is then being passed to the `apiResponse`
     * method, and the final result of that method is being returned.
     */
    public function totalOrder(Request $request)
    {
        $request->validate([
            'order' => 'required',
            'reason' => 'required',
        ]);
        return $this->apiResponse(
            data: $this->service->totalOrder($request),
            message: trans('orders.Order Refund Request sent!')
        );
    }

    /**
     * The function "listRefundOrder" returns an API response for a refund order request.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and any data sent in the request body. In this case, it is being passed
     * to the listRefundOrder method of
     *
     * return the result of the `listRefundOrder` method of the `` object, which is being
     * passed the `` object as an argument. The result is then being passed to the `apiResponse`
     * method, and the final result of that method is being returned.
     */
    public function listRefundOrder(Request $request)
    {
        return $this->apiResponse($this->service->listRefundOrder($request));
    }

    /**
     * The function "actionRefundRequested" returns an API response for a refund request.
     *
     * param Request request The parameter `` is an instance of the `Request` class. It
     * represents the HTTP request made to the server and contains information such as the request
     * method, headers, and request data. It is typically used to retrieve data sent by the client and
     * pass it to the corresponding method or service for
     *
     * return the result of the `apiResponse` method, which is being called with the result of the
     * `actionRefundRequested` method of the `` object, passing in the `` object as an
     * argument.
     */
    public function actionRefundRequested(Request $request, $id)
    {
        return $this->apiResponse($this->service->actionRefundRequested($request, $id));
    }

    /**
     * The function "actionRefundReplacement" takes a request and an ID as parameters, and returns the
     * API response from the "actionRefundReplacement" method of the service class.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to handle HTTP requests in Laravel. It contains information about the current request, such as
     * the request method, headers, and input data.
     * param id The  parameter is the identifier of the replacement that needs to be refunded. It
     * is used to specify which replacement should be refunded.
     *
     * return the result of the `actionRefundReplacement` method from the service class, wrapped in an
     * API response.
     */
    public function actionRefundReplacement(Request $request, $id)
    {
        return $this->apiResponse($this->service->actionRefundReplacement($request, $id));
    }

    /**
     * This function allows a user to pay for an order using their wallet balance, and returns a
     * success message if the payment is successful.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and input data.
     *
     * return either a successful response with the order that was paid using the wallet, or an
     * unknown error response.
     */
    public function payWallet(Request $request)
    {
        if (!$this->service->show($request->order_id)) {
            return $this->notFoundResponse(trans('orders.notFound'));
        }
        $order = Order::find($request->order_id);
        
        if ($order->dropshipper->walletBalance < $order->grandTotal) {
            if ($order->dropshipper->DropshipperOptionCheck('automatic_pay_from_profit_at_wallet')) {
                $check = app(PayByWalleteAutomaticAction::class)->execute($order);
                if ($check) {
                    $order = $this->service->payWallet($order);
                } else {
                    return $this->unKnowError(trans('orders.Sorry! Your balance is not enough'));
                }
            } else {
                return $this->unKnowError(trans('orders.Sorry! Your balance is not enough'));
            }
        }
        $order = $this->service->payWallet($order);
        
        if ($order) {
            return $this->createResponse($order, trans('orders.order was payed wallet successfully'));
        }

        return $this->unKnowError();
    }

    /**
     * Method getDownload
     *
     * return void
     */
    public function getDownload()
    {
        $file = base_path() . "/order-excel/ImportOrders.xlsx";
        $headers = [
            'Content-Type: application/xlsx',
        ];
        return response()->download($file, 'ImportOrders.xlsx', $headers);
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
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);
        if ($validator->passes()) {
            return $this->apiResponse($this->service->sendMessages($request, $id));
        }
        return $this->unKnowError($validator->errors());
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
        return $this->apiResponse($this->service->listMessages($request));
    }

    /**
     * The function sends an SMS message containing a verification code to a specified phone number.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to handle HTTP requests in Laravel. It contains information about the current request, such as
     * the request method, headers, and input data.
     */
    public function smsCode(Request $request)
    {
        $sms = App::make(SMS::class);
        $message = "Your verification code is: 123456";
        return $sms->send($request->phoneNumber, $from = 'OLLTEK', $message);
    }

    /**
     * The function `downloadInvoice` fetches invoice data from the database based on the order ID and
     * returns a PDF file view with the invoice data.
     *
     * @param id The `token` parameter in the `downloadInvoice` function is used to fetch the invoice data
     * from the database. It is specifically used to query the database for the invoice associated with
     * the provided `order_id`.
     *
     * @return The `downloadInvoice` function is returning the dashboard view for the PDF file named
     * 'invoicePdf.pdf' with the invoice data passed as a compact variable.
     */
    public function downloadInvoice($token)
    {
        $order = Order::where('token', $token)->orWhere('id', $token)->first();

        if (!$order) {
            return 'Order Not Found';
        }

        $invoice = Invoice::where('order_id', $order->id)->first();

        if (!$invoice) {
            return 'Invoice Not Found';
        }

        $dropShipper = Dropshipper::find($order->dropshipper_id);
        $dropshipperBranch = DropshipperBranch::find($invoice->dropshipper_branch_id) ?? null;
        switch (true) {
            case $order->status_id == OrderEnum::CANCELED_STATUS:
                $paidAndUnpaid = 'canceled';
                break;
            case $order->paymentMethod == PaymentEnum::CASH_ON_DELIVERY_ID:
            case $order->paymentMethod == PaymentEnum::WALLET_METHOD_ID && in_array($order->status_id, [OrderEnum::NEW_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::PAY_PENDING_STATUS,OrderEnum::ONHOLD_STATUS] ):
                $paidAndUnpaid = 'unpaid';
                break;
            case $order->status_click_payment == ClickPayEnum::Pay :
            case !in_array($order->status_id, [OrderEnum::NEW_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::PAY_PENDING_STATUS,OrderEnum::ONHOLD_STATUS] ):
                $paidAndUnpaid = 'paid';
                break;
            default:
                $paidAndUnpaid = 'unpaid';
                break;
        }
        if (request()->url() == url('api/invoice/download/' . request()->route('token'))) {
            return $this->getDashboardView(
                'invoicePdf.pdf',
                compact('invoice', 'order', 'dropShipper', 'dropshipperBranch', 'paidAndUnpaid')
            );
        } else {
            $mpdf = new \Mpdf\Mpdf([
                'default_font' => 'Cairo',
                'mode' => 'utf-8',
            ]);
            $html = view(
                'invoicePdf.pdf-download',
                compact('invoice', 'order', 'dropShipper', 'dropshipperBranch', 'paidAndUnpaid')
            )->render();
            $mpdf->WriteHTML($html);
            $mpdf->Output('invoice-' . $invoice->id . '.pdf', 'D');
        }
    }

    /**
     * The function `confirmOnlinePayment` processes a request to confirm an online payment and returns
     * a response based on the status received.
     *
     * @param Request request The `confirmOnlinePayment` function takes a `Request` object as a
     * parameter. This object likely contains data related to an online payment transaction. The
     * function retrieves all data from the request, converts it to JSON format, and then decodes it
     * into an associative array.
     *
     * @return The function `confirmOnlinePayment` returns different messages based on the value of
     * `respStatus` in the JSON data extracted from the request. If `respStatus` is 'D', it returns the
     * `respMessage` value. If `respStatus` is 'A', it returns 'Success'. Otherwise, it returns 'Try
     * Again'.
     */
    public function confirmOnlinePayment(Request $request)
    {
        // Retrieve all data from the request and decode it into an associative array
        $data = $request->all();
        $jsonData = json_decode(json_encode($data, JSON_PRETTY_PRINT), true);
        // Extract order ID from cartId
        $orderId = $this->extractOrderId($jsonData['cartId']);
        if (!$orderId) {
            return 'Invalid order ID';
        }
        // Find the order and update its status based on the response status
        $order = Order::find($orderId);
        if (!$order) {
            return 'Order not found';
        }
        $clickPaymentLog = new ClickPaymentLog();
        $clickPaymentLog->data = json_encode($jsonData, JSON_PRETTY_PRINT);
        $clickPaymentLog->order_id = $order->id;
        switch ($jsonData['respStatus']) {
            case 'D':
                $order->status_click_payment = ClickPayEnum::Failed;
                $clickPaymentLog->status = ClickPayEnum::Failed;
                $order->save();
                $status = $jsonData['respMessage'];
                $clickPaymentLog->save();
                return redirect()->route('payment.status', ['status' => $status,'id'=>$order->id]);
            case 'E':
                $order->status_click_payment = ClickPayEnum::Error;
                $order->save();
                $status = $jsonData['respMessage'] ?? "Error Try Again";
                $clickPaymentLog->status = ClickPayEnum::Error;
                $clickPaymentLog->save();
                return redirect()->route('payment.status', ['status' => $status,'id'=>$order->id]);
            case 'A':
                $order->status_click_payment = ClickPayEnum::Pay;
                $order->paymentMethod = PaymentEnum::ONLINE_METHOD_ID;
                $order->validated = now();
                $order->validated_by = 'prepaid';
                $order->save();
                App(CreateShipmentOrderAction::class)->execute($order);
                $status = 'success';
                $clickPaymentLog->status = ClickPayEnum::Pay;
                $clickPaymentLog->save();
                return redirect()->route('payment.status', ['status' => $status,'id'=>$order->id]);
            default:
                $order->status_click_payment = ClickPayEnum::Try;
                $order->save();
                $status = 'Try Again';
                $clickPaymentLog->status = ClickPayEnum::Try;
                $clickPaymentLog->save();
                return redirect()->route('payment.status', ['status' => $status,'id'=>$order->id]);
        }
    }

    /**
     * The function `showPaymentStatus` in PHP displays the payment status on a view template.
     *
     * @param status The `showPaymentStatus` function is a PHP function that takes a parameter
     * `` and returns a view called `payment_status` with the status passed as data to the view.
     *
     * @return A view named 'payment_status' is being returned with the status variable passed to it.
     */
    public function showPaymentStatus($status,$id = null)
    {
        if ($status == 'success') {
            return redirect()->to('https://app.ollops.com/order/success');
        }
        $invoice_id = Order::find($id)->token ?? $id;
        $invoiceLink = "#";
        if($id)
        {
        $invoiceLink = setting('app_debug') == 'live' ? 'https://ollops.com/order-pay/' . $invoice_id  : 'https://beta-app.ollops.com/order-pay/' . $invoice_id ;
        }
        return view('invoicePdf.payment_status', ['status' => $status,'invoiceLink'=>$invoiceLink,'invoice_id'=>$invoice_id]);
    }

    /**
     * Extract the numeric order ID from the given string.
     *
     * @param string $string
     * @return int|null
     */
    private function extractOrderId(string $string): ?int
    {
        if (preg_match('/\d+/', $string, $matches)) {
            return (int)$matches[0];
        }
        return null;
    }

    public function confirmOnlinePaymentYourcallback(Request $request)
    {
        LaravelLog::channel('payments')->info(json_encode($request->all()));
    }

    /**
     * The confirmOnlinePaymentPayNow function executes a payment action using the PaymentAction class
     * for a given ID.
     *
     * @param id The `confirmOnlinePaymentPayNow` function seems to be a method that confirms an
     * online payment using the PayNow service. It takes an `` parameter which is likely the
     * identifier of the payment transaction that needs to be confirmed.
     */
    public function confirmOnlinePaymentPayNow($id)
    {
        $paymentAction = app()->make(PaymentAction::class)->execute($id);

        if(!$paymentAction) {
            $invoice_id = Order::find($id)->token ?? $id;
            $invoiceLink = setting('app_debug') == 'live' ? 'https://ollops.com/order-pay/' . $invoice_id  : 'https://beta-app.ollops.com/order-pay/' . $invoice_id ;
            $status = 'Failed to confirm payment';
            return view('invoicePdf.payment_status',compact('status','invoice_id','invoiceLink'));
        }

        return redirect()->to($paymentAction);
    }

    public function reportList(DateRequest $request)
    {
        return $this->apiResponse($this->service->reportList($request));
    }

    /**
     * Update the attempts count of an order.
     *
     * @param Request $request The request object containing the new attempts count.
     * @param int $id The ID of the order to update.
     * @return \Illuminate\Http\JsonResponse The response indicating the success or failure of the update.
     */
    public function updateAttempts(Request $request, $id)
    {
        $order = $this->service->show($id);
        if (!$order) {
            return $this->notFoundResponse(trans('orders.notFound'));
        }
        $updatedOrder = $this->service->updateAttempts($id, $request->attempts);
        // Cannot use OrderResource here because the toArray function in OrderResource has certain functionality that requires dropshipper
        // Modules\MasterCatalog\Entities\Product has below functions that require dropshipper_id dreived from user()->id
        // queryProfitProduct
        // queryMappingProduct
        // isSelected
        // calculatorCleaningDBOrder
        // queryProfitProductCleaningDBOrder
        // Verify that the attempt count of order matches with the attempt count of request from olldrop
        if ($updatedOrder && $updatedOrder->ollops_attempts == $request->attempts) {
            return $this->updateResponse($order, "Order Attempts updated successfully");
        }
        return $this->unKnowError("Failed to update order attempts");
    }

    public function downloadMediaZip(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $mediaFiles = $product->media;
        $tempDir = public_path('temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        // Create a unique filename for the ZIP file
        $zipFileName = 'product_' . $product->id . '_media.zip';
        $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->json(['error' => 'Could not create ZIP file'], 500);
        }
        foreach ($mediaFiles as $media) {
            // $imagePath = public_path('images/product/1/1_0.png');
            $imagePath = public_path('images/product/' . $product->id . '/' . $media->file);
            if (file_exists($imagePath)) {
                $zip->addFile($imagePath, basename($imagePath));
            } else {
                \Log::error('File not found: ' . $imagePath);
            }
        }
        // Close the archive
        if (!$zip->close()) {
            \Log::error('Failed to close ZIP archive at: ' . $zipFilePath);
            return response()->json(['error' => 'Could not close ZIP file'], 500);
        }
        if (!file_exists($zipFilePath)) {
            return response()->json(['error' => 'ZIP file not created'], 500);
        }
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
