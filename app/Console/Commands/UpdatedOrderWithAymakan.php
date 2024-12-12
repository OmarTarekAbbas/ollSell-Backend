<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatusAymakan;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Repositories\OrderRepository;

class UpdatedOrderWithAymakan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:aymakan-update';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix updated Orders';
    protected $repo;
    protected $statusService;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository, StatusService $statusService)
    {
        parent::__construct();
        $this->repo = $repository;
        $this->statusService = $statusService;
    }

    /**
     * Execute the console command.
     *
     * return mixed
     */
    public function handle()
    {
        $orders = Order::whereNotIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS])->whereNotNull('tracking_number')->orderBy('id','desc')->get();
        foreach($orders as $keys => $order)
        {
            $trackShipment = Http::withHeader('Accept', 'application/json')
                ->withHeader('Authorization',
                    '767278fb55db48b2620dc3bbde89b765-5c828234-3ce7-4987-9ec2-4cccc7817642-5de6ddf1a3d10d3b941f4134380b31da/657b12835fe346a0f950b0fcb06e0a11/51dbdd4d-5532-443a-950f-3a9664d743e5')
                ->get('https://api.aymakan.net/v2/shipping/by_reference/' . $order->id);
            if(isset($trackShipment['error']))
            {
                $this->info($order->id . ' - error');
                continue;
            }
            $trackingInfos = $trackShipment['data']['shipments'][0]['tracking_info'];
            $checkRequestedDeliveryAymakan=true;
            foreach(array_reverse($trackingInfos) as $key => $trackingInfo)
            {
                $checkOrderStatusAymakan = OrderStatusAymakan::where('order_id', $order->id)
                    ->where('status', $trackingInfo['status_code'])
                    ->where('created_at', $trackingInfo['created_at'])
                    ->first();
                if($trackShipment['data']['shipments'][0]['requested_delivery_date']){
                    $checkRequestedDeliveryAymakan =  OrderStatusAymakan::where('order_id', $order->id)
                        ->where('requested_delivery_date',  $trackShipment['data']['shipments'][0]['requested_delivery_date'])
                        ->first();
                }
                if($checkOrderStatusAymakan)
                {
                    continue;
                }
                $OrderStatusAymakan = new OrderStatusAymakan();
                $OrderStatusAymakan->status = $trackingInfo['status_code'];
                $OrderStatusAymakan->description = $trackingInfo['description'];
                $OrderStatusAymakan->reason_code = $trackingInfo['reason_code'];
                $OrderStatusAymakan->reason_description = $trackingInfo['reason_ar'];
                if($checkRequestedDeliveryAymakan  == null && strpos($trackingInfo['description_ar'],"تم تأجيل موعد إستلام الشحنة")){
                    $OrderStatusAymakan->requested_delivery_date = $trackShipment['data']['shipments'][0]['requested_delivery_date'];
                }
                $OrderStatusAymakan->tracking = $trackShipment['data']['shipments'][0]['tracking_number'];
                $OrderStatusAymakan->reference = $trackShipment['data']['shipments'][0]['reference'];
                $OrderStatusAymakan->order_id = $order->id;
                $OrderStatusAymakan->created_at = $trackingInfo['created_at'];
                $OrderStatusAymakan->save();
                $newRequest = new Request();
                if($trackingInfo['status_code'] === 'AY-0005')
                {
                    $this->info($order->id . ' - ' . 'AY-0005');
                    $newRequest->merge([
                        'status_id' => OrderEnum::COMPLETED_STATUS,
                        'deliveryDate' => Carbon::parse($trackingInfo['created_at'])->format('Y-m-d'),
                    ]);
                }elseif (in_array($trackingInfo['status_code'], ['AY-0026','AY-0009','AY-0030','AY-0069','AY-0004','AY-0056','AY-0003','AY-0080','AY-0086',
                    'AY-0034','AY-0082','AY-0096','AY-0076','AY-0079']))
                {
                    $this->info($order->id . ' - ' . 'SHIPPING_STATUS');
                    $newRequest->merge([
                        'status_id' => OrderEnum::SHIPPING_STATUS,
                    ]);
                }elseif($trackingInfo['status_code'] === 'AY-0008')
                {
                    $this->info($order->id . ' - ' . 'AY-0008');
                    $newRequest->merge([
                        'status_id' => OrderEnum::REJECTED_STATUS,
                    ]);
                }elseif($trackingInfo['status_code'] === 'AY-0001')
                {
                    $this->info($order->id . ' - ' . 'AY-0001');
                    $newRequest->merge([
                        'status_id' => OrderEnum::PREPARING_STATUS,
                        'tracking_number' => $trackShipment['data']['shipments'][0]['tracking_number'],
                        'pdf_label' => $trackShipment['data']['shipments'][0]['awb_url'] ?? null,
                    ]);
                }elseif($trackingInfo['status_code'] === 'AY-0029')
                {
                    $this->info($order->id . ' - ' . 'AY-0029');
                    $newRequest->merge([
                        'status_id' => OrderEnum::CANCELED_STATUS,
                        'cancelDate' => Carbon::parse($trackingInfo['created_at'])->format('Y-m-d'),
                    ]);
                }
                $this->repo->save($newRequest, $order->id);
            }
            $this->info($order->id . ' - finish');
            sleep(3);
        }
    }
}
