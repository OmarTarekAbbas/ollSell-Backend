<?php

namespace Modules\Logistics\Service;

use Modules\Order\Enums\OrderEnum;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;

use Modules\Basic\Service\BasicService;

use Modules\Order\Service\OrderService;
use Modules\Logistics\Entities\ShippingCompany;
use Modules\Logistics\Entities\ShippingCompanyCityTime;

use Illuminate\Support\Facades\DB;

use Modules\Acl\Service\DropshipperService;
use Modules\CoreData\Service\CategoryService;
use Modules\Order\Repositories\OrderRepository;
use Modules\MasterCatalog\Service\ProductService;

class ReportService extends BasicService
{
    protected $orderService, $orderRepo, $dropshipperService, $productService, $categoryService;

    /**
     * This is a constructor function that initializes several services and repositories used in an
     * e-commerce application.
     *
     * param  orderService An instance of the OrderService class, which likely contains
     * methods for managing orders in some way.
     * param OrderRepository orderRepo OrderRepository is likely a class that handles database
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
        OrderRepository $orderRepo,
        DropshipperService $dropshipperService,
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->orderService = $orderService;
        $this->orderRepo = $orderRepo;
        $this->dropshipperService = $dropshipperService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }








    public function reportAllShippingCompany(Request $request)
    {
        $conditionWhere = $this->filterByDateNew($request);

        switch ($request->period) {
            case ('thisWeek'):
                $points = $this->getWeekPoints();
                break;
            case ('thisMonth'):
                $points = $this->getMonthPoints();
                break;
            case ('thisYear'):
                $points = $this->getYearPoints();
                break;
            case ('thisCustom'):
                $points = $this->getPointsOfCustomDateRange($conditionWhere);
                break;
            default:
                $points = $this->getTodayPoints();
                break;
        }

        if ($request->statusId == 'all') {
            $orders = Order::whereIn('orders.status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
        } else {
            $orders = Order::where('orders.status_id', $request->statusId);
        }

        if (!empty($request->period)) {

            // $orders = $orders->join('order_statuses', 'order_statuses.order_id', '=', 'orders.id');
            // if ($request->statusId == 'all') {
            //     $orders = $orders->whereIn('order_statuses.status_id',  [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
            // } else {
            //     $orders = $orders->where('order_statuses.status_id', $request->statusId);
            // }

            $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
        }

        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->supplier)) {
            $orders = $orders->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.supplier_id', $request->supplier);
        }
        if (!empty($request->city)) {
            $orders = $orders->whereIn('orders.customerCity', $request->city);
        }
        $orders = $orders->select('orders.*')->get();

//
        $sipping = ShippingCompany::with('shipping_company_vacation')->first();

        $cities = [];
        $stackcities = [];
        $allWithin = 0;
        $allOut = 0;
        $i = 0;

        $arrayin = [];


        foreach ($orders as $row) {
      

            if ($request->statusId == 'all') {
                $end_time = @$row->orderStatus()->whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS])->orderBy('id', 'DESC')->first()->created_at;
            } else {
                $end_time = @$row->orderStatus()->where('status_id', $request->statusId)->orderBy('id', 'DESC')->first()->created_at;
            }
      

            $start_time_vacation =  $start_time = @$row->orderStatus()->where('status_id', operator: OrderEnum::SHIPPING_STATUS)->orderBy('id', 'DESC')->first()->created_at;


            $houreTime =   Carbon::parse($start_time)->format('H:i:s');


            if ($start_time) {
             
                if ($sipping->order_fulfillment_end_time  < $houreTime) {
                    $start_time = Carbon::parse($start_time)->addDays(1)->format('Y-m-d') . ' ' . $sipping->order_fulfillment_start_time;
                }
                $numberHoure =  @ShippingCompanyCityTime::where('city_id', $row->customerCity)->where('number_hours', '!=', 0)->first()->number_hours;

                $end_time_hypothetical = Carbon::create($start_time)->addHour($numberHoure)->endOfDay();


                if (!empty($sipping->weekend)) {
                    $weekend = explode(',', $sipping->weekend);
                    $weekendcount= count($weekend);
                    for ($i = 0; $i < $weekendcount; $i++) {
                        $weekend[$i] = getDay($weekend[$i]);
                    }
                } else {
                    $weekend = '';
                }

           

                $arrayVacation = [];

                for ($i = 0; $i < 7; $i++) {
                    $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->where('end_day', '>=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->first();



                    if ($start_end) {

                        if (!in_array($start_end->id, $arrayVacation)) {
                            $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($start_time_vacation)->format('Y-m-d'));
                            if ($days == 0) {
                                $days = 1;
                            }


                            $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);

                            array_push($arrayVacation, $start_end->id);
                        }
                    } else {
                        if (!empty($weekend)) {
                            if (Carbon::parse($start_time)->addDays($i) < $end_time_hypothetical) {
                                if (in_array(Carbon::parse($start_time)->addDays($i)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            } 
                            else {
                                if (in_array(Carbon::parse($end_time_hypothetical)->addDays(1)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            }
                        }
                    }
                }

                $notAnswer= $row->OrderStatusAymakan()->where('status','AY-0006')->where(function ($query) {
                    $query->where('reason_code', "AY-0043")
                          ->orWhereNotNull('requested_delivery_date');
                })->get();
              
                $arrayVacation=[];
                 foreach($notAnswer as $notanswer){
                         if($notanswer->reason_code == "AY-0043" && empty($notanswer->requested_delivery_date)){
                            $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                            $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', $end_time_hypothetical)->where('end_day', '>=', $end_time_hypothetical)->first();
                            if ($start_end) {
      
                              if (!in_array($start_end->id, $arrayVacation)) {
                                  $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($end_time_hypothetical));
                                  if ($days == 0) {
                                      $days = 1;
                                  }
                                  $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                                  array_push($arrayVacation, $start_end->id);
                              }
                          } else {
                                
                        
      
                              if(!empty($weekend)){
                          
                                  if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
      
                                      $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                      if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
      
                                          $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                          if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
      
                                              $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                              if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
      
                                                  $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                  if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
      
                                                      $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                  }
                                              }
                                          }
                                      }
                                  }
                              }
                           
                          }
                         }else{
                            $end_time_hypothetical = $notanswer->requested_delivery_date;
                            //$end_time_hypothetical = Carbon::create($end_time_hypothetical)->addHour($numberHoure)->endOfDay();
                         }

             

                 }
           
                
           
                   $end_time_hypothetical = Carbon::create($end_time_hypothetical)->endOfDay();
             
     

                $row->took_hour  = ($end_time_hypothetical >= $end_time) ? true : false;
                if ($row->took_hour) {

             
                    $allWithin++;
                } else {

                    $allOut++;
                }



                 $countpoints = count($points);
                 
                for ($i = 0; $i < $countpoints;  $i++) {


                    if ((Carbon::create(@$points[$i]['from'])->startOfDay() <=  $row->created_at  &&  Carbon::create(@$points[$i]['to'])->endOfDay() >=  $row->created_at) || (Carbon::create(@$points[$i]['from'])->startOfDay() <=  $start_time  &&  Carbon::create(@$points[$i]['to'])->endOfDay() >  $start_time) || (Carbon::create(@$points[$i]['from'])->startOfDay() <=  $end_time  &&  Carbon::create(@$points[$i]['to'])->endOfDay() >=   $end_time)) {

                        if (!in_array($row->id, $arrayin)) {
                            
                            if ($row->took_hour) {

                                $points[$i]['in'] = $points[$i]['in'] + 1;
                            } else {

                                $points[$i]['out'] = $points[$i]['out'] + 1;
                            }
                            array_push($arrayin, $row->id);
                        }
                    }
                }






                if (in_array($row->customerCity, $stackcities)) {
                    if ($row->took_hour) {
                        $cities[$row->customerCity]['in']++;
                    } else {
                        $cities[$row->customerCity]['out']++;
                    }
                } else {
                    array_push($stackcities, $row->customerCity);

                    $cities[$row->customerCity] = [
                        'name' => $row->city?->name?->value,
                        'in' => ($row->took_hour) ? 1 : 0,
                        'out' => ($row->took_hour) ? 0 : 1,
                    ];
                }
            }
        }

        $data = [
            'points' => $points,
            'cities' => $cities,
            'allWithin' => $allWithin,
            'allOut' => $allOut
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

    public function reportAllOrderCities(Request $request)
    {
        $sipping = ShippingCompany::first();
        $conditionWhere = $this->filterByDateNew($request);

        if ($request->statusId == 'all') {
            $orders = Order::whereIn('orders.status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
        } else {
            $orders = Order::where('orders.status_id', $request->statusId);
        }
        if (!empty($request->period)) {
            // $orders = $orders->join('order_statuses', 'order_statuses.order_id', '=', 'orders.id');
            // if ($request->statusId == 'all') {
            //     $orders = $orders->whereIn('order_statuses.status_id',  [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
            // } else {
            //     $orders = $orders->where('order_statuses.status_id', $request->statusId);
            // }

            $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
        }

        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->supplier)) {
            $orders = $orders->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.supplier_id', $request->supplier);
        }
        if (!empty($request->city_id)) {
            $orders = $orders->where('orders.customerCity', $request->city_id);
        }
        $orders = $orders->select('orders.*')->get();
        $ordes_cities = [];
        foreach ($orders as $row) {

            if ($request->statusId == 'all') {
                $end_time = @$row->orderStatus()->whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS])->orderBy('id', 'DESC')->first()->created_at;
            } else {
                $end_time = @$row->orderStatus()->where('status_id', $request->statusId)->orderBy('id', 'DESC')->first()->created_at;
            }

            $start_time_vacation = $start_time = @$row->orderStatus()->where('status_id', OrderEnum::SHIPPING_STATUS)->orderBy('id', 'DESC')->first()->created_at;
            $houreTime =   Carbon::parse($start_time)->format('H:i:s');
            if ($start_time) {
                if ($sipping->order_fulfillment_end_time  < $houreTime) {
                    $start_time = Carbon::parse($start_time)->addDays(1)->format('Y-m-d') . ' ' . $sipping->order_fulfillment_start_time;
                }
                $row->time_difference = @$end_time->diffInMinutes($start_time) / 60;
                $numberHoure =  @ShippingCompanyCityTime::where('city_id', $row->customerCity)->where('number_hours', '!=', 0)->first()->number_hours;





                $end_time_hypothetical = Carbon::create($start_time)->addHour($numberHoure)->endOfDay();
                if (!empty($sipping->weekend)) {
                    $weekend = explode(',', $sipping->weekend);
                    for ($i = 0; $i < count($weekend); $i++) {
                        $weekend[$i] = getDay($weekend[$i]);
                    }
                } else {
                    $weekend = '';
                }

                $countDays = ($end_time_hypothetical->diffInMinutes($start_time) / 60) / 24;
                $arrayVacation = [];

                for ($i = 0; $i < 7; $i++) {
                    $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->where('end_day', '>=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->first();

                    if ($start_end) {

                        if (!in_array($start_end->id, $arrayVacation)) {

                            $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($start_time_vacation)->format('Y-m-d'));
                            if ($days == 0) {
                                $days = 1;
                            }
                            $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                            array_push($arrayVacation, $start_end->id);
                        }
                    } else {
                        if (!empty($weekend)) {
                            if (Carbon::parse($start_time)->addDays($i) < $end_time_hypothetical) {
                                if (in_array(Carbon::parse($start_time)->addDays($i)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            } else {
                                if (in_array(Carbon::parse($end_time_hypothetical)->addDays(1)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            }
                        }
                    }
                }
         


                     //dispatsh and request delivery date 
                     $notAnswer= $row->OrderStatusAymakan()->where('status','AY-0006')->where(function ($query) {
                        $query->where('reason_code', "AY-0043")
                              ->orWhereNotNull('requested_delivery_date');
                    })->get();
    
                    $arrayVacation=[];
                     foreach($notAnswer as $notanswer){
                             if($notanswer->reason_code == "AY-0043" && empty($notanswer->requested_delivery_date)){
                                $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', $end_time_hypothetical)->where('end_day', '>=', $end_time_hypothetical)->first();
                                if ($start_end) {
          
                                  if (!in_array($start_end->id, $arrayVacation)) {
                                      $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($end_time_hypothetical));
                                      if ($days == 0) {
                                          $days = 1;
                                      }
                                      $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                                      array_push($arrayVacation, $start_end->id);
                                  }
                              } else {
                                    
                            
          
                                  if(!empty($weekend)){
                              
                                      if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                          $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                          if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                              $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                              if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                                  $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                  if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                                      $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                      if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                                          $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                      }
                                                  }
                                              }
                                          }
                                      }
                                  }
                               
                              }
                             }else{
                                $end_time_hypothetical = $notanswer->requested_delivery_date;
                                //$end_time_hypothetical = Carbon::create($end_time_hypothetical)->addHour($numberHoure)->endOfDay();
                             }
    
    
                     }
               
                     $end_time_hypothetical = Carbon::create($end_time_hypothetical)->endOfDay();

                $row->took_hour  = ($end_time_hypothetical >= $end_time) ? true : false;


                if ($row->took_hour) {
                    if ($request->type == 'Within SLA') {
                        $ordes_cities[] =   $row;
                    }
                } else {
                    if ($request->type == 'Outside SLA') {
                        $ordes_cities[] =   $row;
                    }
                }
            }
        }

        return  $ordes_cities;
    }


    public function reportAllOrderTimes(Request $request)
    {
        $sipping = ShippingCompany::first();

        if ($request->statusId == 'all') {
            $orders = Order::whereIn('orders.status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
        } else {
            $orders = Order::where('orders.status_id', $request->statusId);
        }


        if (!empty($request->time_orders)) {
            if ($request->period == 'today' || ($request->fromDate  == $request->toDate &&  $request->period  == 'thisCustom')) {
                if ($request->fromDate  == $request->toDate) {
                    $today = Carbon::createFromDate($request->fromDate);
                } else {
                    $today = \Illuminate\Support\Carbon::today();
                }

                $arraytime = explode(":", $request->time_orders);
                $first =  $today->copy()->addHours($arraytime[0]);
                $end = $today->copy()->addHours($arraytime[2]);
            } else {
                $arraytime = explode(":", $request->time_orders);
                $first = Carbon::create($arraytime[0])->startOfDay();
                $end = Carbon::create($arraytime[1])->endOfDay();
            }

            // $orders = $orders->join('order_statuses', 'order_statuses.order_id', '=', 'orders.id');
            // if ($request->statusId == 'all') {
            //     $orders = $orders->whereIn('order_statuses.status_id',  [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
            // } else { //$request->statusId
            //     $orders = $orders->where('order_statuses.status_id', $request->statusId);
            // }

            $orders = $orders->whereBetween('orders.created_at', [$first, $end]);
        }

        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->supplier)) {
            $orders = $orders->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.supplier_id', $request->supplier);
        }
        if (!empty($request->city)) {
            $orders = $orders->whereIn('orders.customerCity', $request->city);
        }
        $orders = $orders->select('orders.*')->get();
        $arrayin = [];
        $ordes_times = [];
        $count = 0;
        foreach ($orders as $row) {
            if ($request->statusId == 'all') {
                $end_time = @$row->orderStatus()->whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS])->orderBy('id', 'DESC')->first()->created_at;
            } else {
                $end_time = @$row->orderStatus()->where('status_id', $request->statusId)->orderBy('id', 'DESC')->first()->created_at;
            }
            $start_time_vacation = $start_time = @$row->orderStatus()->where('status_id', OrderEnum::SHIPPING_STATUS)->orderBy('id', 'DESC')->first()->created_at;
            $houreTime =   Carbon::parse($start_time)->format('H:i:s');
            if ($start_time) {
                $count++;
                if ($sipping->order_fulfillment_end_time  < $houreTime) {
                    $start_time = Carbon::parse($start_time)->addDays(1)->format('Y-m-d') . ' ' . $sipping->order_fulfillment_start_time;
                }

                $numberHoure =  @ShippingCompanyCityTime::where('city_id', $row->customerCity)->where('number_hours', '!=', 0)->first()->number_hours;




                $end_time_hypothetical = Carbon::create($start_time)->addHour($numberHoure)->endOfDay();
                if (!empty($sipping->weekend)) {
                    $weekend = explode(',', $sipping->weekend);
                    for ($i = 0; $i < count($weekend); $i++) {
                        $weekend[$i] = getDay($weekend[$i]);
                    }
                } else {
                    $weekend = '';
                }

                $countDays = (@$end_time_hypothetical->diffInMinutes($start_time) / 60) / 24;
                $arrayVacation = [];

                for ($i = 0; $i < 7; $i++) {
                    $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->where('end_day', '>=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->first();

                    if ($start_end) {

                        if (!in_array($start_end->id, $arrayVacation)) {

                            $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($start_time_vacation)->format('Y-m-d'));

                            if ($days == 0) {
                                $days = 1;
                            }
                            $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                            array_push($arrayVacation, $start_end->id);
                        }
                    } else {
                        if (!empty($weekend)) {
                            if (Carbon::parse($start_time)->addDays($i) < $end_time_hypothetical) {
                                if (in_array(Carbon::parse($start_time)->addDays($i)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            } else {
                                if (in_array(Carbon::parse($end_time_hypothetical)->addDays(1)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            }
                        }
                    }
                }


                

                     //dispatsh and request delivery date 
                     $notAnswer= $row->OrderStatusAymakan()->where('status','AY-0006')->where(function ($query) {
                        $query->where('reason_code', "AY-0043")
                              ->orWhereNotNull('requested_delivery_date');
                    })->get();
    
                    $arrayVacation=[];

                
                     foreach($notAnswer as $notanswer){
                             if($notanswer->reason_code == "AY-0043" && empty($notanswer->requested_delivery_date)){
                                $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', $end_time_hypothetical)->where('end_day', '>=', $end_time_hypothetical)->first();
                                if ($start_end) {
          
                                  if (!in_array($start_end->id, $arrayVacation)) {
                                      $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($end_time_hypothetical));
                                      if ($days == 0) {
                                          $days = 1;
                                      }
                                      $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                                      array_push($arrayVacation, $start_end->id);
                                  }
                              } else {
                                    
                            
          
                                  if(!empty($weekend)){
                              
                                      if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                          $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                          if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                              $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                              if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                                  $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                  if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                                      $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                      if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
          
                                                          $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                                      }
                                                  }
                                              }
                                          }
                                      }
                                  }
                               
                              }
                             }else{
                                $end_time_hypothetical = $notanswer->requested_delivery_date;
                                //$end_time_hypothetical = Carbon::create($end_time_hypothetical)->addHour($numberHoure)->endOfDay();
                             }
    
                   
    
                     }

               $end_time_hypothetical = Carbon::create($end_time_hypothetical)->endOfDay();
                $row->took_hour  = ($end_time_hypothetical >= $end_time) ? true : false;


                if (($first <=  $row->created_at  && $end >  $row->created_at) || ($first <=  $start_time  && $end >  $start_time) || ($first <=  $end_time  && $end > $end_time)) {

                    if (!in_array($row->id, $arrayin)) {
                        if ($row->took_hour) {
                            if ($request->type == 'Within SLA') {
                                $ordes_times[] =   $row;
                            }
                        } else {
                            if ($request->type == 'Outside SLA') {
                                $ordes_times[] =   $row;
                            }
                        }
                        array_push($arrayin, $row->id);
                    }
                }
            }
        }

        return  $ordes_times;
    }


    
    public function reportAllOrderAll(Request $request)
    {
        $sipping = ShippingCompany::first();
        $conditionWhere = $this->filterByDateNew($request);
        if ($request->statusId == 'all') {
            $orders = Order::whereIn('orders.status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
        } else {
            $orders = Order::where('orders.status_id', $request->statusId);
        }


  
           // $orders = $orders->join('order_statuses', 'order_statuses.order_id', '=', 'orders.id');
            // if ($request->statusId == 'all') {
            //     $orders = $orders->whereIn('order_statuses.status_id',  [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS]);
            // } else { //$request->statusId
            //     $orders = $orders->where('order_statuses.status_id', $request->statusId);
            // }

            $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0],$conditionWhere[1]]);
        

        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->supplier)) {
            $orders = $orders->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.supplier_id', $request->supplier);
        }
        if (!empty($request->city)) {
            $orders = $orders->whereIn('orders.customerCity', $request->city);
        }
        $orders = $orders->select('orders.*')->get();
        $arrayin = [];
        $ordes_times = [];
        $count = 0;
        foreach ($orders as $row) {
            if ($request->statusId == 'all') {
                $end_time = @$row->orderStatus()->whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS])->orderBy('id', 'DESC')->first()->created_at;
            } else {
                $end_time = @$row->orderStatus()->where('status_id', $request->statusId)->orderBy('id', 'DESC')->first()->created_at;
            }
            $start_time_vacation = $start_time = @$row->orderStatus()->where('status_id', OrderEnum::SHIPPING_STATUS)->orderBy('id', 'DESC')->first()->created_at;
            $houreTime =   Carbon::parse($start_time)->format('H:i:s');
            if ($start_time) {
                $count++;
                if ($sipping->order_fulfillment_end_time  < $houreTime) {
                    $start_time = Carbon::parse($start_time)->addDays(1)->format('Y-m-d') . ' ' . $sipping->order_fulfillment_start_time;
                }

                $numberHoure =  @ShippingCompanyCityTime::where('city_id', $row->customerCity)->where('number_hours', '!=', 0)->first()->number_hours;




                $end_time_hypothetical = Carbon::create($start_time)->addHour($numberHoure)->endOfDay();
                if (!empty($sipping->weekend)) {
                    $weekend = explode(',', $sipping->weekend);
                    for ($i = 0; $i < count($weekend); $i++) {
                        $weekend[$i] = getDay($weekend[$i]);
                    }
                } else {
                    $weekend = '';
                }

                $countDays = (@$end_time_hypothetical->diffInMinutes($start_time) / 60) / 24;
                $arrayVacation = [];

                for ($i = 0; $i < 7; $i++) {
                    $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->where('end_day', '>=', Carbon::parse($start_time)->addDays($i)->format('Y-m-d'))->first();

                    if ($start_end) {

                        if (!in_array($start_end->id, $arrayVacation)) {

                            $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($start_time_vacation)->format('Y-m-d'));

                            if ($days == 0) {
                                $days = 1;
                            }
                            $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                            array_push($arrayVacation, $start_end->id);
                        }
                    } else {
                        if (!empty($weekend)) {
                            if (Carbon::parse($start_time)->addDays($i) < $end_time_hypothetical) {
                                if (in_array(Carbon::parse($start_time)->addDays($i)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            } else {
                                if (in_array(Carbon::parse($end_time_hypothetical)->addDays(1)->locale('en')->dayName, $weekend)) {

                                    $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                }
                            }
                        }
                    }
                }




                     //dispatsh and request delivery date 
                     $notAnswer= $row->OrderStatusAymakan()->where('status','AY-0006')->where(function ($query) {
                        $query->where('reason_code', "AY-0043")
                              ->orWhereNotNull('requested_delivery_date');
                    })->get();
    
                    $arrayVacation=[];
                     foreach($notAnswer as $notanswer){
                             if($notanswer->reason_code == "AY-0043" && empty($notanswer->requested_delivery_date)){
                                $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                
                         $start_end = $sipping->shipping_company_vacation()->where('start_day', '<=', $end_time_hypothetical)->where('end_day', '>=', $end_time_hypothetical)->first();
                         if ($start_end) {
   
                           if (!in_array($start_end->id, $arrayVacation)) {
                               $days = Carbon::parse($start_end->end_day)->diffInDays(Carbon::parse($end_time_hypothetical));
                               if ($days == 0) {
                                   $days = 1;
                               }
                               $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays($days);
                               array_push($arrayVacation, $start_end->id);
                           }
                       } else {
                             
                     
   
                           if(!empty($weekend)){
                       
                               if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
   
                                   $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                   if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
   
                                       $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                       if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
   
                                           $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                           if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
   
                                               $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                               if (in_array(Carbon::parse($end_time_hypothetical)->locale('en')->dayName, $weekend)) {
   
                                                   $end_time_hypothetical = Carbon::create($end_time_hypothetical)->addDays(1);
                                               }
                                           }
                                       }
                                   }
                               }
                           }
                        
                       }
                             }else{
                                $end_time_hypothetical = $notanswer->requested_delivery_date;
                             }
    
    
                     }

                     $end_time_hypothetical = Carbon::create($end_time_hypothetical)->endOfDay();
                $row->took_hour  = ($end_time_hypothetical >= $end_time) ? true : false;

                if (($conditionWhere[0] <=  $row->created_at  && $conditionWhere[1] >  $row->created_at) || ($conditionWhere[0] <=  $start_time  && $conditionWhere[1] >  $start_time) || ($conditionWhere[0] <=  $end_time  && $conditionWhere[1] > $end_time)) {

                    if (!in_array($row->id, $arrayin)) {
                        if ($row->took_hour) {
                            if ($request->type == 'Within SLA') {
                                $ordes_times[] =   $row;
                            }
                        } else {
                            if ($request->type == 'Outside SLA') {
                                $ordes_times[] =   $row;
                            }
                        }
                        array_push($arrayin, $row->id);
                    }
                }
            }
        }

        return  $ordes_times;
    }
}
