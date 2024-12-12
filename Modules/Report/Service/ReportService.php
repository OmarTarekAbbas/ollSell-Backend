<?php

namespace Modules\Report\Service;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Acl\Service\DropshipperService;
use Modules\Basic\Http\Resources\Media\mediaResource;
use Modules\CoreData\Service\CityService;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Finance\Service\WithdrawalRequestService;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Entities\Remark;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Entities\Category;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Service\OrderService;
use Modules\CoreData\Service\CategoryService;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\RemarkService;

class ReportService extends BasicService
{
    protected $orderService, $withdrawalRequestService, $dropshipperService, $productService, $categoryService, $remarkService, $cityService;

    /**
     * This is a constructor function that initializes several services and repositories used in an
     * e-commerce application.
     *
     * param  orderService An instance of the OrderService class, which likely contains
     * methods for managing orders in some way.
     * operations related to orders, such as retrieving, creating, updating, and deleting orders. It is
     * likely injected into this constructor to allow the class to interact with the database and
     * perform these operations.
     * param  dropshipperService This is an instance of the DropshipperService
     * class, which is likely responsible for managing dropshippers and their associated data in the
     * application. It may have methods for creating, updating, and deleting dropshippers, as well as
     * retrieving information about them.
     * param  productService A service class that provides methods for managing products
     * in the system. It may include methods for creating, updating, deleting, and retrieving products
     * from the database.
     * param  categoryService A service that provides functionality related to
     * categories, such as retrieving a list of categories, creating a new category, updating an
     * existing category, and deleting a category. It may also provide methods for retrieving products
     * within a specific category.
     */
    public function __construct(
        OrderService $orderService,
        DropshipperService $dropshipperService,
        RemarkService $remarkService,
        CityService $cityService,
        WithdrawalRequestService $withdrawalRequestService,
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->orderService = $orderService;
        $this->dropshipperService = $dropshipperService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->remarkService = $remarkService;
        $this->cityService = $cityService;
        $this->withdrawalRequestService = $withdrawalRequestService;
    }

    /**
     * The function calculates and returns various sales and profit metrics for a dropshipper based on
     * their orders and product data.
     *
     * param request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request made to the server.
     *
     * return An array with the following keys and values:
     * - 'totalCountOrder': the total count of orders for the current user (based on their ID)
     * - 'sumSaleOrder': the sum of the grand total of all orders for the current user
     * - 'averageOrderSales': the average sale amount per order for the current user
     * - 'totalProfit': the total profit for all products (based on
     */
    public function reportRequests(Request $request, $currentPeriod, $lastCurrentPeriod)
    {
        $allOrder = $this->orderService->findBy(new Request(['dropshipper_id' => user()->id, 'created_at' => [$currentPeriod['from'], $currentPeriod['to']]]));
        $lastAllOrder = $this->orderService->findBy(new Request(['dropshipper_id' => user()->id, 'created_at' => [$lastCurrentPeriod['from'], $lastCurrentPeriod['to']]]));
        $allOrderCount = $allOrder->count();
        $lastAllOrderCount = $lastAllOrder->count();
        $subTotalOrder = $allOrderCount - $lastAllOrderCount;
        $cityOrder = array_count_values(array_filter($allOrder->pluck('customerCity')->toArray()));
        $cityLastOrder = array_count_values(array_filter($lastAllOrder->pluck('customerCity')->toArray()));
        $percentageAllOrder = 0;
        if ($lastAllOrderCount != 0) {
            $percentageAllOrder = $this->percentageChange($allOrderCount, $lastAllOrderCount);
        }
        $order = array_map(function ($value) {
            $x['remark_id'] = 0;
            if ($value['status_id'] == OrderEnum::COMPLETED_STATUS) {
                $x['net_profit'] = $value['net_profit'];
            }
            if (in_array($value['status_id'], [OrderEnum::REJECTED_STATUS, OrderEnum::CANCELED_STATUS])) {
                $x['remark_id'] = $value['remark_id'];
            }
            $x['grandTotal'] = $value['grandTotal'];
            return $x;
        }, $allOrder->toArray());
        $status = [
            'new' => [OrderEnum::NEW_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::PAY_PENDING_STATUS],
            'validated' => [OrderEnum::PENDING_INVENTORY_STATUS, OrderEnum::SHIPPING_STATUS, OrderEnum::PREPARING_STATUS],
            'delivered' => [OrderEnum::COMPLETED_STATUS],
            'canceled_by_validation' => [OrderEnum::CANCELED_STATUS],
            'canceled_by_delivery' => [OrderEnum::REJECTED_STATUS],
            'returned' => [OrderEnum::REFUND_STATUS],
        ];
        $statusReport = [];
        foreach ($status as $name => $s) {
            $statusCount = $allOrder->whereIn('status_id', $s)->count();
            $last = 0;
            if ($statusCount) {
                $last = $lastAllOrder->whereIn('status_id', $s)->count();
            }
            $statusReport[trans('orders.' . $name)] = ['count' => $statusCount, 'rate' => $statusCount ? $this->percentageChange(
                $statusCount,
                $last
            ) : 0];
        }
        $remark = [];
        $remarkCount = array_count_values(array_filter(array_column($order, 'remark_id')));
        foreach ($this->remarkService->findby(new Request()) as $s) {
            if (isset($remarkCount[$s->id]) && $remarkCount[$s->id]) {
                $last = $lastAllOrder->where('remark_id', $s->id)->count();
                $remark[$s->name] = ['id' => $s->id, 'name' => $s->name, 'count' => $remarkCount[$s->id], 'rate' => $this->percentageChange(
                    $remarkCount[$s->id],
                    $last
                )];
            }
        }
        $cities = [];
        foreach ($cityOrder as $key => $city) {
            $last = 0;
            if (isset($cityLastOrder[$key])) {
                $last = $cityLastOrder[$key];
            }
            $cities[] = ['name' => $this->cityService->findBy(
                new Request(['id' => $key]),
                get: 'first'
            )->name->value ?? '', 'count' => $city, 'rate' => $last ? $this->percentageChange(
                $city,
                $last
            ) : $city * 100];
        }
        return [
            'totalCountOrder' => round($allOrderCount, 2),
            'subTotalCountOrder' => round($subTotalOrder, 2),
            'percentageAllOrder' => $percentageAllOrder,
            'status' => $statusReport,
            'city' => $cities,
            'cancellation_reason' => $remark,
        ];
    }

    public function reportFinancial(Request $request, $currentPeriod, $lastCurrentPeriod)
    {
        $allOrder = $this->orderService->findBy(new Request(['dropshipper_id' => user()->id, 'status_id' => OrderEnum::COMPLETED_STATUS, 'created_at' => [$currentPeriod['from'], $currentPeriod['to']]]));
        $lastAllOrder = $this->orderService->findBy(new Request(['dropshipper_id' => user()->id, 'status_id' => OrderEnum::COMPLETED_STATUS, 'created_at' => [$lastCurrentPeriod['from'], $lastCurrentPeriod['to']]]));
        $cityOrder = array_unique(array_filter($allOrder->pluck('customerCity')->toArray()));
        $cityLastOrder = array_unique(array_filter($lastAllOrder->pluck('customerCity')->toArray()));
        $totalSalesAmount = $allOrder->sum('subTotal');
        $totalLastSalesAmount = $lastAllOrder->sum('subTotal');
        $orderRate = $totalSalesAmount - $totalLastSalesAmount;
        $totalAmount = $this->orderService->findBy(
            new Request(['dropshipper_id' => user()->id, 'status_id' => OrderEnum::COMPLETED_STATUS]),
            moreConditionForFirstLevel: ['whereBetween' => ['deliveryDate' => [$currentPeriod['from'], $currentPeriod['to']]], 'where' => ['deliveryDate' => ['<=', Carbon::today()
                ->subDay(7)]]]
        )
            ->sum('net_profit');
        $countAllOrder = $allOrder->count();
        $averageSalesQuantity = 0;
        if ($countAllOrder) {
            $averageSalesQuantity = $totalSalesAmount / $countAllOrder;
        }
        $withdrawnAmount = $this->orderService->transactionService->findBy(new Request(['dropshipper_id' => user()->id, 'isStatus' => [ProfitEnum::WALLETE_DONE, ProfitEnum::WITHDRAWAL_DONE], 'earning_date' => [$currentPeriod['from'], $currentPeriod['to']]]))
            ->sum('profitRatio');
        $pendingAmount = $totalAmount ? ($totalAmount - $withdrawnAmount) : 0;
        $percentageAllOrderAmount = 0;
        if ($totalLastSalesAmount != 0) {
            $percentageAllOrderAmount = $this->percentageChange($totalSalesAmount, $totalLastSalesAmount);
        }
        $cities = [];
        foreach ($cityOrder as $city) {
            $last = 0;
            $salesCity = $allOrder->where('customerCity', $city)->sum('subTotal');
            if (in_array($city, $cityLastOrder)) {
                $last = $lastAllOrder->where('customerCity', $city)->sum('subTotal');
            }
            $cities[] = ['name' => $this->cityService->findBy(
                new Request(['id' => $city]),
                get: 'first'
            )->name->value ?? '', 'sales' => round(
                $salesCity,
                2
            ), 'rate' => $last ? $this->percentageChange($salesCity, $last) : $city * 100];
        }
        return [
            'totalSalesAmount' => round($totalSalesAmount, 2),
            'averageSalesQuantity' => round($averageSalesQuantity, 2),
            'totalAmount' => round($totalAmount, 2),
            'withdrawnAmount' => round($withdrawnAmount, 2),
            'pendingAmount' => round($pendingAmount, 2),
            'orderRate' => round($orderRate, 2),
            'percentageAllOrderAmount' => $percentageAllOrderAmount,
            'city' => $cities,
        ];
    }

    /**
     * This PHP function returns orders filtered by date and belonging to a specific drop shipper.
     *
     * param Request  is an instance of the Request class, which is used to retrieve
     * data from the HTTP request. It contains information about the current request, such as the
     * request method, headers, and parameters. In this case, it is being used to retrieve filter
     * parameters for the report chart.
     *
     * return the result of calling the `findBy` method of the `orderService` object with a `Request`
     * object containing a `drop shipper_id` parameter set to the current user's ID, and an optional
     * `moreConditionForFirstLevel` parameter that is set to the result of calling the `filterByDate`
     * method with the same `Request` object.
     */
    public function reportChart($request, $currentPeriod)
    {
        $userId = user()->id;
        $filter = $this->thisCustom($request, $userId, $currentPeriod);
        return $filter;
    }

