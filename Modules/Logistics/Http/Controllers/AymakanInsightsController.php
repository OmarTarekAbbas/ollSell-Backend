<?php

namespace Modules\Logistics\Http\Controllers;

use Modules\Basic\Http\Controllers\BasicController;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Order\Service\OrderService;
use Modules\Logistics\Service\AymakanInsightsService;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Exports\Order\Admin\OrderInsightsExport;

use Modules\Order\Jobs\ExportOrdersReportJob;

class AymakanInsightsController extends BasicController
{
    protected $service, $orderService;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(AymakanInsightsService $Service, OrderService $orderService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
       $this->middleware('permission:Logistic_report_aymakan')->only('index');
        $this->service = $Service;
        $this->orderService = $orderService;
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $orders = $this->service->reportAllShipping($request);
            return view('logistics::aymakanInsights.mainContent', compact('orders', 'request'));
        }
        $request->merge(['period' => 'thisMonth', 'shippingType'=>'all', 'shipmentStatus'=>'all']);
        $orders = $this->service->reportAllShipping($request);

        return $this->getDashboardView('logistics::aymakanInsights.all', compact('orders', 'request'));
    }

    public function orderAymakanInsights(Request $request)
    {
        $orders = $this->service->orderAymakanInsightsLimit($request);
        return view('logistics::aymakanInsights.showorders', compact('orders', 'request'));
    }
    public function exportInsightsOrdersReporting(Request $request)
    {
        $orders = $this->service->orderAymakanInsights($request);

        $user = user();

        $timestamp = Carbon::now()->timestamp;

        $filePath = 'exports/orders-' . $timestamp . '.xlsx';
        //   Excel::store(new OrderInsightsExport($orders), $filePath, 'public');
         ExportOrdersReportJob::dispatch($orders,$filePath, $user);

        return response()->json([
            'message' => 'Export job dispatched successfully!',
            'url' => $filePath
        ]);
    }

}
