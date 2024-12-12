<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Modules\Acl\Entities\Dropshipper;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Http;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\OrderService;
use Modules\Order\Actions\Order\StartValidationFlowAction;
use Modules\Finance\Actions\Transaction\PayByWalleteAutomaticAction;

class EasyOrderWalletCheckOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:check-payment-easy-order';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check easy order In order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('dropshipper_id', 69)
            ->where('created_platform', PlatformEnum::EASYORDER_PLATFORM)
            ->where('status_id', OrderEnum::PAY_PENDING_STATUS)->get();
        foreach($orders as $order)
        {
            if(Carbon::parse($order->created_at)->diffInMinutes(Carbon::now(),true) > 30)
            {
                $response = Http::withHeader('Accept', 'application/json')
                    ->withHeader('Api-Key', '98678118-9e76-46b7-a970-b04418861a21')
                    ->get('https://api.easy-orders.net/api/v1/external-apps/orders/' . $order->easy_order_id);
                $easy_order = json_decode($response->getBody()->getContents(), true);
                if($easy_order['status'] == 'paid')
                {
                    $dropshipper = Dropshipper::find($order->dropshipper_id);
                    if($order && $dropshipper->DropshipperOptionCheck('automatic_pay_from_profit_at_wallet'))
                    {
                        $request = request();
                        $request->merge($order->toArray());
                        $request->merge(['validated' => now(),'validated_by' => 'prepaid']);
                        $check = app(PayByWalleteAutomaticAction::class)->execute($order);
                        if($check)
                        {
                            $order->validated = now();
                            $order->validated_by = 'prepaid';
                            $order->save();
                            app()->make(OrderService::class)->payWallet($order);
                        }
                        continue;
                    }
                }else
                {
                    $oldItems = $order->orderItems;
                    $itemArray = [];
                    foreach($oldItems as $oldItem)
                    {
                        $itemArray[] = [
                            'product' => $oldItem->product_id,
                            'quantity' => $oldItem->quantity,
                            'variant' => [null],
                            'sellingPrice' => $oldItem->unitPrice,
                        ];
                    }
                    $request = request();
                    $request->merge($order->toArray());
                    $status = OrderEnum::NEW_STATUS;
                    if(setting('validation_type') == 'automatic')
                    {
                        $status = OrderEnum::PENDING_STATUS;
                    }
                    $request->merge(['items' => $itemArray, 'status_id' => $status]);
                    app(OrderService::class)->update($request, $order->id);
                    $newOrder = Order::find($order->id);
                    if(setting('validation_type') == 'automatic')
                    {
                        if($newOrder->status_id == OrderEnum::PENDING_STATUS)
                        {
                            $newRequest = new Request();
                            $newRequest->merge(['orderIds' => [$order->id]]);
                            // send to ollops
                            (new StartValidationFlowAction(
                                request: $newRequest
                            ))->execute();
                        }
                    }
                }
            }
        }
    }
}
