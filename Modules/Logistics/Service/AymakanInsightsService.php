<?php

namespace Modules\Logistics\Service;

use Modules\Order\Enums\OrderEnum;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Basic\Service\BasicService;
use Carbon\CarbonPeriod;
use Modules\Logistics\Entities\ShippingCompanyCityTime;


class AymakanInsightsService extends BasicService
{

    public function orderAymakanInsights(Request $request)
    {

        $conditionWhere = $this->filterByDateNew($request);

        $orders = Order::whereNotNull('orders.tracking_number');
        //->where('orders.status_id', OrderEnum::PREPARING_STATUS);
        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->period)) {
         $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
        
        }


        $orders = $orders->select('orders.*')->distinct()->get();
        $ordersaray = [];
    
        foreach ($orders as $row) {
           
            $statuses = $row->OrderStatusAymakanNo()->get();
            // Determine external/internal based on status AY-0069
            $inExternal = $statuses->firstWhere('status', 'AY-0069');
            // Check for specific statuses only once and store results
            // $statusAY0001 = $statuses->firstWhere('status', 'AY-0001');
            $statusAY0003 = $statuses->firstWhere('status', 'AY-0003');
             $statusAY0005 = $statuses->firstWhere('status', 'AY-0005');
             $statusAY0008 = $statuses->firstWhere('status', 'AY-0008');
            $statusFirstDelivery = $statuses->sortBy('created_at')->firstWhere('status', 'AY-0004');
            $statusLastDelivery = $statuses->sortByDesc('created_at')->firstWhere('status', 'AY-0004');
            $IsFutureDelivery =$statuses->sortByDesc('created_at')->first(function ($item) {
                return $item->status === 'AY-0006' && $item->reason_code === 'AY-103';
            });
            $NoAnswerCount =$statuses->where('status','AY-0006')->where('reason_code','AY-102');

            $status = $statuses->sortByDesc('id')->first();
            $numberHoure =  @ShippingCompanyCityTime::where('city_id', $row->customerCity)->where('number_hours', '!=', 0)->first()->number_hours;

            $row->sla =$numberHoure;
            $row->TotalTransactions =$statuses->count();
            if($startDate= $statuses->sortBy('created_at')->first()){
                $row->SubmittionDate  = $startDate->created_at;
            }
            if($statusAY0003){
                $row->ReceivedAtHub  = $statusAY0003->created_at;
            }
            $row->DeliveryType  =   $inExternal ? 'ED' : 'ID';
            if($inExternal){
                $row->ExternalCreation= $inExternal->created_at;
            }
            if($statusFirstDelivery){
                $row->FirstDelivery=$statusFirstDelivery->created_at;
            }
            if($statusFirstDelivery){
                $row->LastDelivery=$statusLastDelivery->created_at;
            }
            if($statusFirstDelivery){
                $row->DeliveryAttempts  =$statuses->Where('status', 'AY-0004')->count();
            }
             
             if($IsFutureDelivery){
                $row->IsFutureDelivery=$IsFutureDelivery->created_at;
            }
            if($NoAnswerCount){
                $row->NoAnswerCount  =$NoAnswerCount->count();
            }
            if($status){
                $row->LastUpdateDate  =$status->created_at;
                $row->LastStatus =$status->status;
                $row->LastUpdate =$status->description;
            }

            if ($statusAY0003 && $statusFirstDelivery && $statusLastDelivery && $status) {
                $houreTime =   Carbon::parse($statusAY0003->created_at)->format('H:i:s');
                if($houreTime >= '12:30:00'){
                    $receivedDate= Carbon::parse($statusAY0003->created_at)->addDays(1)->format('Y-m-d') . ' ' .'08:00:00';
                }else{
                    $receivedDate=$statusAY0003->created_at;
                }
                // الحسابات المشتركة
                $row->RTFD = $this->diffInHoursRemoveFriday($receivedDate, $statusFirstDelivery->created_at);
                $row->FDTLD = $this->diffInHoursRemoveFriday($statusFirstDelivery->created_at, $statusLastDelivery->created_at);
                $row->OVERALL = $this->diffInHoursRemoveFriday($receivedDate, $status->created_at);
            }
            
              ;
            if($this->checkhandleShippingAndStatus(
                $request->shippingType, 
                $request->shipmentStatus, 
                $status,
                $inExternal, 
                $statusAY0005, 
                $statusAY0008,
                $request->type
              )){
                $ordersaray[] = $row;

               }

         
     
        }

        return $ordersaray;
    }

    public function orderAymakanInsightsLimit(Request $request)
    {

        $conditionWhere = $this->filterByDateNew($request);

        $orders = Order::whereNotNull('orders.tracking_number');
        //->where('orders.status_id', OrderEnum::PREPARING_STATUS);
        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->period)) {
         $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
        
        }
  if('total' == $request->type){
    $orders = $orders->select('orders.*')->distinct()->limit(100)->get();
  }else{
    $orders = $orders->select('orders.*')->distinct()->get();
  }

       
        $ordersaray = [];
    
        foreach ($orders as $row) {
           
            $statuses = $row->OrderStatusAymakanNo()->get();
            // Determine external/internal based on status AY-0069
            $inExternal = $statuses->firstWhere('status', 'AY-0069');
            // Check for specific statuses only once and store results
            // $statusAY0001 = $statuses->firstWhere('status', 'AY-0001');
            $statusAY0003 = $statuses->firstWhere('status', 'AY-0003');
             $statusAY0005 = $statuses->firstWhere('status', 'AY-0005');
             $statusAY0008 = $statuses->firstWhere('status', 'AY-0008');
            $statusFirstDelivery = $statuses->sortBy('created_at')->firstWhere('status', 'AY-0004');
            $statusLastDelivery = $statuses->sortByDesc('created_at')->firstWhere('status', 'AY-0004');
            $IsFutureDelivery =$statuses->sortByDesc('created_at')->first(function ($item) {
                return $item->status === 'AY-0006' && $item->reason_code === 'AY-103';
            });
            $NoAnswerCount =$statuses->where('status','AY-0006')->where('reason_code','AY-102');
      
            $status = $statuses->sortByDesc('id')->first();
            $numberHoure =  @ShippingCompanyCityTime::where('city_id', $row->customerCity)->where('number_hours', '!=', 0)->first()->number_hours;

            $row->sla =$numberHoure;
            $row->TotalTransactions =$statuses->count();
            if($startDate = $statuses->sortBy('created_at')->first()){
                $row->SubmittionDate  = $startDate->created_at;
            }
            if($statusAY0003){
                $row->ReceivedAtHub  = $statusAY0003->created_at;
            }
            $row->DeliveryType  =   $inExternal ? 'ED' : 'ID';
            if($inExternal){
                $row->ExternalCreation= $inExternal->created_at;
            }
            if($statusFirstDelivery){
                $row->FirstDelivery=$statusFirstDelivery->created_at;
            }
            if($statusFirstDelivery){
                $row->LastDelivery=$statusLastDelivery->created_at;
            }
            if($statusFirstDelivery){
                $row->DeliveryAttempts  =$statuses->Where('status', 'AY-0004')->count();
            }
             
             if($IsFutureDelivery){
                $row->IsFutureDelivery=$IsFutureDelivery->created_at;
            }
            if($NoAnswerCount){
                $row->NoAnswerCount  =$NoAnswerCount->count();
            }
            if($status){
                $row->LastUpdateDate  =$status->created_at;
                $row->LastStatus =$status->status;
                $row->LastUpdate =$status->description;
            }

            if ($statusAY0003 && $statusFirstDelivery && $statusLastDelivery && $status) {
                $houreTime =   Carbon::parse($statusAY0003->created_at)->format('H:i:s');
                if($houreTime >= '12:30:00'){
                    $receivedDate= Carbon::parse($statusAY0003->created_at)->addDays(1)->format('Y-m-d') . ' ' .'08:00:00';
                }else{
                    $receivedDate=$statusAY0003->created_at;
                }
                // الحسابات المشتركة
                $row->RTFD = $this->diffInHoursRemoveFriday($receivedDate, $statusFirstDelivery->created_at);
                $row->FDTLD = $this->diffInHoursRemoveFriday($statusFirstDelivery->created_at, $statusLastDelivery->created_at);
                $row->OVERALL = $this->diffInHoursRemoveFriday($receivedDate, $status->created_at);
            }
            
              ;
            if($this->checkhandleShippingAndStatus(
                $request->shippingType, 
                $request->shipmentStatus, 
                $status,
                $inExternal, 
                $statusAY0005, 
                $statusAY0008,
                $request->type
              )){
                $ordersaray[] = $row;

               }

         
     
        }
