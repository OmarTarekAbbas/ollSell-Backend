<?php

namespace Modules\Order\Exports\Order\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Order\Service\OrderService;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Order\Exports\Order\Admin\OrderExport;

class OrdersExportByChunks implements WithMultipleSheets
{
    protected $request;
    protected $orderService;

    public function __construct(Request $request, OrderService $orderService)
    {
        $this->request = $request;
        $this->orderService = $orderService;
    }

    /**
     * Returns an array of sheet instances.
     */
    public function sheets(): array
    {
        $sheets = [];
        $perPage = 1000;
        $page = 1;

        do {
            // Set the current page in the request for pagination
            $this->request->merge(['page' => $page, 'per_page' => $perPage]);

            // Fetch paginated orders using the enhancedList method
            $paginatedOrders = $this->orderService->exportEnhancedList($this->request, true, $perPage);

            $orders = $paginatedOrders['data'];
            // Log the current page and orders for debugging
            Log::channel('orders')->info($page);
            // Log::channel('orders')->info($paginatedOrders['recordsTotal']);

            // If there are orders, add them as a new sheet
            if (! empty($orders)) {
                $sheets[] = new OrderExport($orders);
            }

            $page++;
        } while (count($orders) === $perPage);

        return $sheets;
    }
}
