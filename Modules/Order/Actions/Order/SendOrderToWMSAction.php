<?php

namespace Modules\Order\Actions\Order;

use App\Services\AymakanService;
use Modules\Order\Entities\WMS;

class SendOrderToWMSAction
{
    public function execute($order)
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
                "tax_percent" => 0,
                "shipping_tax" => 0,
                "sub_total_tax_inclusive" => true,
                "sub_total_discount_inclusive" => true,
                "shipping_tax_inclusive" => false,
                "shipping_discount_inclusive" => false
            ],
            'billing_address' => [
                "address1" => $order->customerAddress,
                "city" => $order->city?->nameValueId(1),
                "country" => $order->country?->nameValueId(1),
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
                "city" => $order->city?->nameValueId(1),
                "country" => $order->country?->nameValueId(1),
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
            $body = $response->getBody()->getContents();
            $responseData = json_decode($body, true);
            if($statusCode != 200)
            {
                $message = $responseData['message'] ?? null;
                if($message != 'The order_id you entered is already used')
                {
                    $data['taggable_id'] = $order->id;
                    $data['status'] = $statusCode;
                    $data['reason'] = $message;
                    $data['taggable_type'] = "Modules\Order\Entities\Order";
                    $data['payload'] = $responseData;
                    $data['type'] = 'WMS';
                    App(FailOrderAction::class)->execute($data);
                }
                return false;
            }
        }else
        {
            $data['taggable_id'] = $order->id;
            $data['status'] = 'It is not known why';
            $data['taggable_type'] = "Modules\Order\Entities\Order";
            $data['type'] = 'WMS';
            App(FailOrderAction::class)->execute($data);
            return false;
        }
        return true;
    }
}