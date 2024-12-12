<?php

namespace App\Http\Controllers;

use Modules\Basic\Http\Controllers\BasicController;
use Modules\Basic\Http\Requests\DateRequest;
use Modules\Report\Service\ReportService;

class HomeController extends BasicController
{
    protected $service;

    public function __construct(ReportService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_report')->only('index');
        $this->service = $Service;
    }

    public function home(DateRequest $request)
    {
        $request->period ?? $request->merge(['period'=>'this_month']);
        $currentPeriod = $this->service->getPeriodBestOnPeriodType($request->period, $request);
        if($request->ajax())
        {
            return view('dashboard2.home.mainContent', $this->service->reportDashboard($request));
        }
        return $this->getDashboardView('dashboard2.home.index', get_defined_vars());
    }
}
