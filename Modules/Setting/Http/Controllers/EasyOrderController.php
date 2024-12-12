<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Exports\Order\MissingOrdersExport;
use Modules\Setting\Service\RequestLogService;
use Modules\Basic\Http\Controllers\BasicController;

/**
 * @extends BasicController
 * controller user about web function
 */
class EasyOrderController extends BasicController
{
    protected $service;

    /**
     * controller user about web function
     * @required user login
     */
    public function __construct(RequestLogService $service)
    {
        $this->middleware('auth')->except('download');
        $this->middleware('admin')->except('download');
        $this->service = $service;
    }

    /**
     * param Request $request
     * get all user to manage it
     */
    public function index()
    {
        return $this->getDashboardView('setting::log.easy_order.index');
    }

    public function download(Request $request)
    {
        if(file_exists(public_path("missings/easy_order/" . $request->date . "/orders_failed_rows.csv")))
        {
            $csvFile = fopen(public_path("missings/easy_order/" . $request->date . "/orders_failed_rows.csv"), "r");
            $firstLine = true;
            while(($data = fgetcsv($csvFile, 2000, ",")) !== false)
            {
                if($firstLine)
                {
                    $firstLine = false;
                    continue;
                }
                $data['message'] = $data['message'] ?? null;
                $new[] = $data;
            }
            return Excel::download(
                new MissingOrdersExport($new), $request->date .".xlsx"
            );
            fclose($csvFile);
            return true;
        }
        return $this->getDashboardView('setting::log.easy_order.index');
    }
}
