<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AymakanService;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;

class updateOrderFailToWMS extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'app:update-order-fail-to-wMS';

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
    
      $orders = Order::whereIn('id', ['11204'])->get();
      // $orders = Order::where('status_id', OrderEnum::PREPARING_STATUS)->get();
      foreach($orders as $order){
         $this->createOrder($order);
      }



   }

   
   public function createOrder($order)
   {

       $name=explode(" ",$order->customerName);
       $payload = [
        'order_id' => "$order->id",
        'hub_code'=>'ollkomHub',
        'order_created_at'=>$order->created_at->format('Y-m-d\TH:i:s\Z'),
        "payment_method"=> ($order->paymentMethod == 2) ? "postpaid" :"prepaid",
        "is_cash_on_delivery"=> ($order->paymentMethod == 2) ? true :false,
        "require_shipping"=> true,
        "note"=> "Please deliver during weekdays",
        'customer' => [
            'first_name'=>@$name[0],
            'last_name'=>@$name[1],
            'mobile'=>$order->customerPhone,
            'mobile_code'=>"$order->phone_code"
        ],
        'invoice' => [
         "currency"=> "SAR",
         "subtotal"=>  $order->subTotal,
         "shipping_price" =>  0,
         "shipping_refund"=>  0,
         "tax" => 0,
         "discount"=>  0,
         "total" => $order->grandTotal,
         "total_paid"=> ($order->paymentMethod == 2) ? 0 : $order->grandTotal,
         "total_due" => ($order->paymentMethod == 2) ? $order->grandTotal : 0,
         "total_refunded"=>  0,
         "tax_percent"=> 0,
         "shipping_tax"=>  0,
         "sub_total_tax_inclusive"=>  true,
         "sub_total_discount_inclusive"=>  true,
         "shipping_tax_inclusive"=>  false,
         "shipping_discount_inclusive"=>  false
        ],
        'billing_address' => [
                    "address1"=> $order->customerAddress,
                    "city"=> @$order->city?->nameValueId(1),
                    "country"=> @$order->country?->nameValueId(1),
                    "first_name"=> @$name[0],
                    "last_name"=> @$name[1],
                    "phone"=> $order->customerPhone,
                    "state"=> $order->district,
                    "zip"=> null,
                    "state_code"=> null,
                    "country_code"=> null,
                    "latitude"=> null,
                    "longitude"=> null
        ],
        'shipping_address' => [
            "address1"=> $order->customerAddress,
            "city"=> @$order->city?->nameValueId(1),
            "country"=> @$order->country?->nameValueId(1),
            "first_name"=> @$name[0],
            "last_name"=> @$name[1],
            "phone"=> $order->customerPhone,
            "state"=> $order->district,
            "zip"=> null,
            "state_code"=>null,
            "country_code"=> null,
            "latitude"=> null,
            "longitude"=> null,

         ],
         'shipment' => [
           "shipping_partner_name"=> $order->shippingMethod,
           "shipping_partner_tag"=> $order->shippingMethod,
           "awb_number"=> $order->tracking_number,
           "awb_label"=> $order->pdf_label,
           "tracking_link"=> $order->pdf_label
        ],
        'order_items'=>$order->orderItems->map(function ($item) {
            return [
                 'sku_code'=>$item->sku,
                 'name' => $item->product?->name?->value,
                 'display_price'=>$item->unitPrice,
                 'selling_price'=>$item->unitPrice,// note
                 'is_substituted'=>false,
                 'quantity' => $item->quantity,
                 'tax_percent'=>0,
                 'tax'=>0,
                 'unit_price'=>$item->unitPrice,
                 "subtotal"=> $item->totalPrice,
                 "total"=> $item->totalPrice,
                 "discount"=> 0,
                 "tax_inclusive"=> false  
            ];
        }),

    ];
 
    
       $response = App(AymakanService::class)->updateOrderWMS($payload);

       if (isset($response)) {
           $statusCode = $response->getStatusCode();
           // Get the response body
           $body = $response->getBody()->getContents();
            $this->info($body);
           $responseData = json_decode($body, true);
           sleep(3);
           if ($statusCode == 200) {
              return true;
           } else {
              return false;
           }
       } else {
           return false;
       }
   }

}
