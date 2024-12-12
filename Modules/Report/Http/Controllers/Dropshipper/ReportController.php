<?php

namespace Modules\Report\Http\Controllers\Dropshipper;

use Carbon\Carbon;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Basic\Http\Requests\DateRequest;
use Modules\Report\Service\ReportService;

class ReportController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a ReportService object.
     *
     * param ReportService Service The parameter "Service" is an instance of the "ReportService" class
     * that is being injected into the constructor of another class. This is a common practice in
     * dependency injection, where the dependencies of a class are passed in through its constructor
     * rather than being created inside the class itself. This allows for better
     */
    public function __construct(ReportService $Service)
    {
        $this->service = $Service;
    }

    public function reportRequests(DateRequest $request)
    {
        $currentPeriod = $this->service->getPeriodBestOnPeriodType($request->filter, $request);
        $lastCurrentPeriod = $this->service->getLastPeriodBestOnPeriodType($request->filter, $request);
        $data = $this->service->reportRequests($request, $currentPeriod, $lastCurrentPeriod);
        $request->merge(['chartType' => 'grandTotal']);
        $reportChart = $this->service->reportChart($request, $currentPeriod);
        $reportProduct = $this->service->reportRequestsProduct($request, $currentPeriod);
        return [
            'report' => $data,
            'salesAnalytics' => $reportChart,
            'topSellingProducts' => $reportProduct
        ];
    }

    public function reportFinancial(DateRequest $request)
    {
        $currentPeriod = $this->service->getPeriodBestOnPeriodType($request->filter, $request);
        $lastCurrentPeriod = $this->service->getLastPeriodBestOnPeriodType($request->filter, $request);
        $data = $this->service->reportFinancial($request, $currentPeriod, $lastCurrentPeriod);
        $request->merge(['chartType' => 'net_profit']);
        $profitMargin = $this->service->reportChart($request, $currentPeriod);
        $request->merge(['chartType' => 'grandTotal']);
        $salesAnalytics = $this->service->reportChart($request, $currentPeriod);
        $grandTotal = array();
        foreach($salesAnalytics['grandTotal'] as $i => $iValue)
        {
            if(isset($salesAnalytics['grandTotal'][$i - 1]))
            {
                $last =$salesAnalytics['grandTotal'][$i - 1] ?? 0;
            }else{
                $request->merge(['chartType' => 'grandTotal']);
                $salesAnalytics1 = $this->service->reportChart($request,
                    ['from'=>Carbon::parse($salesAnalytics['point'][$i])->subDay()->startOfDay(),
                        'to'=>Carbon::parse($salesAnalytics['point'][$i])->subDay()->endOfDay()]);
                $last = $salesAnalytics1['grandTotal'][0] ?? 0;
            }
            $current = $iValue;
            $percentage = 0;
            if($last != 0)
            {
                $percentage = $this->service->percentageChange($current, $last);
            }
            $grandTotal[] = $percentage;
        }
        $salesAnalytics['grandTotal'] = $grandTotal;
        unset($salesAnalytics['grandTotal'][0], $salesAnalytics['count'][0], $salesAnalytics['point'][0]);
        return [
            'report' => $data,
            'salesAnalytics' => $salesAnalytics,
            'profitMargin' => $profitMargin,
        ];
    }
}
