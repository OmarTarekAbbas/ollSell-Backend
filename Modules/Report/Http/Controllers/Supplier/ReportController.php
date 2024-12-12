<?php

namespace Modules\Report\Http\Controllers\Supplier;

use Modules\Basic\Http\Requests\DateRequest;
use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;
use Modules\Report\Service\ReportService;
use Modules\Supplier\Entities\Warehouse;

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
        $this->middleware('auth:supplier');
        $this->service = $Service;
    }

    public function default(DateRequest $request)
    {     
        //todo change
        $supplierId = auth()->id();
        // warehouses count
        $warehousesCount = Warehouse::where('supplier_id', auth()->id())->count();
        $products  = Product::where('supplier_id', auth()->id())->get();
        // products count
        $totalProducts = Product::where('supplier_id', auth()->id())->count();
        $productStatistics = [];

        foreach ($products as $product) {
            $productOrders = OrderItem::where('product_id', $product->id)
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status_id', OrderEnum::COMPLETED_STATUS)
                ->get();

            $productStatistics[] = [
                'product' => $product,
                'total_orders' => $productOrders->count(),
                'total_revenue' => $productOrders->sum('total_price'),
            ];
        }

        $topProducts = collect($productStatistics)->sortByDesc('total_revenue')->take(5);

        $totalOrders = Order::whereHas('orderItems.product', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })->count();

        $orderItems = OrderItem::whereHas('product', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })->get();

        // Calculate total revenue
        $totalRevenue = $orderItems->sum(function ($orderItem) {
            return $orderItem->unitPrice * $orderItem->quantity;
        });

        // Get total products sold for the supplier
        $orders = Order::whereHas('orderItems.product', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })->get();

        $totalProductsSold = $orders->flatMap(function ($order) {
            return $order->orderItems;
        })->sum('quantity');

        return $this->getDashboardView('report::report.supplier.default.index', [
            'warehousesCount' => $warehousesCount,
            'productsCount' => $totalProducts,
            'productStatistics' => $productStatistics,
            'topProducts' => $topProducts,
            'totalRevenue' => $totalRevenue,
            'totalProductsSold' => $totalProductsSold
        ]);
    }

    public function allProductReport(Request $request)
    {
        $request->merge(['supplier_id'=>[user()->id]]);
        if($request->ajax())
        {
            return view('report::report.supplier.product.mainContent', $this->service->reportAllProduct($request));
        }
        return $this->getDashboardView('report::report.supplier.product.all', $this->service->reportAllProduct($request));
    }
}
