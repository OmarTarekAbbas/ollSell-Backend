<?php

namespace Modules\Order\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Remark;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Enums\PaymentEnum;
use App\Services\OllopsClientService;
use Modules\Acl\Entities\Dropshipper;
use Modules\CoreData\Entities\Status;
use Modules\Order\Entities\SubStatus;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Jobs\ExportOrdersJob;
use Modules\Order\Service\OrderService;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\CoreData\Service\CityService;
use Modules\Order\Jobs\ProcessOrderImport;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Service\AttemptsLogService;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Order\Actions\Order\SendMessageAction;
use Modules\Order\Exports\Order\Admin\OrderExport;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Actions\Order\AdminUpdateOrderAction;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;
use Modules\Order\Actions\Order\GetMessageTemplatesAction;
use Modules\Order\Actions\Order\StartValidationFlowAction;
use Modules\Order\Http\Resources\Order\Admin\OrderResource;
use Modules\Order\Actions\Order\SyncUpdateOrderStatusAction;
use Modules\Order\Http\Resources\Order\Admin\RemarkResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductResource;

class OrderListController extends BasicController
{
    protected $service;
    protected $attemptsLogService;

    public function __construct(OrderService $Service, AttemptsLogService $attemptsLogService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_order')->only('index');
        $this->middleware('permission:extract_order')->only('extract');
        $this->service = $Service;
        $this->attemptsLogService = $attemptsLogService;
    }

    public function index(Request $request)
    {
        // $orders = $this->service->index($request);
        $canUpdateOrder = user()->can('update_order');
        $canViewAll = user()->can('view_all_order');
        $haveBothMajorPermissions = $canUpdateOrder && $canViewAll;
        return $this->getDashboardView('order::listing.index', [
            'canUpdateOrder' => $canUpdateOrder,
            'canViewAll' => $canViewAll,
            'haveBothMajorPermissions' => $haveBothMajorPermissions,
        ]);
    }

