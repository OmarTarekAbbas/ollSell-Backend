<?php

namespace Modules\Order\Http\Controllers\Saas;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Service\OrderService;
use Modules\Order\Exports\Order\OrderExport;
use Modules\Order\Http\Requests\Order\EditRequest;
use Modules\Order\PaymentMethod\PaymentMethodList;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Http\Requests\SaasOrder\CreateRequest;

class OrderController extends BasicController
{
    private $service;

    public function __construct(OrderService $Service)
    {
        $this->service = $Service;
    }

    /**
     * It checks if the product exists in the favorites table, if it does, it returns an error message,
     * if it doesn't, it adds the product to the favorites table
     *
     * param CreateRequest request
     */
    public function store(CreateRequest $request)
    {
        $order = $this->service->store($request);
        if ($order) {
            return $this->createResponse($order, 'A new order has added successfully');
        }
        return $this->unKnowError();
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
            return $this->notFoundResponse('notFound');
        }

        $order = $this->service->update($request, $id);
        if ($order) {
            return $this->updateResponse($order, 'Order has updated successfully');
        }
        return $this->unKnowError();
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
    public function export()
    {
        return Excel::download(new OrderExport, 'exportOrders.xlsx');
    }

    /**
     * This PHP function imports an Excel file and checks if the file extension is valid before
     * importing.
     *
     * param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an incoming HTTP request. It contains information about the request such as the HTTP
     * method, headers, and any data that was sent with the request. In this case, it is used to
     * retrieve the uploaded file from the request.
     *
     * return either a success message if the file extension is valid and the Excel file is
     * successfully imported using the OrderImport class, or a bad request message if the file
     * extension is invalid or there is an exception thrown during the import process.
     */
    public function import(Request $request)
    {

        $extensions = ["xls", "xlsx", "csv", "xlm", "xla", "xlc", "xlt", "xlw"];
        if (!empty($request->file('excelFile'))) {
            $fileExtension = $request->file('excelFile')->getClientOriginalExtension();

            if (in_array($fileExtension, $extensions)) {
                $files = Storage::allFiles("public/missing/orders/".user()->id);
                Storage::delete($files);

                $filePath = 'public/missing/orders/' . user()->id . '/missing_orders_data.xlsx';

                Excel::import(new OrderImport($filePath), $request->file('excelFile'));
                    $files = Storage::allFiles("public/missing/orders/".user()->id);
                    if ($files) {
                        $file = array_reverse($files)[0];
                        $filePath = asset($file);
                        return $filePath;
                        return $this->createResponse(['url' => $filePath], 'Uploaded done with errors.');
                    }
                    return $this->createResponse([], 'Success Upload Excel File.');

            } else {
                return $this->apiValidation('Please Upload Excel File.');
            }
        }
        return $this->unKnowError('Please Upload Excel File.');
    }

    /**
     * Get Payment Method list
     *
     * param Request $request
     * return \Illuminate\Http\Response|string
     */
    public function payNow(Request $request)
    {
        $order = $this->service->payNow($request);
        if ($order) {
            return $this->updateResponse($order, 'Order has payed successfully');
        }
        return $this->unKnowError();
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
            return $this->notFoundResponse('notFound');
        }

        $order = $this->service->cancel($request, $id);
        if ($order) {
            return $this->updateResponse($order, 'Order has canceled successfully');
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
        if (!$this->service->show($id)) {
            return $this->notFoundResponse('notFound');
        }
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
        $order = $this->service->webhooksShipping($request);
        if ($order) {
            return $this->updateResponse($order, 'Order has updated successfully');
        }
        return $this->unKnowError();
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
     * This function returns an API response for the PaymentProfit service.
     *
     * param Request request  is an instance of the Request class which is used to retrieve
     * data from the HTTP request. It contains information such as the request method, headers, and
     * parameters. In this case, it is being passed as a parameter to the PaymentProfit method of a
     * service class.
     *
     * return the result of the `PaymentProfit` method of the `` object, which is being passed
     * the `` object as a parameter. The result is then being passed to the `apiResponse`
     * method, which is likely formatting the result in a specific way for an API response.
     */
    public function PaymentProfit(Request $request)
    {
        return $this->apiResponse($this->service->PaymentProfit($request));
    }
}
