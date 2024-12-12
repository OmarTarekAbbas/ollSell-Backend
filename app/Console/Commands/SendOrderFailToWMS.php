<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AymakanService;
use Modules\Order\Actions\Order\AymakanCreateShipmentOrderAction;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\WmsOrderStatus;
use Modules\Order\Enums\OrderEnum;

class SendOrderFailToWMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-order-fail-to-wMS';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('status_id', OrderEnum::PREPARING_STATUS)->orderBy('id','desc')->get();
        foreach($orders as $order)
        {
            $response = App(AymakanService::class)->getOrderWMS($order->id);
            if(isset($response))
            {
                $body = $response->getBody()->getContents();
                $responseData = json_decode($body, true);
                if(isset($responseData['success']) && $responseData['success'] == true)
                {
                    if(isset($responseData['data']['status_code']))
                    {
                        $check = WmsOrderStatus::where('order_id', $order->id)
                            ->where('status', $responseData['data']['status_code'])->first();
                        if(!$check)
                        {
                            WmsOrderStatus::create([
                                'order_id' => $order->id,
                                'status' => $responseData['data']['status_code'],
                                'created_at' => now()]);
                        }
                        if($responseData['data']['status_code'] == 'new_order')
                        {
                            App(AymakanCreateShipmentOrderAction::class)->getOrderByReference($order);
                        }
                        if($responseData['data']['status_code'] == 'shipped')
                        {
                            $order->update(['status_id'=>OrderEnum::SHIPPING_STATUS]);
                        }
                        $this->info($order->id . ' - found at wms - status : ' . $responseData['data']['status_code']);
                    }else
                    {
                        $this->info($order->id . ' - check at wms');
                    }
                }else
                {
                    $this->createOrder($order);
                }
            }else
            {
                $this->createOrder($order);
            }
            sleep(3);
        }
    }

    public function createOrder($order)
    {
        $name = explode(" ", $order->customerName);
        $payload = [
            'order_id' => "$order->id",
            'hub_code' => 'ollkomHub',
            'order_created_at' => $order->created_at->format('Y-m-d\TH:i:s\Z'),
            "payment_method" => ($order->paymentMethod == 2) ? "postpaid" : "prepaid",
            "is_cash_on_delivery" => ($order->paymentMethod == 2) ? true : false,
            "require_shipping" => true,
            "note" => "Please deliver during weekdays",
            'customer' => [
                'first_name' => @$name[0],
                'last_name' => @$name[1],
                'mobile' => $order->customerPhone,
                'mobile_code' => "$order->phone_code"
            ],
            'invoice' => [
                "currency" => "SAR",
                "subtotal" => $order->subTotal,
                "shipping_price" => 0,
                "shipping_refund" => 0,
                "tax" => 0,
                "discount" => 0,
                "total" => $order->grandTotal,
                "total_paid" => ($order->paymentMethod == 2) ? 0 : $order->grandTotal,
                "total_due" => ($order->paymentMethod == 2) ? $order->grandTotal : 0,
                "total_refunded" => 0,
                //   "payment_mode"=>  "Credit Card",
                "tax_percent" => 0,
                "shipping_tax" => 0,
                "sub_total_tax_inclusive" => true,
                "sub_total_discount_inclusive" => true,
                "shipping_tax_inclusive" => false,
                "shipping_discount_inclusive" => false
            ],
            'billing_address' => [
                "address1" => $order->customerAddress,
                "city" => @$order->city?->nameValueId(1),
                "country" => @$order->country?->nameValueId(1),
                "first_name" => @$name[0],
                "last_name" => @$name[1],
                "phone" => $order->customerPhone,
                "state" => $order->district,
                "zip" => null,
                "state_code" => null,
                "country_code" => null,
                "latitude" => null,
                "longitude" => null
            ],
            'shipping_address' => [
                "address1" => $order->customerAddress,
                "city" => @$order->city?->nameValueId(1),
                "country" => @$order->country?->nameValueId(1),
                "first_name" => @$name[0],
                "last_name" => @$name[1],
                "phone" => $order->customerPhone,
                "state" => $order->district,
                "zip" => null,
                "state_code" => null,
                "country_code" => null,
                "latitude" => null,
                "longitude" => null,
            ],
            'shipment' => [
                "shipping_partner_name" => $order->shippingMethod,
                "shipping_partner_tag" => $order->shippingMethod,
                "awb_number" => $order->tracking_number,
                "awb_label" => $order->pdf_label,
                "tracking_link" => $order->pdf_label
            ],
            'order_items' => $order->orderItems->map(function($item)
            {
                return [
                    'sku_code' => $item->sku,
                    'name' => $item->product?->name?->value,
                    'display_price' => $item->unitPrice,
                    'selling_price' => $item->unitPrice,// note
                    'is_substituted' => false,
                    'quantity' => $item->quantity,
                    'tax_percent' => 0,
                    'tax' => 0,
                    'unit_price' => $item->unitPrice,
                    "subtotal" => $item->totalPrice,
                    "total" => $item->totalPrice,
                    "discount" => 0,
                    "tax_inclusive" => false
                ];
            }),
        ];
        $response = App(AymakanService::class)->sendOrderWMS($payload);
        if(isset($response))
        {
            $statusCode = $response->getStatusCode();
            // Get the response body
            $body = $response->getBody()->getContents();
            $this->info($body);
            $responseData = json_decode($body, true);
            if($statusCode == 200)
            {
                $this->info($order->id . ' - send to wms');
                return true;
            }else
            {
                $this->info($order->id . ' - error for send to wms');
                return false;
            }
        }else
        {
            $this->info($order->id . ' - error');
            return false;
        }
    }
}
