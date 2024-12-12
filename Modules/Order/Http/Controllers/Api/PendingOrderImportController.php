<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Actions\PendingOrder\ScanPendingOrderAction;
use Modules\Order\Entities\PendingOrder;
use Modules\CoreData\Service\CityService;
use Modules\CoreData\Service\CountryService;
use Modules\Order\Imports\PendingOrdersImport;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Service\PendingOrderImportService;
use Modules\Order\Http\Resources\Order\OrderResource;
use Modules\Order\Actions\Order\UpdatePendingOrderAction;
use Modules\Order\Actions\PendingOrder\ExportPendingOrdersAction;
use Modules\Order\Http\Requests\PendingOrder\UpdatePendingOrderRequest;

class PendingOrderImportController extends BasicController
{
    protected $countryService;
    protected $cityService;
    protected $service;

    /**
     * The function is a constructor that initializes the CountryService, CityService, and OrderService
     * dependencies, and sets the middleware for authentication.
     *
     * param CountryService countryService An instance of the CountryService class, which is
     * responsible for handling operations related to countries.
     * param CityService cityService An instance of the CityService class, which is responsible for
     * handling operations related to cities.
     * param OrderService service The `` parameter is an instance of the `OrderService` class.
     * It is used to perform operations related to orders, such as creating, updating, and retrieving
     * orders.
     */
    public function __construct(CountryService $countryService, CityService $cityService, PendingOrderImportService $service)
    {
        $this->middleware('auth:dropshipper')
            ->except(['import', 'download', 'webhooksShipping', 'downloadInvoice', 'confirmOnlinePayment', 'confirmOnlinePaymentYourcallback', 'confirmOnlinePaymentPayNow', 'showPaymentStatus']);
        $this->countryService = $countryService;
        $this->cityService = $cityService;
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
        return  $this->apiResponse([
            'filedValid' => PendingOrder::where('dropshipper_id', user()->id)->where('invalid', 1)->count(),
            'successValid' => PendingOrder::where('dropshipper_id', user()->id)->where('invalid', 0)->count(),
            'list' =>  $this->service->list($request, $this->pagination(), $this->perPage()),
        ]);
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
        if ($data) {
            return $this->apiResponse(new OrderResource($data));
        }
        return $this->notFoundResponse(trans('orders.notFound'));
    }

    /**
     * The function `importPendingOrders` imports pending orders from an Excel file, processes them,
     * and provides a response with success or error details.
     *
     * @param Request request The `importPendingOrders` function is responsible for importing pending
     * orders from an Excel file. Here's a breakdown of the code:
     *
     * @return The function `importPendingOrders` returns a response based on the conditions and logic
     * within the function.
     */
    public function importPendingOrders(Request $request)
    {
        try {
            executionTime();
            $extensions = ["xls", "xlsx", "csv", "xlm", "xla", "xlc", "xlt", "xlw"];

            if (!empty($request->file('excelFile'))) {
                $fileExtension = $request->file('excelFile')->getClientOriginalExtension();

                if (in_array($fileExtension, $extensions)) {
                    $pendingOrder = PendingOrder::where('dropshipper_id', user()->id)->count();
                    if ($pendingOrder > 0) {
                        return $this->unKnowError(trans('orders.Please delete or take action on everything in the table'));
                    }
                    executionTime();
                    Excel::import(new PendingOrdersImport($this->cityService, $this->service), $request->file('excelFile'));
                    executionTime();
                    try {
                        return $this->createResponse(trans('orders.Success Upload Excel File.'));
                    } catch (\Exception $e) {
                        // Log the exception
                        Log::error('Error importing Excel file: ' . $e->getMessage());
                        return $this->unKnowError(trans('orders.Failed to upload Excel file.'));
                    }
                } else {
                    return $this->apiValidation(trans('orders.Please Upload Excel File.'));
                }
            } else {
                return $this->unKnowError(trans('orders.No file uploaded.'));
            }
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error handling file upload: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function export(Request $request)
    {
        // Execute the action to export and email the file
        (new ExportPendingOrdersAction(request: $request))->execute();

        return response()->json([
            'success' => true,
            'message' => trans('orders.The pending orders export has been emailed successfully.'),
        ]);
    }

    /**
     * Destroy a pending order by its ID.
     *
     * @param int $id The ID of the pending order to be destroyed.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success or failure of the deletion process.
     */
    public function destroy($id)
    {
        $data = $this->service->show($id);

        if (! $data) {
            return $this->notFoundResponse(trans('orders.notFound'));
        }

        if (auth()->id() !=  $data->dropshipper_id) {
            // you don't have permission to delete this order
            return $this->unPermissionResponse(trans('orders.notAuthorized'));
        }

        PendingOrder::destroy($id);

        return response()->json(['message' => trans('orders.deleted')]);
    }

    public function updatePendingOrder(UpdatePendingOrderRequest $request, $id)
    {
        try {
            // update pending order action
            $data =  (new UpdatePendingOrderAction(
                request: $request,
                id: $id
            ))->execute();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => trans('orders.The pending orders Updated successfully.'),
                    'data' => $data,
                ]);
            }
        } catch (\Exception $e) {
            return $this->unKnowError($e->getMessage());
        }
    }

    public function scanOrder(Request $request)
    {
        try {
            $pendingOrders = PendingOrder::where('dropshipper_id', Auth::guard('dropshipper')->user()->id)->get();
            // update pending order action
            $data =  (new ScanPendingOrderAction($pendingOrders))->execute();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => trans('orders.The pending orders Updated successfully.'),
                ]);
            }
        } catch (\Exception $e) {
            return $this->unKnowError($e->getMessage());
        }
    }
}
