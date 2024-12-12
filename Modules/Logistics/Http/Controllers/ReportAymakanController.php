<?php

namespace Modules\Logistics\Http\Controllers;

use Modules\Basic\Http\Controllers\BasicController;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Order\Service\OrderService;
use Modules\Logistics\Service\ReportAymakanService;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Exports\Order\Admin\OrderExport;



class ReportAymakanController extends BasicController
{
    protected $service, $orderService;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(ReportAymakanService $Service, OrderService $orderService)
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
            return view('logistics::reportAymakan.mainContent', compact('orders', 'request'));
        }
        $request->merge(['period' => 'thisMonth', 'status'=>'all']);
        $orders = $this->service->reportAllShipping($request);

        return $this->getDashboardView('logistics::reportAymakan.all', compact('orders', 'request'));
    }

    public function orderAymakan(Request $request)
    {
        $orders = $this->service->orderAymakan($request);
        return view('logistics::reportAymakan.showorders', compact('orders', 'request'));
    }
    public function exportCustamOrdersReporting(Request $request)
    {
        $orders = $this->service->orderAymakan($request);
       
        $timestamp = Carbon::now()->timestamp;

        $filePath = 'exports/orders-' . $timestamp . '.xlsx';
        Excel::store(new OrderExport($orders), $filePath, 'public');

        // Return the URL to access the stored file
        return response()->json(['url' => asset('storage/' . $filePath)]);
    }

}
