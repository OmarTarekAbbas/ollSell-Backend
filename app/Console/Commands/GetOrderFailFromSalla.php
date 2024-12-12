<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Order\Enums\OrderEnum;
use App\Services\SallaAuthService;
use Modules\Order\Enums\PlatformEnum;
use Modules\Store\Entities\DropshipperMappingOrder;
use Illuminate\Http\Request;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Actions\Order\CheckDuplicatedOrderAction;
use Modules\Order\Service\OrderService;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GetOrderFailFromSalla extends Command
{
    use validationRulesTrait;

   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'app:get-order-fail-from-salla';

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
      $dropshippers =  DB::table('oauth_tokens')->get();
      foreach($dropshippers as $dropshipper){
        $response=  $this->getOrders($dropshipper);
    
        if (isset($response)) {
            $statusCode = $response->getStatusCode();
        
            // Get the response body
            $body = $response->getBody()->getContents();

            $responseData = json_decode($body, true);
            if ($statusCode == 200) {
                foreach($responseData['data'] as $row){
                   $mapping= DropshipperMappingOrder::where('model_type','salla')->where('model_id',$row['id'])->first();
                   if($mapping == null){
                  $order = $this->getOrder($row['id'],$dropshipper);
                  $order = json_decode($order, true);
                  if(@$order['data']['id']){
                    $this->createOrder($order['data'],$dropshipper);
                  }

                   }
                    $this->info('app:dropshipper-segmentation-month Cummand Run successfully!'.$row['id']);
                }
            
            }
         }
         sleep(3);
     
    //     $this->createOrder($order);
      }
   }


public function getOrders($dropshipper){

    $client = new Client();
  $headers = [
      'Accept' => 'application/json',
     'Authorization' => 'Bearer '.$dropshipper->access_token
    ];
    try {
        $response = $client->get('https://api.salla.dev/admin/v2/orders?payment_method[]=cod&from_date=2024-09-09', [
            'headers' => $headers 
        ]);

        return  $response; // Return the entire response object
    } catch (RequestException $e) {
      
        // Handle exceptions (e.g., API errors)
        if ($e->hasResponse()) {
            return $e->getResponse(); // Return the response object
        } else {
            return json_encode(['error' => 'Something went wrong']);
        }
    }
}

