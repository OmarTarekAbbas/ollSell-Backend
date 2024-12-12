<?php

namespace Modules\Logistics\Http\Controllers;

use Modules\Basic\Http\Controllers\BasicController;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Order\Enums\OrderEnum;
use Modules\CoreData\Entities\Status;
use Illuminate\Routing\Controller;
use Modules\Order\Service\OrderService;
use Modules\Logistics\Service\ReportService;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Exports\Order\Admin\OrderExport;
use Modules\Order\Entities\Order;



class ReportController extends BasicController
{
    protected $service, $orderService;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(ReportService $Service, OrderService $orderService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:Logistic_report')->only('index');
        $this->service = $Service;
        $this->orderService = $orderService;
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $orders = $this->service->reportAllShippingCompany($request);
            return view('logistics::report.mainContent', compact('orders', 'request'));
        }
        if($request->statusId){
            $request->merge(["statusId" => $request->statusId]);
        }else{
            $request->merge(["statusId" => OrderEnum::COMPLETED_STATUS]);
        }
      
        $status = Status::whereIn('id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS])->get();
        $request->merge(['period' => 'thisMonth']);
        $orders = $this->service->reportAllShippingCompany($request);
        return $this->getDashboardView('logistics::report.all', compact('orders', 'request', 'status'));
    }

    public function exportReporting(Request $request)
    {
        $orders =Order::whereIn('id', $request->orders)->get();
     
        $timestamp = Carbon::now()->timestamp;

        $filePath = 'exports/orders-' . $timestamp . '.xlsx';
        Excel::store(new OrderExport($orders), $filePath, 'public');

        // Return the URL to access the stored file
        return response()->json(['url' => asset('storage/' . $filePath)]);
    }
    
    public function exportCustamReporting(Request $request)
    {
        if(!empty($request->city_id)){
            $orders = $this->service->reportAllOrderCities($request);
        }elseif(!empty($request->time_orders)){
            $orders = $this->service->reportAllOrderTimes($request);
        }else{
            $orders = $this->service->reportAllOrderAll($request);  
        }
       
        $timestamp = Carbon::now()->timestamp;

        $filePath = 'exports/orders-' . $timestamp . '.xlsx';
        Excel::store(new OrderExport($orders), $filePath, 'public');

        // Return the URL to access the stored file
        return response()->json(['url' => asset('storage/' . $filePath)]);
    }
    public function orderCities(Request $request)
    {

        $orders = $this->service->reportAllOrderCities($request);
        return view('logistics::report.tableCities', compact('orders', 'request'));
    }

    public function orderTimes(Request $request)
    {

        $orders = $this->service->reportAllOrderTimes($request);

        return view('logistics::report.tableCities', compact('orders', 'request'));
    }

    public function orderAll(Request $request)
    {

        $orders = $this->service->reportAllOrderAll($request);
    
        return view('logistics::report.tableCities', compact('orders', 'request'));
    }
}
