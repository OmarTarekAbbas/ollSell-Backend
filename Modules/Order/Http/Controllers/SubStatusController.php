<?php

namespace Modules\Order\Http\Controllers;

use Modules\Order\Enums\OrderStatusEnum;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Remark;
use Modules\Order\Entities\SubStatus;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Service\SubStatusService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Basic\Http\Controllers\BasicController;

class SubStatusController extends BasicController
{
    protected $service;

    /**
     * This function sets up middleware and permissions for a SubStatusService object in a PHP
     * application.
     *
     * param SubStatusService Service The  parameter is an instance of the SubStatusService class,
     * which is likely a service class responsible for handling business logic related to subStatus
     * entities in the application. It is being injected into the constructor using dependency
     * injection.
     */
    public function __construct(SubStatusService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        // $this->middleware('permission:view_status')->only('index');
        // $this->middleware('permission:create_status')->only('create');
        // $this->middleware('permission:create_status')->only('store');
        // $this->middleware('permission:update_status')->only('edit');
        // $this->middleware('permission:update_status')->only('update');
        // $this->middleware('permission:delete_status')->only('destroy');
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $subStatuses = $this->service->findBy($request);
        return $this->getDashboardView('order::subStatus.index', compact('subStatuses'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $statuses = OrderStatusEnum::getAllStatuses();

        return $this->getDashboardView('order::subStatus.create', compact('statuses'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function edit($id, Request $request)
    {
        $subStatus = $this->service->show($id);
        $statuses = OrderStatusEnum::getAllStatuses();

        return $this->getDashboardView('order::subStatus.edit', compact('subStatus', 'statuses'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status_id' => 'required|exists:status,id',
            'remarks' => 'nullable',
        ]);

        // Find the SubStatus instance by ID
        $subStatus = SubStatus::findOrFail($id);

        // Update the SubStatus attributes
        $subStatus->name = $validatedData['name'];
        $subStatus->status_id = $validatedData['status_id'];
        $subStatus->save();

        // Update or delete remarks as needed
        if (isset($validatedData['remarks'])) {
            // Convert remarks JSON string to array if it's a string
            if (is_string($validatedData['remarks'])) {
                $validatedData['remarks'] = json_decode($validatedData['remarks'], true);
            }

            // Get the existing remarks for the sub status
            $existingRemarks = $subStatus->remarks()->pluck('name')->toArray();

            // Update or delete existing remarks
            foreach ($existingRemarks as $existingRemark) {
                // If the existing remark is not in the new remarks array, delete it
                if (!in_array($existingRemark, $validatedData['remarks'])) {
                    Remark::where('sub_status_id', $subStatus->id)->where('name', $existingRemark)->delete();
                }
            }

            // Add new remarks
            foreach ($validatedData['remarks'] as $newRemark) {
                // If the new remark does not exist in the existing remarks array, create it
                if (!in_array($newRemark, $existingRemarks)) {
                    $remark = new Remark();
                    $remark->name = $newRemark['value'];
                    $remark->sub_status_id = $subStatus->id;
                    $remark->save();
                }
            }
        } else {
            Remark::where('sub_status_id', $subStatus->id)->delete();
        }

        return redirect()->route('order.subStatus.index')->with('message', 'Sub status updated successfully');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status_id' => 'required|exists:status,id',
            'remarks' => 'nullable',
        ]);

        // Convert remarks JSON string to array if it's a string
        if (is_string($validatedData['remarks'])) {
            $validatedData['remarks'] = json_decode($validatedData['remarks'], true);
        }

        // Create a new sub status instance
        $subStatus = new SubStatus();
        $subStatus->name = $validatedData['name'];
        $subStatus->status_id = $validatedData['status_id'];
        $subStatus->save();

        // If remarks are provided, store them
        if (isset($validatedData['remarks']) && is_array($validatedData['remarks'])) {
            foreach ($validatedData['remarks'] as $remark) {
                // Create a new remark instance and associate it with the sub status
                $newRemark = new Remark();
                $newRemark->name = $remark['value'];
                $newRemark->sub_status_id = $subStatus->id;
                $newRemark->save();
            }
        }

        return redirect()->route('order.subStatus.index')->with('message', 'Sub status and remarks added successfully');
    }

    public function destroy($id)
    {
        $subStatus = SubStatus::findOrFail($id);

        $subStatus->delete();

        return response()->json(['message' => 'Sub status deleted successfully']);
    }

    public function getNextStatusOptions(Request $request)
    {
        $order = Order::find($request->input('currentOrder'));
        $nextStatuses = $order->nextPossibleStatuses();

        // Initialize sub-status and remark options arrays
        $subStatusOptions = [];
        $remarkOptions = [];

        $subStatusOptions[] = [
            'id' => '',
            'name' => 'Select Sub Status',
            'selected' => $order->subStatus ? false : true
        ];
        $remarkOptions[] = [
            'id' => '',
            'name' => 'Select Remark',
            'selected' => $order->remark ? false : true
        ];

        // Add current sub-status to subStatusOptions array if it exists
        if ($order->subStatus) {
            $subStatusOptions[] = [
                'id' => $order->subStatus->id,
                'name' => $order->subStatus->name,
                'selected' => true
            ];
        }

        // Add current remark to remarkOptions array if it exists
        if ($order->remark) {
            $remarkOptions[] = [
                'id' => $order->remark->id,
                'name' => $order->remark->name,
                'selected' => true
            ];
        }

        // Get sub-statuses for the current order status
        $statusSubStatuses = $order->status->subStatuses()->where('id', '!=', $order->subStatus?->id)->select('id', 'name')->get();
        foreach ($statusSubStatuses as $subStatus) {
            $subStatusOptions[] = [
                'id' => $subStatus->id,
                'name' => $subStatus->name,
                'selected' => false
            ];
        }

        // Get remarks for the current sub-status if it exists
        if ($order->subStatus) {
            $subStatusRemarks = $order->subStatus->remarks()->where('id', '!=', $order->remark?->id)->select('id', 'name')->get();
            foreach ($subStatusRemarks as $remark) {
                $remarkOptions[] = [
                    'id' => $remark->id,
                    'name' => $remark->name,
                    'selected' => false
                ];
            }
        }

        // Build status options array
        $statusOptions = [];
        $statusOptions[] = [
            'id' => $order->status->id,
            'name' => $this->getModifiedName($order->status->name->value),
            'selected' => true
        ];
        foreach ($nextStatuses as $nextStatus) {
            $statusOptions[] = [
                'id' => $nextStatus->id,
                'name' => $this->getModifiedName($nextStatus->name->value),
                'selected' => false
            ];
        }
        return response()->json([
            'statusOptions' => $statusOptions,
            'subStatusOptions' => $subStatusOptions,
            'remarkOptions' => $remarkOptions
        ]);
    }

    private function getModifiedName($name)
    {
        switch ($name) {
            case 'new':
                return 'New';
            case 'pending':
                return 'Pending confirmation';
            case 'shipping':
                return 'Shipping';
            case 'rejected':
                return 'Rejected';
            case 'completed':
                return 'Completed';
            case 'canceled':
                return 'Canceled';
            case 'pay_pending':
                return 'Pending payment';
            case 'payPending':
                return 'Pending payment';
            case 'preparing':
                return 'Preparing';
            default:
                return $name;
        }
    }

    public function getNextSubStatusOptions(Request $request)
    {
        $nextSubStatusOptions = SubStatus::where('status_id', $request->input('status_id'))->get();

        return response()->json($nextSubStatusOptions);
    }

    public function getNextRemarkOptions(Request $request)
    {
        $nextRemarkOptions = Remark::where('sub_status_id', $request->input('sub_status_id'))->get();

        return response()->json($nextRemarkOptions);
    }
}