public function getOrder($id,$dropshipper){

    $client = new Client();
  $headers = [
      'Accept' => 'application/json',
     'Authorization' => 'Bearer '.$dropshipper->access_token
    ];
    try {
        $response = $client->get("https://api.salla.dev/admin/v2/orders/$id", [
            'headers' => $headers 
        ]);

        return  $response->getBody()->getContents(); // Return the entire response object
    } catch (RequestException $e) {
      
        // Handle exceptions (e.g., API errors)
        if ($e->hasResponse()) {
            return $e->getResponse(); // Return the response object
        } else {
            return json_encode(['error' => 'Something went wrong']);
        }
    }
}
   
   public function createOrder($order,$dropshipper)
   {
        $city='';
        $request = request();
        $service = app()->make(OrderService::class);
        $newData['order_id'] =$order['id'] ;
        $isInvalid = true;
        $itemArray=[] ;
        try
        {


            if(isset($order['payment_method']) && $order['payment_method'] != 'cod')
            {
                $isInvalid = false;
                $message[] = 'wrong payment method';

            }
            if(isset($order['shipments'][0]['ship_to']['city']))
            {
                $oldCity =$order['shipments'][0]['ship_to']['city'];
                $city = app()->make(CityService::class)->search(new Request(['alias' => $order['shipments'][0]['ship_to']['city']]));
            }
            if(empty($city))
            {
                $isInvalid = false;
                $message[] = "City not found";
            }
            if(isset($order['items']) && !isset($order['items'][0]['sku']))
            {
                $isInvalid = false;
                $message[] = 'not found products';

            }
            $items = $order['items'];
            foreach($items as $item)
            {
               

                $product = Product::where('sku', $item['sku'])->first();
                if(!$product)
                {
                    $isInvalid = false;
                    $message[] = 'Product SKU not found: ' . $item['sku'];
                    break;
                 
                }
                $productQuantity = $product->quantity;
                if($productQuantity <= 0)
                {
                    $isInvalid = false;
                    $message[] = 'Product Is out of stock';
                 
                }
                if($productQuantity < $item['quantity'])
                {
                      $isInvalid = false;
                    $message[] = 'Product inventory is not sufficient for ' . $item['sku'];
                 
                }
               
                if(($item['amounts']['total']['amount']/$item['quantity'] ) <= $product->cost_price)
                {
                    $isInvalid = false;
                    $message[] = 'The Selling Price must be greater than the Cost Price.';
                 
                }
                $totalPriceForProduct[] = $item['amounts']['total']['amount'] ;
                $totalSellingPriceForProduct[] = ($product->cost_price * $item['quantity']);
                $totalProductVatArray[] = $this->getOrderItemVatImport($product, $item);
                $netProfitArray[] = $this->netProfitImport($product, $item);
                $itemArray[] = [
                    'product' => $product->id,
                    'quantity' => $item['quantity'],
                    'sellingPrice' => ($item['amounts']['total']['amount']/$item['quantity'] ),
                    'variant' => [
                        0 => 'null',
                    ],
                ];
            }

            if(!count($itemArray))
            {
                $isInvalid = false;
            }else{
                $totalQuantity = collect($itemArray)->sum('quantity');
                $countOrderItem = collect($items)->count();
                $totalPrice = collect($totalPriceForProduct)->sum();
                $costPrice = collect($totalSellingPriceForProduct)->sum();
                $shippingFees = setting('shipping_fee') ?? 25;
                $totalProductVat = collect($totalProductVatArray)->sum();
                $netProfit = collect($netProfitArray)->sum();
            }


            if(isset($order['customer']['mobile']))
            {
                $newData['customerPhone'] = $this->handlePhoneKSA($order['customer']['mobile']);
            }
            if(isset($newData['customerPhone']) && strlen($newData['customerPhone']) != 10)
            {
                $message[] = 'The phone field must be 10 number.';
                $isInvalid = false;
            }
            if($isInvalid)
            {
                $newData['customerCity'] = $city->id;
                $newData['paymentMethod'] = $order['payment_method'] == 'cod' ? 2 : 1;
                $newData['customerCountry'] = '178'; // Country set to Saudi Arabia (id:178)
                $newData['phone_code'] = '996'; // Phone_code set as per country 996 for saudi arabia
                $newData['dropshipper_id'] =@$dropshipper->dropshipper_id;
                $newData['source_platform'] = PlatformEnum::SALLA_PLATFORM;
                $newData['items'] =   $itemArray;
                $newData['is_duplicated'] = false;
                $newData['duplicated_order_ids'] = null;
                $newData['fresh_duplicated'] = false;
                $request->merge([
                    'is_duplicated'=>$newData['is_duplicated'],
                    'duplicated_order_ids'=>$newData['duplicated_order_ids'],
                    'fresh_duplicated'=>$newData['fresh_duplicated'],
                    'shippingFees' => $shippingFees,
                    'shippingMethod' => 'shippingMethod',
                    'totalQuantity' => $totalQuantity,
                    'subTotal' => $totalPrice,
                    'grandTotal' => ($totalPrice + $shippingFees),
                    'dropshipper_id' =>@$dropshipper->dropshipper_id,
                    'status_id' => OrderEnum::NEW_STATUS,
                    'customerName' =>  $order['customer']['first_name'] . ' ' . $order['customer']['last_name'],
                    'customerPhone' =>  $newData['customerPhone'] ,
                    'customerAddress' => $order['shipments'][0]['ship_to']['address_line'],
                    'customerLocation' => $order['shipments'][0]['ship_to']['latitude'].','.$order['shipments'][0]['ship_to']['longitude'],
                    'district' =>  $newData['customerCountry'],
                    'country_id' =>  $newData['customerCountry'],
                    'customerCity' =>  $newData['customerCity'],
                    'countOrderItem' => $countOrderItem,
                    'paymentMethod' =>  $newData['paymentMethod'],
                    'items' => $itemArray,
                    'costPrice' => $costPrice,
                    'phone_code' => 966,
                    'net_profit' => $netProfit,
                    'totalVat' => $totalProductVat,
                    'source_platform' => PlatformEnum::SALLA_PLATFORM
                ]);



                $duplicatedOrders = (new CheckDuplicatedOrderAction(
                    request: $request
                ))->execute();
                $request->merge([...$duplicatedOrders]);
                if($request->fresh_duplicated)
                {
                    $message[] = 'Order is duplicated.';
                    $isInvalid = false;
                }
                if($isInvalid)
                {
                    $request->merge($newData);
                    $newOrder = $service->store($request);
                    $orderMaping = new DropshipperMappingOrder();
                    $orderMaping->dropshipper_id = @$dropshipper->dropshipper_id;
                    $orderMaping->model_type = 'salla';
                    $orderMaping->model_id = $order['id'];
                    $orderMaping->order_id = $newOrder->id;
                    $orderMaping->save();

                }else{
                    $newData['customerCity'] =$oldCity ?? null;
                    $newData['paymentMethod'] = 'cod';
                    $newData['message'] = implode(',', $message);
                    $this->handleOrderError($newData);
                }
            }else
            {
                $newData['message'] = implode(',', $message);
                $this->handleOrderError($newData);
            }
        }catch(\Exception $exception)
        {
         
            $newData['customerCity'] =$oldCity ?? null;
            $newData['paymentMethod'] = 'cod';
            $newData['message'] = implode(',', $message ?? []);
            $this->handleOrderError($newData);
        }

   }


   
   public function handleOrderError($data)
   {
       $newData = [
           'order_id'=> $data['order_id'] ?? null,
           'message' => $data['message'] ?? null,
           'name' => $data['customerName'] ?? null,
           "phone" => $data['customerPhone'] ?? null,
           'address' => $data['customerAddress'] ?? null,
           'district' => $data['customerAddress'] ?? null,
           'city' => $data['customerCity'] ?? null,
           'country' => 'KSA',
           'location' => null,
           'paymentMethod' => $data['paymentMethod'] ?? null,
        
       ];
       $directoryPath = public_path("missings/salla_order/" . today()->format('Y-m-d'));
       if (!is_dir($directoryPath)) {
           mkdir($directoryPath, 0777, true);  // إنشاء الدليل بالأذونات المناسبة
       }
       // $csvFile = fopen(public_path("missings/salla_order/" . today()->format('Y-m-d') . "/orders_failed_rows.csv"),"a");
       $csvFile = fopen($directoryPath . "/orders_failed_rows.csv", "a");
       fputcsv($csvFile, $newData);
       fclose($csvFile);
   }

   public function getOrderItemVatImport($product, $item)
   {
       $itemSellingPrice = ($item['amounts']['total']['amount']/$item['quantity'] );
       $productBasePrice = $product->is_discount ? $product->priceAfterDiscount : $product->cost_price;
       $baseVat = $productBasePrice * setting('shipping_fee');
       $totalProfit = $itemSellingPrice - $productBasePrice;
       $profitVat = $totalProfit * setting('shipping_fee');
       $netProfit = $totalProfit - $profitVat;
       $totalVat = $baseVat + $profitVat;
       return ($totalVat * $item['quantity']);
   }
   
   public function netProfitImport($product, $item)
   {
       $netProfit = $this->totalProfitImport($product, $item) - $this->vatProfitImport($product, $item);
       return $netProfit;
   }

   public function totalProfitImport($product, $item)
   {
       $itemSellingPrice = ($item['amounts']['total']['amount']/$item['quantity'] );
       return ($itemSellingPrice  - $product->cost_price) * $item['quantity'];
   }

   public function vatProfitImport($product, $item)
   {
       $itemSellingPrice = ($item['amounts']['total']['amount']/$item['quantity'] );
       $totalProfit =  $itemSellingPrice  - $product->cost_price;
       return ($totalProfit * setting('shipping_fee')) * $item['quantity'];
   }

}