if(count($ordersaray) > 100){
    return collect($ordersaray)->chunk(100)[0];

}
        return $ordersaray;
    }
    public function reportAllShipping(Request $request)
    {

        $conditionWhere = $this->filterByDateNew($request);

        $orders = Order::whereNotNull('orders.tracking_number');
        if (!empty($request->dropshipper)) {
            $orders = $orders->whereIn('orders.dropshipper_id', $request->dropshipper);
        }
        if (!empty($request->period)) {
                $orders = $orders->whereBetween('orders.created_at', [$conditionWhere[0], $conditionWhere[1]]);
            }


        $orders = $orders->select('orders.*')->distinct()->get();
        $allOrderInShipping = count($orders);
        $onHold = 0;
        $returned = 0;
        $delivered = 0;
        $aWBCreated = 0;
        $inTransit = 0;
        $receivedAtRiyadhWarehouse = 0;
        $internal = 0;
        $external = 0;
        $submitAwb = 0;
        $receiveWarehouse = 0;
        $receiveWarehouseDeliver = 0;
        $receiveWarehouseReturn = 0;
        $internalReturn = 0;
        $internalDeliver = 0;
        $externalDeliver = 0;
        $externalReturn = 0;

        $RTFD = 0;
        $FDTLD = 0;
        $OVERALL = 0;
        foreach ($orders as $row) {

            $statuses = $row->OrderStatusAymakanNo()->get();

            // Determine external/internal based on status AY-0069
            $inExternal = $statuses->firstWhere('status', 'AY-0069');
            $inExternal ? $external++ : $internal++;

            // Check for specific statuses only once and store results
            $statusAY0001 = $statuses->firstWhere('status', 'AY-0001');
            $statusAY0003 = $statuses->firstWhere('status', 'AY-0003');
            $statusAY0005 = $statuses->firstWhere('status', 'AY-0005');
            $statusAY0008 = $statuses->firstWhere('status', 'AY-0008');


            $statusFirstDelivery = $statuses->sortBy('created_at')->firstWhere('status', 'AY-0004');
            $statusLastDelivery = $statuses->sortByDesc('created_at')->firstWhere('status', 'AY-0004');
            $status = $statuses->sortByDesc('id')->first();

            if ($statusAY0001) {
                $submitAwb++;
            }

            if ($statusAY0003) {
                $receiveWarehouse++;
                if ($statusAY0005) {
                    $receiveWarehouseDeliver++;
                }
                if ($statusAY0008) {
                    $receiveWarehouseReturn++;
                }
            }

            // Distinguish between internal and external deliveries and returns
            if ($inExternal) {
                if ($statusAY0005) {
                    $externalDeliver++;
                }
                if ($statusAY0008) {
                    $externalReturn++;
                }
            } else {
                if ($statusAY0005) {
                    $internalDeliver++;
                }
                if ($statusAY0008) {
                    $internalReturn++;
                }
            }


            // Get the latest status, ordering by 'id' in descending order
            $this->handleShippingAndStatus(
                $request->shippingType, 
                $request->shipmentStatus, 
                $statusAY0003, 
                $statusFirstDelivery, 
                $statusLastDelivery, 
                $status, 
            $onHold, 
            $returned, 
            $delivered, 
            $aWBCreated, 
            $inTransit, 
            $receivedAtRiyadhWarehouse, 
            $RTFD, 
            $FDTLD, 
            $OVERALL, 
                $inExternal, 
                $statusAY0005, 
                $statusAY0008
            );

        }

        $data = [
            'allOrderInShipping' => $allOrderInShipping,
            'onHold' => $onHold,
            'returned' => $returned,
            'delivered' => $delivered,
            'aWBCreated' => $aWBCreated,
            'inTransit' => $inTransit,
            'receivedAtRiyadhWarehouse' => $receivedAtRiyadhWarehouse,
            'internal' => $internal,
            'external' => $external,
            'submitAwb' => $submitAwb,
            'receiveWarehouse' => $receiveWarehouse,
            'receiveWarehouseDeliver' => $receiveWarehouseDeliver,
            'receiveWarehouseReturn' => $receiveWarehouseReturn,
            'internalReturn' => $internalReturn,
            'internalDeliver' => $internalDeliver,
            'externalDeliver' => $externalDeliver,
            'externalReturn' => $externalReturn,
            'RTFD' => $RTFD,
            'FDTLD' => $FDTLD,
            'OVERALL' => $OVERALL,
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

    public function diffInHoursRemoveFriday($startTime, $endTime)
    {
        //   dd($startTime,$endTime);
        $startTime= Carbon::parse($startTime); 
        $startTime1 = Carbon::parse($startTime); // بداية الفترة مع الوقت
        $endTime1 = Carbon::parse($endTime);   // نهاية الفترة مع الوقت

        $period = CarbonPeriod::create($startTime->startOfDay(), $endTime->endOfDay());
        $hasFriday =collect($period)->filter(function ($date) {
            return $date->isFriday();
        })->count();

        $extraHours = $startTime1->diffInHours($endTime1);
        if ($hasFriday) {
            $totalHours = $extraHours - (24 * $hasFriday);
        } else {
            $totalHours = $extraHours;
        }
        return $totalHours;
    }

    private function checkhandleShippingAndStatus($shippingType, $shipmentStatus, $status, $inExternal, $statusAY0005, $statusAY0008,$type)
    {
        if ($shippingType == 'all' || $shippingType == 'Internal' || $shippingType == 'External') {
             if($shipmentStatus == 'all') {
            if ($shippingType == 'all' || ($shippingType == 'Internal' && $inExternal == null) || ($shippingType == 'External' && $inExternal)) {
                return $this->checkIsType($type,$status);
               
             }
               }
                if($shipmentStatus =='delivered') {
                   
                    // الحالة الخاصة بالشحنات التي تم تسليمها
                    if (($shippingType == 'all' && $statusAY0005) || ($shippingType == 'Internal' && $inExternal == null && $statusAY0005) || ($shippingType == 'External' && $inExternal && $statusAY0005)) {
                        return $this->checkIsType($type,$status);
                    }
                }
    
                if($shipmentStatus =='returned') {  
                    // الحالة الخاصة بالشحنات التي تم إرجاعها
                    if (($shippingType == 'all' && $statusAY0008) || ($shippingType == 'Internal' && $inExternal == null && $statusAY0008) || ($shippingType == 'External' && $inExternal && $statusAY0008)) {
                        return $this->checkIsType($type,$status);
                    }
                }
                  return false;
            }
     }

public function checkIsType($type,$status): bool{
    if('total' == $type){
        return true;
    }
    if(@$status->status == $type){
        return true;
    }

    return false;
}


// دالة مشتركة لمعالجة حالة الشحنة
private function handleShippingAndStatus($shippingType, $shipmentStatus, $statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status, &$onHold, &$returned, &$delivered, &$aWBCreated, &$inTransit, &$receivedAtRiyadhWarehouse, &$RTFD, &$FDTLD, &$OVERALL, $inExternal, $statusAY0005, $statusAY0008)
{
    if ($shippingType == 'all' || $shippingType == 'Internal' || $shippingType == 'External') {
         if($shipmentStatus == 'all') {
        if ($shippingType == 'all' || ($shippingType == 'Internal' && $inExternal == null) || ($shippingType == 'External' && $inExternal)) {
         
            $this->handleStatusUpdate( $statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status, $onHold, $returned, $delivered, $aWBCreated, $inTransit, $receivedAtRiyadhWarehouse, $RTFD, $FDTLD, $OVERALL);
         }
        }
            if($shipmentStatus =='delivered') {
               
                // الحالة الخاصة بالشحنات التي تم تسليمها
                if (($shippingType == 'all' && $statusAY0005) || ($shippingType == 'Internal' && $inExternal == null && $statusAY0005) || ($shippingType == 'External' && $inExternal && $statusAY0005)) {
                    $this->handleStatusUpdate( $statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status, $onHold, $returned, $delivered, $aWBCreated, $inTransit, $receivedAtRiyadhWarehouse, $RTFD, $FDTLD, $OVERALL);

                }
            }

            if($shipmentStatus =='returned') {  
                // الحالة الخاصة بالشحنات التي تم إرجاعها
                if (($shippingType == 'all' && $statusAY0008) || ($shippingType == 'Internal' && $inExternal == null && $statusAY0008) || ($shippingType == 'External' && $inExternal && $statusAY0008)) {
                    $this->handleStatusUpdate( $statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status, $onHold, $returned, $delivered, $aWBCreated, $inTransit, $receivedAtRiyadhWarehouse, $RTFD, $FDTLD, $OVERALL);

                }
            }
              
        }
 }


 function calculateDifferences(&$RTFD, &$FDTLD, &$OVERALL, $statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status)
{
    $houreTime =   Carbon::parse($statusAY0003->created_at)->format('H:i:s');
    if($houreTime >= '12:30:00'){
        $receivedDate= Carbon::parse($statusAY0003->created_at)->addDays(1)->format('Y-m-d') . ' ' .'08:00:00';
    }else{
        $receivedDate=$statusAY0003->created_at;
    }
    
    $RTFD += $this->diffInHoursRemoveFriday($receivedDate, $statusFirstDelivery->created_at);
    $FDTLD += $this->diffInHoursRemoveFriday($statusFirstDelivery->created_at, $statusLastDelivery->created_at);
    $OVERALL += $this->diffInHoursRemoveFriday($receivedDate, $status->created_at);
}
 function handleStatusUpdate($statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status, &$onHold, &$returned, &$delivered, &$aWBCreated, &$inTransit, &$receivedAtRiyadhWarehouse,&$RTFD, &$FDTLD, &$OVERALL)
{
    // تحقق إذا كانت البيانات المطلوبة موجودة
    if ($statusAY0003 && $statusFirstDelivery && $statusLastDelivery && $status) {
        // الحسابات المشتركة
        $this->calculateDifferences($RTFD, $FDTLD, $OVERALL, $statusAY0003, $statusFirstDelivery, $statusLastDelivery, $status);
    }

    // تحديث العدادات بناءً على الحالة
    $this->updateStatusCounts($status, $onHold, $returned, $delivered, $aWBCreated, $inTransit, $receivedAtRiyadhWarehouse);
}

function updateStatusCounts( $status, &$onHold, &$returned,&$delivered, &$aWBCreated, &$inTransit, &$receivedAtRiyadhWarehouse ) {
    if ($status) {
        switch ($status->status) {
            case 'AY-0050':
                $onHold++;
                break;
            case 'AY-0008':
                $returned++;
                break;
            case 'AY-0005':
                $delivered++;
                break;
            case 'AY-0001':
                $aWBCreated++;
                break;
            case 'AY-0009':
                $inTransit++;
                break;
            case 'AY-0026':
                $receivedAtRiyadhWarehouse++;
                break;
        }
    }
}
   
}
