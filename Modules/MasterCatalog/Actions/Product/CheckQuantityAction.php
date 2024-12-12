<?php

namespace Modules\MasterCatalog\Actions\Product;

use App\Services\AymakanService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Actions\Order\SyncUpdateOrderStatusAction;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Enums\PlatformEnum;

class CheckQuantityAction
{
    protected $order;

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($order)
    {
        $items = $order->orderItems->select('quantity', 'product_id')->toArray();
        $newItem = [];
        foreach($items as $item)
        {
            $newItem[$item['product_id']] = ($i[$item['product_id']] ?? 0) + $item['quantity'];
        }
        $isValid = true;
        foreach($newItem as $key => $value)
        {
            $wmsQuantity = 0;
            $product = Product::find($key);
            if($product)
            {
                $quantity = $product->quantity;
                $aymakan = new  AymakanService();
                $response = $aymakan->fetchSKU($product->sku);
                if(isset($response))
                {
                    $body = $response->getBody()->getContents();
                    $statusCode = $response->getStatusCode();
                    $responseData = json_decode($body, true);
                    if($statusCode == 200)
                    {
                        $wmsQuantity = $responseData['data']['available_quantity'] ?? 0;
                    }
                }
                if(config('services.aymakan.AYMKAN_DEBUG') == true) $wmsQuantity = 1000;
                if($quantity > 0 && $quantity - $value >= 0 && $wmsQuantity > 0 && $wmsQuantity - $value >= 0)
                {
                    continue;
                }else
                {
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }

    public function SyncStockPendingOrder($id)
    {
        $platforms = PlatformEnum::list();
        foreach($platforms as $platform)
        {
            $product = Product::find($id);
            if($product->quantity > 0 )
            {
                $orderItems = $product->orderItems()->where('status_id', OrderEnum::PENDING_INVENTORY_STATUS)
                    ->whereHas('order', function($query) use ($platform)
                    {
                        $query->where('status_id', OrderEnum::PENDING_INVENTORY_STATUS)
                            ->whereIn('paymentMethod', [PaymentEnum::ONLINE_METHOD_ID, PaymentEnum::WALLET_METHOD_ID])
                            ->where('source_platform', $platform);
                    })->orderBy('created_at', 'asc')->get();
                if($orderItems->count())
                {
                    foreach($orderItems as $orderItem)
                    {
                        $order = Order::find($orderItem->order_id);
                        if($order->status_id == OrderEnum::PENDING_INVENTORY_STATUS)
                        {
                            (new SyncUpdateOrderStatusAction(
                                order: $order,
                                status_id: OrderEnum::PREPARING_STATUS
                            ))->execute();
                            $order = Order::find($orderItem->order_id);
                            if($order->status_id != OrderEnum::PENDING_INVENTORY_STATUS)
                            {
                                return true;
                            }
                        }
                    }
                }
            }else
            {
                break;
            }
        }
    }
}
