<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Log;
use App\Services\OllopsClientService;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Service\OrderService;

class UpdateItemOrders extends Command
{
    protected $signature = 'order:update-status-item';
    protected $description = 'Update order status in Node.js application';


    public function handle()
    {
       $items = OrderItem::where('status_id', OrderEnum::PENDING_STATUS)->get();
       foreach ($items as $item) {
           $order = Order::find($item->order_id);
           $item->update(['status_id' => $order->status_id]);
        }
    }
}
