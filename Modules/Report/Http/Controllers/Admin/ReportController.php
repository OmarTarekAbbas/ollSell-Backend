<?php

namespace Modules\Report\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Basic\Http\Requests\DateRequest;
use Modules\Report\Service\ReportService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Exports\OrderRemarkCancellationRates;
use Modules\Report\Exports\OrderSourcesReportExport;

class ReportController extends BasicController
{
    protected $service;

    /**
     * This is a constructor function that sets middleware and a service for a report controller in a
     * PHP application.
     *
     * param ReportService Service The `` parameter is an instance of the `ReportService`
     * class that is being injected into the constructor of the current class. This is a common
     * practice in Laravel and other PHP frameworks, where dependencies are injected into classes
     * rather than being instantiated within the class itself. This allows for better testability
     */
    public function __construct(ReportService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_report')->only('index');
        $this->middleware('permission:product_report')->only('allProductReport');
        $this->middleware('permission:platform_performance_report')->only('platformPerformanceReport');
        $this->middleware('permission:performance_report')->only('allperformanceReport');
        $this->middleware('permission:canceled_performance_report')->only('cancelledPerformance');
        $this->middleware('permission:payment_performance_report')->only('paymentPerformance');
        $this->middleware('permission:wms_performance_report')->only('wmsPerformance');
        $this->middleware('permission:order_sources_report')->only('orderSourcesReport');
        $this->service = $Service;
    }
    //todo change
    public function default(DateRequest $request)
    {
        if (empty($request->period)) {
            $request->merge(["period" => "this_month"]);
        }
        $TopProducts = $this->service->getTopProducts($request) ?? [];
        foreach ($TopProducts as $row) {
            $row->name = $this->service->getProduct($row)->name->value ?? "";
        }
        $TopCategories = $this->service->getTopCategories($request) ?? [];
        foreach ($TopCategories as $row) {
            if ($row->category_id) {
                $row->name = $this->service->getCategory($row)->name->value ?? "";
            }
        }
        $topOrders = $this->service->ordersChart($request);
        $topUsers = $this->service->topUsers($request);
        if ($request->ajax()) {
            return view('report::report.admin.default.mainContent', get_defined_vars());
        }
        return $this->getDashboardView('report::report.admin.default.report', get_defined_vars());
    }

    public function allProductReport(DateRequest $request)
    {
        if ($request->ajax()) {
            return view('report::report.admin.product.mainContent', $this->service->reportAllProduct($request));
        }
        return $this->getDashboardView('report::report.admin.product.all', $this->service->reportAllProduct($request));
    }

    public function allperformanceReport(DateRequest $request)
    {
        $request->period ?? $request->merge(['period' => 'this_month']);
        $currentPeriod = $this->service->getPeriodBestOnPeriodType($request->period, $request);
        if ($request->ajax()) {
            return view('report::report.admin.performance.mainContent', $this->service->reportAllPerformance($request));
        }
        return $this->getDashboardView('report::report.admin.performance.all', get_defined_vars());
    }

    public function platformPerformanceReport(DateRequest $request)
    {
        $request->period ?? $request->merge(['period' => 'this_month']);
        $currentPeriod = $this->service->getPeriodBestOnPeriodType($request->period, $request);
        if ($request->ajax()) {
            return view('report::report.admin.performance.platform.mainContent', $this->service->platformPerformanceReport($request));
        }
        return $this->getDashboardView('report::report.admin.performance.platform.all', get_defined_vars());
    }

    public function productCapastePerformance(Request $request)
    {
        $request->period ?? $request->merge(['period' => 'this_month']);
        if ($request->ajax()) {
            return view('report::report.admin.performance.product.table', $this->service->productCapastePerformance($request));
        }
        return $this->getDashboardView('report::report.admin.performance.product.index', $this->service->productCapastePerformance($request));
    }

    public function cancelledPerformance(DateRequest $request)
    {
        $request->period ?? $request->merge(['period' => 'this_month']);
        if ($request->ajax()) {
            return view('report::report.admin.performance.cancelled.mainContent', $this->service->cancelledPerformance($request));
        }
        return $this->getDashboardView('report::report.admin.performance.cancelled.all', $this->service->cancelledPerformance($request));
    }

    public function paymentPerformance(Request $request)
    {
        if ($request->ajax()) {
            return view('report::report.admin.performance.payment.mainContent', $this->service->reportPaymentPerformance($request));
        }
        return $this->getDashboardView('report::report.admin.performance.payment.all', $this->service->reportPaymentPerformance($request));
    }

    /**
     * Generates an order sources report for a specified period and returns the view with the report data.
     * Sets the period to 'this_month' if no period is specified in the request.
     * 
     * @param Request $request The request object containing the parameters for the report.
     * 
     * @return \Illuminate\View\View The view for the order sources report, either as an AJAX response or a full page view.
     */
    public function orderSourcesReport(Request $request)
    {
        $request->merge(['period' => $request->period ?? 'this_month']);
        $result = $this->service->orderSourcesReport($request);

        if ($request->ajax()) {
            return view(
                'report::report.admin.orderSourcesReport.performance.mainContent',
                compact('request', 'result')
            );
        }

        return $this->getDashboardView(
            'report::report.admin.orderSourcesReport.performance.all',
            compact('request', 'result')
        );
    }

    /**
     * Exports the order sources report to an Excel file.
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Excel file download of the order sources report.
     */
    public function exportOrderSourcesReport()
    {
        return Excel::download(new OrderSourcesReportExport(), 'export_orderSourcesReport.xlsx');
    }

    /**
     * Exports the remark cancellation rates to an Excel file.
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Excel file download of the remark cancellation rates report.
     */
    public function exportRemarkCancellationRates()
    {
        return Excel::download(new OrderRemarkCancellationRates(), 'export_remarkCancellationRates.xlsx');
    }

    public function wmsPerformance(Request $request)
    {
        $request->period ?? $request->merge(['period' => 'this_month']);
        if ($request->ajax()) {
            return view('report::report.admin.performance.wms.mainContent', $this->service->wmsPerformance($request));
        }
        return $this->getDashboardView('report::report.admin.performance.wms.all', $this->service->wmsPerformance($request));
    }
}