    public function orders(Request $request)
    {
        $orders = $this->service->enhancedList(request: $request, pagination: true, perPage: request('perPage') ?? 10);
        $ordersArray = $orders->toArray();
        return response()->json([
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $ordersArray['current_page'],
                'from' => $ordersArray['from'],
                'last_page' => $ordersArray['last_page'],
                'per_page' => $ordersArray['per_page'],
                'to' => $ordersArray['to'],
                'total' => $ordersArray['total'],
            ],
        ]);
        // return response()->json(OrderResource::collection($orders));
    }

    public function getCitiesBaedOnCountryId(Request $request)
    {
        $request->merge(['status' => activeType()['as']]);
        $cities = App(CityService::class)->findBy($request);
        // Transform the data to an array of objects with id and name properties
        $transformedCities = [];
        foreach ($cities as $city) {
            $transformedCities[] = [
                'id' => $city->id,
                'name' => $city->name['value'], // Assuming 'name' is an array
            ];
        }
        return response()->json($transformedCities);
    }

    public function getStatuses(Request $request)
    {
        $statuses = OrderStatusEnum::getAllFilterStatuses();
        foreach ($statuses as &$status) {
            $statusCount = Order::where('status_id', $status['id']);
            if ($status['id'] == OrderEnum::PENDING_STATUS) {
                $statusCount = $statusCount->where('validated', null);
            }
            $status['count'] = $statusCount->count();
        }
        $statuses[3]['count'] = Order::whereNotNull('validated')->whereNull('tracking_number')
            ->where('status_id', OrderEnum::PENDING_STATUS)->count();
        return response()->json($statuses);
    }

    public function orderLogs($id)
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
            $log->user_name = $log->user?->name;
            return $log;
        });
        return response()->json($logs);
    }

    public function updateOrderAddress(Request $request, $id)
    {
        $order = Order::find($id);
        (new AdminUpdateOrderAction(
            order: $order,
            request: $request
        ))->execute();
        return new OrderResource($order->refresh());
    }

    public function updateOrderStatus(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Retrieve data from the request
            $status_id = $request->input('status_id');
            // find core data
            $order = Order::find($request->input('currentOrder'));
            if ($status_id == OrderEnum::REFUND_REPLACEMENT_REQUESTED_STATUS) {
                // Check if deliveryDate is more than 7 days from today
                $deliveryDate = \Carbon\Carbon::parse($order->deliveryDate);
                $today = \Carbon\Carbon::today();
                if ($deliveryDate->diffInDays($today) > 7) {
                    // Add order to not updated list
                    return new OrderResource($order->refresh());
                }
            }
            if ($status_id == OrderEnum::CANCELED_STATUS && $order->status_id == OrderEnum::PREPARING_STATUS) {
                $check = $this->service->cancelOrder($request);
                if (!$check) {
                    // order cannot be prepare if not validated
                    return response()->json([
                        "message" => "Can't Cancelled Order need to Call Aymakan",
                        "data" => new OrderResource($order->refresh())
                    ]);
                }
            }
            if ($order->status_id != $status_id && $status_id == OrderEnum::PREPARING_STATUS && !$order->validated) {
                // order cannot be prepare if not validated
                return response()->json([
                    "message" => "Order need to be validated first",
                    "data" => new OrderResource($order->refresh())
                ]);
            }
            if ($order->status_id != $status_id && $status_id == OrderEnum::PREPARING_STATUS && (($order->status_click_payment != ClickPayEnum::Pay && $order->paymentMethod == PaymentEnum::ONLINE_METHOD_ID) || ($order->paymentMethod == PaymentEnum::CASH_ON_DELIVERY_ID && !user()->can('cod_preparing_order')) || ($order->paymentMethod == PaymentEnum::WALLET_METHOD_ID && $order->validated_by != 'prepaid'))) {
                // order cannot be prepare if not validated
                return response()->json([
                    "message" => "Order need to be Paid first",
                    "data" => new OrderResource($order->refresh())
                ]);
            }
            (new SyncUpdateOrderStatusAction(
                order: $order,
                status_id: $status_id
            ))->execute();
            return new OrderResource($order->refresh());
        });
    }

    public function updateOrderSubStatus(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $sub_status_id = $request->input('sub_status_id');
            // find core data
            $order = Order::find($request->input('currentOrder'));
            $order->sub_status_id = $sub_status_id;
            $order->remark_id = null;
            // Save changes to the order
            $order->save();
            return new OrderResource($order->refresh());
        });
    }

    public function updateOrderRemark(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $remark_id = $request->input('remark_id');
            // find core data
            $order = Order::find($request->input('currentOrder'));
            $order->remark_id = $remark_id;
            // Save changes to the order
            $order->save();
            return new OrderResource($order->refresh());
        });
    }

    public function getSubStatuses(Request $request)
    {
        return response()->json(SubStatus::with('status.name')->get());
    }

    public function getDropshippers(Request $request)
    {
        $data = [];
        if (!empty($request['query'])) {
            $data = $this->service->getDropshipper(new Request(['term' => $request['query']]));
        }
        return response()->json($data);
    }

    public function getProducts(Request $request)
    {
        $data = [];
        if (!empty($request['query'])) {
            $data = ProductResource::collection($this->service->getProducts(new Request(['term' => $request['query']])));
        }
        return response()->json($data);
    }

    public function getRemarks(Request $request)
    {
        return response()->json(RemarkResource::collection(Remark::all()));
    }

    public function getOperators(Request $request)
    {
        $operators = $this->service->userList($request);
        return response()->json($operators);
    }

    public function validateOrder(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order->validated) {
        } else {
            $status_id = $order->status_id;
            if ($status_id == OrderEnum::NEW_STATUS || $status_id == OrderEnum::PENDING_STATUS || $status_id == OrderEnum::ONHOLD_STATUS) {
                $order->sub_status_id = null;
                $order->remark_id = null;
                $order->validated = now();
                $order->validated_by = 'system';
                $order->validation_operator_id = user()->id;
                $order->save();
            }
        }
        return new OrderResource($order->refresh());
    }

    public function updateAttempts(Request $request, $id)
    {
        $order = Order::find($id);
        if (!user()->canUpdateOrder($order)) {
            return new OrderResource($order->refresh());
        }
        $order->update([
            'attempts_count' => $request->attempts
        ]);
        $this->attemptsLogService->store($order->refresh());
        return new OrderResource($order->refresh());
    }

    public function bulkUpdateStatus(Request $request)
    {
        $status = $request->input('status');
        $orderIds = $request->input('orderIds');
        // Fetch orders with their current statuses
        $orders = Order::whereIn('id', $orderIds)->get();
        $ordersToUpdate = [];
        $ordersNotUpdated = [];
        foreach ($orders as $order) {
            if (!user()->canUpdateOrder($order)) {
                $ordersNotUpdated[] = $order;
                continue;
            }
            // Check if the new status is different from the current status
            if ($order->status_id != $status) {
                $nextPossibleStatuses = $order->nextPossibleStatuses()->pluck('id')->toArray();
                // Check if the new status is in the next possible statuses for the order
                if (in_array($status, $nextPossibleStatuses)) {
                    if ($status == OrderEnum::REFUND_REPLACEMENT_REQUESTED_STATUS) {
                        // Check if deliveryDate is more than 7 days from today
                        $deliveryDate = \Carbon\Carbon::parse($order->deliveryDate);
                        $today = \Carbon\Carbon::today();
                        if ($deliveryDate->diffInDays($today) > 7) {
                            // Add order to not updated list
                            $ordersNotUpdated[] = $order;
                            continue;
                        }
                    }
                    if ($status == OrderEnum::CANCELED_STATUS && $order->status_id == OrderEnum::PREPARING_STATUS) {
                        $check = $this->service->cancelOrder($request);
                        if (!$check) {
                            // order cannot be prepare if not validated
                            return response()->json([
                                "message" => "Can't Cancelled Order need to Call Aymakan",
                                "data" => new OrderResource($order->refresh())
                            ]);
                        }
                    }
                    if ($order->status_id != $status && $status == OrderEnum::PREPARING_STATUS && !$order->validated) {
                        $ordersNotUpdated[] = $order;
                        continue;
                    }
                    if ($order->status_id != $status && $status == OrderEnum::PREPARING_STATUS && (($order->status_click_payment != ClickPayEnum::Pay && $order->paymentMethod == PaymentEnum::ONLINE_METHOD_ID) || ($order->paymentMethod == PaymentEnum::CASH_ON_DELIVERY_ID && !user()->can('cod_preparing_order')) || ($order->paymentMethod == PaymentEnum::WALLET_METHOD_ID && $order->validated_by != 'prepaid'))) {
                        // order cannot be prepare if not validated
                        return response()->json([
                            "message" => "Order need to be Paid first",
                            "data" => new OrderResource($order->refresh())
                        ]);
                    }
                    (new SyncUpdateOrderStatusAction(
                        order: $order,
                        status_id: $status
                    ))->execute();
                    $ordersToUpdate[] = new OrderResource($order->refresh());
                } else {
                    // Add the order to the list of orders not updated
                    $ordersNotUpdated[] = $order;
                }
            }
        }
        return response()->json([
            'updatedOrders' => $ordersToUpdate,
            'notUpdatedOrders' => $ordersNotUpdated
        ]);
    }

    public function export(Request $request)
    {
        $orders = $this->service->enhancedList(request: $request, pagination: false);
        $timestamp = Carbon::now()->timestamp;
        $filePath = 'exports/orders-' . $timestamp . '.xlsx';
        Excel::store(new OrderExport($orders), $filePath, 'public');
        // Return the URL to access the stored file
        return response()->json(['url' => asset('storage/' . $filePath)]);
    }

    public function exportAdvanced(Request $request)
    {
        $user = auth()->user();
        // cache()->forget('order-export-' . $user->id);
        // Check if an export is already in progress
        if (cache()->has('order-export-' . $user->id)) {
            return response()->json(['message' => 'An export is already in progress. Please wait.'], 429);
        }
        // Set a cache key to prevent multiple exports
        cache()->put('order-export-' . $user->id, true, now()->addMinutes(30));
        // Extract necessary filters from the request
        $filters = $request->all();
        $columns = $request->input('columns', []);
        // Dispatch the export job with filters
        ExportOrdersJob::dispatch($user, $filters, $columns)->onQueue('exports');
        return response()->json(['message' => 'Your export is being processed. You will receive an email when it is ready.']);
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Store the uploaded file and get its path
            $filePath = $file->store('imports'); // Adjust the storage path as needed
            // Dispatch the import job to the queue with the file path
            ProcessOrderImport::dispatch($filePath);
            return response()->json(['message' => 'Import operation queued for processing.']);
        }
        return response()->json(['error' => 'File not found.'], 400);
    }

    public function assign(Request $request)
    {
        // Validate the request data
        $request->validate([
            'orderIds' => 'required|array',
            'operator_id' => 'required|exists:users,id',
        ]);
        $ordersToUpdate = [];
        $ordersNotUpdated = [];
        // Assign orders to the specified operator
        $orders = Order::whereIn('id', $request->orderIds)
            ->get();
        foreach ($orders as $order) {
            if (user()->canUpdateOrder($order)) {
                $order->update([
                    'operator_id' => $request->operator_id,
                    'assigned_at' => now(),
                ]);
                $ordersToUpdate[] = new OrderResource($order->refresh());
            } else {
                $ordersNotUpdated[] = new OrderResource($order->refresh());
            }
        }
        return response()->json([
            'updatedOrders' => $ordersToUpdate,
            'notUpdatedOrders' => $ordersNotUpdated
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->merge(['is_admin' => true]);
        try {
            $order = Order::find($id);
            $response = $this->service->update($request, $id);
            if (!$response) {
                return $this->unKnowError();
            }
            return new OrderResource($order->refresh());
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    public function startValidationFlow(Request $request)
    {
        return (new StartValidationFlowAction(request: $request))->execute();
    }

    /**
     * This PHP function retrieves orders validated by the system and updates the validation operator
     * ID based on the order's prepare status.
     */
    public function scriptValidationOperator()
    {
        $orders = Order::where('validated_by', 'system')
            ->orWhereNull('validated_by')
            ->whereNull('validation_operator_id')
            ->get();
        foreach ($orders as $order) {
            $prepareStatus = $order->orderLogs()
                ->where('new_value', 13)
                ->where('attribute_changed', 'status_id')
                ->first();
            if ($prepareStatus) {
                // check if the user exist
                if (!$prepareStatus->user_id) {
                    Log::info('Order prepare status doesn\'t have a user: ' . $order->id);
                    continue;
                }
                if (!User::find($prepareStatus->user_id)->exists()) {
                    Log::info('Order prepare status user doesnt exist: ' . $order->id);
                    continue;
                }
                $order->update([
                    'validation_operator_id' => $prepareStatus->user_id
                ]);
                Log::info('Order prepare status updated: ' . $order->id);
            } else {
                Log::info('Order prepare status doesn\'t exist: ' . $order->id);
            }
        }
    }

    public function searchProducts(Request $request)
    {
        $request->merge(['isApproved' => 1, 'perPage' => 5, 'pagination' => true, 'orderBy' => ['column' => 'quantity', 'order' => 'desc']]);
        return $this->apiResponse(app(ProductService::class)->list($request, $this->pagination(), $this->perPage()));
    }

    public function addItem(Request $request, $id)
    {
        $order = Order::find($id);
        $this->service->update($request, $id);
        return new OrderResource($order->refresh());
    }

    public function messageTemplates()
    {
        $messageServices = app(GetMessageTemplatesAction::class)->execute();

        return response()->json($messageServices);
    }

    public function sendMessage(Request $request)
    {
        (new SendMessageAction(
            payload: $request->all()
        ))->execute();

        return response()->json(['message' => 'Message sent successfully']);
    }

    /**
     * The function `updateDiscount` updates a discount for a specific order and handles any exceptions
     * that may occur.
     * 
     * @param Request request  is an instance of the Request class, which contains the data
     * sent by the client in the HTTP request.
     * @param id The `id` parameter in the `updateDiscount` function is used to identify the specific
     * order that needs to be updated with the discount information. It is typically an integer value
     * that corresponds to the unique identifier of the order in the database.
     * 
     * @return If the update operation is successful, an instance of the `OrderResource` class
     * representing the updated order will be returned. If there is an unknown error during the update
     * process, the `unKnowError` method will be called. If an exception is caught during the update
     * process, the `unKnowError` method will be called with the exception message as a parameter.
     */
    public function updateDiscount(Request $request, $id)
    {

        $request->merge(['is_admin' => true, 'applied_discount' => true]);
        try {
            $order = Order::find($id);
            $response = $this->service->update($request, $id);
            if (!$response) {
                return $this->unKnowError();
            }
            return new OrderResource($order->refresh());
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }
}
