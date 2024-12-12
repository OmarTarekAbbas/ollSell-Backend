<?php

namespace Modules\Logistics\Service;

use Modules\Order\Enums\OrderEnum;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Basic\Service\BasicService;

use Illuminate\Support\Facades\DB;



class ReportAymakanService extends BasicService
{

    public function orderAymakan(Request $request)
    {

        $conditionWhere = $this->filterByDateNew($request);

        $orders = Order::whereNotNull('orders.tracking_number');
        //->where('orders.status_id', OrderEnum::PREPARING_STATUS);
        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->period)) {

            if (isset($request->type_date) && $request->type_date == 'validated') {
                $orders = $orders->join('order_statuses_aymakan', 'order_statuses_aymakan.order_id', '=', 'orders.id')
                ->where('order_statuses_aymakan.status', 'AY-0003')
                ->whereBetween('order_statuses_aymakan.created_at', [$conditionWhere[0], $conditionWhere[1]]);
            } else {
                $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
            }
        }
        if (!empty($request->source_platform)) {
            $orders = $orders->where('orders.source_platform', $request->source_platform);
        }
        if (!empty($request->created_platform)) {
            $orders = $orders->where('orders.created_platform', $request->created_platform);
        }
        if (!empty($request->paymentMethod)) {

            if ($request->paymentMethod == 2) {
                $orders = $orders->where('orders.paymentMethod', 2);
            } else {
                $orders = $orders->where('orders.paymentMethod', '!=', 2);
            }
        }

        $orders = $orders->select('orders.*')->distinct()->get();
        $ordersaray = [];
        if ($request->type == 'total') {
            return  $orders;
        }
        foreach ($orders as $row) {
            $inExternal = $row->OrderStatusAymakanNo()->whereIn('status', ['AY-0069', 'AY-0070'])->first();

            if ($request->type == 'internal' || $request->type == 'external') {
                if ($request->type == 'external') {
                    if ($inExternal) {
                        $ordersaray[] = $row;
                    }
                }
                if ($request->type == 'internal') {
                    if ($inExternal == null) {
                        $ordersaray[] = $row;
                    }
                }
            } else {
                $status = $row->OrderStatusAymakanNo()->orderBy('id', direction: 'DESC')->first();
                if ($request->status == 'all') {
                    if ($status) {
                        if ($status->status == $request->type) {
                            $ordersaray[] = $row;
                        } elseif ($request->type == 'returnInprogress') {

                            if ($status->status == 'AY-0028' || $status->status == 'AY-0059' || $status->status == 'AY-0084') {
                                $ordersaray[] = $row;
                            }
                        } elseif ($request->type == 'shipping') {
                            if (
                                $status->status != 'AY-0028' && $status->status != 'AY-0059' && $status->status != 'AY-0084' &&

                                $status->status != 'AY-0050' && $status->status != 'AY-0008' && $status->status != 'AY-0005' && $status->status !=  'AY-0029'
                            ) {
                                $ordersaray[] = $row;
                            }
                        }
                    } else {
                        if ($request->type == 'shipping') {
                            $ordersaray[] = $row;
                        }
                    }
                } elseif ($request->status == 'internal') {
                    if ($inExternal == null) {
                        if ($status) {
                            if ($status->status == $request->type) {
                                $ordersaray[] = $row;
                            } elseif ($request->type == 'returnInprogress') {

                                if ($status->status == 'AY-0028' || $status->status == 'AY-0059' || $status->status == 'AY-0084') {
                                    $ordersaray[] = $row;
                                }
                            } elseif ($request->type == 'shipping') {
                                if (
                                    $status->status != 'AY-0028' && $status->status != 'AY-0059' && $status->status != 'AY-0084' &&

                                    $status->status != 'AY-0050' && $status->status != 'AY-0008' && $status->status != 'AY-0005' && $status->status !=  'AY-0029'
                                ) {
                                    $ordersaray[] = $row;
                                }
                            }
                        } else {
                            if ($request->type == 'shipping') {
                                $ordersaray[] = $row;
                            }
                        }
                    }
                } elseif ($request->status == 'external') {
                    if ($inExternal) {
                        if ($status) {
                            if ($status->status == $request->type) {
                                $ordersaray[] = $row;
                            } elseif ($request->type == 'returnInprogress') {

                                if ($status->status == 'AY-0028' || $status->status == 'AY-0059' || $status->status == 'AY-0084') {
                                    $ordersaray[] = $row;
                                }
                            } elseif ($request->type == 'shipping') {
                                if (
                                    $status->status != 'AY-0028' && $status->status != 'AY-0059' && $status->status != 'AY-0084' &&

                                    $status->status != 'AY-0050' && $status->status != 'AY-0008' && $status->status != 'AY-0005' && $status->status !=  'AY-0029'
                                ) {
                                    $ordersaray[] = $row;
                                }
                            }
                        } else {
                            if ($request->type == 'shipping') {
                                $ordersaray[] = $row;
                            }
                        }
                    }
                }
            }
        }

        return $ordersaray;
    }




    public function reportAllShipping(Request $request)
    {

        $conditionWhere = $this->filterByDateNew($request);

        $orders = Order::whereNotNull('orders.tracking_number');
        if (!empty($request->period)) {
            if (isset($request->type_date) && $request->type_date == 'validated') {
                $orders = $orders->join('order_statuses_aymakan', 'order_statuses_aymakan.order_id', '=', 'orders.id')
                 ->where('order_statuses_aymakan.status', 'AY-0003')->whereBetween('order_statuses_aymakan.created_at', [$conditionWhere[0], $conditionWhere[1]]);
            } else {
                $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
            }
        }
        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->source_platform)) {
            $orders = $orders->where('orders.source_platform', $request->source_platform);
        }
        if (!empty($request->created_platform)) {
            $orders = $orders->where('orders.created_platform', $request->created_platform);
        }
        if (!empty($request->paymentMethod)) {

            if ($request->paymentMethod == 2) {
                $orders = $orders->where('orders.paymentMethod', 2);
            } else {
                $orders = $orders->where('orders.paymentMethod', '!=', 2);
            }
        }

        $orders = $orders->select('orders.*')->distinct()->get();
        $allOrderInShipping = count($orders);
        $onHold = 0;
        $return = 0;
        $completed = 0;
        $canceled = 0;
        $returnInprogress = 0;
        $shipping = 0;
        $notAnyReply = 0;
        $internal = 0;
        $external = 0;
        foreach ($orders as $row) {

            $inExternal = $row->OrderStatusAymakanNo()->whereIn('status', ['AY-0069', 'AY-0070'])->first();
            if ($inExternal) {
                $external++;
            } else {
                $internal++;
            }

            $status = $row->OrderStatusAymakanNo()->orderBy('id', direction: 'DESC')->first();
            if ($request->status == 'all') {
                if ($status) {
                    if ($status->status == 'AY-0050') {
                        $onHold++;
                    } elseif ($status->status == 'AY-0008') {
                        $return++;
                    } elseif ($status->status == 'AY-0005') {
                        $completed++;
                    } elseif ($status->status == 'AY-0029') {
                        $canceled++;
                    } elseif ($status->status == 'AY-0028' || $status->status == 'AY-0059' || $status->status == 'AY-0084') {
                        $returnInprogress++;
                    } else {
                        $shipping++;
                    }
                } else {
                    $notAnyReply++;
                }
            } elseif ($request->status == 'internal') {
                if ($inExternal == null) {
                    if ($status) {
                        if ($status->status == 'AY-0050') {
                            $onHold++;
                        } elseif ($status->status == 'AY-0008') {
                            $return++;
                        } elseif ($status->status == 'AY-0005') {
                            $completed++;
                        } elseif ($status->status == 'AY-0029') {
                            $canceled++;
                        } elseif ($status->status == 'AY-0028' || $status->status == 'AY-0059' || $status->status == 'AY-0084') {
                            $returnInprogress++;
                        } else {
                            $shipping++;
                        }
                    } else {
                        $notAnyReply++;
                    }
                }
            } elseif ($request->status == 'external') {
                if ($inExternal) {
                    if ($status) {
                        if ($status->status == 'AY-0050') {
                            $onHold++;
                        } elseif ($status->status == 'AY-0008') {
                            $return++;
                        } elseif ($status->status == 'AY-0005') {
                            $completed++;
                        } elseif ($status->status == 'AY-0029') {
                            $canceled++;
                        } elseif ($status->status == 'AY-0028' || $status->status == 'AY-0059' || $status->status == 'AY-0084') {
                            $returnInprogress++;
                        } else {
                            $shipping++;
                        }
                    } else {
                        $notAnyReply++;
                    }
                }
            }
        }


        $data = [
            'allOrderInShipping' => $allOrderInShipping,
            'onHold' => $onHold,
            'return' => $return,
            'completed' => $completed,
            'canceled' => $canceled,
            'returnInprogress' => $returnInprogress,
            'shipping' => $shipping + $notAnyReply,
            'notAnyReply' => $notAnyReply,
            'internal' => $internal,
            'external' => $external,
        ];
        return $data;
    }



    public function filterByDateNew($request)
    {
        $moreConditionForFirstLevel = [];
        $day = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        if ($request->period === 'today') {
            $date = Carbon::parse("{$day}-{$month}-{$year}");
            $from = $date->format('Y-m-d 00:00:00');
            $to = $date->format('Y-m-d 23:59:59');
            $moreConditionForFirstLevel = [$from, $to];
        }
        if ($request->period === 'thisWeek') {
            $day = Carbon::now()->startOfWeek(Carbon::SATURDAY);
            $from = Carbon::create($day)->startOfDay();
            $to = Carbon::create($day)->addDays(6)->endOfDay();

            $moreConditionForFirstLevel = [$from, $to];
        }
        if ($request->period === 'thisMonth') {
            $date = Carbon::parse("01-{$month}-{$year}");
            $from = $date->format('Y-m-d 00:00:00');
            $to = $date->copy()->lastOfMonth()->format('Y-m-d 23:59:59');
            $moreConditionForFirstLevel = [$from, $to];
        }
        if ($request->period === 'thisYear') {
            $date = Carbon::parse("01-01-{$year}");
            $from = $date->format('Y-m-d 00:00:00');
            $to = $date->copy()->lastOfYear()->format('Y-m-d 23:59:59');
            $moreConditionForFirstLevel = [$from, $to];
        }
        if ($request->period === 'thisCustom') {
            $from = Carbon::createFromDate($request->fromDate)->startOfDay();
            $to = Carbon::createFromDate($request->toDate)->endOfDay();
            $moreConditionForFirstLevel = [$from, $to];
        }
        return $moreConditionForFirstLevel;
    }
    public function getTodayPoints()
    {
        $today = \Illuminate\Support\Carbon::now();

        $today->setTime(0, 0, 0);
        $periods = [];
        for ($i = 1; $i <= 6; $i++) {
            $periods[$i]['from'] = $today->copy()->addHours(4 * ($i - 1));
            $periods[$i]['to'] = $today->copy()->addHours(4 * $i);
            $periods[$i]['in'] = 0;
            $periods[$i]['out'] = 0;
        }
        return $periods;
    }

    public function getPointsOfCustomDateRange($conditionWhere)
    {
        $periods = [];
        // Set the two dates you want to compare $conditionWhere[1]
        $startDate = Carbon::createFromDate($conditionWhere[0]);
        $endDate = Carbon::createFromDate($conditionWhere[1]);
        // Get the difference in days between the two dates
        $diffInDays = $endDate->diffInDays($startDate);
        if ($diffInDays == 0) {

            $today = $startDate;

            $today->setTime(0, 0, 0);
            $periods = [];
            for ($i = 1; $i <= 6; $i++) {
                $periods[$i]['from'] = $today->copy()->addHours(4 * ($i - 1));
                $periods[$i]['to'] = $today->copy()->addHours(4 * $i);
                $periods[$i]['in'] = 0;
                $periods[$i]['out'] = 0;
            }

            return $periods;
        }
        if ($diffInDays <= 7) {
            $periods[0]['from'] = Carbon::createFromDate($conditionWhere[0])->startOfDay();
            $periods[0]['to'] = Carbon::createFromDate($conditionWhere[0])->endOfDay();
            $periods[0]['in'] = 0;
            $periods[0]['out'] = 0;
            for ($i = 1; $i <= $diffInDays; $i++) {
                $day = $startDate->addDays(1);
                $periods[$i]['from'] = Carbon::create($day)->startOfDay();
                $periods[$i]['to'] = Carbon::create($day)->endOfDay();
                $periods[$i]['in'] = 0;
                $periods[$i]['out'] = 0;
            }
            // Output the array of days
            return $periods;
        } else if ($diffInDays <= 30) {
            $newDiffInDays = $diffInDays + 1;
            $numIntervals = ceil($newDiffInDays / 5);
            // Split the period into parts of 5 days each
            for ($i = 0; $i < $numIntervals; $i++) {
                $first_date = $startDate->copy()->addDays($i * 5);
                $last_date = $first_date->copy()->addDays(5);
                // Do something with each 5-day interval, such as print it
                $periods[$i]['from'] = $first_date->startOfDay();
                if ($last_date->toDateString() > Carbon::createFromDate($conditionWhere[1])) {
                    $periods[$i]['to'] = Carbon::createFromDate($conditionWhere[1])->endOfDay();
                } else {
                    $periods[$i]['to'] = $last_date->endOfDay();
                }
                $periods[$i]['in'] = 0;
                $periods[$i]['out'] = 0;
            }
            return $periods;
        } else if ($diffInDays <= 180) {
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
                    'in' => 0,
                    'out' => 0
                ];
                // Set the next period start date as the current period end date
                $periodStart = $periodEnd;
            }
            return $periods;
        } else if ($diffInDays > 180) {
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
                    'in' => 0,
                    'out' => 0
                ];
                // Set the next period start date as the current period end date plus one day
                $periodStart = $periodEnd->copy();
            }
            // Return the period parts array as a response
            return $periods;
        }
    }
    public function getWeekPoints()
    {
        $periods = [];
        // startOfDay
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::now()->startOfWeek(Carbon::SATURDAY)->addDays($i);
            $periods[$i]['from'] = Carbon::create($day)->startOfDay();
            $periods[$i]['to'] = Carbon::create($day)->endOfDay();
            $periods[$i]['in'] = 0;
            $periods[$i]['out'] = 0;
        }

        return $periods;
    }

    public function getMonthPoints()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $monthParts = [];
        for ($i = 0; $i < 7; $i++) {
            $x = $i * 5;
            $monthParts[$i]['in'] = 0;
            $monthParts[$i]['out'] = 0;
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
                ->startOfDay(), 'in' => 0, 'out' => 0],
            ['from' => Carbon::create($currentYear, 2, 28)->startOfDay(), 'to' => Carbon::create($currentYear, 4, 30)
                ->startOfDay(), 'in' => 0, 'out' => 0],
            ['from' => Carbon::create($currentYear, 4, 30)->startOfDay(), 'to' => Carbon::create($currentYear, 6, 30)
                ->startOfDay(), 'in' => 0, 'out' => 0],
            ['from' => Carbon::create($currentYear, 6, 30)->startOfDay(), 'to' => Carbon::create($currentYear, 8, 31)
                ->startOfDay(), 'in' => 0, 'out' => 0],
            ['from' => Carbon::create($currentYear, 8, 31)->startOfDay(), 'to' => Carbon::create($currentYear, 10, 31)
                ->startOfDay(), 'in' => 0, 'out' => 0],
            ['from' => Carbon::create($currentYear, 10, 31)->startOfDay(), 'to' => Carbon::create($currentYear, 12, 31)
                ->startOfDay(), 'in' => 0, 'out' => 0]
        ];
        return $periods;
    }
}
