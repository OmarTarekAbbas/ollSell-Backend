<?php

namespace Modules\Order\Exports\Order\Admin;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Order\Entities\Order;

class OrderInsightsExport implements FromCollection, WithHeadings
{
    protected $orders;
    protected $title;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {

        $exportData = [];

        foreach ($this->orders as $order) {
            // $order = Order::find($order['id']);

                // Gather data for each order item
                $exportData[] = [
                    'tracking' => $order->tracking_number,
                    'Order ID' =>$order->id,
                    'OrderCreation' => $order->created_at,
                    'OrderStatus' => $order->status->name->value,
                    'City' => $order->city->name->value,
                    'SLA' => $order->sla,
                    'TotalTransactions' => $order->TotalTransactions,
                    'SubmittionDate' => $order->SubmittionDate,
                    'ReceivedAtHub' => $order->ReceivedAtHub,
                    'DeliveryType' => $order->DeliveryType,
                    'ExternalCreation' => $order->ExternalCreation,
                    'FirstDelivery' => $order->FirstDelivery,
                    'LastDelivery' => $order->LastDelivery,
                    'DeliveryAttempts' => $order->DeliveryAttempts,
                    'NoAnswerCount' => $order->NoAnswerCount,
                    'IsFutureDelivery' => $order->IsFutureDelivery,
                    'LastUpdateDate' => $order->LastUpdateDate,
                    'LastStatus' => $order->LastStatus,
                    'LastUpdate' => $order->LastUpdate,
                    'RTFD' => $order->RTFD,
                    'FDTLD' => $order->FDTLD,
                    'OVERALL' => $order->OVERALL,
                ];

              
            }
        

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'tracking',
            'Order ID',
            'OrderCreation',
            'OrderStatus',
            'City',
            'SLA',
            'TotalTransactions',
            'SubmittionDate',
            'ReceivedAtHub',
            'DeliveryType',
            'ExternalCreation',
            'FirstDelivery',
            'LastDelivery',
            'DeliveryAttempts',
            'NoAnswerCount',
            'IsFutureDelivery',
            'LastUpdateDate',
            'LastStatus',
            'LastUpdate',
            'RTFD',
            'FDTLD',
            'OVERALL',
   
        ];
    }


}