    /**
     * This PHP function reports the top selling products by returning an array of their names.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and parameters. In this case, it is being passed to the reportProduct method as an
     * argument, but it is not being used
     *
     * return An array of top selling products with their names.
     */
    public function reportRequestsProduct(Request $request, $currentPeriod)
    {
        //todo call service not model
        $topSellingProducts = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.id', function ($query) {
                $query->select('orders.id')
                    ->from('order_refunds')
                    ->where('order_refunds.status_id', OrderEnum::REFUND_BALANCE_STATUS);
            })
            ->whereNotIn('orders.status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->where('orders.dropshipper_id', user()->id)
            ->whereBetween('orders.created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->select(
                'order_items.product_id',
                DB::raw('count(order_items.order_id) as revenue'),
                DB::raw('sum(order_items.totalPrice) as totalPrice'),
                DB::raw('sum(order_items.net_profit) as net_profit')
            )
            ->groupBy('order_items.product_id')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();
        $topSellingProductsWithNames = $topSellingProducts->map(function ($item) {
            $product = $this->productService->show($item->product_id);
            return [
                'product_id' => $item->product_id,
                'revenue' => (int)$item->revenue,
                'product_name' => $product->name->value,
                'sku' => $product->sku,
                'price' => $product->calculator(),
                'selling_price' => round($item->totalPrice, 2),
                'net_profit' => round($item->net_profit, 2),
                'logo' => mediaResource::collection($product->logo),
            ];
        });
        return $topSellingProductsWithNames;
    }

    public function thisCustomDay($request, $dropshipperId, $currentPeriod)
    {
        $startDate = $currentPeriod['from'];
        $endDate = $currentPeriod['to'];
        $diffInDays = $startDate->diffInDays($endDate);
        $range = $diffInDays * 24 / 12;
        $range = $range ? $range : 1;
        $chartType = $request->chartType ?? 'subTotal';
        //todo call service not model
        $grandTotals = Order::where('dropshipper_id', $dropshipperId)
            ->select([
                DB::raw('sum(' . $chartType . ') as `total`'),
                DB::raw('count(' . $chartType . ') as `countTotal`'),
                DB::raw('min(`created_at`) as min_day'),
                DB::raw('floor(HOUR(created_at) / ' . $range . ' ) as hour'),
            ])->groupBy('hour')->orderBy('hour', 'ASC')
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->whereNotIn('status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('order_refunds')
                    ->whereRaw('order_refunds.order_id = orders.id');
            })
            ->get();
        $point = [];
        $times = [];
        $count = [];
        foreach ($grandTotals as $grandTotal) {
            $dates[$grandTotal->hour] = $grandTotal->total;
            $dates['count'][$grandTotal->hour] = $grandTotal->countTotal;
        }
        for ($i = 0; $i < 24; $i++) {
            if (@$dates[$i] > 0) {
                array_push($point, $dates[$i]);
                array_push($count, $dates['count'][$i]);
                array_push($times, Carbon::parse($startDate)->addHour($range * $i)->format('Y-m-d h:i:s'));
            } else {
                array_push($point, 0);
                array_push($count, 0);
                array_push($times, Carbon::parse($startDate)->addHour($range * $i)->format('Y-m-d h:i:s'));
            }
        }
        return [
            'grandTotal' => array_values($point),
            'count' => array_values($count),
            'point' => array_values($times),
        ];
    }

    public function thisCustomWeek($request, $dropshipperId, $currentPeriod)
    {
        $startDate = $currentPeriod['from'];
        $endDate = $currentPeriod['to'];
        $diffInDays = $startDate->diffInDays($endDate);
        $range = $diffInDays ? $diffInDays : 1;
        $chartType = $request->chartType ?? 'subTotal';
        //todo call service not model
        $grandTotals = Order::where('dropshipper_id', $dropshipperId)
            ->select([
                DB::raw('sum(' . $chartType . ') as `total`'),
                DB::raw('count(' . $chartType . ') as `countTotal`'),
                DB::raw('min(`created_at`) as min_day'),
                DB::raw('DATE(created_at) as day'),
            ])->groupBy('day')->orderBy('day', 'ASC')
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->whereNotIn('status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('order_refunds')
                    ->whereRaw('order_refunds.order_id = orders.id');
            })
            ->get();
        $point = [];
        $times = [];
        $count = [];
        foreach ($grandTotals as $grandTotal) {
            $dates[$grandTotal->day] = $grandTotal->total;
            $dates['count'][$grandTotal->day] = $grandTotal->countTotal;
        }
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay($range)) {
            if (@$dates[$date->format('Y-m-d')] > 0) {
                array_push($point, $dates[$date->format('Y-m-d')]);
                array_push($count, $dates['count'][$date->format('Y-m-d')]);
                array_push($times, $date->format('Y-m-d'));
            } else {
                array_push($point, 0);
                array_push($count, 0);
                array_push($times, $date->format('Y-m-d'));
            }
        }
        return [
            'grandTotal' => array_values($point),
            'count' => array_values($count),
            'point' => array_values($times),
        ];
    }

    public function thisCustomMonth($request, $dropshipperId, $currentPeriod)
    {
        $startDate = $currentPeriod['from'];
        $endDate = $currentPeriod['to'];
        //todo call service not model
        $chartType = $request->chartType ?? 'subTotal';
        $grandTotals = Order::where('dropshipper_id', $dropshipperId)
            ->select([
                DB::raw('sum(' . $chartType . ') as `total`'),
                DB::raw('count(' . $chartType . ') as `countTotal`'),
                DB::raw('DATE(created_at) as day'),
            ])->groupBy('day')->orderBy('day', 'ASC')
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->whereNotIn('status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('order_refunds')
                    ->whereRaw('order_refunds.order_id = orders.id');
            })
            ->get();
        $point = [];
        $times = [];
        $count = [];
        foreach ($grandTotals as $grandTotal) {
            $dates[$grandTotal->day] = $grandTotal->total;
            $dates['count'][$grandTotal->day] = $grandTotal->countTotal;
        }
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if (@$dates[$date->format('Y-m-d')] > 0) {
                array_push($point, $dates[$date->format('Y-m-d')]);
                array_push($count, $dates['count'][$date->format('Y-m-d')]);
                array_push($times, $date->format('Y-m-d'));
            } else {
                array_push($point, 0);
                array_push($count, 0);
                array_push($times, $date->format('Y-m-d'));
            }
        }
        return [
            'grandTotal' => array_values($point),
            'count' => array_values($count),
            'point' => array_values($times),
        ];
    }

    public function thisCustomYear($request, $dropshipperId, $currentPeriod)
    {
        $startDate = $currentPeriod['from'];
        $endDate = $currentPeriod['to'];
        $diffInDays = $startDate->diffInDays($endDate);
        $range = floor($diffInDays / 30);
        //todo call service not model
        $chartType = $request->chartType ?? 'subTotal';
        $grandTotals = Order::where('dropshipper_id', $dropshipperId)
            ->select([
                DB::raw('sum(' . $chartType . ') as `total`,count(' . $chartType . ') as `countTotal`, DATE(min(`created_at`)) as min_day , DATE(max(`created_at`)) as max_day'),
                DB::raw('DATE(created_at) as day'),
            ])->groupBy('day')->orderBy('day', 'ASC')
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->whereNotIn('status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('order_refunds')
                    ->whereRaw('order_refunds.order_id = orders.id');
            })
            ->get();
        $point = [];
        $times = [];
        $count = [];
        foreach ($grandTotals as $grandTotal) {
            $dates[$grandTotal->day] = $grandTotal->total;
            $dates['count'][$grandTotal->day] = $grandTotal->countTotal;
        }
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if (@$dates[$date->format('Y-m-d')] > 0) {
                array_push($point, $dates[$date->format('Y-m-d')]);
                array_push($count, $dates['count'][$date->format('Y-m-d')]);
                array_push($times, $date->format('Y-m-d'));
            } else {
                array_push($point, 0);
                array_push($count, 0);
                array_push($times, $date->format('Y-m-d'));
            }
        }
        return [
            'grandTotal' => array_values($point),
            'count' => array_values($count),
            'point' => array_values($times),
        ];
    }

    /**
     * This function calculates the total sales for different time intervals within the current day.
     *
     * param request It is a parameter that is not used in the function and is likely not needed. It
     * may have been included for future use or as a placeholder.
     * param grandTotals It is an array of objects containing information about the grand total of
     * sales made. Each object has a "created_at" property which represents the date and time when the
     * sale was made, and a "grandTotal" property which represents the total amount of the sale.
     *
     * return An array with two keys: 'grandTotal' and 'point'. The 'grandTotal' key contains an array
     * of the total sales for each time period of the current day (divided into 6 time periods of 4
     * hours each), and the 'point' key contains an array of the start and end times for each of those
     * time periods.
     */
    public function thisCustom($request, $dropshipperId, $currentPeriod)
    {
        $startDate = $currentPeriod['from'];
        $endDate = $currentPeriod['to'];
        $diffInDays = $startDate->diffInDays($endDate);
        if ($diffInDays <= 0) {
            return $this->thisCustomDay($request, $dropshipperId, $currentPeriod);
        } elseif ($diffInDays <= 30) {
            return $this->thisCustomMonth($request, $dropshipperId, $currentPeriod);
        } else {
            return $this->thisCustomYear($request, $dropshipperId, $currentPeriod);
        }
    }

    /**
     * This function generates a report dashboard with various statistics and data related to orders,
     * drop shippers, products, and categories.
     *
     * param request The  parameter is an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request made to the application. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body. It is used to
     * retrieve input data and perform validation on
     *
     * return an array of all the variables defined in the function using the `get_defined_vars()`
     * function.
     */
    public function reportDashboard($request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getLastPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);
        $order = $this->orderService->findBy($request);
        $currentOrder = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $currentOrderCount = $currentOrder->count();
        $CODOrder = array_sum(array_map(function ($value) {
            return $value['subTotal'];
        }, $currentOrder->toArray()));
        $lastOrder = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
        $CODLastOrder = array_sum(array_map(function ($value) {
            return $value['subTotal'];
        }, $lastOrder->toArray()));
        $lastOrderCount = $lastOrder->count();
        $CODPercentageChangeOrder = 0;
        if ($CODLastOrder != 0) {
            $CODPercentageChangeOrder = $this->percentageChange($CODOrder, $CODLastOrder);
        }
        $orderPercentageChangeOrder = 0;
        if ($lastOrderCount != 0) {
            $orderPercentageChangeOrder = $this->percentageChange($currentOrderCount, $lastOrderCount);
        }
        $cod_chart = $currentOrder->groupby('status_id');
        $cod_chart_array = [['Status', 'COD']];
        foreach ($cod_chart as $key => $values) {
            App::setlocale('en');
            $y = array_sum(array_map(function ($value) {
                return $value['subTotal'];
            }, $values->toArray()));
            $name = getStatusTitle($key);
            $cod_chart_array[] = [$name, $y];
        }
        $dropshippers = $this->dropshipperService->findBy(new Request(['isVerified' => 1, 'created_at' => [$lastPeriod['from'], $currentPeriod['to']]]));
        $newDropshippers = $dropshippers->wherebetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->count();
        $lastDropshippers = $dropshippers->wherebetween('created_at', [$lastPeriod['from'], $lastPeriod['to']])
            ->count();
        $dropshippersPercentageChangeOrder = 0;
        if ($lastDropshippers != 0) {
            $dropshippersPercentageChangeOrder = $this->percentageChange($newDropshippers, $lastDropshippers);
        }
        $todayDropshippers = $this->dropshipperService->findBy(
            new Request(['isVerified' => 1, 'created_at' => [Carbon::now()
                ->startOfDay(), Carbon::now()->endOfDay()]]),
            $pagination = false,
            $perPage = 10,
            $get = '',
            $latest = '',
            withRelations: ['avatar']
        );
        //todo call service not model
        $latestOrders = Order::latest()
            ->take(7)
            ->get();
        //todo call service not model
        $productDelivery = Order::where('status_id', getStatusId(OrderEnum::COMPLETED_STATUS))->latest()
            ->take(10)
            ->get();
        //todo call service not model
        $latestCategory = Category::with(['products' => function ($query) {
            $query->select('products.*')
                ->join('category_product as cp', 'cp.product_id', '=', 'products.id')
                ->join('categories as c', 'cp.category_id', '=', 'c.id')
                ->take(3);
        }])
            ->take(5)
            ->get();
        if ($request->category_id) {
            //todo call service not model
            $listProduct = Category::find($request->category_id)->products()->latest()
                ->take(3)
                ->get();
        } else {
            $listProduct = $latestCategory->first()->products()->take(3)->get();
        }
        return get_defined_vars();
    }

    public function getTopProducts(Request $request)
    {
        //todo call service not DB
        $topsales = DB::table('order_items')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->select(
                'products.id',
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as total')
            )
            ->groupBy('products.id', 'order_items.product_id')
            ->orderBy('total', 'desc');
        if (!empty($request->period)) {
            $range = $this->getPeriodBestOnPeriodType($request->period, $request);
            $topsales = $topsales->whereBetween('order_items.created_at', [$range['from'], $range['to']]);
        }
        return $topsales->limit(3)->get();
    }

    public function ordersChart($request)
    {
        switch ($request->period) {
            case 'this_week':
                $points = $this->getWeekPoints();
                break;
            case 'this_month':
                $points = $this->getMonthPoints();
                break;
            case 'this_year':
                $points = $this->getYearPoints();
                break;
            case 'custom':
                $points = $this->getPointsOfCustomDateRange($request);
                break;
            default:
                $points = $this->getTodayPoints();
                break;
        }
        $chartPoints = [];
        $chartNumbers = [];
        foreach ($points as $index => $point) {
            //todo call service not DB
            $chartNumbers[] = DB::table('orders')
                ->whereBetween('created_at', [$point['from'], $point['to']])
                ->whereNotIn('status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])->count();
            $chartPoints[] = $this->changePointFormat($request->period, $point['from']);
        }
        return ['numbers' => $chartNumbers, 'points' => $chartPoints];
    }

    public function getMonthPoints()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $monthParts = [];
        $ddd = [];
        for ($i = 0; $i < 7; $i++) {
            $x = $i * 5;
            $monthParts[$i]['from'] = $currentMonth->copy()->addDays($x)->startOfDay();
            if ($currentMonth->month != $currentMonth->copy()->addDays($x + 5)->subDay()->month || $i >= 5) {
                $monthParts[$i]['to'] = Carbon::now()->endOfDay();
            } else {
                $monthParts[$i]['to'] = $currentMonth->copy()->addDays($x + 5)->subDay()->endOfDay();
            }
        }
        return $monthParts;
    }

    public function getYearPoints()
    {
        $currentYear = Carbon::now()->year;
        $periods = [
            ['from' => Carbon::create($currentYear, 1, 1)->startOfDay(), 'to' => Carbon::create($currentYear, 2, 28)
                ->endOfDay()],
            ['from' => Carbon::create($currentYear, 3, 1)->startOfDay(), 'to' => Carbon::create($currentYear, 4, 30)
                ->endOfDay()],
            ['from' => Carbon::create($currentYear, 5, 1)->startOfDay(), 'to' => Carbon::create($currentYear, 6, 30)
                ->endOfDay()],
            ['from' => Carbon::create($currentYear, 7, 1)->startOfDay(), 'to' => Carbon::create($currentYear, 8, 31)
                ->endOfDay()],
            ['from' => Carbon::create($currentYear, 9, 1)->startOfDay(), 'to' => Carbon::create($currentYear, 10, 31)
                ->endOfDay()],
            ['from' => Carbon::create($currentYear, 11, 1)->startOfDay(), 'to' => Carbon::create($currentYear, 12, 31)
                ->endOfDay()],
        ];
        return $periods;
    }

    public function getPointsOfCustomDateRange($request)
    {
        $periods = [];
        // Set the two dates you want to compare
        $startDate = Carbon::createFromDate($request->fromDate);
        $endDate = Carbon::createFromDate($request->toDate);
        // Get the difference in days between the two dates
        $diffInDays = $endDate->diffInDays($startDate);
        if ($diffInDays <= 7) {
            $periods[0]['from'] = Carbon::createFromDate($request->fromDate)->startOfDay();
            $periods[0]['to'] = Carbon::createFromDate($request->fromDate)->endOfDay();
            for ($i = 1; $i <= $diffInDays; $i++) {
                $day = $startDate->addDays(1);
                $periods[$i]['from'] = Carbon::create($day)->startOfDay();
                $periods[$i]['to'] = Carbon::create($day)->endOfDay();
            }
            // Output the array of days
            return $periods;
        } elseif ($diffInDays <= 30) {
            $newDiffInDays = $diffInDays + 1;
            $numIntervals = ceil($newDiffInDays / 5);
            // Split the period into parts of 5 days each
            for ($i = 0; $i < $numIntervals; $i++) {
                $first_date = $startDate->copy()->addDays($i * 5);
                $last_date = $first_date->copy()->addDays(4);
                // Do something with each 5-day interval, such as print it
                $periods[$i]['from'] = $first_date->startOfDay();
                if ($last_date->toDateString() > Carbon::createFromDate($request->toDate)) {
                    $periods[$i]['to'] = Carbon::createFromDate($request->toDate)->endOfDay();
                } else {
                    $periods[$i]['to'] = $last_date->endOfDay();
                }
            }
            return $periods;
        } elseif ($diffInDays <= 180) {
            // Set the initial period start date to the request's start date
            $periodStart = $startDate;
            // Loop through the period and split it into parts of 1 month each
            while ($periodStart < $endDate) {
                // Calculate the period end date as 1 month after the period start date
                $periodEnd = $periodStart->copy()->addMonth();
                // Make sure the period end date does not exceed the request's end date
                if ($periodEnd > $endDate) {
                    $periodEnd = $endDate;
                }
                // Add this period as a new element to the period parts array
                $periods[] = [
                    'from' => $periodStart,
                    'to' => $periodEnd,
                ];
                // Set the next period start date as the current period end date
                $periodStart = $periodEnd;
            }
            return $periods;
        } elseif ($diffInDays > 180) {
            // Set the initial period start date to the request's start date
            $periodStart = $startDate;
            // Loop through the periods and split them into parts of 2 months each
            while ($periodStart < $endDate) {
                // Calculate the period end date as 2 months after the period start date
                $periodEnd = $periodStart->copy()->addMonths(2)->subDay();
                // Make sure the period end date does not exceed the request's end date
                if ($periodEnd > $endDate) {
                    $periodEnd = $endDate;
                }
                // Add this period as a new element to the period parts array
                $periods[] = [
                    'from' => $periodStart,
                    'to' => $periodEnd,
                ];
                // Set the next period start date as the current period end date plus one day
                $periodStart = $periodEnd->copy()->addDay();
            }
            // Return the period parts array as a response
            return $periods;
        }
    }

    public function topUsers($request)
    {
        //todo call service not model
        $orders = Order::select('orders.dropshipper_id', 'dropshippers.email', DB::raw('count(*) as order_count'))
            ->whereNotIn('status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->join('dropshippers', 'orders.dropshipper_id', '=', 'dropshippers.id')
            ->groupBy('orders.dropshipper_id', 'dropshippers.email');
        if (!empty($request->period)) {
            $range = $this->getPeriodBestOnPeriodType($request->period, $request);
            $orders = $orders->whereBetween('orders.created_at', [$range['from'], $range['to']]);
        }
        return $orders->orderBy('order_count', 'desc')->limit(5)->get();
    }

    public function reportAllProduct(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getLastPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);
        $sku = $this->allSku($request);
        if (isset($request->product_id)) {
            $currentSku = $sku;
        } else {
            $currentSku = $sku->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        }
        $currentSkuCount = $currentSku->count();
        $lastSku = $sku->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']])->count();
        $percentageChangeSku = 0;
        if ($lastSku != 0) {
            $percentageChangeSku = round((($currentSkuCount - $lastSku) / $lastSku) * 100, 2);
        }
        $order = collect();
        $currentSkuId = $currentSku->pluck('id')->toArray();
        if ($currentSkuCount) {
            $order = $this->allOrder($request);
        }
        $currentOrder = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $dropshipperCount = count(array_filter(array_unique($currentOrder->pluck('dropshipper_id')->toArray())));
        $currentOrderCount = $currentOrder->count();
        $lastOrder = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']])->count();
        $percentageChangeOrder = 0;
        if ($lastOrder != 0) {
            $percentageChangeOrder = round((($currentOrderCount - $lastOrder) / $lastOrder) * 100, 2);
        }
        $currentStatusOrder = array_count_values($currentOrder->pluck('status.id', 'id')->toArray());
        $warehouseCount = count(array_filter(array_unique($currentSku->pluck('warehouse_id')->toArray())));
        if (Auth::guard('web')->check()) {
            $supplierCount = count(array_filter(array_unique($currentSku->pluck('supplier_id')->toArray())));
            $CODOrder = array_sum(array_map(function ($value) use ($currentSkuId, $request) {
                $price = 0;
                foreach ($value['order_items'] as $order_items) {
                    $price += in_array(
                        $order_items['product_id'],
                        $currentSkuId
                    ) && ((isset($request->supplier_id) && !empty($request->supplier_id)) ? in_array(
                        $order_items['supplier_id'],
                        $request->supplier_id
                    ) : true) ? $order_items['unitPrice'] * $order_items['quantity'] : 0;
                }
                return $price;
            }, $currentOrder->toArray()));
        }
        if (Auth::guard('supplier')->check()) {
            $costOrder = array_sum(array_map(function ($value) use ($currentSkuId, $request) {
                $price = 0;
                foreach ($value['order_items'] as $order_items) {
                    $price += in_array(
                        $order_items['product_id'],
                        $currentSkuId
                    ) && ((isset($request->supplier_id) && !empty($request->supplier_id)) ? in_array(
                        $order_items['supplier_id'],
                        $request->supplier_id
                    ) : true) ? $order_items['product']['supplier_price_cost'] * $order_items['quantity'] : 0;
                }
                return $price;
            }, $currentOrder->toArray()));
        }
        $productOrderId = array_map(function ($value) use ($currentSkuId) {
            $id = [];
            foreach ($value['order_items'] as $order_items) {
                $id[] = in_array($order_items['product_id'], $currentSkuId) ? $order_items['product_id'] : null;
            }
            return $id;
        }, $currentOrder->toArray());
        $productActiveOrder = count(array_filter(array_unique(array_merge(...$productOrderId))));
        $productInactiveOrder = $currentSkuCount - $productActiveOrder;
        $activeCount = count($currentSku->where('status', 1)->pluck('status')->toArray());
        $InactiveCount = $currentSkuCount - $activeCount;
        return get_defined_vars();
    }

    public function allSku(Request $request)
    {
        $moreConditionForFirstLevel = $recursiveRel = [];
        if (isset($request->created_at) && !isset($request->product_id)) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => $request->created_at]];
        }
        if (isset($request->product_id)) {
            $moreConditionForFirstLevel = ['where' => ['id' => $request->product_id]];
        }
        if (isset($request->dropshipper_id) && !empty($request->dropshipper_id)) {
            $recursiveRel = [
                'orderItems' => [
                    'type' => 'whereHas',
                    'recursive' => [
                        'order' => [
                            'type' => 'whereHas',
                            'whereIn' => ['dropshipper_id' => $request->dropshipper_id],
                        ],
                    ],
                ],
            ];
        }
        return $this->productService->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            recursiveRel: $recursiveRel,
            withRelations: ['productTargetMarket']
        );
    }

    public function allOrder(Request $request)
    {
        $moreConditionForFirstLevel = $recursiveRel = [];
        if (isset($request->created_at)) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => $request->created_at]];
        }
        $recursiveRel = [
            'orderItems' => [
                'type' => 'whereHas',
                'recursive' => [
                    'product' => [
                        'type' => 'whereHas',
                    ],
                ],
            ],
        ];
        if (!(isset($request->product_id) && !empty($request->product_id))) {
            $recursiveRel['orderItems']['recursive']['product']['whereBetween'] = ['created_at' => $request->created_at];
        }
        if (isset($request->product_id) && !empty($request->product_id)) {
            $recursiveRel['orderItems']['where'] = ['product_id' => $request->product_id];
        }
        if (isset($request->supplier_id) && !empty($request->supplier_id)) {
            $recursiveRel += [
                'orderItems' => [
                    'type' => 'whereHas',
                    'whereIn' => ['supplier_id' => $request->supplier_id],
                ],
            ];
        }
        if (Auth::guard('supplier')->check()) {
            $moreConditionForFirstLevel += [
                'whereNotIn' => ['status_id' => [OrderEnum::NEW_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::PAY_PENDING_STATUS]],
            ];
        }
        return $this->orderService->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            recursiveRel: $recursiveRel,
            withRelations: ['orderItems.product', 'status']
        );
    }

    public function reportAllPerformance(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getLastPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);
        $recursiveRel = [
            'orderStatus' => [
                'type' => 'whereHas',
                'where' => ['status_id' => OrderEnum::SHIPPING_STATUS],
                'whereBetween' => ['created_at' => [$lastPeriod['from'], $currentPeriod['to']]],
            ],
        ];
        $order = $this->orderService->findBy($request, recursiveRel: $recursiveRel, withRelations: ['orderStatus']);
        $currentOrderShipping = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $timeOrderShipping = $this->sumTimeOrder($currentOrderShipping, $currentPeriod['from'], $currentPeriod['to'],
            OrderEnum::NEW_STATUS, [OrderEnum::SHIPPING_STATUS]);
        $secondShipping = 0;
        if($timeOrderShipping)
        {
            $secondShipping = $timeOrderShipping / $currentOrderShipping->count();
        }
        $lastOrderShipping = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
        $lastTimeOrderShipping = $this->sumTimeOrder($lastOrderShipping, $lastPeriod['from'], $lastPeriod['to'],
            OrderEnum::NEW_STATUS,
            [OrderEnum::SHIPPING_STATUS]);
        $lastOrderShipping = $lastOrderShipping->count();
        $lastSecondShipping = 0;
        if($lastTimeOrderShipping)
        {
            $lastSecondShipping = $lastTimeOrderShipping / $lastOrderShipping;
        }
        $lastPercentageChangeOrderShipping = 0;
        if($lastSecondShipping != 0)
        {
            $lastPercentageChangeOrderShipping = $this->percentageChange($secondShipping, $lastSecondShipping);
        }
        if($secondShipping != 0)
        {
            $averageTimeShipping = $this->avargeTime($secondShipping);
        }
        $currentOrderShippingCount = $currentOrderShipping->count();
        $percentageChangeOrderShipping = 0;
        if($lastOrderShipping != 0)
        {
            $percentageChangeOrderShipping = $this->percentageChange($currentOrderShippingCount, $lastOrderShipping);
        }
        $timeOrderConfirmedShipped = $this->sumTimeOrder($currentOrderShipping, $currentPeriod['from'],
            $currentPeriod['to'],
            OrderEnum::PREPARING_STATUS, [OrderEnum::SHIPPING_STATUS]);
        $secondConfirmedShipped = 0;
        if($timeOrderConfirmedShipped)
        {
            $secondConfirmedShipped = $timeOrderConfirmedShipped / $currentOrderShipping->count();
        }
        $lastOrderConfirmedShipped = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
        $lastTimeOrderConfirmedShipped = $this->sumTimeOrder($lastOrderConfirmedShipped, $lastPeriod['from'],
            $lastPeriod['to'],
            OrderEnum::PREPARING_STATUS,
            [OrderEnum::SHIPPING_STATUS]);
        $lastOrderConfirmedShipped = $lastOrderConfirmedShipped->count();
        $lastSecondConfirmedShipped = 0;
        if($lastTimeOrderConfirmedShipped)
        {
            $lastSecondConfirmedShipped = $lastTimeOrderConfirmedShipped / $lastOrderConfirmedShipped;
        }
        $lastPercentageChangeOrderConfirmedShipped = 0;
        if($lastSecondConfirmedShipped != 0)
        {
            $lastPercentageChangeOrderConfirmedShipped = $this->percentageChange($secondConfirmedShipped,
                $lastSecondConfirmedShipped);
        }
        if($secondConfirmedShipped != 0)
        {
            $averageTimeConfirmedShipped = $this->avargeTime($secondConfirmedShipped);
        }
        $recursiveRel = [
            'orderStatus' => [
                'type' => 'whereHas',
                'where' => ['status_id' => OrderEnum::PREPARING_STATUS],
                'whereBetween' => ['created_at' => [$lastPeriod['from'], $currentPeriod['to']]],
            ],
        ];
        $order = $this->orderService->findBy($request, recursiveRel: $recursiveRel, withRelations: ['orderStatus']);
        $currentOrderPerparing = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $timeOrderPerparing = $this->sumTimeOrder($currentOrderPerparing, $currentPeriod['from'], $currentPeriod['to'],
            OrderEnum::NEW_STATUS, [OrderEnum::PREPARING_STATUS]);
        $secondPerparing = 0;
        if($timeOrderPerparing)
        {
            $secondPerparing = $timeOrderPerparing / $currentOrderPerparing->count();
        }
        $lastOrderPerparing = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
        $lastTimeOrderPerparing = $this->sumTimeOrder($lastOrderPerparing, $lastPeriod['from'], $lastPeriod['to'],
            OrderEnum::NEW_STATUS,
            [OrderEnum::PREPARING_STATUS]);
        $lastOrderPerparing = $lastOrderPerparing->count();
        $lastSecondPerparing = 0;
        if($lastTimeOrderPerparing)
        {
            $lastSecondPerparing = $lastTimeOrderPerparing / $lastOrderPerparing;
        }
        $lastPercentageChangeOrderPerparing = 0;
        if($lastSecondPerparing != 0)
        {
            $lastPercentageChangeOrderPerparing = $this->percentageChange($secondPerparing, $lastSecondPerparing);
        }
        if($secondPerparing != 0)
        {
            $averageTimePerparing = $this->avargeTime($secondPerparing);
        }
        $currentOrderPerparingCount = $currentOrderPerparing->count();
        $percentageChangeOrderPerparing = 0;
        if($lastOrderPerparing != 0)
        {
            $percentageChangeOrderPerparing = $this->percentageChange($currentOrderPerparingCount, $lastOrderPerparing);
        }
        $recursiveRel = [
            'orderStatus' => [
                'type' => 'whereHas',
                'whereIn' => ['status_id' => [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS, OrderEnum::COMPLETED_STATUS]],
                'whereBetween' => ['created_at' => [$lastPeriod['from'], $currentPeriod['to']]],
            ],
        ];
        $order = $this->orderService->findBy($request, recursiveRel: $recursiveRel, withRelations: ['orderStatus']);
        $currentOrderDone = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $timeOrderDone = $this->sumTimeOrder($currentOrderDone, $currentPeriod['from'], $currentPeriod['to'],
            OrderEnum::NEW_STATUS,
            [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS, OrderEnum::COMPLETED_STATUS]);
        $secondDone = 0;
        if($timeOrderDone)
        {
            $secondDone = $timeOrderDone / $currentOrderDone->count();
        }
        $lastOrderDone = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
        $lastTimeOrderDone = $this->sumTimeOrder($lastOrderDone, $lastPeriod['from'], $lastPeriod['to'],
            OrderEnum::NEW_STATUS,
            [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS, OrderEnum::COMPLETED_STATUS]);
        $lastOrderDoneCount = $lastOrderDone->count();
        $lastSecondDone = 0;
        if($lastTimeOrderDone)
        {
            $lastSecondDone = $lastTimeOrderDone / $lastOrderDoneCount;
        }
        $lastPercentageChangeOrderDone = 0;
        if($lastSecondDone != 0)
        {
            $lastPercentageChangeOrderDone = $this->percentageChange($secondDone, $lastSecondDone);
        }
        if($secondDone != 0)
        {
            $averageTimeDone = $this->avargeTime($secondDone);
        }
        $currentOrderDoneCount = $currentOrderDone->count();
        $percentageChangeOrderDone = 0;
        if($lastOrderDoneCount != 0)
        {
            $percentageChangeOrderDone = $this->percentageChange($currentOrderDoneCount, $lastOrderDoneCount);
        }
        $currentRateOrderDelivered = $this->rateOrderDelivered($currentOrderDone, $currentPeriod['from'],
            $currentPeriod['to'],
            OrderEnum::NEW_STATUS, [OrderEnum::COMPLETED_STATUS]);
        $lastRateOrderDelivered = $this->rateOrderDelivered($lastOrderDone, $lastPeriod['from'], $lastPeriod['to'],
            OrderEnum::NEW_STATUS, [OrderEnum::COMPLETED_STATUS]);
        $percentageRateOrderDeliveredSameDay = 0;
        if(isset($lastRateOrderDelivered[1]) && $lastRateOrderDelivered[1] != 0)
        {
            $percentageRateOrderDeliveredSameDay = $this->percentageChange($currentRateOrderDelivered[1] ?? 0,
                $lastRateOrderDelivered[1]);
        }
        $percentageRateOrderDeliveredNextDay = 0;
        if(isset($lastRateOrderDelivered[2]) && $lastRateOrderDelivered[2] != 0)
        {
            $percentageRateOrderDeliveredNextDay = $this->percentageChange($currentRateOrderDelivered[2] ?? 0,
                $lastRateOrderDelivered[2]);
        }
        $percentageRateOrderDeliveredMoreDay = 0;
        if(isset($lastRateOrderDelivered[3]) && $lastRateOrderDelivered[3] != 0)
        {
            $percentageRateOrderDeliveredMoreDay = $this->percentageChange($currentRateOrderDelivered[3] ?? 0,
                $lastRateOrderDelivered[3]);
        }
        return get_defined_vars();
    }

    public function sumTimeOrder($order, $startDate, $endDate, $status_start, $status_end)
    {
        return array_sum(array_map(function($value) use ($startDate, $endDate, $status_start, $status_end)
        {
            $time_end = $time_start = null;
            foreach($value['order_status'] as $order_status)
            {
                if($order_status['status_id'] == $status_start)
                {
                    $time_start = Carbon::parse($order_status['created_at']);
                    if(!$time_start->between($startDate, $endDate))
                    {
                        continue;
                    }
                }
                if(in_array($order_status['status_id'], $status_end))
                {
                    $time_end = Carbon::parse($order_status['created_at']);
                    if(!$time_end->between($startDate, $endDate))
                    {
                        continue;
                    }
                }
            }
            if($time_end && $time_start)
            {
                return $time_end->diffInSeconds($time_start);
            }
        }, $order->toArray()));
    }

    public function rateOrderDelivered($order, $startDate, $endDate, $status_start, $status_end)
    {
        $orders = array_filter(array_map(function($value) use ($startDate, $endDate, $status_start, $status_end)
        {
            $time_end = $time_start = null;
            foreach($value['order_status'] as $order_status)
            {
                if($order_status['status_id'] == $status_start)
                {
                    $time_start = Carbon::parse($order_status['created_at']);
                    if(!$time_start->between($startDate, $endDate))
                    {
                        continue;
                    }
                }
                if(in_array($order_status['status_id'], $status_end))
                {
                    $time_end = Carbon::parse($order_status['created_at']);
                    if(!$time_end->between($startDate, $endDate))
                    {
                        continue;
                    }
                }
            }
            if($time_end && $time_start)
            {
                $time = $time_end->diffInSeconds($time_start);
                if($time <= 86400)
                {
                    return 1;
                }elseif($time >= 86400 && $time <= 172800)
                {
                    return 2;
                }else
                {
                    return 3;
                }
            }
        }, $order->toArray()));
        return array_count_values($orders);
    }

    public function avargeTime($second)
    {
        $day = floor($second / 86400);
        $second -= $day * 86400;
        $hours = floor($second / 3600);
        $second -= $hours * 3600;
        $minutes = floor($second / 60);
        $second = floor($second - ($minutes * 60));
        return get_defined_vars();
    }

    public function getPeriodBestOnPeriodType($type, $request)
    {
        $from = $request->fromDate ?? Carbon::now()->firstOfMonth()->startOfDay();
        $to = $request->toDate ?? Carbon::now()->lastOfMonth()->endOfDay();
        switch($type)
        {
            case 'this_week':
            case 'thisWeek':
                $periodType = ['from' => Carbon::now()->startOfWeek(CarbonInterface::SATURDAY)
                    ->startOfDay(), 'to' => Carbon::now()
                    ->endOfWeek(Carbon::FRIDAY)->endOfDay()];
                break;
            case 'this_month':
            case 'thisMonth':
                $periodType = ['from' => Carbon::now()->firstOfMonth()->startOfDay(), 'to' => Carbon::now()
                    ->lastOfMonth()
                    ->endOfDay()];
                break;
            case 'this_year':
            case 'thisYear':
                $periodType = ['from' => Carbon::now()->firstOfYear()->startOfDay(), 'to' => Carbon::now()->lastOfYear()
                    ->endOfDay()];
                break;
            case 'custom':
            case 'thisCustom':
                $periodType = ['from' => Carbon::parse($from)->startOfDay(),
                    'to' => Carbon::parse($to)->endOfDay() ?? Carbon::now()->endOfDay()];
                break;
            case 'today':
                $periodType = ['from' => Carbon::now()->startOfDay(), 'to' => Carbon::now()->endOfDay()];
                break;
            default:
                $periodType = ['from' => Carbon::now()->firstOfMonth()->startOfDay(), 'to' => Carbon::now()
                    ->lastOfMonth()
                    ->endOfDay()];
                break;
        }
        return $periodType;
    }

    public function getLastPeriodBestOnPeriodType($type, $request)
    {
        switch($type)
        {
            case 'this_week':
                $periodType = ['from' => Carbon::now()->startOfWeek(Carbon::SATURDAY)->startOfDay()
                    ->subWeek(), 'to' => Carbon::now()
                    ->endOfWeek(Carbon::FRIDAY)
                    ->endOfDay()->subWeek()];
                break;
            case 'this_month':
                $end = new Carbon('last day of last month');
                $periodType = ['from' => Carbon::now()->firstOfMonth()->startOfDay()
                    ->subMonth(), 'to' => $end->endOfDay()];
                break;
            case 'this_year':
                $periodType = ['from' => Carbon::now()->firstOfYear()->startOfDay()
                    ->subYear(), 'to' => Carbon::now()->lastOfYear()
                    ->endOfDay()->subYear()];
                break;
            case 'custom':
            case 'thisCustom':
                $from = Carbon::parse($request->fromDate)->startOfDay();
                $to = Carbon::parse($request->toDate)->endOfDay() ?? Carbon::now()->endOfDay();
                $countDays = $to->diffInDays($from) + 1;
                $periodType = ['from' => Carbon::parse($request->fromDate)->startOfDay()->subDays($countDays),
                    'to' => Carbon::parse($request->toDate)->endOfDay()->subDays($countDays)];
                break;
            case 'today':
                $periodType = ['from' => Carbon::now()->startOfDay()->subDay(), 'to' => Carbon::now()->endOfDay()
                    ->subDay()];
                break;
            default:
                $end = new Carbon('last day of last month');
                $periodType = ['from' => Carbon::now()->firstOfMonth()->startOfDay()
                    ->subMonth(), 'to' => $end->endOfDay()];
                break;
        }
        return $periodType;
    }

    /**
     * This function returns the category of a given row using a category service and a request with
     * the row's ID.
     *
     * param row It is a variable that represents a row of data from a database or a collection. It is
     * passed as a parameter to the function `getCategory()`.
     *
     * return the result of a call to the `findBy` method of the `` object, passing in
     * a new `Request` object with an `id` parameter set to the `id` property of the `` parameter,
     * and specifying the `get` parameter as `'first'`. The specific value being returned depends on
     * the implementation of the `findBy` method and the
     */
    public function getCategory($row)
    {
        return $this->categoryService->show($row->category_id);
    }

    public function getTodayPoints()
    {
        $today = Carbon::now();
        $today->setTime(0, 0, 0);
        $periods = [];
        for($i = 1; $i <= 6; $i++)
        {
            $periods[$i]['from'] = $today->copy()->addHours(4 * ($i - 1));
            $periods[$i]['to'] = $today->copy()->addHours(4 * $i);
        }
        return $periods;
    }

    public function getWeekPoints()
    {
        // Set the start of the week to Sunday (default is Monday)
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        // Set the end of the week to Friday
        Carbon::setWeekEndsAt(Carbon::FRIDAY);
        // Get an array of all the weekdays in the current week
        $periods = [];
        // startOfDay
        for($i = 0; $i < 7; $i++)
        {
            $day = Carbon::now()->startOfWeek()->addDays($i);
            $periods[$i]['from'] = Carbon::create($day)->startOfDay();
            $periods[$i]['to'] = Carbon::create($day)->endOfDay();
        }
        // Output the array of days
        return $periods;
    }

    public function changePointFormat($periodType, $date)
    {
        switch ($periodType) {
            case 'today':
                return $date->format('H');
                break;
            default:
                return $date->format('Y-m-d');
        }
    }

    /**
     * This function returns the product found by ID using the productService.
     *
     * param row It is a variable that represents a row of data from a database or a collection. It is
     * passed as a parameter to the function `getProduct()`.
     *
     * return a product object that is found by calling the `findBy` method of the ``
     * object with a `Request` object containing the `id` property of the `` parameter and the
     * `get` parameter set to `'first'`.
     */
    public function getProduct($row)
    {
        return $this->productService->show($row->id);
    }

    public function percentageChange($data, $last)
    {
        if ($last) {
            return round((($data - $last) / $last) * 100, 2);
        }
        return 0;
    }

    public function getTopCategories(Request $request)
    {
        //todo call service not DB
        $topsales = DB::table('order_items')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('category_product', 'category_product.product_id', '=', 'products.id')
            ->leftJoin('categories', 'categories.id', '=', 'category_product.category_id')
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.status_id', [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS])
            ->select('category_product.category_id', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('category_product.category_id', 'categories.id')
            ->orderBy('total', 'desc');
        if (!empty($request->period)) {
            $range = $this->getPeriodBestOnPeriodType($request->period, $request);
            $topsales = $topsales->whereBetween('order_items.created_at', [$range['from'], $range['to']]);
        }
        return $topsales->limit(3)->get();
    }

    public function reportValidationPerformance(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$currentPeriod['from'], $currentPeriod['to']]]);
        $order = $this->orderService->findBy($request);
        $totalOrderBulk = $order->where('created_platform', 'website')->where('is_import', 1);
        $totalOrderEasyOrder = $order->where('created_platform', 'easy_order');
        $totalOrderManual = $order->where('is_import', 0)->where('created_platform', 'website');
        $totalOrderCount = $order->count();
        $totalOrderBulkCount = $totalOrderBulk->count();
        $totalOrderEasyOrderCount = $totalOrderEasyOrder->count();
        $totalOrderManualCount = $totalOrderManual->count();
        $totalOrderPrepaidCount = $order->where('validated_by', 'prepaid')->count();
        $totalOrderBulkPrepaidCount = $totalOrderBulk->where('validated_by', 'prepaid')->count();
        $totalOrderEasyOrderPrepaidCount = $totalOrderEasyOrder->where('validated_by', 'prepaid')
            ->count();
        $totalOrderManualPrepaidCount = $totalOrderManual->where('validated_by', 'prepaid')->count();
        $totalOrderOllopsCount = $order->where('ollops_confirmation_status', 'confirmed')->count();
        $totalOrderBulkOllopsCount = $totalOrderBulk->where('ollops_confirmation_status', 'confirmed')->count();
        $totalOrderEasyOrderOllopsCount = $totalOrderEasyOrder->where('ollops_confirmation_status', 'confirmed')
            ->count();
        $totalOrderManualOllopsCount = $totalOrderManual->where('ollops_confirmation_status', 'confirmed')->count();
        $totalOrderAssginCount = $order->where('ollops_confirmation_status', 'confirmed_by_system')->count();
        $totalOrderBulkAssginCount = $totalOrderBulk->where('ollops_confirmation_status', 'confirmed_by_system')
            ->count();
        $totalOrderEasyOrderAssginCount = $totalOrderEasyOrder->where(
            'ollops_confirmation_status',
            'confirmed_by_system'
        )->count();
        $totalOrderManualAssginCount = $totalOrderManual->where('ollops_confirmation_status', 'confirmed_by_system')
            ->count();
        $totalOrderPendingCount = $order->where('status_id', OrderEnum::PENDING_STATUS)->count();
        $totalOrderBulkPendingCount = $totalOrderBulk->where('status_id', OrderEnum::PENDING_STATUS)->count();
        $totalOrderEasyOrderPendingCount = $totalOrderEasyOrder->where('status_id', OrderEnum::PENDING_STATUS)->count();
        $totalOrderManualPendingCount = $totalOrderManual->where('status_id', OrderEnum::PENDING_STATUS)->count();
        $totalOrderOllopsPendingCount = $order->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNull('operator_id')->count();
        $totalOrderBulkOllopsPendingCount = $totalOrderBulk->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNull('operator_id')->count();
        $totalOrderEasyOrderOllopsPendingCount = $totalOrderEasyOrder->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNull('operator_id')->count();
        $totalOrderManualOllopsPendingCount = $totalOrderManual->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNull('operator_id')->count();
        $totalOrderOllopsOperaterPendingCount = $order->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNotNull('operator_id')->count();
        $totalOrderBulkOllopsOperaterPendingCount = $totalOrderBulk->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNotNull('operator_id')->count();
        $totalOrderEasyOrderOllopsOperaterPendingCount = $totalOrderEasyOrder->where(
            'status_id',
            OrderEnum::PENDING_STATUS
        )->where('ollops_confirmation_status', 'pending')->whereNotNull('operator_id')
            ->count();
        $totalOrderManualOllopsOperaterPendingCount = $totalOrderManual->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'pending')->whereNotNull('operator_id')->count();
        $totalOrderOperatorPendingCount = $order->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNotNull('operator_id')->count();
        $totalOrderBulkOperatorPendingCount = $totalOrderBulk->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNotNull('operator_id')->count();
        $totalOrderEasyOrderOperatorPendingCount = $totalOrderEasyOrder->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNotNull('operator_id')->count();
        $totalOrderManualOperatorPendingCount = $totalOrderManual->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNotNull('operator_id')->count();
        $totalOrderOperatorPendingWaitCount = $order->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNull('operator_id')->count();
        $totalOrderBulkOperatorPendingWaitCount = $totalOrderBulk->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNull('operator_id')->count();
        $totalOrderEasyOrderOperatorPendingWaitCount = $totalOrderEasyOrder->where(
            'status_id',
            OrderEnum::PENDING_STATUS
        )->where('ollops_confirmation_status', 'not_validated')->whereNull('operator_id')
            ->count();
        $totalOrderManualOperatorPendingWaitCount = $totalOrderManual->where('status_id', OrderEnum::PENDING_STATUS)
            ->where('ollops_confirmation_status', 'not_validated')->whereNull('operator_id')->count();
        $totalOrderOllopsCancalledCount = $order->where('ollops_confirmation_status', 'cancelled')->count();
        $totalOrderBulkOllopsCancalledCount = $totalOrderBulk->where('ollops_confirmation_status', 'cancelled')
            ->count();
        $totalOrderEasyOrderOllopsCancalledCount = $totalOrderEasyOrder->where(
            'ollops_confirmation_status',
            'cancelled'
        )->count();
        $totalOrderManualOllopsCancalledCount = $totalOrderManual->where('ollops_confirmation_status', 'cancelled')
            ->count();
        $totalOrderOperatorCancalledCount = $order->where('ollops_confirmation_status', 'cancelled_by_system')->count();
        $totalOrderBulkOperatorCancalledCount = $totalOrderBulk->where(
            'ollops_confirmation_status',
            'cancelled_by_system'
        )->count();
        $totalOrderEasyOrderOperatorCancalledCount = $totalOrderEasyOrder->where(
            'ollops_confirmation_status',
            'cancelled_by_system'
        )->count();
        $totalOrderManualOperatorCancalledCount = $totalOrderManual->where(
            'ollops_confirmation_status',
            'cancelled_by_system'
        )->count();
        return get_defined_vars();
    }

    public function platformPerformanceReport(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$currentPeriod['from'], $currentPeriod['to']]]);
        $query = DB::table('orders')->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $source = $query->select('source_platform', DB::raw('count(*) as total'))
            ->groupBy('source_platform')
            ->pluck('total', 'source_platform')->toArray();
        $source_chart = [array_merge(['name'], array_keys($source)), array_merge(['Source Platform of Orders'],
            array_values($source))];
        $query = DB::table('orders')->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $created = $query->select('created_platform', DB::raw('count(*) as total'))
            ->groupBy('created_platform')
            ->pluck('total', 'created_platform')->toArray();
        $created_chart = [array_merge(['name'], array_keys($created)), array_merge(['Created Platform of Orders'],
            array_values($created))];
        return get_defined_vars();
    }

    public function productCapastePerformance(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $onHoldOrders = OrderItem::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->where('status_id', OrderEnum::PREPARING_STATUS)->whereHas('order', function($query)
            {
                $query->whereHas('wms_status', function($quy)
                {
                    $quy->where('status', 'on_hold')
                        ->whereRaw('wms_order_status.status = (SELECT MAX(op.status) FROM wms_order_status op WHERE op.order_id = wms_order_status.order_id)');
                });
            });
        $returnOrders = OrderItem::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->whereIn('status_id', [OrderEnum::SHIPPING_STATUS, OrderEnum::REJECTED_STATUS])
            ->whereHas('order', function($query)
            {
                $query->whereHas('OrderStatusAymakan', function($quy)
                {
                    $quy->whereIn('status', ['AY-0059', 'AY-0028', 'AY-0008', 'AY-0084'])
                        ->whereRaw('order_statuses_aymakan.status = (SELECT MAX(op.status) FROM order_statuses_aymakan op WHERE op.order_id = order_statuses_aymakan.order_id)');
                })->whereHas('wms_status', function($quy)
                {
                    $quy->where('status', 'shipped')
                        ->whereRaw('wms_order_status.status = (SELECT MAX(op.status) FROM wms_order_status op WHERE op.order_id = wms_order_status.order_id)');
                });
            });
        if($request->statusType == 'on_hold')
        {
            $recursiveRel = [
                'orderItems' => [
                    'type' => 'whereHas',
                    'whereBetween' => ['created_at' => [$currentPeriod['from'], $currentPeriod['to']]],
                    'whereIn' => ['id' => $onHoldOrders->pluck('id')]
                ],
            ];
        }elseif($request->statusType == 'return')
        {
            $recursiveRel = [
                'orderItems' => [
                    'type' => 'whereHas',
                    'whereBetween' => ['created_at' => [$currentPeriod['from'], $currentPeriod['to']]],
                    'whereIn' => ['id' => $returnOrders->pluck('id')]
                ],
            ];
        }elseif($request->statusType == 'pending')
        {
            $recursiveRel = [
                'orderItems' => [
                    'type' => 'whereHas',
                    'whereBetween' => ['created_at' => [$currentPeriod['from'], $currentPeriod['to']]],
                    'where' => ['status_id' => OrderEnum::PENDING_STATUS]
                ],
            ];
        }elseif($request->statusType == 'pending_inventory')
        {
            $recursiveRel = [
                'orderItems' => [
                    'type' => 'whereHas',
                    'whereBetween' => ['created_at' => [$currentPeriod['from'], $currentPeriod['to']]],
                    'where' => ['status_id' => OrderEnum::PENDING_INVENTORY_STATUS]
                ],
            ];
        }else
        {
            $recursiveRel = [
                'orderItems' => [
                    'type' => 'whereHas',
                    'whereIn' => ['status_id' => [OrderEnum::PENDING_INVENTORY_STATUS, OrderEnum::PENDING_STATUS]],
                    'whereBetween' => ['created_at' => [$currentPeriod['from'], $currentPeriod['to']]],
                    'orWhereIn' => ['id' => array_merge($onHoldOrders->pluck('id')->toArray(),
                        $returnOrders->pluck('id')->toArray())]
                ],
            ];
        }
        $products = $this->productService->findBy($request, recursiveRel: $recursiveRel);
        $orderPendingInventory = OrderItem::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->where('status_id', OrderEnum::PENDING_INVENTORY_STATUS);
        $orderPendingInventoryCount = $orderPendingInventory->count();
        $orderPendingInventoryQuantityCount = $orderPendingInventory->sum('quantity');
        $orderPending = OrderItem::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->where('status_id', OrderEnum::PENDING_STATUS);
        $orderPendingCount = $orderPending->count();
        $orderPendingQuantityCount = $orderPending->sum('quantity');
        $onHoldOrderCount = $onHoldOrders->count();
        $onHoldOrderQuantityCount = $onHoldOrders->sum('quantity');
        $returnOrderCount = $returnOrders->count();
        $returnOrderQuantityCount = $returnOrders->sum('quantity');
        return get_defined_vars();
    }

    public function cancelledPerformance(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getLastPeriodBestOnPeriodType($request->period, $request);
        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);
        if(isset($request->cancelled_by) && $request->cancelled_by == 'whatsapp')
        {
            $request->merge(['ollops_confirmation_status' => 'cancelled']);
        }elseif(isset($request->cancelled_by) && $request->cancelled_by == 'system')
        {
            $request->merge(['ollops_confirmation_status' => ['cancelled_by_system', 'not_validated']]);
        }
        $order = $this->orderService->findBy($request);
        $currentOrder = $order->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);
        $total['total']['total'] = $currentOrder->count();
        $total['total']['canceled'] = $currentOrder->where('status_id', OrderEnum::CANCELED_STATUS)->count();
        $total['total']['pending'] = $currentOrder->where('status_id', '!=', OrderEnum::CANCELED_STATUS)
            ->whereNull('validated')->count();
        $total['total']['validation'] = $currentOrder->where('status_id', '!=', OrderEnum::CANCELED_STATUS)
            ->whereNotNull('validated')->count();
        $qcanelled = Order::join('order_statuses as os1', function ($join) {
            $join->on('orders.id', '=', 'os1.order_id')
                ->where('os1.status_id', OrderEnum::NEW_STATUS); // ضع اسم الحالة الأولى
        })
            ->join('order_statuses as os2', function ($join) {
                $join->on('orders.id', '=', 'os2.order_id')
                    ->where('os2.status_id', OrderEnum::CANCELED_STATUS); // ضع اسم الحالة الثانية
            })
            ->select(
                'orders.id as order_id',
                DB::raw('TIMESTAMPDIFF(MINUTE, os1.created_at, os2.created_at) AS minutes_difference')
            )
            ->whereBetween(
                'orders.created_at',
                [$currentPeriod['from'], $currentPeriod['to']]
            ) // الفلترة حسب الفترة الزمنية
            ->whereRaw('TIMESTAMPDIFF(MINUTE, os1.created_at, os2.created_at) <= 5');
        if (isset($request->cancelled_by) && $request->cancelled_by == 'whatsapp') {
            $qcanelled = $qcanelled->where('orders.ollops_confirmation_status', 'cancelled');
        } elseif (isset($request->cancelled_by) && $request->cancelled_by == 'system') {
            $qcanelled = $qcanelled->whereIn(
                'orders.ollops_confirmation_status',
                ['cancelled_by_system', 'not_validated']
            );
        }
        if (isset($request->dropshipper_id) && $request->dropshipper_id) {
            $qcanelled = $qcanelled->whereIn('orders.dropshipper_id', $request->dropshipper_id);
        }
        if (isset($request->source_platform) && $request->source_platform) {
            $qcanelled = $qcanelled->where('orders.source_platform', $request->source_platform);
        }
        if (isset($request->created_platform) && $request->created_platform) {
            $qcanelled = $qcanelled->where('orders.created_platform', $request->created_platform);
        }
        $total['total']['qcanelled'] = $qcanelled->count();
        $notesArray = [];
        foreach (Remark::all() as $remark) {
            $orderRemark = $currentOrder->where('status_id', OrderEnum::CANCELED_STATUS)
                ->where('remark_id', $remark->id);
            $count = $orderRemark->count();
            if ($count) {
                $total['total']['remark'][$remark->name] = $count;
            }
            if ($remark->name == 'Others') {
                $new = [];
                foreach ($orderRemark->pluck('notes') as $notes) {
                    foreach ($notes as $note) {
                        $new[] = "" . $note->content . " - ( " . ($note->user ? $note->user->name : 'client') . ")";
                    }
                }
                $notesArray[] = implode(',', $new);
            }
        }
        $chart = [];
        if ($total['total']['remark'] ?? []) {
            $nameRemark = array_keys($total['total']['remark']);
            $countRemark = array_values($total['total']['remark']);
            $chart = [array_merge(['name'], $nameRemark), array_merge(['Remarks'], $countRemark)];
            arsort($total['total']['remark']);
        }
        $lastOrder = $order->whereBetween('created_at', [$lastPeriod['from'], $lastPeriod['to']]);
        $totalLast['total']['total'] = $this->percentageChange($total['total']['total'], $lastOrder->count());
        $totalLast['total']['canceled'] = $this->percentageChange(
            $total['total']['canceled'],
            $lastOrder->where('status_id', OrderEnum::CANCELED_STATUS)->count()
        );
        $totalLast['total']['pending'] = $this->percentageChange(
            $total['total']['pending'],
            $lastOrder->where('status_id', OrderEnum::CANCELED_STATUS)->whereNull('validated')->count()
        );
        $totalLast['total']['validation'] = $this->percentageChange(
            $total['total']['pending'],
            $lastOrder->where('status_id', '!=', OrderEnum::CANCELED_STATUS)->whereNotNull('validated')->count()
        );
        foreach (Remark::all() as $remark) {
            $totalLast['total']['remark'][$remark->name] = $this->percentageChange(
                $total['total']['remark'][$remark->name] ?? 00,
                $lastOrder->where('status_id', OrderEnum::CANCELED_STATUS)->where('remark_id', $remark->id)->count()
            );
        }
        $notes = array_filter($notesArray);
        return get_defined_vars();
    }

    public function reportPaymentPerformance(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        if (isset($request->type_date) && $request->type_date == 'validated') {
            $request->merge(['validated' => [$currentPeriod['from'], $currentPeriod['to']]]);
        } else {
            $request->merge(['created_at' => [$currentPeriod['from'], $currentPeriod['to']]]);
        }
        $order = $this->orderService->findBy($request);

        $orderCod = $order->where('paymentMethod', PaymentEnum::CASH_ON_DELIVERY_ID);
        $orderWallet = $order->where('paymentMethod', PaymentEnum::WALLET_METHOD_ID);
        $orderOnline = $order->where('paymentMethod', PaymentEnum::ONLINE_METHOD_ID);

        $orderCodCanceled = $orderCod->where('status_id', OrderEnum::CANCELED_STATUS)->count();
        $orderWalletCanceled = $orderWallet->where('status_id', OrderEnum::CANCELED_STATUS)->count();
        $orderOnlineCanceled = $orderOnline->where('status_id', OrderEnum::CANCELED_STATUS)->count();

        $orderCodPay = $orderCod->where('status_id', OrderEnum::COMPLETED_STATUS)->count();
        $orderWalletPay = $orderWallet->whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::SHIPPING_STATUS, OrderEnum::PREPARING_STATUS,OrderEnum::PENDING_INVENTORY_STATUS])->count();
        $orderOnlinePay = $orderOnline->where('status_click_payment',ClickPayEnum::Pay)->whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::SHIPPING_STATUS, OrderEnum::PREPARING_STATUS,OrderEnum::PENDING_INVENTORY_STATUS])->count();

        $orderCodRejected = $orderCod->where('status_id', OrderEnum::REJECTED_STATUS)->count();
        $orderWalletRejected = $orderWallet->whereIn('status_id', [OrderEnum::REJECTED_STATUS, OrderEnum::RETURN_BALANCE_STATUS ])->count();
        $orderOnlineRejected = $orderOnline->where('status_click_payment',ClickPayEnum::Pay)->whereIn('status_id', [OrderEnum::REJECTED_STATUS, OrderEnum::RETURN_BALANCE_STATUS ])->count();

        $orderCodWaitPay = $orderCod->whereIn('status_id', [OrderEnum::NEW_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::ONHOLD_STATUS])->count();
        $orderWalletWaitPay = $orderWallet->whereIn('status_id', [OrderEnum::NEW_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::ONHOLD_STATUS])->count();
        $orderOnlineWaitPay = $orderOnline->whereIn('status_id', [OrderEnum::NEW_STATUS, OrderEnum::PAY_PENDING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::ONHOLD_STATUS])->count();

        $orderCount = $order->count();
        $orderCodCount = $orderCod->count();
        $orderWalletCount = $orderWallet->count();
        $orderOnlineCount = $orderOnline->count();
        return get_defined_vars();
    }

    /**
     * Generates order sources report based on specified period and calculates various statistics.
     *
     * @param Request $request Contains parameters, including the period for generating the report.
     * @return array Returns an array with keys 'orderConfirmationRates', 'deliveryRatesForConfirmedOrders',
     * 'deliveryRatesForTotalOrders', and 'getRemarkCancellationRates', each containing specific dataset values.
     */
    public function orderSourcesReport(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);
        $lastPeriod = $this->getLastPeriodBestOnPeriodType($request->period, $request);

        $request->merge(['created_at' => [$lastPeriod['from'], $currentPeriod['to']]]);

        $orderAllRates = $this->getOrderAllRates($currentPeriod, $lastPeriod);
        $orderConfirmationRates = $this->getOrderConfirmationRates($currentPeriod, $lastPeriod, $orderAllRates['platformCounts']);

        $result = [
            'orderAllRates' => $orderAllRates,
            'deliveryRatesForTotalOrders' => $this->getDeliveryRates(OrderEnum::COMPLETED_STATUS, false, $currentPeriod, $lastPeriod, $orderAllRates['platformCounts']),
            'getOrderCancellationRates' => $this->getOrderCancellationRates($currentPeriod, $lastPeriod, $orderAllRates['platformCounts']),
            'orderConfirmationRates' => $orderConfirmationRates,
            'deliveryRatesForConfirmedOrders' => $this->getDeliveryRates(OrderEnum::COMPLETED_STATUS, true, $currentPeriod, $lastPeriod, $orderConfirmationRates['platformCounts']),
        ];

        return [
            'date' => [
                'currentPeriod' => $currentPeriod,
                'lastPeriod' => $lastPeriod,
            ],
            'result' => $result,
            'getRemarkCancellationRates' => $this->getRemarkCancellationRates($currentPeriod, $lastPeriod, $request),
        ];
    }

    /**
     * Calculates order confirmation rates for different platforms.
     *
     * @param array $currentPeriod The current period range.
     * @param array $lastPeriod The last period range.
     * @param array $orderAllRates Counts for all platforms in the period.
     * @return array Returns counts and percentages for confirmed orders by platform.
     */
    public function getOrderConfirmationRates($currentPeriod, $lastPeriod, $orderAllRates)
    {
        $orderIsConfirmationCount = Order::where(function ($query) {
            $query->where('validated_by', 'prepaid')->orWhere(function ($query) {
                $query->where('paymentMethod', PaymentEnum::CASH_ON_DELIVERY_ID)
                    ->where('status_id', OrderEnum::COMPLETED_STATUS);
            });
        })->where('status_id', '!=', OrderEnum::CANCELED_STATUS)
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($currentPeriod) {
            return Order::where(function ($query) {
                $query->where('validated_by', 'prepaid')->orWhere(function ($query) {
                    $query->where('paymentMethod', PaymentEnum::CASH_ON_DELIVERY_ID)
                        ->where('status_id', OrderEnum::COMPLETED_STATUS);
                });
            })->where('status_id', '!=', OrderEnum::CANCELED_STATUS)
                ->where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
                ->count();
        }, $platforms);

        $percentages = array_map(function ($count) use ($orderIsConfirmationCount) {
            return $orderIsConfirmationCount > 0 ? round(($count / $orderIsConfirmationCount) * 100, 2) : 0;
        }, $platformCounts);

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = $orderAllRates['totalCount'] > 0
            ? round(($platformCounts['totalCount'] / $orderAllRates['totalCount']) * 100, 2)
            : 0;

        return [
            'orderIsConfirmationCount' => $orderIsConfirmationCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Retrieves order counts and percentages for each platform in the given period.
     *
     * @param array $currentPeriod The current period.
     * @param array $lastPeriod The last period (not used).
     * @return array Counts and percentages by platform and total.
     */
    public function getOrderAllRates($currentPeriod, $lastPeriod)
    {
        $orderIsConfirmationCount = Order::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($currentPeriod) {
            return Order::where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
                ->count();
        }, $platforms);

        $percentages = array_map(function ($count) use ($orderIsConfirmationCount) {
            return $orderIsConfirmationCount > 0 ? round(($count / $orderIsConfirmationCount) * 100, 2) : 0;
        }, $platformCounts);

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = array_sum($percentages);

        return [
            'orderIsConfirmationCount' => $orderIsConfirmationCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Calculates delivery rates for orders based on status and confirmation.
     *
     * @param string $status Order status to filter.
     * @param bool $confirmedOnly Whether to include only confirmed orders.
     * @return array Contains count and percentage data by platform.
     */
    public function getDeliveryRates($status, $confirmedOnly = false, $currentPeriod, $lastPeriod, $orderConfirmationRates)
    {
        $orderIsConfirmationCount = Order::whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->where('status_id', $status)
            ->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($status, $confirmedOnly, $currentPeriod) {
            $query = Order::where('status_id', $status)
                ->where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);

            if ($confirmedOnly) {
                $query->whereNotNull('validated');
            }

            return $query->count();
        }, $platforms);

        foreach ($platformCounts as $key => $value) {
            $percentages[$key] = $orderConfirmationRates[$key] > 0 ? round(($value / $orderConfirmationRates[$key]) * 100, 2) : 0;
        }

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = $orderConfirmationRates['totalCount'] > 0
            ? round(($platformCounts['totalCount'] / $orderConfirmationRates['totalCount']) * 100, 2)
            : 0;

        return [
            'orderIsConfirmationCount' => $orderIsConfirmationCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Retrieves cancellation rates for orders by platform.
     *
     * @return array Contains count and percentage data by platform.
     */
    public function getOrderCancellationRates($currentPeriod, $lastPeriod, $orderAllRates)
    {
        $orderIsCancellationRatesCount = Order::where('status_id', OrderEnum::CANCELED_STATUS)
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
            ->count();

        $platforms = [
            'website' => PlatformEnum::WEBSITE_PLATFORM,
            'tikTok' => PlatformEnum::TiKTOK_PLATFORM,
            'salla' => PlatformEnum::SALLA_PLATFORM,
            'easyOrder' => PlatformEnum::EASYORDER_PLATFORM,
        ];

        $platformCounts = array_map(function ($platform) use ($currentPeriod) {
            return Order::where('status_id', OrderEnum::CANCELED_STATUS)
                ->where('source_platform', $platform)
                ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
                ->count();
        }, $platforms);

        $percentages = array_map(function ($count) use ($orderIsCancellationRatesCount) {
            return $orderIsCancellationRatesCount > 0 ? round(($count / $orderIsCancellationRatesCount) * 100, 2) : 0;
        }, $platformCounts);

        $platformCounts['totalCount'] = array_sum($platformCounts);
        $percentages['totalPercentage'] = $orderAllRates['totalCount'] > 0
            ? round(($platformCounts['totalCount'] / $orderAllRates['totalCount']) * 100, 2)
            : 0;

        return [
            'orderIsCancellationRatesCount' => $orderIsCancellationRatesCount,
            'platformCounts' => $platformCounts,
            'platformPercentages' => $percentages,
        ];
    }

    /**
     * Retrieves the cancellation rates for different remarks within a specified time period.
     *
     * @return array Returns an array containing the total count of canceled orders for each remark.
     */
    public function getRemarkCancellationRates($currentPeriod, $lastPeriod, $request)
    {
        $getRemarkCancellationRates = Order::where('status_id', OrderEnum::CANCELED_STATUS)
            ->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']]);

        if ($request->has('source_platform') && !empty($request->source_platform)) {
            $getRemarkCancellationRates = $getRemarkCancellationRates->where('source_platform', $request->source_platform);
        }

        $getRemarkCancellationRates = $getRemarkCancellationRates->get();

        $total = [];

        foreach (Remark::all() as $remark) {
            $orderRemark = $getRemarkCancellationRates->where('remark_id', $remark->id);
            $count = $orderRemark->count();

            if ($count) {
                $total['remark'][$remark->name] = $count;
            }
        }

        return $total;
    }
    public function wmsPerformance(Request $request)
    {
        $currentPeriod = $this->getPeriodBestOnPeriodType($request->period, $request);

        $orders = Order::has('wms_status')->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])
               ->with(['wms_status' => function ($query) {
                   $query->latest('id')->limit(1); // جلب آخر صف بناءً على id
               }]);
        if($request->has('source_platform') && !empty($request->source_platform))
        {
        $orders = $orders->where('source_platform', $request->source_platform);
         }
        if($request->has('created_platform') && !empty($request->created_platform))
        {
            $orders = $orders->where('created_platform', $request->created_platform);
        }
        if($request->has('dropshipper_id') && !empty($request->dropshipper_id))
        {
            $orders = $orders->whereIn('dropshipper_id', $request->dropshipper_id);
        }
               $orders=$orders->get()
               ->groupBy(function ($order) {
                   return $order->wms_status()->first()->status; // تجميع بناءً على آخر status لكل order
               })
               ->map(function ($orders, $status) {
                   return [
                       'status' => $status,
                       'count' => $orders->count(),
                       'orders' => $orders
                   ];
               });
        return get_defined_vars();
    }
}
