<?php

namespace Modules\Report\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Basic\Http\Controllers\BasicController;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Jobs\ExportValidationJob;
use Modules\Report\Exports\ValidationOperatorExport;
use Modules\Report\Service\ReportService;

class ValidationStatisticsController extends BasicController
{
    public function __construct(ReportService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:validation_performance_report')->only('allReport');
        $this->service = $Service;
    }
    public function index()
    {
        return $this->getDashboardView('report::report.admin.validation.index', get_defined_vars());
    }

    public function getStats(Request $request)
    {
        $query = Order::whereNotNull('validated');

        // Apply filters
        if ($request->validated_by) {
            if ($request->validated_by === 'system') {
                // Include both 'system' and null values for system orders
                $query->where(function ($query) {
                    $query->where('validated_by', 'system')
                        ->orWhereNull('validated_by');
                });
            } else {
                $query->where('validated_by', $request->validated_by);
            }
        }

        if ($request->validated_from && $request->validated_to) {
            $query->whereBetween('validated', [
                Carbon::parse($request->validated_from)->startOfDay(),
                Carbon::parse($request->validated_to)->endOfDay()
            ]);
        }

        if ($request->validated_from) {
            $query->where('validated', '>=', Carbon::parse($request->validated_from)->startOfDay());
        }
        if ($request->validated_to) {
            $query->where('validated', '<=', Carbon::parse($request->validated_to)->endOfDay());
        }

        // Created at filter
        if ($request->created_from && $request->created_to) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->created_from)->startOfDay(),
                Carbon::parse($request->created_to)->endOfDay()
            ]);
        }

        if ($request->created_from) {
            $query->where('created_at', '>=', Carbon::parse($request->created_from)->startOfDay());
        }
        if ($request->created_to) {
            $query->where('created_at', '<=', Carbon::parse($request->created_to)->endOfDay());
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Total Orders
        $totalOrders = $orders->count();

        // Total SAR
        $totalSAR = $orders->sum('grandTotal');

        // Orders by System
        $ordersBySystem = $orders->filter(function ($order) {
            return $order->validated_by === 'system' || is_null($order->validated_by);
        })->count();

        $ordersBySMS = $orders->where('validated_by', 'sms')->count();
        $ordersByWhatsapp = $orders->where('validated_by', 'whatsapp')->count();

        // Initialize counters for confirmation after each message

        foreach ($orders as $order) {
            $validated = Carbon::parse($order->validated);
            $firstMessageTime = $order->first_message_time ? Carbon::parse($order->first_message_time) : null;
            $secondMessageTime = $order->second_message_time ? Carbon::parse($order->second_message_time) : null;
        }

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalSAR' => number_format($totalSAR, 2, '.', ','),
            'ordersBySystem' => $ordersBySystem,
            'ordersBySMS' => $ordersBySMS,
            'ordersByWhatsapp' => $ordersByWhatsapp,
        ]);
    }

    public function getFilteredOrders(Request $request)
    {
        $query = Order::query();

        $query->whereNotNull('validated');

        if ($request->validated_by) {
            if ($request->validated_by === 'system') {
                // Include both 'system' and null values for system orders
                $query->where(function ($query) {
                    $query->where('validated_by', 'system')
                        ->orWhereNull('validated_by');
                });
            } else {
                $query->where('validated_by', $request->validated_by);
            }
        }

        // Validated at filter
        if ($request->validated_from && $request->validated_to) {
            $query->whereBetween('validated', [
                Carbon::parse($request->validated_from)->startOfDay(),
                Carbon::parse($request->validated_to)->endOfDay()
            ]);
        }

        if ($request->validated_from) {
            $query->where('validated', '>=', Carbon::parse($request->validated_from)->startOfDay());
        }
        if ($request->validated_to) {
            $query->where('validated', '<=', Carbon::parse($request->validated_to)->endOfDay());
        }

        // Created at filter
        if ($request->created_from && $request->created_to) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->created_from)->startOfDay(),
                Carbon::parse($request->created_to)->endOfDay()
            ]);
        }

        if ($request->created_from) {
            $query->where('created_at', '>=', Carbon::parse($request->created_from)->startOfDay());
        }
        if ($request->created_to) {
            $query->where('created_at', '<=', Carbon::parse($request->created_to)->endOfDay());
        }

        // Handle pagination parameters
        $itemsPerPage = $request->get('itemsPerPage', 10);
        $orders = $query->with('validationOperator')->orderBy('created_at', 'desc')->paginate($itemsPerPage);
        return response()->json($orders);
    }

    public function exportValidation()
    {
        return Excel::download(new ValidationOperatorExport(), 'export_validation.xlsx');
    }

    public function exportAdvancedValidation(Request $request)
    {
        $user = auth()->user();
        // cache()->forget('order-export-' . $user->id);
        // Check if an export is already in progress
        if (cache()->has('export_validation-' . $user->id)) {
            return response()->json(['message' => 'An export is already in progress. Please wait.'], 429);
        }

        // Set a cache key to prevent multiple exports
        cache()->put('export_validation-' . $user->id, true, now()->addMinutes(15));

        // Extract necessary filters from the request
        $filters = $request->all();
        // Dispatch the export job with filters
        ExportValidationJob::dispatch($user, $filters)->onQueue('exports');

        return response()->json(['message' => 'Your export is being processed. You will receive an email when it is ready.']);
    }

    public function allReport(Request $request)
    {
        if ($request->ajax()) {
            return view('report::report.admin.validation.performance.mainContent', $this->service->reportValidationPerformance($request));
        }
        return $this->getDashboardView('report::report.admin.validation.performance.all', $this->service->reportValidationPerformance($request));
    }
}
